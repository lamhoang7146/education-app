<?php

namespace App\Services;

use Google\Client;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Exception;

class GoogleDriveOauthService
{
    protected Client $client;
    protected ?Drive $driveService = null;
    protected ?string $storageFolderId;

    public function __construct()
    {
        $this->client = new Client();
        $this->client->setClientId(config('services.google.client_id'));
        $this->client->setClientSecret(config('services.google.client_secret'));
        $this->client->setRedirectUri(route('google-drive.callback'));
        $this->client->setScopes([Drive::DRIVE, Drive::DRIVE_FILE]);
        $this->client->setAccessType('offline');
        $this->client->setPrompt('consent');
        $this->client->setIncludeGrantedScopes(true);

        // Get the storage folder ID from environment variable
        $this->storageFolderId = env('GOOGLE_DRIVE_STORAGE_EDUCATION_FOLDER');

        // Try to load tokens if they exist
        $this->loadTokens();
    }

    /**
     * Get authentication URL
     */
    public function getAuthUrl(): string
    {
        return $this->client->createAuthUrl();
    }

    /**
     * Authenticate with authorization code
     */
    public function authenticate(string $code): void
    {
        // Exchange authorization code for access token
        $accessToken = $this->client->fetchAccessTokenWithAuthCode($code);

        // Check for errors
        if (isset($accessToken['error'])) {
            throw new Exception('Error fetching access token: ' . $accessToken['error_description']);
        }

        // Save tokens
        $this->saveTokens($accessToken);
    }

    /**
     * Check if authenticated
     */
    public function isAuthenticated(): bool
    {
        if (!$this->client->getAccessToken()) {
            return false;
        }

        // Check if token is expired
        if ($this->client->isAccessTokenExpired()) {
            // Try to refresh the token
            try {
                $this->refreshToken();
                return true;
            } catch (Exception $e) {
                Log::error('Failed to refresh Google Drive token: ' . $e->getMessage());
                return false;
            }
        }

        return true;
    }

    /**
     * Refresh the access token
     */
    protected function refreshToken(): void
    {
        if (!$this->client->getRefreshToken()) {
            throw new Exception('No refresh token available');
        }

        $accessToken = $this->client->fetchAccessTokenWithRefreshToken();

        if (isset($accessToken['error'])) {
            throw new Exception('Error refreshing access token: ' . $accessToken['error_description']);
        }

        $this->saveTokens($accessToken);
    }

    /**
     * Save tokens to cache
     */
    protected function saveTokens(array $accessToken): void
    {
        $this->client->setAccessToken($accessToken);

        // Store the token in cache
        Cache::put('google_drive_token', $accessToken, now()->addDays(30));
    }

    /**
     * Load tokens from cache
     */
    protected function loadTokens(): void
    {
        $accessToken = Cache::get('google_drive_token');

        if ($accessToken) {
            $this->client->setAccessToken($accessToken);

            // If token is expired, try to refresh it
            if ($this->client->isAccessTokenExpired() && $this->client->getRefreshToken()) {
                try {
                    $this->refreshToken();
                } catch (Exception $e) {
                    Log::error('Failed to refresh Google Drive token: ' . $e->getMessage());
                }
            }
        }
    }

    /**
     * Get Drive service
     */
    protected function getDriveService(): Drive
    {
        if (!$this->driveService) {
            $this->driveService = new Drive($this->client);
        }

        return $this->driveService;
    }

    /**
     * Upload file to Google Drive
     */
    public function uploadFile(string $filePath, string $fileName, ?string $folderId = null): DriveFile
    {
        if (!$this->isAuthenticated()) {
            throw new Exception('Not authenticated with Google Drive');
        }

        $service = $this->getDriveService();

        $fileMetadata = new DriveFile([
            'name' => $fileName,
        ]);

        // Use the environment variable folder ID if none provided
        if (!$folderId && $this->storageFolderId) {
            $folderId = $this->storageFolderId;
        }

        if ($folderId) {
            $fileMetadata->setParents([$folderId]);
        }

        $content = file_get_contents($filePath);

        $file = $service->files->create($fileMetadata, [
            'data' => $content,
            'mimeType' => mime_content_type($filePath),
            'uploadType' => 'multipart',
            'fields' => 'id,name,webViewLink'
        ]);

        return $file;
    }

    /**
     * List files in a folder
     */
    public function listFilesInFolder(string $folderId = null): array
    {
        if (!$this->isAuthenticated()) {
            throw new Exception('Not authenticated with Google Drive');
        }

        // Use default folder ID if none provided
        if (!$folderId && $this->storageFolderId) {
            $folderId = $this->storageFolderId;
        }

        if (!$folderId) {
            throw new Exception('No folder ID provided');
        }

        $service = $this->getDriveService();

        $query = "'" . $folderId . "' in parents and trashed = false";
        $files = $service->files->listFiles([
            'q' => $query,
            'fields' => 'files(id, name, mimeType, webViewLink, createdTime, size)'
        ]);

        return $files->getFiles();
    }

    /**
     * Delete a file from Google Drive
     */
    public function deleteFile(string $fileId): void
    {
        if (!$this->isAuthenticated()) {
            throw new Exception('Not authenticated with Google Drive');
        }

        $service = $this->getDriveService();
        $service->files->delete($fileId);
    }

    /**
     * Get the storage folder ID
     */
    public function getStorageFolderId(): ?string
    {
        return $this->storageFolderId;
    }
}

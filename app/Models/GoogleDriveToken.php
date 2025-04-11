<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoogleDriveToken extends Model
{
    use HasFactory;

    protected $table = 'google_drive_tokens';

    protected $fillable = [
        'access_token',
        'refresh_token',
        'expires_at'
    ];
}

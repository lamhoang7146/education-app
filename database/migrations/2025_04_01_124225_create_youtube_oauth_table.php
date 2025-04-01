<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('youtube_oauth', function (Blueprint $table) {
            $table->id(); // Tự động tạo cột id (AUTO_INCREMENT)
            $table->string('provider'); // Tên nhà cung cấp (ví dụ: YouTube)
            $table->text('provider_value'); // Giá trị OAuth (ví dụ: access_token, refresh_token)
            $table->timestamps(); // Tự động tạo cột created_at và updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('youtube_oauth');
    }
};

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YoutubeOauth extends Model
{
    use HasFactory;

    protected $table = 'youtube_oauth'; // Tên bảng
    protected $fillable = ['provider', 'provider_value']; // Các cột có thể được gán giá trị
    public $timestamps = true; // Sử dụng cột `created_at` và `updated_at`
}

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
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->tinyInteger('is_important')->default(false);
            $table->timestamps();
        });
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('image')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('google_id')->nullable();
            $table->rememberToken();
            $table->string('two_factor_secret')->nullable();
            $table->timestamp('two_factor_expires_at')->nullable();
            $table->tinyInteger('status')->default(true);
            $table->unsignedBigInteger('role_id');
            $table->timestamps();
            $table->foreign('role_id')->references('id')->on('roles');
        });
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->integer('groupBy');
            $table->timestamps();
        });
        Schema::create('role_permissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('role_id');
            $table->unsignedBigInteger('permission_id');
            $table->timestamps();
            $table->foreign('role_id')->references('id')->on('roles');
            $table->foreign('permission_id')->references('id')->on('permissions');
        });
        Schema::create('category_courses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->tinyInteger('status');
            $table->timestamps();
        });
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('thumbnail');
            $table->decimal('price');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('category_courses_id');
            $table->tinyInteger("is_free");
            $table->enum("level",["ease","medium","hard","extremely"]);
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('category_courses_id')->references('id')->on('category_courses');
            $table->tinyInteger('status');
            $table->timestamps();
        });
        Schema::create('courses_content', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->unsignedBigInteger('courses_id');
            $table->foreign('courses_id')->references('id')->on('courses');
            $table->tinyInteger('status');
            $table->timestamps();
        });

        Schema::create('quiz_content', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->tinyInteger('status');
            $table->timestamps();
        });
        Schema::create('quiz_content_detail', function (Blueprint $table) {
            $table->id();
            $table->string('question', 255);
            $table->string('answer', 255);
            $table->string('result', 255);
            $table->unsignedBigInteger('quiz_content_id');
            $table->foreign('quiz_content_id')->references('id')->on('quiz_content');
            $table->timestamps();
        });
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->string('youtube_id', 255);
            $table->string('title', 255);
            $table->text('description');
            $table->integer('duration');
            $table->tinyInteger('status');
            $table->timestamps();
        });

        Schema::create('courses_content_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('courses_content_id');
            $table->foreign('courses_content_id')->references('id')->on('courses_content');
            $table->enum('content_type', ['video', 'quiz']);
            $table->unsignedBigInteger('video_id')->nullable();
            $table->unsignedBigInteger('quiz_content_id')->nullable();
            $table->foreign('video_id')->references('id')->on('videos');
            $table->foreign('quiz_content_id')->references('id')->on('quiz_content');
            $table->tinyInteger('status');
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};

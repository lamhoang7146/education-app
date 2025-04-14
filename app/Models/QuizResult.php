<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizResult extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'quiz_id',
        'correct_answers',
        'incorrect_answers',
        'user_answers',
    ];

    /**
     * Get the user that owns the quiz result.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the quiz that owns the quiz result.
     */
    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }
}

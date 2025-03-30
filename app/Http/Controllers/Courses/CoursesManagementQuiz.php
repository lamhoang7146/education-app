<?php

namespace App\Http\Controllers\Courses;

use App\Http\Controllers\Controller;
use App\Models\CoursesContentItem;
use App\Models\Quiz;
use Inertia\Inertia;

class CoursesManagementQuiz extends Controller
{
    public function store($courses_id,$content_item_id)
    {
        $credentials = request()->validate([
            'name' => ['required','string','max:255','min:3',function ($attribute, $value, $fail) use ($content_item_id) {
                if (Quiz::whereIn('id', CoursesContentItem::where('courses_content_id', $content_item_id)
                    ->where('content_type', 'quiz')
                    ->pluck('content_id'))
                    ->where('name', $value)
                    ->exists()) {
                    $fail("Quiz name '{$value}' already exists for this content item.");
                }
            }],
            'status' => 'required',
        ]);
        $quiz = Quiz::create([
            'name'=>$credentials['name'],
            'status'=>$credentials['status'],
        ]);
        CoursesContentItem::create([
            'courses_content_id'=>$content_item_id,
            'content_type'=>'quiz',
            'content_id'=>$quiz->id,
            'status'=>true
        ]);
        return redirect()->route('courses.management.courses.content',['id'=>$courses_id])->with([
            'message' => "Quiz $credentials[name] created successfully",
            'status' => true
        ]);
    }

    public function update(Quiz $quiz, $content_item_id)
    {
        $credentials = request()->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'min:3',
                function ($attribute, $value, $fail) use ($content_item_id, $quiz) {
                    $quizIds = CoursesContentItem::where('courses_content_id', $content_item_id)
                        ->where('content_type', 'quiz')
                        ->pluck('content_id');
                    if (Quiz::whereIn('id', $quizIds)
                        ->where('name', $value)
                        ->where('id', '!=', $quiz->id)
                        ->exists()) {
                        $fail("Quiz name '{$value}' already exists for this content item.");
                    }
                }
            ],
            'status' => 'required',
        ]);
        $quiz->update([
            'name' => $credentials['name'],
            'status' => $credentials['status'],
        ]);
        return back()->with([
            'message' => "Quiz '{$credentials['name']}' updated successfully",
            'status' => true
        ]);
    }

}

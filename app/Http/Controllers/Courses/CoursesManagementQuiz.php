<?php

namespace App\Http\Controllers\Courses;

use App\Http\Controllers\Controller;
use App\Models\CoursesContentItem;
use App\Models\Quiz;
use Inertia\Inertia;

class CoursesManagementQuiz extends Controller
{
    private array $data = [];

    public function quiz()
    {
        $this->data['quizzes'] = Quiz::select('*')->latest()->paginate(9);
        return Inertia::render('CoursesManagementQuiz/Quiz', [
            ...$this->data,
            'message' => session('message'),
            'status' => session('status')
        ]);
    }

    public function store($courses_id,$content_item_id)
    {
        $credentials = request()->validate([
            'name' => ['required','string','max:255','min:3'],
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

    public function update(Quiz $quiz)
    {
        $credentials = request()->validate([
            'name' => ['required','string','max:255','min:3'],
            'status' => 'required',
        ]);
        $quiz_name = $credentials['name'];
        $quiz->update([
            'name'=>$credentials['name'],
            'status'=>$credentials['status'],
        ]);
        return back()->with([
            'message' => "Quiz $quiz_name updated successfully",
            'status' => true
        ]);
    }

}

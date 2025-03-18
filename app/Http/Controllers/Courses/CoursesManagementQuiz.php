<?php

namespace App\Http\Controllers\Courses;

use App\Http\Controllers\Controller;
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

    public function store()
    {
        $quiz = request()->validate([
            'name' => 'required|string|max:255|min:3',
        ]);
        Quiz::create([...$quiz, 'status' => request('status')]);
        return back()->with(['message' => 'Quiz added successfully', 'status' => true]);
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

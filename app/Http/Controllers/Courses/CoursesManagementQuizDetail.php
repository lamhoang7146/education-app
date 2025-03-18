<?php

namespace App\Http\Controllers\Courses;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\Quiz_content_detail;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class CoursesManagementQuizDetail extends Controller
{
    private array $data = [];

    public function index(Quiz $quiz)
    {
        $this->data['quiz'] = $quiz;
        $this->data['quiz_content_details'] = Quiz_content_detail::select('*')->where('quiz_id','=',$quiz->id)->get();
        return Inertia::render('CoursesManagementQuiz/Detail', [
            ...$this->data,
            'message'=>session('message'),
            'status'=>session('status')
        ]);
    }

    public function store()
    {
        $quiz_id = request()->quiz_id;

        $quizExists = Quiz_content_detail::where('quiz_id', $quiz_id)->exists();

        $quiz_detail = request()->validate([
            'question' => [
                'required',
                'string',
                'max:255',
                'min:3',
                Rule::unique('quiz_content_details', 'question')->where(function ($query) use ($quiz_id, $quizExists) {
                    if ($quizExists) {
                        return $query->where('quiz_id', $quiz_id);
                    }
                })
            ],
            'answers' => ['required', 'string', function ($attribute, $value, $fail) {
                $answers = array_unique(array_filter(array_map('trim', explode(',', $value))));
                if (count($answers) !== 4) {
                    $fail('The answers field must contain exactly 4 unique values.');
                }
            }],
            'result' => ['required', 'string', function ($attribute, $value, $fail) {
                $answers = array_unique(array_filter(array_map('trim', explode(',', request()->input('answers', '')))));
                if (!in_array($value, $answers)) {
                    $fail('The result must be one of the answers.');
                }
            }],
            'quiz_id' => ['required', 'integer'],
        ]);
        Quiz_content_detail::create($quiz_detail);
        return back()->with(['message' => "Quiz {$quiz_detail["question"]} Added Successfully",'status' => true]);
    }
    public function update(){
        $quizDetail = Quiz_content_detail::findOrFail(request()->id);

        $quiz_id = request()->quiz_id;

        $validatedData = request()->validate([
            'question' => [
                'required',
                'string',
                'max:255',
                'min:3',
                Rule::unique('quiz_content_details', 'question')
                    ->where(function ($query) use ($quiz_id) {
                        return $query->where('quiz_id', $quiz_id);
                    })
                    ->ignore($quizDetail->id),
            ],
            'answers' => ['required', 'string', function ($attribute, $value, $fail) {
                $answers = array_unique(array_filter(array_map('trim', explode(',', $value))));
                if (count($answers) !== 4) {
                    $fail('The answers field must contain exactly 4 unique values.');
                }
            }],
            'result' => ['required', 'string', function ($attribute, $value, $fail) {
                $answers = array_unique(array_filter(array_map('trim', explode(',', request()->input('answers', '')))));
                if (!in_array($value, $answers)) {
                    $fail('The result must be one of the answers.');
                }
            }],
            'quiz_id' => ['required', 'integer'],
        ]);

        $quizDetail->update($validatedData);

        return back()->with(['message' => "Quiz {$validatedData["question"]} Updated Successfully", 'status' => true]);
    }
    public function destroy(Quiz_content_detail $id){
        $id->delete();
        return back()->with(['message' => "Quiz {$id->question} Deleted Successfully", 'status' => true]);
    }
}

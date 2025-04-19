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
                // Split answers by tilde (~) instead of comma
                $answers = array_unique(array_filter(array_map('trim', explode('~', $value))));
                if (count($answers) < 2 || count($answers) > 4) {
                    $fail('The answers field must contain between 2 and 4 unique values.');
                }
            }],
            'result' => ['required', 'string', function ($attribute, $value, $fail) {
                // Split answers by tilde (~) instead of comma
                $answers = array_unique(array_filter(array_map('trim', explode('~', request()->input('answers', '')))));
                if (!in_array($value, $answers)) {
                    $fail('The result must be one of the answers.');
                }
            }],
            'quiz_id' => ['required', 'integer'],
        ]);

        Quiz_content_detail::create($quiz_detail);
        return back()->with(['message' => "Quiz {$quiz_detail["question"]} Added Successfully",'status' => true]);
    }

    public function generateWithAI(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'topic' => 'required|string|min:3|max:255',
            'numberOfQuestions' => 'required|integer|min:1|max:20',
            'answersPerQuestion' => 'required|integer|min:2|max:4',
            'difficultyLevel' => 'required|string|in:Easy,Medium,Hard',
            'quiz_id' => 'required|integer|exists:quizzes,id',
        ]);

        $generatedQuestions = $request->input('generatedQuestions');

        if (!$generatedQuestions || !is_array($generatedQuestions)) {
            return back()->with([
                'message' => 'No questions were generated or invalid question format',
                'status' => false
            ]);
        }

        $successCount = 0;
        $errors = [];

        // Process each question and save to database
        foreach ($generatedQuestions as $question) {
            try {
                // Format answers to the expected tilde-separated string
                $answers = implode('~', $question['answers']);
                $result = $question['correctAnswer'];

                // Check if question already exists for this quiz
                $existingQuestion = Quiz_content_detail::where('quiz_id', $validated['quiz_id'])
                    ->where('question', $question['question'])
                    ->first();

                if ($existingQuestion) {
                    $errors[] = "Question '{$question['question']}' already exists for this quiz";
                    continue;
                }

                // Create the quiz question
                Quiz_content_detail::create([
                    'quiz_id' => $validated['quiz_id'],
                    'question' => $question['question'],
                    'answers' => $answers,
                    'result' => $result
                ]);

                $successCount++;
            } catch (\Exception $e) {
                $errors[] = "Error adding question '{$question['question']}': " . $e->getMessage();
            }
        }

        $message = $successCount > 0
            ? "Successfully added {$successCount} AI-generated questions"
            : "Failed to add any questions";

        if (count($errors) > 0) {
            $message .= " with " . count($errors) . " errors";
        }

        return back()->with('message', $message)
            ->with('status', $successCount > 0)
            ->with('error', implode(', ', $errors));
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
                // Split answers by tilde (~) instead of comma
                $answers = array_unique(array_filter(array_map('trim', explode('~', $value))));
                if (count($answers) < 2 || count($answers) > 4) {
                    $fail('The answers field must contain between 2 and 4 unique values.');
                }
            }],
            'result' => ['required', 'string', function ($attribute, $value, $fail) {
                // Split answers by tilde (~) instead of comma
                $answers = array_unique(array_filter(array_map('trim', explode('~', request()->input('answers', '')))));
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

<?php

namespace App\Http\Controllers\Courses;

use App\Http\Controllers\Controller;
use App\Models\Category_courses;
use App\Models\Courses;
use App\Models\CoursesContentItem;
use App\Models\Quiz;
use App\Models\QuizResult;
use App\Models\Video;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CoursesController extends Controller
{
    private array $data = [];
    public function index()
    {
        $this->data['courses'] = Courses::with([
            'user:id,name,image',
            'categoryCourses:id,name',
        ])->select('id', 'title', 'price', 'is_free', 'level', 'status', 'category_courses_id', 'user_id','thumbnail','created_at')
            ->filter([request('category_courses_id')])
            ->where('status', 1)
            ->paginate(10)->withQueryString();
        $this->data['category_courses'] = Category_courses::where('status', 1)
            ->select('id', 'name')
            ->get();
        return Inertia::render('Courses/List',$this->data);
    }

    public function detail($id){
        $course_detail = Courses::with([
            'categoryCourses',
            'user:id,name,image',
            'coursesContents' => function($query) {
                $query->where('status', 1)
                    ->with(['contentItems' => function($query) {
                        $query->where('status', 1)
                            ->with(['content']);
                    }]);
            }
        ])
            ->select('id', 'title', 'price', 'is_free', 'level', 'category_courses_id', 'user_id', 'thumbnail', 'description', 'created_at', 'status')
            ->findOrFail($id);

        // Count the total number of course contents
        $content_count = $course_detail->coursesContents->count();

        return Inertia::render('Courses/Detail', [
            'courses_detail' => $course_detail,
            'content_count' => $content_count
        ]);
    }
    public function learning($id,$type,$content_id){
        $user_id = Auth::user()->id;
        $this->data['courses_detail'] = Courses::with([
            'categoryCourses',
            'user:id,name,image',
            'coursesContents' => function($query) {
                $query->where('status', 1)
                    ->with(['contentItems' => function($query) {
                        $query->where('status', 1)
                            ->with(['content']);
                    }]);
            }
        ])
            ->select('id')
            ->findOrFail($id);
        if ($type === 'video') {
            // Fetch video content
            $this->data['content_item'] = Video::findOrFail($content_id);
            $this->data['content_type'] = 'video';
        } elseif ($type === 'quiz') {
            $this->data['content_item'] = Quiz::with(['contentItem', 'quizContentDetails' => function($query) {
                $query->select('id', 'question', 'answers', 'result', 'quiz_id');
            }])->findOrFail($content_id);
            $this->data['content_type'] = 'quiz';
            $this->data['content_item']->quiz_result = QuizResult::select('user_answers')->where('quiz_id', '=', $content_id)
                ->where('user_id', '=', $user_id)->first();
        } else {
            // Handle invalid type
            return redirect()->route('courses.detail', ['id' => $id])
                ->with('error', 'Invalid content type specified.');
        }

        return Inertia::render('Courses/Learning',[
            ...$this->data,
            'message'=>session('message'),
            'status'=>session('status')
        ]);
    }
    public function submitQuiz($id){
        QuizResult::create([
           'user_id'=>Auth::user()->id,
            'quiz_id'=>$id,
            'correct_answers'=>request()->correctAnswers,
            'incorrect_answers'=>request()->inCorrectAnswers,
            'user_answers'=>request()->userAnswers
        ]);
        return back()->with([
           'message'=>'Your result have been saved!',
           'status'=>true
        ]);
    }
    public function resetQuiz($id) {
        $user_id = Auth::user()->id;
        QuizResult::where('quiz_id', $id)
            ->where('user_id', $user_id)
            ->delete();

        return back()->with([
            'message' => 'Quiz reset successfully',
            'status' => true
        ]);
    }
}

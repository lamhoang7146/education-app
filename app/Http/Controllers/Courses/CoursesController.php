<?php

namespace App\Http\Controllers\Courses;

use App\Http\Controllers\Controller;
use App\Models\Category_courses;
use App\Models\Courses;
use App\Models\Quiz;
use App\Models\QuizResult;
use App\Models\Video;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
            ->paginate(8)->withQueryString();
        $this->data['category_courses'] = Category_courses::where('status', 1)
            ->select('id', 'name')
            ->get();
        return Inertia::render('Courses/List',[
            ...$this->data,
            'message'=>session('message'),
            'status'=>session('status')
        ]);
    }

    public function detail($id)
    {
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
            ->select('id', 'title', 'price', 'is_free', 'level', 'category_courses_id', 'user_id', 'thumbnail', 'description', 'created_at', 'status')
            ->findOrFail($id);

        // Count the total number of course contents
        $this->data['content_count'] = $this->data['courses_detail']->coursesContents->count();

        $first_content = $this->data['courses_detail']->coursesContents->first();
        $this->data['first_content_item'] = null;

        if ($first_content) {
            $this->data['first_content_item'] = $first_content->contentItems->first();
        }

        $this->data['hasPurchased'] = false;

        if (auth()->check()) {
            $this->data['hasPurchased'] = DB::table('user_courses')
                ->where('user_id', auth()->id())
                ->where('courses_id', $id)
                ->exists();
        }

        return Inertia::render('Courses/Detail', [
            ...$this->data,
            'message' => session('message'),
            'status' => session('status'),
        ]);
    }

    public function learning($id,$type,$content_id){

        if(empty(Auth::user()->id)){
            return redirect()->route('login');
        }
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
    public function purchased(){
        $userId = Auth::user()->id;
        $this->data['purchased_courses'] = Courses::with(['user:id,name,image'])
            ->join('user_courses', 'courses.id', '=', 'user_courses.courses_id')
            ->join('category_courses', 'courses.category_courses_id', '=', 'category_courses.id')
            ->where('user_courses.user_id', $userId)
            ->filter([request('category_courses_id')])
            ->select(
                'courses.id',
                'courses.title',
                'courses.price',
                'courses.is_free',
                'courses.level',
                'courses.status',
                'courses.category_courses_id',
                'courses.user_id',
                'courses.thumbnail',
                'courses.created_at',
                'category_courses.name as category_name'
            )
            ->paginate(8)->withQueryString();

        $this->data['category_courses'] = Category_courses::where('status', 1)
            ->select('id', 'name')
            ->get();

        return Inertia::render('Courses/Purchased', [
            ...$this->data,
            'message'=>session('message'),
            'status'=>session('status')
        ]);
    }
}

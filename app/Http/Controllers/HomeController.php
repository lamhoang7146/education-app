<?php

namespace App\Http\Controllers;

use App\Models\Category_courses;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
    private array $data = [];
    public function index(){
        $this->data['categories'] = Category_courses::select('id','name')->where('status',1)->get();
        return Inertia::render('Home',$this->data);
    }
}

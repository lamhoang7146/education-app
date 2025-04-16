<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class AiAnalyticsController extends Controller
{
    public function index(){
        return Inertia::render('AiAnalytics',[]);
    }
}

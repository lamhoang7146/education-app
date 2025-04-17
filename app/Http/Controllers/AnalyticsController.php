<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class AnalyticsController extends Controller
{
    private array $data = [];
    public function index(){
        return Inertia::render('Analytics',[
            ...$this->data
        ]);
    }
}

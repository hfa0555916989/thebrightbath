<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\BookChapter;

class HomeController extends Controller
{
    /**
     * Display the home page
     */
    public function index()
    {
        $assessments = Assessment::active()->take(3)->get();
        $chapters = BookChapter::published()->ordered()->take(3)->get();
        
        return view('home', compact('assessments', 'chapters'));
    }
}







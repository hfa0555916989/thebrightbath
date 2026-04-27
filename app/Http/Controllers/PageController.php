<?php

namespace App\Http\Controllers;

class PageController extends Controller
{
    /**
     * Display the about page
     */
    public function about()
    {
        return view('pages.about');
    }

    /**
     * Display vision and mission page
     */
    public function visionMission()
    {
        return view('pages.vision-mission');
    }

    /**
     * Display strategic goals page
     */
    public function strategicGoals()
    {
        return view('pages.strategic-goals');
    }

    /**
     * Display values page
     */
    public function values()
    {
        return view('pages.values');
    }

    /**
     * Display services page
     */
    public function services()
    {
        return view('pages.services');
    }
}







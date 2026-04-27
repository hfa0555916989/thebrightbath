<?php

namespace App\Http\Controllers;

use App\Models\BookChapter;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    /**
     * Display list of book chapters
     */
    public function index()
    {
        $chapters = BookChapter::published()->ordered()->get();
        $user = Auth::user();
        
        return view('book.index', compact('chapters', 'user'));
    }

    /**
     * Display a single chapter
     */
    public function show(string $slug)
    {
        $chapter = BookChapter::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        $user = Auth::user();

        // Check access
        if (!$chapter->canAccess($user)) {
            return view('book.locked', compact('chapter'));
        }

        // Get prev/next chapters for navigation
        $prevChapter = BookChapter::published()
            ->where('order', '<', $chapter->order)
            ->orderBy('order', 'desc')
            ->first();
            
        $nextChapter = BookChapter::published()
            ->where('order', '>', $chapter->order)
            ->orderBy('order')
            ->first();

        return view('book.show', compact('chapter', 'prevChapter', 'nextChapter'));
    }
}







<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BookChapter;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BookChapterController extends Controller
{
    public function index()
    {
        $chapters = BookChapter::ordered()->get();
        return view('admin.book-chapters.index', compact('chapters'));
    }

    public function create()
    {
        return view('admin.book-chapters.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
            'order'   => 'nullable|integer|min:1',
        ]);

        $coverPath = null;
        if ($request->hasFile('cover')) {
            $coverPath = store_upload($request->file('cover'), 'chapters');
        }

        BookChapter::create([
            'title'        => $request->title,
            'slug'         => Str::slug($request->title) . '-' . time(),
            'excerpt'      => $request->description,
            'content_html' => $request->content,
            'order'        => $request->order ?? 1,
            'is_free'      => $request->has('is_free'),
            'is_published' => $request->status === 'published',
            'cover'        => $coverPath,
        ]);

        return redirect()->route('admin.book-chapters.index')
            ->with('success', 'تم إضافة الفصل بنجاح');
    }

    public function edit(BookChapter $bookChapter)
    {
        return view('admin.book-chapters.edit', ['chapter' => $bookChapter]);
    }

    public function update(Request $request, BookChapter $bookChapter)
    {
        $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
            'order'   => 'nullable|integer|min:1',
        ]);

        $data = [
            'title'        => $request->title,
            'excerpt'      => $request->description,
            'content_html' => $request->content,
            'order'        => $request->order ?? $bookChapter->order,
            'is_free'      => $request->has('is_free'),
            'is_published' => $request->status === 'published',
        ];

        if ($request->hasFile('cover')) {
            delete_upload($bookChapter->cover);
            $data['cover'] = store_upload($request->file('cover'), 'chapters');
        }

        $bookChapter->update($data);

        return redirect()->route('admin.book-chapters.index')
            ->with('success', 'تم تحديث الفصل بنجاح');
    }

    public function destroy(BookChapter $bookChapter)
    {
        $bookChapter->delete();
        return back()->with('success', 'تم حذف الفصل بنجاح');
    }
}

<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    public function index()
    {
        $media = Media::all();
        $subcategories = SubCategory::all();
        return view('dashboard.media', compact('media', 'subcategories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:audio,video',
            'path' => 'nullable|string',
            'question' => 'nullable|string|max:255',
            'answer' => 'nullable|string',
            'subcategory_id' => 'required|exists:sub_categorys,id',
        ]);

        $data = $request->all();

        if ($request->type == 'audio' && $request->hasFile('path')) {
            $path = $request->file('path')->store('audio', 'public');
            $data['path'] = $path;
        } elseif ($request->type == 'video') {
            $data['path'] = $request->input('path');
        }

        Media::create($data);

        return redirect()->back()->with('success', 'Media added successfully!');
    }

    public function update(Request $request, Media $medium)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:audio,video',
            'path' => 'nullable|string',
            'question' => 'nullable|string|max:255',
            'answer' => 'nullable|string',
            'subcategory_id' => 'required|exists:sub_categorys,id',
        ]);

        $data = $request->all();

        if ($request->type == 'audio' && $request->hasFile('path')) {
            $path = $request->file('path')->store('audio', 'public');
            $data['path'] = $path;
        } elseif ($request->type == 'video') {
            $data['path'] = $request->input('path');
        }

        $medium->update($data);

        return redirect()->back()->with('success', 'Media updated successfully!');
    }

    public function destroy(Media $medium)
    {
        if ($medium->type == 'audio' && $medium->path) {
            Storage::disk('public')->delete($medium->path);
        }

        $medium->delete();

        return redirect()->back()->with('success', 'Media deleted successfully!');
    }
}

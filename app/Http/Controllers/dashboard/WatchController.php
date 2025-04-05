<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class WatchController extends Controller
{
    public function index()
    {
        $watch = Media::where('type', 'video')->get();
        $subcategories = SubCategory::all();
        return view('dashboard.watch', compact('watch', 'subcategories'));
    }
    // تخزين البيانات
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'question' => 'nullable|string',
            'answer' => 'nullable|string',
            'subcategory_id' => 'required|exists:sub_categorys,id',
        ]);

        // معالجة رفع الملفات
        Media::create([
            'title' => $request->title,
            'description' => $request->description,
            'type' => 'video',
            'question' => $request->question,
            'answer' => $request->answer,
            'subcategory_id' => $request->subcategory_id,
        ]);
        return redirect()->route('media.index')->with('success', 'تم إنشاء العنصر بنجاح.');
    }

    // تحديث البيانات
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'question' => 'nullable|string',
            'answer' => 'nullable|string',
            'subcategory_id' => 'required|exists:sub_categorys,id',
        ]);

        $media = Media::findOrFail($id);
        $media->update($request->all());
        return redirect()->route('media.index')->with('success', 'تم تحديث العنصر بنجاح.');
    }

    // حذف العنصر
    public function destroy($id)
    {
        $media = Media::findOrFail($id);
        $media->delete();
        return redirect()->route('media.index')->with('success', 'تم حذف العنصر بنجاح.');
    }
}

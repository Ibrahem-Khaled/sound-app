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
            'title.*' => 'nullable|string|max:255',
            'description.*' => 'nullable|string',
            'type.*' => 'required|in:audio,video',
            'path.*' => 'required',
            'path.*' => 'nullable|file|mimes:mp3,wav,ogg',
            'question.*' => 'nullable|string|max:255',
            'answer.*' => 'nullable|string',
            'subcategory_id.*' => 'required|exists:sub_categories,id',
        ]);

        $titles = $request->title;
        $descriptions = $request->description;
        $types = $request->type;
        $paths = $request->path;
        $questions = $request->question;
        $answers = $request->answer;
        $subcategoryIds = $request->subcategory_id;

        foreach ($types as $index => $type) {
            $data = [
                'title' => $titles[$index] ?? null,
                'description' => $descriptions[$index] ?? null,
                'type' => $type,
                'question' => $questions[$index] ?? null,
                'answer' => $answers[$index] ?? null,
                'subcategory_id' => $subcategoryIds[$index],
            ];

            if ($type === 'audio' && isset($paths[$index]) && is_file($paths[$index])) {
                $data['path'] = $paths[$index]->store('media', 'public'); // رفع الملف
            } elseif ($type === 'video') {
                $data['path'] = $paths[$index]; // حفظ الرابط
            }

            Media::create($data);
        }

        return redirect()->back()->with('success', 'تمت إضافة الوسائط بنجاح!');
    }



    public function update(Request $request, Media $medium)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:audio,video',
            'path' => 'nullable|file',
            'question' => 'nullable|string|max:255',
            'answer' => 'nullable|string',
            'subcategory_id' => 'required', // تعديل بسيط هنا لتصحيح الاسم
        ]);

        $data = $request->except('path'); // استخدم except لاستبعاد الحقل path من البيانات
        if ($request->hasFile('path')) {
            // حذف الملف القديم إذا كان موجود
            if ($medium->type == 'audio' && $medium->path) {
                Storage::disk('public')->delete($medium->path);
            }

            $path = $request->file('path')->store('media', 'public'); // تخزين الملف في مجلد media
            $data['path'] = $path;
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

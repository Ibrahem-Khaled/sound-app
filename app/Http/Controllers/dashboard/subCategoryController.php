<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class subCategoryController extends Controller
{
    public function index()
    {
        $subcategories = SubCategory::all();
        $categories = Category::all();
        return view('dashboard.subcategories', compact('subcategories', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'category_id' => 'required|exists:categorys,id',
        ]);

        SubCategory::create($request->all());

        return redirect()->back()->with('success', 'Subcategory added successfully!');
    }

    public function update(Request $request, SubCategory $subcategory)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'category_id' => 'required|exists:categorys,id',
        ]);

        $subcategory->update($request->all());

        return redirect()->back()->with('success', 'Subcategory updated successfully!');
    }

    public function destroy(SubCategory $subcategory)
    {
        $subcategory->delete();

        return redirect()->back()->with('success', 'Subcategory deleted successfully!');
    }
}

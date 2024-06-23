<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::all();
        return view('dashboard.books', compact('books'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
            $data['image'] = $imagePath;
        }

        Book::create($data);

        return redirect()->back()->with('success', 'Book added successfully!');
    }

    public function update(Request $request, Book $book)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
            $data['image'] = $imagePath;
        }

        $book->update($data);

        return redirect()->back()->with('success', 'Book updated successfully!');
    }

    public function destroy(Book $book)
    {
        if ($book->image) {
            \Storage::disk('public')->delete($book->image);
        }

        $book->delete();

        return redirect()->back()->with('success', 'Book deleted successfully!');
    }
}

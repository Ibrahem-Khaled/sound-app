<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use App\Models\Media;
use App\Models\User;
use Illuminate\Http\Request;

class ApiController extends Controller
{

    public function categorieyVideo()
    {
        $categories = Category::whereHas('subCategoreys.media', function ($query) {
            $query->where('type', 'video');
        })->get();

        return response()->json($categories, 200);
    }
    public function categorieyAudio()
    {
        $categories = Category::whereHas('subCategoreys.media', function ($query) {
            $query->where('type', 'audio');
        })->get();

        return response()->json($categories, 200);
    }

    public function subCategoreys($id)
    {
        $subcategories = Category::find($id);
        $subcategories->subCategoreys;
        return response()->json($subcategories, 200);
    }

    public function media($type, $subCateId)
    {
        $media = Media::where('type', $type)->where('subcategory_id', $subCateId)->get();
        return response()->json($media, 200);
    }

    public function sovieMedia($id)
    {
        $media = Media::find($id);
        return response()->json($media, 200);
    }

    public function aboutUs()
    {
        $user = User::first();
        return response()->json($user, 200);
    }

    public function books()
    {
        $books = Book::all();
        return response()->json($books, 200);
    }

    public function search($query)
    {
        $search = Media::where('title', 'like', '%' . $query . '%')->get();
        $books = Book::where('name', 'like', '%' . $query . '%')->orWhere('slug', 'like', '%' . $query . '%')->get();
        return response()->json(['search' => $search, 'books' => $books], 200);
    }
}

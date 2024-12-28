<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Models\Media;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $usersCount = User::count();
        $categoriesCount = Category::count();
        $booksCount = Book::count();
        $mediaCount = Media::count();

        // بيانات الرسوم البيانية للأنشطة
        $mediaActivity = Media::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        $bookActivity = Book::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        // ترتيب البيانات حسب الأشهر
        $months = ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو', 'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'];
        $mediaData = [];
        $bookData = [];
        for ($i = 1; $i <= 12; $i++) {
            $mediaData[] = $mediaActivity[$i] ?? 0;
            $bookData[] = $bookActivity[$i] ?? 0;
        }

        return view('home', compact('usersCount', 'categoriesCount', 'booksCount', 'mediaCount', 'months', 'mediaData', 'bookData'));
    }



    public function login()
    {
        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

}

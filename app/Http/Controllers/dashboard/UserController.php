<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('dashboard.users', compact('users'));
    }

    public function store(Request $request)
    {
        $validatedData = $this->validateUserData($request);

        // Handle file upload
        if ($request->hasFile('image')) {
            $validatedData['image'] = $this->saveImage($request->file('image'));
        }

        User::create($validatedData);

        return redirect()->back()->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        return response()->json($user);
    }

    public function update(Request $request, User $user)
    {
        $validatedData = $this->validateUserData($request, $user->id);

        // Handle file upload
        if ($request->hasFile('image')) {
            $validatedData['image'] = $this->saveImage($request->file('image'));
        }

        $user->update($validatedData);

        return redirect()->back()->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        try {
            $user->delete();
            return redirect()->back()->with('success', 'User deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while deleting the user: ' . $e->getMessage());
        }
    }

    private function validateUserData(Request $request, $userId = null)
    {

        return $request->validate([
            'name' => 'required',
            'email' => 'nullable|email',
            'phone' => 'nullable',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description' => 'nullable',
            'facebook' => 'nullable',
            'twitter' => 'nullable',
            'instagram' => 'nullable',
            'linkedin' => 'nullable',
            'telegram' => 'nullable',
        ]);
    }

    private function saveImage($image)
    {
        $path = $image->store('images', 'public');
        return $path;
    }
}

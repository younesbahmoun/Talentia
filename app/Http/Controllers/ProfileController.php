<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    // public function show()
    // {
    //     $user = Auth::user();
    //     return view('profile', compact('user'));
    // }

     public function show()
    {
        $user = Auth::user();
        return view('profile/profile', compact('user'));
    }

    public function edit()
    {
        $user = Auth::user();
        return view('profile/action/edit', compact('user'));
    }

    // public function update (Request $request) {
    //     // $user = Auth::user();
    //     // $user->update($request->all());
    //     return redirect()->route('profile')->with('success', 'Profile updated successfully!');
    // }
}
<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    // public function show()
    // {
    //     $user = Auth::user();
    //     return view('profile', compact('user'));
    // }

     public function show() {
        $user = Auth::user();
        return view('utilisateur/profile/profile', compact('user'));
    }

    public function edit()
    {
        $user = Auth::user();
        return view('utilisateur/profile/action/edit', compact('user'));
    }

    public function update (Request $request) {
        $user = Auth::user();
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'specialite' => ['nullable', 'string', 'max:255'],
            'bio' => ['nullable', 'string'],
            'photo' => ['nullable', 'image', 'max:2048'],
            // 'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Handle photo upload and store as a public URL
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('photos', 'public');
            $data['photo'] = '/storage/' . $path;
        }

        $user->fill($data);
        $user->save();

        return redirect()->route('profile.show')->with('text', 'Profile updated successfully!')
            ->with('type', 'success');
    }

    public function allProfiles () {
        $profiles = User::paginate(10);
        return view('utilisateur/profile/all', compact('profiles'));
    }


}
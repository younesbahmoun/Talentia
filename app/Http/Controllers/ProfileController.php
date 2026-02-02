<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    
    public function show() {
        $user = Auth::user();
        return view('utilisateur/profile/profile', compact('user'));
    }

    public function edit() {
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

    public function allProfiles(Request $request) {
        $nom = $request->input('nom');
        $specialite = $request->input('specialite');

        $query = User::query();

        if (!empty($nom)) {
            $query->where('name', 'like', '%' . $nom . '%');
        }

        if (!empty($specialite)) {
            $query->where('specialite', 'like', '%' . $specialite . '%');
        }

        $profiles = $query->paginate(10);

        return view('utilisateur/profile/all', compact('profiles'));
    }

                /**
     * POST /profiles
     * Recherche simple par nom (name/prenom).
     */
    // public function allProfiless(Request $request)
    // {
    //     $search = (string) $request->input('search', '');

    //     $query = User::query();

    //     if ($search !== '') {
    //         $query->where(function ($q) use ($search) {
    //             $q->where('name', 'LIKE', "%{$search}%")
    //               ->orWhere('prenom', 'LIKE', "%{$search}%");
    //         });
    //     }

    //     $profiles = $query->orderBy('name')->paginate(10);

    //     return view('utilisateur/profile/all', compact('profiles', 'search'));
    // }


    public function showDetails ($id) {
        $user = User::findOrFail($id);
        return view('utilisateur/profile/detail-profile', compact('user'));
    }


    // POST route now points to `allProfiles` so `allProfiless` is removed.

}
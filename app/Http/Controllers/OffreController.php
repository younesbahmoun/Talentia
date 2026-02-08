<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Offre;

class OffreController extends Controller
{
    public function index() {
        $offres = Offre::all();
        return view('offres.index', compact('offres'));
    }

    public function show(Request $request) {
        $offres = Offre::find($request->id);
        return view('offres.detail', compact('offres'));
    }

    public function store(Request $request) {
        $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|string|max:255',
            'entreprise' => 'required|string|max:255',
            'type_contrat' => 'required|in:CDI,CDD,Freelance,Stage,Alternance',
        ]);

        // Offre::create($request->all());
        Offre::create([
            'titre' => $request->titre,
            'description' => $request->description,
            'image' => $request->image,
            'entreprise' => $request->entreprise,
            'type_contrat' => $request->type_contrat,
            'status' => 'ouvert',
            'user_id' => auth()->user()->id,
        ]);
        return redirect()->route('offres.create')->with('success', 'Offre créée avec succès!');
    }

    public function create() {
        return view('recrutur.offres.create');
    }
}

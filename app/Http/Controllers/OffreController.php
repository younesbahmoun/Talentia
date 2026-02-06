<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OffreController extends Controller
{
    public function index()
    {
        $offres = Offre::all();
        return view('offres.index', compact('offres'));
    }

    public function create()
    {
        return view('offres.create');
    }

    public function store(Request $request) {
        $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|string|max:255',
            'entreprise' => 'required|string|max:255',
            'type_contrat' => 'required|in:CDI,CDD,Freelance,Stage,Alternance',
            'status' => 'required|in:ferme,ouvert',
            'user_id' => 'required|exists:users,id',
        ]);

        Offre::create($request->all());

        return redirect()->route('offres.index')->with('success', 'Offre créée avec succès!');
    }
}

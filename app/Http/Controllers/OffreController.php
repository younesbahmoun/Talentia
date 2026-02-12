<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Offre;
use App\Models\Application;

class OffreController extends Controller
{
    public function index() {
        $offres = Offre::all();
        return view('offres.index', compact('offres'));
    }

    public function show($id) {
        $offres = Offre::findOrFail($id);
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

    public function apply(Request $request, $id) {
        $application = Application::firstOrCreate(
            [
                'user_id' => auth()->id(),
                'offre_id' => $id
            ],
            [
                'status' => 'pending'
            ]
        );

        if ($application->wasRecentlyCreated) {
            return redirect()->back()->with('success', 'Votre candidature a été envoyée avec succès!');
        } else {
            return redirect()->back()->with('info', 'Vous avez déjà postulé à cette offre.');
        }
    }

    public function candidats() {
        $user = auth()->user();
        $offres = Offre::where('user_id', $user->id)
                       ->with(['applications.user'])
                       ->get();
        
        return view('recrutur.offres.candidats', compact('offres'));
    }
}

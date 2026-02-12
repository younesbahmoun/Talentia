<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Offre;
use App\Models\Application;
use App\Events\NewNotification;
use App\Notifications\ApplicationStatusNotification;
use Illuminate\Support\Facades\Log;
use Throwable;

class OffreController extends Controller
{
    public function index()
    {
        $offres = Offre::all();
        return view('offres.index', compact('offres'));
    }

    public function show($id)
    {
        $offres = Offre::findOrFail($id);
        return view('offres.detail', compact('offres'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|string|max:255',
            'entreprise' => 'required|string|max:255',
            'type_contrat' => 'required|in:CDI,CDD,Freelance,Stage,Alternance',
        ]);

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

    public function create()
    {
        return view('recrutur.offres.create');
    }

    public function apply(Request $request, $id)
    {
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

    public function candidats()
    {
        $user = auth()->user();
        $offres = Offre::where('user_id', $user->id)
                       ->with(['applications.user'])
                       ->get();
        
        return view('recrutur.offres.candidats', compact('offres'));
    }

    /**
     * Update the status of an application (accept or reject).
     */
    public function updateApplicationStatus(Request $request, Application $application)
    {
        $request->validate([
            'status' => 'required|in:accepted,rejected',
        ]);

        // Verify the recruiter owns the offre
        if ($application->offre->user_id !== auth()->id()) {
            abort(403, 'Non autorisé.');
        }

        $application->update(['status' => $request->status]);
        $application->load('offre');

        // Notify the candidate
        $candidate = $application->user;
        $candidate->notify(new ApplicationStatusNotification($application));

        // Broadcast real-time notification
        $statusText = $request->status === 'accepted' ? 'acceptée' : 'refusée';
        $this->broadcastSafely(new NewNotification(
            $candidate->id,
            'application_status',
            [
                'message' => 'Votre candidature pour "' . $application->offre->titre . '" a été ' . $statusText . '.',
                'offre_id' => $application->offre_id,
                'status' => $request->status,
            ]
        ));

        return back()->with('success', 'Statut de la candidature mis à jour.');
    }

    private function broadcastSafely(object $event): void
    {
        try {
            broadcast($event);
        } catch (Throwable $exception) {
            Log::warning('Real-time broadcast failed on offer update', [
                'event' => $event::class,
                'message' => $exception->getMessage(),
            ]);
        }
    }
}

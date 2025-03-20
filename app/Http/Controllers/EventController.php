<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Event::with('organisateur');
        if ($request->has('my_events') && auth()->check() && (auth()->user()->isAdmin() || auth()->user()->isOrganisateur())) {
            $query->where('organisateur_id', auth()->user()->id);
        }
        $events = $query->paginate(10);
        return view('events.index', ['events' => $events]);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        return view('event.index', compact('event'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        //
    }

    public function register(Event $event)
    {
        $user = auth()->user();

         if (!$user->isClient()) {
            return redirect()->back()->with('error', "Seuls les clients peuvent s'inscrire.");
        }

         if ($event->status !== 'remplissage_en_cours') {
            return redirect()->back()->with('error', "Les inscriptions ne sont pas ouvertes pour cet événement.");
        }

         if ($event->clients()->count() >= $event->max_participants) {
            return redirect()->back()->with('error', "Le nombre maximum de participants est atteint.");
        }

         if (!$event->clients()->where('user_id', $user->id)->exists()) {
            $event->clients()->attach($user->id);
        }

        return redirect()->back()->with('success', "Vous êtes inscrit à l'événement.");
    }

    public function unregister(Event $event)
    {
        $user = auth()->user();

         if (!$user->isClient()) {
            return redirect()->back()->with('error', "Seuls les clients peuvent se désinscrire.");
        }

         $event->clients()->detach($user->id);

        return redirect()->back()->with('success', "Vous êtes désinscrit de l'événement.");
    }
}

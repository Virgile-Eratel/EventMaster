<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // les events de l'orga co
        $query = Event::with('organisateur');
        if ($request->has('my_events') && auth()->check() && (auth()->user()->isAdmin() || auth()->user()->isOrganisateur())) {
            $query->where('organisateur_id', auth()->user()->id);
        }
        // les events du client co
        if ($request->has('registered') && auth()->check() && auth()->user()->isClient()) {
            $user = auth()->user();
            $query->whereHas('clients', function($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }
        $events = $query->paginate(10);
        return view('events.index', ['events' => $events]);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    }
    public function showCreate()
    {
        return view('events.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title'          => 'required|string|max:255',
            'banner_image'   => 'nullable|image',
            'description'    => 'nullable|string',
            'event_date'     => 'required|date',
            'address'        => 'nullable|string',
            'latitude'       => 'nullable|numeric',
            'longitude'      => 'nullable|numeric',
            'status'         => 'required|string',
            'max_participants' => 'required|integer',
            'organisateur_id' => 'required|exists:users,id',
        ]);

        if ($request->hasFile('banner_image')) {
             $data['banner_image'] = $request->file('banner_image')->store('/images/events', 'public');
        }

        Event::create($data);

        return redirect()->route('events')->with('success', "L'événement a bien été créé.");
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
        if (auth()->user()->isAdmin() || (auth()->user()->isOrganisateur() && auth()->user()->id == $event->organisateur_id)) {
            return view('events.edit', compact('event'));
        }
        abort(403, 'Vous n\'êtes pas autorisé à modifier cet événement.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
         if (!(auth()->user()->isAdmin() || (auth()->user()->isOrganisateur() && auth()->user()->id == $event->organisateur_id))) {
            abort(403, 'Vous n\'êtes pas autorisé à modifier cet événement.');
        }

        $data = $request->validate([
            'title'          => 'required|string|max:255',
            'banner_image'   => 'nullable|image',
            'description'    => 'nullable|string',
            'event_date'     => 'required|date',
            'address'        => 'nullable|string',
            'latitude'       => 'nullable|numeric',
            'longitude'      => 'nullable|numeric',
            'status'         => 'required|string',
            'max_participants' => 'required|integer',
        ]);

        if ($request->hasFile('banner_image')) {
            $data['banner_image'] = $request->file('banner_image')->store('images/events', 'public');
        }

        $event->update($data);

        return redirect()->route('event.show', $event)->with('success', "L'événement a bien été mis à jour.");
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

        $event->updateStatus();

        return redirect()->back()->with('success', "Vous êtes inscrit à l'événement.");
    }

    public function unregister(Event $event)
    {
        $user = auth()->user();

         if (!$user->isClient()) {
            return redirect()->back()->with('error', "Seuls les clients peuvent se désinscrire.");
        }

         $event->clients()->detach($user->id);

         $event->updateStatus();

        return redirect()->back()->with('success', "Vous êtes désinscrit de l'événement.");
    }
}

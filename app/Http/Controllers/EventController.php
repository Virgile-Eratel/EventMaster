<?php

namespace App\Http\Controllers;

use App\Mail\EventCancelledMail;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
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
            'is_free'        => 'required|boolean',
            'price'          => 'required_if:is_free,0|numeric|min:0',
        ]);

        if ($request->hasFile('banner_image')) {
             $data['banner_image'] = $request->file('banner_image')->store('/images/events', 'public');
        }

        if ($data['is_free']) {
            $data['price'] = 0;
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

        if ($request->has('title')) {
            $event->title = $request->input('title');
        }

        if ($request->has('description')) {
            $event->description = $request->input('description');
        }

        if ($request->has('event_date')) {
            $event->event_date = $request->input('event_date');
        }

        if ($request->has('address')) {
            $event->address = $request->input('address');
        }

        if ($request->has('status')) {
            $event->status = $request->input('status');
        }

        if ($request->has('max_participants')) {
            $event->max_participants = $request->input('max_participants');
        }

        // Handle is_free field which might be a string "0"/"1" or boolean
        if ($request->has('is_free')) {
            $event->is_free = filter_var($request->input('is_free'), FILTER_VALIDATE_BOOLEAN);
        }

        // Always set price to ensure it's not null
        if ($request->has('price') && !$event->is_free) {
            $event->price = $request->input('price');
        } else {
            // Default to 0 for free events or if price is not provided
            $event->price = 0;
        }

        if ($request->has('latitude')) {
            $event->latitude = $request->input('latitude');
        }

        if ($request->has('longitude')) {
            $event->longitude = $request->input('longitude');
        }

        if ($request->hasFile('banner_image')) {
            $event->banner_image = $request->file('banner_image')->store('images/events', 'public');
        }

        $event->save();

        return redirect()->route('event.show', $event)->with('success', "L'événement a bien été mis à jour.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        //
    }

    public function cancel(Event $event)
    {
        $user = auth()->user();

         if (!($user->isAdmin() || ($user->isOrganisateur() && $event->organisateur_id == $user->id))) {
            abort(403, 'Vous n\'êtes pas autorisé à annuler cet événement.');
        }

         $event->update(['status' => 'annule']);

        foreach ($event->clients as $client) {
            Mail::to($client->email)->sendNow(new EventCancelledMail($event, $client));
        }

        return redirect()->back()->with('success', "L'événement a été annulé et un mail a été envoyé aux participants.");
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

        if ($event->clients()->where('user_id', $user->id)->exists()) {
            return redirect()->back()->with('error', "Vous êtes déjà inscrit à cet événement.");
        }

        return redirect()->route('payment.checkout', ['event' => $event]);
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

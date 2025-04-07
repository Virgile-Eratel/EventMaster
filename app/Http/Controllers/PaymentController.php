<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\Exception\ApiErrorException;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function checkout(Event $event)
    {

        if ($event->is_free) {

            $event->clients()->attach(Auth::id());
            $event->updateStatus();
            return redirect()->route('event.show', $event)->with('success', 'Vous êtes inscrit à cet événement.');
        }


        if ($event->clients->contains(Auth::id())) {
            return redirect()->route('event.show', $event)->with('error', 'Vous êtes déjà inscrit à cet événement.');
        }


        if ($event->status === 'complet' || $event->clients->count() >= $event->max_participants) {
            return redirect()->route('event.show', $event)->with('error', 'Cet événement est complet.');
        }


        if ($event->status === 'annule') {
            return redirect()->route('event.show', $event)->with('error', 'Cet événement a été annulé.');
        }

        try {

            Stripe::setApiKey(config('stripe.secret'));


            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => $event->title,
                            'description' => substr($event->description, 0, 255),
                        ],
                        'unit_amount' => (int)($event->price * 100),
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('payment.success', ['event' => $event->id]) . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('payment.cancel', ['event' => $event->id]),
                'client_reference_id' => $event->id . '_' . Auth::id(),
                'metadata' => [
                    'event_id' => $event->id,
                    'user_id' => Auth::id(),
                ],
            ]);

            return view('payment.checkout', [
                'event' => $event,
                'sessionId' => $session->id,
                'stripeKey' => config('stripe.key'),
            ]);
        } catch (ApiErrorException $e) {
            return redirect()->route('event.show', $event)->with('error', 'Erreur lors de la création de la session de paiement : ' . $e->getMessage());
        }
    }

    public function success(Request $request, Event $event)
    {

        if ($event->clients->contains(Auth::id())) {
            return redirect()->route('event.show', $event)->with('success', 'Vous êtes déjà inscrit à cet événement.');
        }


        $sessionId = $request->query('session_id');
        if (!$sessionId) {
            return redirect()->route('event.show', $event)->with('error', 'Session de paiement invalide.');
        }

        try {

            Stripe::setApiKey(config('stripe.secret'));


            $session = Session::retrieve($sessionId);


            $metadata = $session->metadata;
            if ($metadata->event_id != $event->id || $metadata->user_id != Auth::id()) {
                return redirect()->route('event.show', $event)->with('error', 'Session de paiement invalide.');
            }


            if ($session->payment_status !== 'paid') {
                return redirect()->route('event.show', $event)->with('error', 'Le paiement n\'a pas été effectué.');
            }


            $event->clients()->attach(Auth::id());
            $event->updateStatus();

            return redirect()->route('event.show', $event)->with('success', 'Paiement réussi ! Vous êtes inscrit à cet événement.');
        } catch (ApiErrorException $e) {
            return redirect()->route('event.show', $event)->with('error', 'Erreur lors de la vérification du paiement : ' . $e->getMessage());
        }
    }

    public function cancel(Event $event)
    {
        return redirect()->route('event.show', $event)->with('error', 'Paiement annulé.');
    }
}

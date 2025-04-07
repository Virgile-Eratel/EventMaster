@component('mail::message')
    # Annulation de l'événement

    Bonjour {{ $client->name }},

    Nous vous informons que l'événement **{{ $event->title }}** prévu le {{ \Carbon\Carbon::parse($event->event_date)->format('d/m/Y H:i') }} a été annulé.

    @component('mail::button', ['url' => route('event.show', $event)])
        Voir l'événement
    @endcomponent

    Cordialement,
    L'équipe d'organisation
@endcomponent

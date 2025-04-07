@component('mail::message')
    # Rappel d'événement

    Bonjour {{ $client->name }},

    Ceci est un rappel que l'événement **{{ $event->title }}** aura lieu dans 24 heures, le {{ \Carbon\Carbon::parse($event->event_date)->format('d/m/Y H:i') }}.

    @component('mail::button', ['url' => route('event.show', $event)])
        Voir l'événement
    @endcomponent

    À bientôt,
    L'équipe d'organisation c'est moi Virgile MAAMMENNN
@endcomponent

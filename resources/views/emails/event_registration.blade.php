@component('mail::message')
# Confirmation d'inscription

Bonjour {{ $user->name }},

Nous vous confirmons votre inscription à l'événement **{{ $event->title }}**.

## Détails de l'événement

**Date :** {{ $event->event_date->format('d/m/Y à H:i') }}

**Lieu :** {{ $event->address }}

@if($isPaid)
**Paiement :** Votre paiement de {{ number_format($event->price, 2, ',', ' ') }} € a bien été reçu.
@else
**Événement gratuit**
@endif

## Description de l'événement

{{ $event->description }}

Nous sommes impatients de vous y retrouver !

Cordialement,<br>
L'équipe EventMaster
@endcomponent

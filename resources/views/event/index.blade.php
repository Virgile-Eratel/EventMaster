<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Détails de l'événement : {{ $event->title }}
        </h2>
    </x-slot>
    <div class="container mx-auto px-4 mt-6">
        <div class="bg-white shadow-md rounded p-6">
            <p class="mb-2"><span class="font-bold">ID :</span> {{ $event->id }}</p>
            <p class="mb-2"><span class="font-bold">Titre :</span> {{ $event->title }}</p>
            <p class="mb-2">
                <span class="font-bold">Date :</span>
                {{ \Carbon\Carbon::parse($event->event_date)->format('d/m/Y H:i') }}
            </p>
            <p class="mb-2"><span class="font-bold">Organisateur :</span> {{ $event->organisateur->name }}</p>
            <p class="mb-2"><span class="font-bold">Statut :</span> {{ $event->status }}</p>
            <p class="mb-2"><span class="font-bold">Max Participants :</span> {{ $event->max_participants }}</p>
            <!-- Ajoutez d'autres informations si nécessaire -->
        </div>
    </div>
</x-app-layout>

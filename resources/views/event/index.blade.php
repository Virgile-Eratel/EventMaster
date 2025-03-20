<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Détails de l'événement : {{ $event->title }}
        </h2>
    </x-slot>
    <div class="container mx-auto px-4 mt-6">
        <div class="bg-white shadow-md rounded mb-6">
            @if($event->banner_image)
                <div class="w-full">
                    <img src="{{ asset('storage/' . $event->banner_image) }}" alt="{{ $event->title }}"
                         class="w-full h-64 object-cover rounded-t">
                </div>
            @endif
            <div class="p-6">
            <p class="mb-2"><span class="font-bold">ID :</span> {{ $event->id }}</p>
            <p class="mb-2"><span class="font-bold">Titre :</span> {{ $event->title }}</p>
            <p class="mb-2">
                <span class="font-bold">Date :</span>
                {{ \Carbon\Carbon::parse($event->event_date)->format('d/m/Y H:i') }}
            </p>
            <p class="mb-2"><span class="font-bold">Organisateur :</span> {{ $event->organisateur->name }}</p>
            <p class="mb-2"><span class="font-bold">Statut :</span> {{ $event->status }}</p>
            <p class="mb-2"><span class="font-bold">Max Participants :</span> {{ $event->max_participants }}</p>

            <!-- Intégration de la liste des participants dans le même bloc -->
            <div x-data="{ open: false }">
                <p class="mb-2">
                    <span class="font-bold">Participants ({{ $event->clients->count() }}) :</span>
                    <button @click="open = !open" class="ml-2 text-blue-500 underline">
                        Afficher
                    </button>
                </p>
                <div x-show="open" x-cloak class="mt-2">
                    @if($event->clients->count() > 0)
                        <ul class="list-disc list-inside">
                            @foreach($event->clients as $client)
                                <li>{{ $client->name }}</li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-gray-600">Aucun participant pour le moment.</p>
                    @endif
                </div>
            </div>
            </div>
        </div>

        @if(auth()->check() && auth()->user()->isClient())
            @if($event->clients->contains(auth()->user()->id))
                 <form action="{{ route('event.unregister', $event) }}" method="POST" class="mb-4">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                        Désinscrire
                    </button>
                </form>
            @else
                 @if($event->status === 'remplissage_en_cours' && $event->clients->count() < $event->max_participants)
                    <form action="{{ route('event.register', $event) }}" method="POST" class="mb-4">
                        @csrf
                        <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            S'inscrire
                        </button>
                    </form>
                @else
                    <p class="text-gray-600">Les inscriptions ne sont pas ouvertes ou le nombre maximum de participants est atteint.</p>
                @endif
            @endif
        @endif
    </div>
</x-app-layout>

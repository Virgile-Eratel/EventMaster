<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Liste des évenements') }}
        </h2>
    </x-slot>
    <div class="container mx-auto px-4 mt-6">

        @if(auth()->check() && (auth()->user()->isAdmin() || auth()->user()->isOrganisateur()))
            <div class="mb-4">
                @if(request()->has('my_events'))
                    <button onclick="window.location.href='{{ route('events') }}'" class="font-bold py-2 px-4 rounded" style="background-color: #3b82f6; color: white;">
                        Vers tous les événements
                    </button>
                @else
                    <button onclick="window.location.href='{{ route('events', ['my_events' => 1]) }}'" class="font-bold py-2 px-4 rounded" style="background-color: #3b82f6; color: white;">
                        Vers mes événements
                    </button>
                @endif
            </div>
        @endif
            @if(auth()->check() && auth()->user()->isClient())
                <div class="mb-4">
                    @if(request()->has('registered'))
                        <button onclick="window.location.href='{{ route('events') }}'" class="font-bold py-2 px-4 rounded" style="background-color: #3b82f6; color: white;">
                            Voir tous les événements
                        </button>
                    @else
                        <button onclick="window.location.href='{{ route('events', ['registered' => 1]) }}'" class="font-bold py-2 px-4 rounded" style="background-color: #3b82f6; color: white;">
                            Voir mes inscriptions
                        </button>
                    @endif
                </div>
            @endif

        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3">ID</th>
                    <th scope="col" class="px-6 py-3">Titre</th>
                    <th scope="col" class="px-6 py-3">Date</th>
                    <th scope="col" class="px-6 py-3">Organisateur</th>
                    <th scope="col" class="px-6 py-3">Statut</th>
                    <th scope="col" class="px-6 py-3">Max Participants</th>
                </tr>
                </thead>
                <tbody>
                @foreach($events as $event)
                    <tr onclick="window.location.href='{{ route('event.show', $event) }}'" class="bg-white border-b hover:bg-gray-50 cursor-pointer">
                        <td class="px-6 py-4 text-gray-900">{{ $event->id }}</td>
                        <td class="px-6 py-4 text-gray-900">{{ $event->title }}</td>
                        <td class="px-6 py-4 text-gray-900">{{ \Carbon\Carbon::parse($event->event_date)->format('d/m/Y H:i') }}</td>
                        <td class="px-6 py-4 text-gray-900">{{ $event->organisateur->name }}</td>
                        <td class="px-6 py-4 text-gray-900">{{ $event->status }}</td>
                        <td class="px-6 py-4 text-gray-900">{{ $event->max_participants }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $events->links() }}
        </div>
    </div>
</x-app-layout>

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
                    <button onclick="window.location.href='{{ route('events') }}'" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Vers tous les événements
                    </button>
                @else
                    <button onclick="window.location.href='{{ route('events', ['my_events' => 1]) }}'" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Vers mes événements
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
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <td class="px-6 py-4">{{ $event->id }}</td>
                        <td class="px-6 py-4">{{ $event->title }}</td>
                        <td class="px-6 py-4">{{ \Carbon\Carbon::parse($event->event_date)->format('d/m/Y H:i') }}</td>
                        <td class="px-6 py-4">{{ $event->organisateur->name }}</td>
                        <td class="px-6 py-4">{{ $event->status }}</td>
                        <td class="px-6 py-4">{{ $event->max_participants }}</td>
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

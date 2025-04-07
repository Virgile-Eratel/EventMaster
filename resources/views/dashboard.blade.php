<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            @if(auth()->check())
                @if(auth()->user()->isAdmin())
                    {{ __('Dashboard Admin') }}
                @elseif(auth()->user()->isOrganisateur())
                    {{ __('Dashboard Organisateur') }}
                @elseif(auth()->user()->isClient())
                    {{ __('Dashboard Client') }}
                @else
                    {{ __('Dashboard') }}
                @endif
            @else
                {{ __('Dashboard') }}
            @endif        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                {{-- Section pour le client --}}
                @if(auth()->check() && auth()->user()->isClient())
                    <h3 class="text-lg font-bold mb-4">Mes prochains événements</h3>

                    @php
                        $registeredEvents = auth()->user()->registeredEvents()
                           ->where('event_date', '>=', now())
                           ->orderBy('event_date', 'asc')
                           ->limit(5)
                           ->get();
                    @endphp

                    @if($registeredEvents->count() > 0)
                        <ul class="space-y-2">
                            @foreach($registeredEvents as $event)
                                <li>
                                    <a href="{{ route('event.show', $event) }}" class="block p-4 bg-gray-100 rounded hover:bg-gray-200 transition-colors">
                                        <div class="flex justify-between items-center">
                                            <span class="font-semibold">{{ $event->title }}</span>
                                            <span class="text-sm text-gray-600">
                                                {{ \Carbon\Carbon::parse($event->event_date)->locale('fr')->diffForHumans() }}
                                            </span>
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-gray-600">Vous n'êtes inscrit à aucun événement :'(</p>
                    @endif
                @endif

                {{-- Bouton de création d'événement pour admin/organisateur --}}
                @if(auth()->check() && (auth()->user()->isAdmin() || auth()->user()->isOrganisateur()))
                    <div class="mt-6">
                        <a href="{{ route('events.create') }}"
                           class="inline-block font-bold py-2 px-4 rounded" style="background-color: #3b82f6; color: white;">
                            Créer un événement
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
             <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
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
            </div>
        </div>
    </div>
</x-app-layout>

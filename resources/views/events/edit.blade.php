<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Modifier l'événement
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if(auth()->check() && (auth()->user()->isAdmin() || auth()->user()->isOrganisateur()))
                    <form action="{{ route('event.update', $event) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                         <div class="mb-4">
                            <label for="title" class="block text-gray-700 font-medium">Titre * :</label>
                            <input type="text" name="title" id="title" value="{{ old('title', $event->title) }}" required
                                   class="mt-1 block w-full border-gray-300 rounded">
                        </div>

                         <div class="mb-4">
                            <label for="banner_image" class="block text-gray-700 font-medium">Image de bannière :</label>
                            <input type="file" name="banner_image" id="banner_image"
                                   class="mt-1 block w-full border-gray-300 rounded">
                            @if($event->banner_image)
                                <img src="{{ asset('storage/' . $event->banner_image) }}" alt="{{ $event->title }}" class="w-32 mt-2">
                            @endif
                        </div>

                         <div class="mb-4">
                            <label for="description" class="block text-gray-700 font-medium">Description :</label>
                            <textarea name="description" id="description" rows="4"
                                      class="mt-1 block w-full border-gray-300 rounded">{{ old('description', $event->description) }}</textarea>
                        </div>

                         <div class="mb-4">
                            <label for="event_date" class="block text-gray-700 font-medium">Date de l'événement * :</label>
                            <input type="datetime-local" name="event_date" id="event_date"
                                   value="{{ old('event_date', \Carbon\Carbon::parse($event->event_date)->format('Y-m-d\TH:i')) }}" required
                                   class="mt-1 block w-full border-gray-300 rounded">
                        </div>

                         <div class="mb-4">
                            <label for="address" class="block text-gray-700 font-medium">Adresse :</label>
                            <input type="text" name="address" id="address" value="{{ old('address', $event->address) }}"
                                   class="mt-1 block w-full border-gray-300 rounded">
                        </div>

                         <div class="mb-4">
                            <label for="latitude" class="block text-gray-700 font-medium">Latitude :</label>
                            <input type="text" name="latitude" id="latitude" value="{{ old('latitude', $event->latitude) }}"
                                   class="mt-1 block w-full border-gray-300 rounded">
                        </div>

                         <div class="mb-4">
                            <label for="longitude" class="block text-gray-700 font-medium">Longitude :</label>
                            <input type="text" name="longitude" id="longitude" value="{{ old('longitude', $event->longitude) }}"
                                   class="mt-1 block w-full border-gray-300 rounded">
                        </div>

                         <div class="mb-4">
                            <label for="status" class="block text-gray-700 font-medium">Statut * :</label>
                            <select name="status" id="status" required
                                    class="mt-1 block w-full border-gray-300 rounded">
                                <option value="remplissage_en_cours" {{ old('status', $event->status) === 'remplissage_en_cours' ? 'selected' : '' }}>Remplissage en cours</option>
                                <option value="complet" {{ old('status', $event->status) === 'complet' ? 'selected' : '' }}>Complet</option>
                                <option value="annule" {{ old('status', $event->status) === 'annule' ? 'selected' : '' }}>Annulé</option>
                            </select>
                        </div>

                         <div class="mb-4">
                            <label for="max_participants" class="block text-gray-700 font-medium">Nombre maximum de participants :</label>
                            <input type="number" name="max_participants" id="max_participants" value="{{ old('max_participants', $event->max_participants) }}" required
                                   class="mt-1 block w-full border-gray-300 rounded">
                        </div>

                         @if(auth()->user()->isOrganisateur())
                            <input type="hidden" name="organisateur_id" value="{{ auth()->user()->id }}">
                        @endif

                         @if ($errors->any())
                            <div class="text-red-700 py-1">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <button type="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Mettre à jour l'événement
                        </button>
                    </form>
                @else
                    <p class="text-red-500">Vous n'êtes pas autorisé à accéder à cette page.</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

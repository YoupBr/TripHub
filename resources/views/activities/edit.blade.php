<x-app-layout>
    <div class="min-h-screen bg-slate-100 pb-24">
        <main class="mx-auto max-w-3xl px-4 py-5 sm:px-6 sm:py-8">

            <header class="mb-6 flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm font-medium text-slate-500">
                        {{ $activity->trip?->name ?? 'Reis' }}
                    </p>

                    <h1 class="text-2xl font-bold text-slate-900 sm:text-3xl">
                        Activiteit wijzigen
                    </h1>
                </div>

                <a
                    href="{{ route('calendar.index') }}"
                    class="rounded-xl bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm ring-1 ring-slate-200 hover:bg-slate-50"
                >
                    Terug
                </a>
            </header>

            @if ($errors->any())
                <div class="mb-6 rounded-2xl bg-red-50 p-4 text-sm text-red-700 ring-1 ring-red-200">
                    <p class="font-semibold">Controleer de volgende velden:</p>

                    <ul class="mt-2 list-disc space-y-1 pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <section class="rounded-3xl bg-white p-5 shadow-sm ring-1 ring-slate-200 sm:p-7">
                <form
                    method="POST"
                    action="{{ route('activities.update', $activity) }}"
                    class="space-y-6"
                >
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="trip_id" class="block text-sm font-semibold text-slate-700">
                            Reis
                        </label>

                        <select
                            id="trip_id"
                            name="trip_id"
                            required
                            class="mt-2 w-full rounded-xl border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        >
                            @foreach ($trips as $trip)
                                <option
                                    value="{{ $trip->id }}"
                                    @selected((int) old('trip_id', $activity->trip_id) === $trip->id)
                                >
                                    {{ $trip->name }} — {{ $trip->destination }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="title" class="block text-sm font-semibold text-slate-700">
                            Titel
                        </label>

                        <input
                            id="title"
                            name="title"
                            type="text"
                            value="{{ old('title', $activity->title) }}"
                            required
                            class="mt-2 w-full rounded-xl border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        >
                    </div>

                    <div class="grid gap-5 sm:grid-cols-2">
                        <div>
                            <label for="starts_at" class="block text-sm font-semibold text-slate-700">
                                Begint op
                            </label>

                            <input
                                id="starts_at"
                                name="starts_at"
                                type="datetime-local"
                                value="{{ old('starts_at', $activity->starts_at?->format('Y-m-d\TH:i')) }}"
                                required
                                class="mt-2 w-full rounded-xl border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            >
                        </div>

                        <div>
                            <label for="ends_at" class="block text-sm font-semibold text-slate-700">
                                Eindigt op
                            </label>

                            <input
                                id="ends_at"
                                name="ends_at"
                                type="datetime-local"
                                value="{{ old('ends_at', $activity->ends_at?->format('Y-m-d\TH:i')) }}"
                                class="mt-2 w-full rounded-xl border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            >
                        </div>
                    </div>

                    <div>
                        <label for="location" class="block text-sm font-semibold text-slate-700">
                            Locatie
                        </label>

                        <input
                            id="location"
                            name="location"
                            type="text"
                            value="{{ old('location', $activity->location) }}"
                            class="mt-2 w-full rounded-xl border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        >
                    </div>

                    <div>
                        <label for="category" class="block text-sm font-semibold text-slate-700">
                            Categorie
                        </label>

                        <select
                            id="category"
                            name="category"
                            required
                            class="mt-2 w-full rounded-xl border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        >
                            @php
                                $categories = [
                                    'activiteit' => 'Activiteit',
                                    'restaurant' => 'Restaurant',
                                    'hotel' => 'Hotel',
                                    'vlucht' => 'Vlucht',
                                    'vervoer' => 'Vervoer',
                                    'vrije_tijd' => 'Vrije tijd',
                                    'overig' => 'Overig',
                                ];
                            @endphp

                            @foreach ($categories as $value => $label)
                                <option
                                    value="{{ $value }}"
                                    @selected(old('category', $activity->category) === $value)
                                >
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-semibold text-slate-700">
                            Beschrijving
                        </label>

                        <textarea
                            id="description"
                            name="description"
                            rows="5"
                            class="mt-2 w-full rounded-xl border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        >{{ old('description', $activity->description) }}</textarea>
                    </div>

                    <div class="grid gap-5 sm:grid-cols-2">
                        <div>
                            <label for="latitude" class="block text-sm font-semibold text-slate-700">
                                Latitude
                            </label>

                            <input
                                id="latitude"
                                name="latitude"
                                type="number"
                                step="any"
                                value="{{ old('latitude', $activity->latitude) }}"
                                class="mt-2 w-full rounded-xl border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            >
                        </div>

                        <div>
                            <label for="longitude" class="block text-sm font-semibold text-slate-700">
                                Longitude
                            </label>

                            <input
                                id="longitude"
                                name="longitude"
                                type="number"
                                step="any"
                                value="{{ old('longitude', $activity->longitude) }}"
                                class="mt-2 w-full rounded-xl border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            >
                        </div>
                    </div>

                    <div class="flex flex-col gap-3 border-t border-slate-200 pt-6 sm:flex-row sm:items-center sm:justify-between">
                        <button
                            type="submit"
                            class="inline-flex items-center justify-center rounded-xl bg-blue-700 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-800"
                        >
                            Wijzigingen opslaan
                        </button>
                    </div>
                </form>

                <form
                    method="POST"
                    action="{{ route('activities.destroy', $activity) }}"
                    class="mt-4"
                    onsubmit="return confirm('Weet je zeker dat je deze activiteit wilt verwijderen?')"
                >
                    @csrf
                    @method('DELETE')

                    <button
                        type="submit"
                        class="inline-flex items-center justify-center rounded-xl bg-red-50 px-5 py-2.5 text-sm font-semibold text-red-700 ring-1 ring-red-200 hover:bg-red-100"
                    >
                        Activiteit verwijderen
                    </button>
                </form>
            </section>

        </main>
    </div>
</x-app-layout>
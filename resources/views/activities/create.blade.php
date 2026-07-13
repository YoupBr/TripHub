<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activiteit toevoegen - TripHub</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-100 text-slate-900">
<div class="min-h-screen">
    <main class="mx-auto max-w-2xl px-4 py-5 sm:px-6 sm:py-8">

        <a href="{{ route('dashboard') }}" class="mb-5 inline-flex text-sm font-semibold text-blue-700">
            ← Terug naar dashboard
        </a>

        <section class="rounded-3xl bg-white p-5 shadow-sm sm:p-8">
            <div class="mb-6">
                <p class="text-sm font-medium text-slate-500">{{ $trip->name }}</p>
                <h1 class="mt-1 text-2xl font-bold sm:text-3xl">Activiteit toevoegen</h1>
            </div>

            <form method="POST" action="{{ route('activities.store') }}" class="space-y-5">
                @csrf

                <input type="hidden" name="trip_id" value="{{ $trip->id }}">

                <div>
                    <label class="mb-1 block text-sm font-semibold">Titel</label>
                    <input
                        type="text"
                        name="title"
                        value="{{ old('title') }}"
                        placeholder="Bijv. Tannen"
                        class="w-full rounded-xl border-slate-300"
                        required
                    >
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-sm font-semibold">Start</label>
                        <input
                            type="datetime-local"
                            name="starts_at"
                            value="{{ old('starts_at') }}"
                            class="w-full rounded-xl border-slate-300"
                            required
                        >
                        @error('starts_at')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-semibold">Einde</label>
                        <input
                            type="datetime-local"
                            name="ends_at"
                            value="{{ old('ends_at') }}"
                            class="w-full rounded-xl border-slate-300"
                        >
                        @error('ends_at')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-semibold">Categorie</label>
                    <select name="category" class="w-full rounded-xl border-slate-300">
                        <option value="activity">Activiteit</option>
                        <option value="travel">Reis</option>
                        <option value="food">Eten</option>
                        <option value="stay">Verblijf</option>
                        <option value="important">Belangrijk</option>
                    </select>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-semibold">Beschrijving</label>
                    <textarea
                        name="description"
                        rows="4"
                        placeholder="Extra info, reserveringsnummer, opmerkingen..."
                        class="w-full rounded-xl border-slate-300" "px-5 py-3"
                    >{{ old('description') }}</textarea>
                </div>

                <div>
                        <input type="hidden" name="latitude" id="latitude">
                        <input type="hidden" name="longitude" id="longitude">

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Locatie</label>
                            <input
                                name="location"
                                id="location"
                                type="text"
                                placeholder="Zoek bijvoorbeeld: Dichtstbijzijnde Café"
                                class="mt-1 block w-full rounded-md border-gray-300"
                                autocomplete="off"
                            >

                            <div id="location-results" class="mt-2 space-y-2"></div>
                        </div>

                        <div id="pick-map" class="mt-4 h-72 w-full rounded-xl border"></div>

                <button
                    type="submit"
                    class="w-full rounded-xl bg-blue-700 px-5 py-3 font-semibold text-white shadow-sm hover:bg-blue-800"
                >
                    Activiteit opslaan
                </button>
            </form>
        </section>

    </main>
</div>
</body>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    const locationInput = document.getElementById('location');
    const resultsBox = document.getElementById('location-results');
    const latInput = document.getElementById('latitude');
    const lngInput = document.getElementById('longitude');

    const map = L.map('pick-map').setView([52.3676, 4.9041], 6);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap'
    }).addTo(map);

    let marker = null;
    let searchTimeout = null;

    function setLocation(lat, lon, label) {
        latInput.value = lat;
        lngInput.value = lon;
        locationInput.value = label;

        if (marker) {
            marker.setLatLng([lat, lon]);
        } else {
            marker = L.marker([lat, lon], { draggable: true }).addTo(map);

            marker.on('dragend', function () {
                const position = marker.getLatLng();
                latInput.value = position.lat;
                lngInput.value = position.lng;
            });
        }

        map.setView([lat, lon], 14);
        resultsBox.innerHTML = '';
    }

    locationInput.addEventListener('input', function () {
        clearTimeout(searchTimeout);

        const query = this.value.trim();

        if (query.length < 3) {
            resultsBox.innerHTML = '';
            return;
        }

        searchTimeout = setTimeout(async () => {
            const response = await fetch(
                `https://photon.komoot.io/api/?q=${encodeURIComponent(query)}&limit=5`
            );

            const data = await response.json();

            resultsBox.innerHTML = '';

            data.features.forEach(feature => {
                const props = feature.properties;
                const [lon, lat] = feature.geometry.coordinates;

                const label = [
                    props.name,
                    props.street,
                    props.city,
                    props.country
                ].filter(Boolean).join(', ');

                const button = document.createElement('button');
                button.type = 'button';
                button.className = 'block w-full rounded-lg border bg-white px-3 py-2 text-left text-sm hover:bg-slate-50';
                button.textContent = label;

                button.addEventListener('click', () => {
                    setLocation(lat, lon, label);
                });

                resultsBox.appendChild(button);
            });
        }, 350);
    });

    map.on('click', function (e) {
        setLocation(e.latlng.lat, e.latlng.lng, locationInput.value || 'Gekozen locatie');
    });
</script>
</html>

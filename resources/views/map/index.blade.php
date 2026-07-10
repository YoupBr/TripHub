<x-app-layout>
    <div class="min-h-screen bg-slate-50">
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <div class="mb-4">
                <h1 class="text-2xl font-bold text-slate-900">Kaart</h1>
                <p class="text-sm text-slate-600">Alle activiteiten met locatie op één kaart.</p>
            </div>

            <div
                id="map"
                class="h-[70vh] w-full overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm"
            ></div>
        </div>
    </div>

    <link
        rel="stylesheet"
        href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    />

    <script
        src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js">
    </script>

    <script>
        const activities = @json($trip->activities);

        const map = L.map('map').setView([52.3676, 4.9041], 12);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);

        const markers = [];

        activities.forEach(activity => {
            const marker = L.marker([activity.latitude, activity.longitude])
                .addTo(map)
                .bindPopup(`
                    <strong>${activity.title}</strong><br>
                    ${activity.location ?? ''}<br>
                    ${activity.starts_at ?? ''}
                `);

            markers.push(marker);
        });

        if (markers.length > 0) {
            const group = L.featureGroup(markers);
            map.fitBounds(group.getBounds().pad(0.2));
        }
    </script>
</x-app-layout>

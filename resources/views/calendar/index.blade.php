<x-app-layout>
    <div class="min-h-screen bg-slate-100">
        <main class="mx-auto max-w-7xl px-4 py-5 sm:px-6 sm:py-8 lg:px-8">
            <header class="mb-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm font-medium text-slate-500">
                Alle reizen en bestemmingen
            </p>

            <h1 class="text-2xl font-bold text-slate-900 sm:text-3xl">
                Agenda
            </h1>
        </div>

        <a href="{{ route('activities.create', ['trip' => $selectedTripId]) }}">
            class="inline-flex items-center justify-center rounded-xl bg-blue-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-800" >
            + Activiteit</a>
    </div>

    <form
        method="GET"
        action="{{ route('calendar.index') }}"
        class="mt-5">
        <label
            for="trip"
            class="block text-sm font-semibold text-slate-700">
            Filter op reis
        </label>

        <select
            id="trip"
            name="trip"
            onchange="this.form.submit()"
            class="mt-2 w-full rounded-xl border-slate-300 bg-white px-4 py-3 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:max-w-sm">
            <option value="">Alle reizen</option>

            @foreach ($trips as $trip)
                <option
                    value="{{ $trip->id }}"
                    @selected($selectedTripId === $trip->id)>
                    {{ $trip->name }} — {{ $trip->destination }}
                </option>
            @endforeach
        </select>
    </form>
</header>

            <section class="overflow-hidden rounded-3xl bg-white p-4 shadow-sm ring-1 ring-slate-200 sm:p-6">
                <div id="calendar"
                data-events-url="{{ route('calendar.events', ['trip' => $selectedTripId]) }}"
                data-create-url="{{ route('activities.create', ['trip' => $selectedTripId]) }}"></div>
            </section>
        </main>
    </div>

    <script>
    window.calendarEventsUrl = @json(
        route('calendar.events', ['trip' => $selectedTripId])
    );
</script>
</x-app-layout>

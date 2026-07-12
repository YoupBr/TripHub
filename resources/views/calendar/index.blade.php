<x-app-layout>
    <div class="min-h-screen bg-slate-100">
        <main class="mx-auto max-w-7xl px-4 py-5 sm:px-6 sm:py-8 lg:px-8">
            <header class="mb-6 flex items-center justify-between gap-4">
                <div>
                    <p class="text-sm font-medium text-slate-500">
                        {{ $trip->name }}
                    </p>

                    <h1 class="text-2xl font-bold text-slate-900 sm:text-3xl">
                        Agenda
                    </h1>

                    <p class="mt-1 text-sm text-slate-500">
                        Bekijk alle activiteiten per maand, week of als lijst.
                    </p>
                </div>

                <a
                    href="{{ route('activities.create') }}"
                    class="shrink-0 rounded-xl bg-blue-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-800"
                >
                    + Activiteit
                </a>
            </header>

            <section class="overflow-hidden rounded-3xl bg-white p-4 shadow-sm ring-1 ring-slate-200 sm:p-6">
                <div id="calendar"></div>
            </section>
        </main>
    </div>

    <script>
        window.calendarEventsUrl = @json(route('calendar.events'));
    </script>
</x-app-layout>
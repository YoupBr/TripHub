<x-app-layout>
    <div class="min-h-screen bg-slate-100 pb-24">
        <main class="mx-auto max-w-6xl px-4 py-5 sm:px-6 sm:py-8">

            <header class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">{{ $trip->name }}</p>
                    <h1 class="text-2xl font-bold text-slate-900 sm:text-3xl">Agenda</h1>
                </div>

                <a
                    href="{{ route('activities.create') }}"
                    class="inline-flex items-center justify-center rounded-xl bg-blue-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-800"
                >
                    + Activiteit
                </a>
            </header>

            <section class="mb-8 rounded-3xl bg-white p-4 shadow-sm ring-1 ring-slate-200 sm:p-6">
                <div
                    id="calendar"
                    data-events-url="{{ route('calendar.events') }}"
                    data-create-url="{{ route('activities.create') }}"
                ></div>
            </section>

            @if($activitiesByDay->isEmpty())
                <section class="rounded-3xl bg-white p-8 text-center shadow-sm ring-1 ring-slate-200">
                    <div class="text-4xl">📅</div>
                    <h2 class="mt-3 text-xl font-bold">Nog geen agenda-items</h2>
                    <p class="mt-2 text-sm text-slate-500">
                        Voeg activiteiten toe om je vakantieplanning hier te zien.
                    </p>
                </section>
            @else
                <section>
                    <div class="mb-4">
                        <h2 class="text-xl font-bold text-slate-900">Planning per dag</h2>
                        <p class="text-sm text-slate-500">
                            Alle activiteiten chronologisch onder elkaar.
                        </p>
                    </div>

                    <div class="space-y-6">
                        @foreach($activitiesByDay as $date => $activities)
                            <section>
                                <div class="mb-3 flex items-center gap-3">
                                    <div class="flex h-12 w-12 flex-col items-center justify-center rounded-2xl bg-blue-700 text-white">
                                        <span class="text-xs font-bold">
                                            {{ strtoupper(\Carbon\Carbon::parse($date)->format('M')) }}
                                        </span>

                                        <span class="text-lg font-bold">
                                            {{ \Carbon\Carbon::parse($date)->format('d') }}
                                        </span>
                                    </div>

                                    <div>
                                        <h3 class="text-lg font-bold text-slate-900">
                                            {{ \Carbon\Carbon::parse($date)->translatedFormat('l d F') }}
                                        </h3>

                                        <p class="text-sm text-slate-500">
                                            {{ $activities->count() }}
                                            activiteit{{ $activities->count() === 1 ? '' : 'en' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="space-y-3">
                                    @foreach($activities as $activity)
                                        <a
                                            href="{{ route('activities.edit', $activity) }}"
                                            class="block rounded-3xl bg-white p-4 shadow-sm ring-1 ring-slate-200 transition hover:-translate-y-0.5 hover:shadow-md"
                                        >
                                            <article class="flex gap-4">
                                                <div class="w-16 shrink-0 text-sm font-bold text-blue-700">
                                                    {{ $activity->starts_at->format('H:i') }}
                                                </div>

                                                <div class="min-w-0 flex-1">
                                                    <h4 class="font-semibold text-slate-900">
                                                        {{ $activity->title }}
                                                    </h4>

                                                    @if($activity->location)
                                                        <p class="mt-1 text-sm text-slate-500">
                                                            📍 {{ $activity->location }}
                                                        </p>
                                                    @endif

                                                    @if($activity->description)
                                                        <p class="mt-2 text-sm text-slate-600">
                                                            {{ $activity->description }}
                                                        </p>
                                                    @endif

                                                    @if($activity->category)
                                                        <span class="mt-3 inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
                                                            {{ ucfirst($activity->category) }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </article>
                                        </a>
                                    @endforeach
                                </div>
                            </section>
                        @endforeach
                    </div>
                </section>
            @endif

        </main>
    </div>
</x-app-layout>
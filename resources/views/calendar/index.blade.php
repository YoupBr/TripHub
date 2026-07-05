<x-app-layout>
    <div class="min-h-screen bg-slate-100 pb-24">
        <main class="mx-auto max-w-4xl px-4 py-5 sm:px-6 sm:py-8">

            <header class="mb-6 flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">{{ $trip->name }}</p>
                    <h1 class="text-2xl font-bold text-slate-900 sm:text-3xl">Agenda</h1>
                </div>

                <a href="{{ route('activities.create') }}"
                   class="rounded-xl bg-blue-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-800">
                    + Activiteit
                </a>
            </header>

            @if($activitiesByDay->isEmpty())
                <section class="rounded-3xl bg-white p-8 text-center shadow-sm ring-1 ring-slate-200">
                    <div class="text-4xl">📅</div>
                    <h2 class="mt-3 text-xl font-bold">Nog geen agenda-items</h2>
                    <p class="mt-2 text-sm text-slate-500">
                        Voeg activiteiten toe om je vakantieplanning hier te zien.
                    </p>
                </section>
            @else
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
                                    <h2 class="text-lg font-bold text-slate-900">
                                        {{ \Carbon\Carbon::parse($date)->translatedFormat('l d F') }}
                                    </h2>
                                    <p class="text-sm text-slate-500">
                                        {{ $activities->count() }} activiteit{{ $activities->count() === 1 ? '' : 'en' }}
                                    </p>
                                </div>
                            </div>

                            <div class="space-y-3">
                                @foreach($activities as $activity)
                                    <article class="rounded-3xl bg-white p-4 shadow-sm ring-1 ring-slate-200">
                                        <div class="flex gap-4">
                                            <div class="w-16 shrink-0 text-sm font-bold text-blue-700">
                                                {{ $activity->starts_at->format('H:i') }}
                                            </div>

                                            <div class="min-w-0 flex-1">
                                                <h3 class="font-semibold text-slate-900">
                                                    {{ $activity->title }}
                                                </h3>

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

                                                <span class="mt-3 inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
                                                    {{ ucfirst($activity->category) }}
                                                </span>
                                            </div>
                                        </div>
                                    </article>
                                @endforeach
                            </div>
                        </section>
                    @endforeach
                </div>
            @endif

        </main>
    </div>
</x-app-layout>
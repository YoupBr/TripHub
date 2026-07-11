<x-app-layout>
    <div class="min-h-screen bg-slate-100 pb-24 sm:pb-0">
        <main class="mx-auto max-w-6xl px-4 py-5 sm:px-6 sm:py-8 lg:px-8">

           <!-- <header class="mb-6 flex items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div>
                        <p class="text-sm font-medium text-slate-500">Vakantie dashboard</p>
                        <h1 class="text-2xl font-bold text-slate-900 sm:text-3xl">TripHub</h1>
                    </div>
                </div>
            
                <a href="{{ route('activities.create') }}"
                   class="hidden rounded-xl bg-blue-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-800 sm:block">
                    + Activiteit
                </a>
            </header>
        -->
            @if($trip)
                <section class="overflow-hidden rounded-3xl bg-gradient-to-br from-blue-800 to-sky-500 p-6 text-white shadow-lg sm:p-8">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-sm opacity-80">{{ $trip->name }}</p>

                            <h2 class="mt-2 text-3xl font-bold sm:text-5xl">
                                {{ $trip->destination ?? 'Bestemming onbekend' }}
                            </h2>

                            <p class="mt-3 text-sm opacity-90 sm:text-lg">
                                @php
                                    $daysLeft = now()->startOfDay()->diffInDays($trip->start_date, false);
                                @endphp

                                @if($daysLeft > 0)
                                    Nog {{ $daysLeft }} dagen tot vertrek
                                @elseif($daysLeft === 0)
                                    Vandaag begint de reis
                                @else
                                    Deze reis is al begonnen
                                @endif
                            </p>
                        </div>

                        <div class="hidden h-20 w-20 items-center justify-center rounded-3xl bg-white/15 text-4xl backdrop-blur sm:flex">
                            ✈️
                        </div>
                    </div>

                    <div class="mt-6 grid grid-cols-1 gap-3 sm:grid-cols-3">
                        <div class="rounded-2xl bg-white/15 p-4 backdrop-blur">
                            <p class="text-xs opacity-75">Vertrek</p>
                            <p class="mt-1 text-lg font-semibold">
                                {{ $trip->start_date->format('d-m-Y') }}
                            </p>
                        </div>

                        <div class="rounded-2xl bg-white/15 p-4 backdrop-blur">
                            <p class="text-xs opacity-75">Doorreis</p>
                            <p class="mt-1 text-lg font-semibold">
                                {{ $trip->end_date?->format('d-m-Y') ?? 'Nog niet ingevuld' }}
                            </p>
                        </div>

                        <div class="rounded-2xl bg-white/15 p-4 backdrop-blur">
                            <p class="text-xs opacity-75">Activiteiten</p>
                            <p class="mt-1 text-lg font-semibold">
                                {{ $trip->activities->count() }}
                            </p>
                        </div>
                    </div>
                </section>

                <section class="mt-6 grid grid-cols-1 gap-5 lg:grid-cols-3">
                    <div class="rounded-3xl bg-white p-5 shadow-sm ring-1 ring-slate-200 lg:col-span-2">
                        <div class="mb-4 flex items-center justify-between">
                            <h2 class="text-xl font-bold text-slate-900">Komende activiteiten</h2>
                            <a href="{{ route('activities.create') }}" class="text-sm font-semibold text-blue-700">+</a>
                        </div>

                        @if($trip->activities->isEmpty())
                            <div class="rounded-2xl border border-dashed border-slate-300 p-6 text-center">
                                <div class="text-3xl">📅</div>
                                <h3 class="mt-3 font-semibold">Nog geen activiteiten</h3>
                                <p class="mt-1 text-sm text-slate-500">Voeg je vlucht, reservering of dagplanning toe.</p>

                                <a href="{{ route('activities.create') }}"
                                   class="mt-4 inline-flex rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white">
                                    + Activiteit toevoegen
                                </a>
                            </div>
                        @else
                            <div class="space-y-3">
                                @foreach($trip->activities as $activity)
                                    <a href="{{ route('activities.edit', $activity) }}" class="flex gap-4 rounded-2xl border border-slate-200 p-4">
                                        <div class="flex h-14 w-14 shrink-0 flex-col items-center justify-center rounded-xl bg-blue-100 text-blue-800">
                                            <span class="text-xs font-bold">{{ strtoupper($activity->starts_at->format('M')) }}</span>
                                            <span class="text-lg font-bold">{{ $activity->starts_at->format('d') }}</span>
                                        </div>

                                        <div class="min-w-0">
                                            <h3 class="truncate font-semibold text-slate-900">{{ $activity->title }}</h3>
                                            <p class="truncate text-sm text-slate-500">
                                                {{ $activity->starts_at->format('H:i') }}
                                                @if($activity->location)
                                                    · {{ $activity->location }}
                                                @endif
                                            </p>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="space-y-5">
                        <div class="rounded-3xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
                            <div class="mb-4 flex items-center justify-between">
                                <h2 class="text-xl font-bold text-slate-900">Documenten</h2>
                                <a href="{{ route('documents.index') }}" class="text-sm font-semibold text-blue-700">+</a>
                            </div>

                            @if($trip->documents->isEmpty())
                                <div class="rounded-2xl border border-dashed border-slate-300 p-5 text-center">
                                    <div class="text-3xl">📄</div>
                                    <h3 class="mt-3 font-semibold">Nog geen documenten</h3>
                                    <p class="mt-1 text-sm text-slate-500">Upload tickets, boekingen of verzekeringen.</p>
                                </div>
                            @else
                                <div class="space-y-3">
                                    @foreach($trip->documents->take(3) as $document)
                                        <a href="{{ Storage::url($document->file_path) }}" target="_blank"
                                           class="flex items-center justify-between rounded-xl bg-slate-100 p-3">
                                            <span class="truncate text-sm font-medium text-slate-700">{{ $document->title }}</span>
                                            <span class="ml-3 text-xs text-slate-500">{{ strtoupper($document->type ?? 'file') }}</span>
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <div class="rounded-3xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
                            <div class="mb-4 flex items-center justify-between">
                                <h2 class="text-xl font-bold text-slate-900">Checklist</h2>
                                <a href="{{ route('checklist.index') }}" class="text-sm font-semibold text-blue-700">+</a>
                            </div>

                            @if($trip->checklistItems->isEmpty())
                                <div class="rounded-2xl border border-dashed border-slate-300 p-5 text-center">
                                    <div class="text-3xl">✅</div>
                                    <h3 class="mt-3 font-semibold">Nog geen checklist</h3>
                                    <p class="mt-1 text-sm text-slate-500">Maak een paklijst of takenlijst.</p>
                                </div>
                            @else
                                <div class="space-y-3">
                                    @foreach($trip->checklistItems->take(4) as $item)
                                        <div class="flex items-center gap-3 text-sm">
                                            <span class="flex h-5 w-5 items-center justify-center rounded border {{ $item->is_done ? 'border-blue-700 bg-blue-700 text-white' : 'border-slate-300' }}">
                                                @if($item->is_done) ✓ @endif
                                            </span>

                                            <span class="truncate {{ $item->is_done ? 'text-slate-400 line-through' : 'text-slate-700' }}">
                                                {{ $item->title }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </section>
            @else
                <section class="rounded-3xl bg-white p-8 text-center shadow-sm ring-1 ring-slate-200">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-blue-100 text-3xl">
                        🧳
                    </div>

                    <h2 class="mt-5 text-2xl font-bold">Nog geen vakantie toegevoegd</h2>
                    <p class="mx-auto mt-2 max-w-md text-sm text-slate-500">
                        Maak eerst een reis aan om activiteiten, documenten en checklists te gebruiken.
                    </p>
                </section>
            @endif
        </main>

        <nav class="fixed inset-x-0 bottom-0 border-t border-slate-200 bg-white/95 px-4 py-3 shadow-lg backdrop-blur sm:hidden">
            <div class="mx-auto flex max-w-md items-center justify-around text-xs font-medium text-slate-600">
                <a href="{{ route('dashboard') }}" class="flex flex-col items-center gap-1 text-blue-700">
                    <span class="text-xl">🏠</span>
                    Home
                </a>

                <a href="{{ route('activities.create') }}" class="flex flex-col items-center gap-1">
                    <span class="text-xl">📅</span>
                    Agenda
                </a>

                <a href="{{ route('documents.index') }}" class="flex flex-col items-center gap-1">
                    <span class="text-xl">📄</span>
                    Docs
                </a>

                <a href="{{ route('checklist.index') }}" class="flex flex-col items-center gap-1">
                    <span class="text-xl">✅</span>
                    Taken
                </a>
            </div>
        </nav>
    </div>
</x-app-layout>

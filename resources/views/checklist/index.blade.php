<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checklist - TripHub</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-100 text-slate-900">
<div class="min-h-screen pb-24">
    <main class="mx-auto max-w-3xl px-4 py-5 sm:px-6 sm:py-8">

        <a href="{{ route('dashboard') }}" class="mb-5 inline-flex text-sm font-semibold text-blue-700">
            ← Terug naar dashboard
        </a>

        <section class="rounded-3xl bg-white p-5 shadow-sm sm:p-8">
            <div class="mb-6">
                <p class="text-sm font-medium text-slate-500">{{ $trip->name }}</p>
                <h1 class="mt-1 text-2xl font-bold sm:text-3xl">Checklist</h1>
                <p class="mt-2 text-sm text-slate-500">
                    Houd bij wat nog geregeld of ingepakt moet worden.
                </p>
            </div>

            <form method="POST" action="{{ route('checklist.store') }}"
                  class="mb-8 flex flex-col gap-3 rounded-2xl border border-slate-200 bg-slate-50 p-4 sm:flex-row">
                @csrf

                <input type="hidden" name="trip_id" value="{{ $trip->id }}">

                <div class="flex-1">
                    <input
                        type="text"
                        name="title"
                        value="{{ old('title') }}"
                        placeholder="Bijv. Paspoorten meenemen"
                        class="w-full rounded-xl border-slate-300"
                        required
                    >

                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <button
                    type="submit"
                    class="rounded-xl bg-blue-700 px-5 py-3 font-semibold text-white shadow-sm hover:bg-blue-800"
                >
                    Toevoegen
                </button>
            </form>

            @if($trip->checklistItems->isEmpty())
                <div class="rounded-2xl border border-dashed border-slate-300 p-8 text-center">
                    <div class="text-4xl">✅</div>
                    <h2 class="mt-3 text-lg font-bold">Nog geen checklist-items</h2>
                    <p class="mt-1 text-sm text-slate-500">
                        Voeg bijvoorbeeld paspoorten, opladers of verzekeringen toe.
                    </p>
                </div>
            @else
                <div class="space-y-3">
                    @foreach($trip->checklistItems as $item)
                        <article class="rounded-2xl border border-slate-200 bg-white p-4">
                            <div class="flex items-center justify-between gap-3">
                                <form method="POST" action="{{ route('checklist.toggle', $item) }}" class="min-w-0 flex-1">
                                    @csrf
                                    @method('PATCH')

                                    <button type="submit" class="flex w-full items-center gap-3 text-left">
                                        <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg border
                                            {{ $item->is_done ? 'border-blue-700 bg-blue-700 text-white' : 'border-slate-300 bg-white' }}">
                                            @if($item->is_done)
                                                ✓
                                            @endif
                                        </span>

                                        <span class="truncate text-sm font-medium sm:text-base {{ $item->is_done ? 'text-slate-400 line-through' : 'text-slate-900' }}">
                                            {{ $item->title }}
                                        </span>
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('checklist.destroy', $item) }}"
                                      onsubmit="return confirm('Weet je zeker dat je dit item wilt verwijderen?')">
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="rounded-xl bg-red-100 px-3 py-2 text-sm font-semibold text-red-700 hover:bg-red-200"
                                    >
                                        Verwijder
                                    </button>
                                </form>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </section>

    </main>

    <nav class="fixed inset-x-0 bottom-0 border-t border-slate-200 bg-white/95 px-4 py-3 shadow-lg backdrop-blur sm:hidden">
        <div class="mx-auto flex max-w-md items-center justify-around text-xs font-medium text-slate-600">
            <a href="{{ route('dashboard') }}" class="flex flex-col items-center gap-1">
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

            <a href="{{ route('checklist.index') }}" class="flex flex-col items-center gap-1 text-blue-700">
                <span class="text-xl">✅</span>
                Taken
            </a>
        </div>
    </nav>
</div>
</body>
</html>
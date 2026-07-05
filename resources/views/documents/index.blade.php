<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documenten - TripHub</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-100 text-slate-900">
<div class="min-h-screen pb-24">
    <main class="mx-auto max-w-4xl px-4 py-5 sm:px-6 sm:py-8">

        <a href="/." class="mb-5 inline-flex text-sm font-semibold text-blue-700">
            ← Terug naar dashboard
        </a>

        <section class="rounded-3xl bg-white p-5 shadow-sm sm:p-8">
            <div class="mb-6">
                <p class="text-sm font-medium text-slate-500">{{ $trip->name }}</p>
                <h1 class="mt-1 text-2xl font-bold sm:text-3xl">Documenten</h1>
                <p class="mt-2 text-sm text-slate-500">
                    Upload tickets, reserveringen, verzekeringen of andere belangrijke bestanden.
                </p>
            </div>

            <form method="POST" action="{{ route('documents.store') }}" enctype="multipart/form-data"
                  class="mb-8 space-y-4 rounded-2xl border border-slate-200 bg-slate-50 p-4">
                @csrf

                <input type="hidden" name="trip_id" value="{{ $trip->id }}">

                <div>
                    <label class="mb-1 block text-sm font-semibold">Naam document</label>
                    <input
                        type="text"
                        name="title"
                        value="{{ old('title') }}"
                        placeholder="Bijv. Vliegtickets"
                        class="w-full rounded-xl border-slate-300"
                        required
                    >

                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm font-semibold">Bestand</label>
                    <input
                        type="file"
                        name="file"
                        class="w-full rounded-xl border border-slate-300 bg-white p-3 text-sm"
                        required
                    >

                    @error('file')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <button
                    type="submit"
                    class="w-full rounded-xl bg-blue-700 px-5 py-3 font-semibold text-white shadow-sm hover:bg-blue-800 sm:w-auto"
                >
                    Uploaden
                </button>
            </form>

            @if($trip->documents->isEmpty())
                <div class="rounded-2xl border border-dashed border-slate-300 p-8 text-center">
                    <div class="text-4xl">📄</div>
                    <h2 class="mt-3 text-lg font-bold">Nog geen documenten toegevoegd</h2>
                    <p class="mt-1 text-sm text-slate-500">
                        Upload je eerste bestand, zoals tickets of een reservering.
                    </p>
                </div>
            @else
                <div class="space-y-3">
                    @foreach($trip->documents as $document)
                        <article class="rounded-2xl border border-slate-200 bg-white p-4">
                            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                                <div class="min-w-0">
                                    <h2 class="truncate font-semibold">
                                        {{ $document->title }}
                                    </h2>

                                    <p class="mt-1 text-sm text-slate-500">
                                        {{ strtoupper($document->type ?? 'bestand') }}
                                        · toegevoegd op {{ $document->created_at->format('d-m-Y') }}
                                    </p>
                                </div>

                                <div class="flex gap-2">
                                    @if($document->file_path)
                                        <a
                                            href="{{ Storage::url($document->file_path) }}"
                                            target="_blank"
                                            class="flex-1 rounded-xl bg-slate-900 px-4 py-2 text-center text-sm font-semibold text-white sm:flex-none"
                                        >
                                            Bekijk
                                        </a>
                                    @endif

                                    <form method="POST" action="{{ route('documents.destroy', $document) }}"
                                          onsubmit="return confirm('Weet je zeker dat je dit document wilt verwijderen?')">
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="rounded-xl bg-red-100 px-4 py-2 text-sm font-semibold text-red-700 hover:bg-red-200"
                                        >
                                            Verwijder
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </section>

    </main>

    <nav class="fixed inset-x-0 bottom-0 border-t border-slate-200 bg-white/95 px-4 py-3 shadow-lg backdrop-blur sm:hidden">
        <div class="mx-auto flex max-w-md items-center justify-around text-xs font-medium text-slate-600">
            <a href="{{ url('/dashboard') }}" class="flex flex-col items-center gap-1">
                <span class="text-xl">🏠</span>
                Home
            </a>

            <a href="{{ url('/activities/create') }}" class="flex flex-col items-center gap-1">
                <span class="text-xl">📅</span>
                Agenda
            </a>

            <a href="{{ route('documents.index') }}" class="flex flex-col items-center gap-1 text-blue-700">
                <span class="text-xl">📄</span>
                Docs
            </a>
        </div>
    </nav>
</div>
</body>
</html>
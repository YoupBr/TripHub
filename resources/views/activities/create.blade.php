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
                        placeholder="Bijv. Vlucht naar Pisa"
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
                    <label class="mb-1 block text-sm font-semibold">Locatie</label>
                    <input
                        type="text"
                        name="location"
                        value="{{ old('location') }}"
                        placeholder="Bijv. Schiphol Airport"
                        class="w-full rounded-xl border-slate-300"
                    >
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
                        class="w-full rounded-xl border-slate-300"
                    >{{ old('description') }}</textarea>
                </div>

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
</html>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TripHub</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-100 text-slate-900">
    <main class="flex min-h-screen items-center justify-center px-4 py-10">
        <div class="w-full max-w-md">
            <div class="mb-6 text-center">
                <!--<div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-700 text-2xl text-white shadow-sm">
                    🧳
                </div>. -->
                <img src="{{ asset('img/th_logo_trans.png') }}"
                        alt="TripHub Logo" class="mx-auto h-40 w-auto">
            </div>

            <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200 sm:p-8">
                {{ $slot }}
            </div>
        </div>
    </main>
</body>
</html>
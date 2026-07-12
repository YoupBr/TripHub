<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\TripController;
use App\Models\ChecklistItem;
use App\Models\Document;
use App\Models\Trip;
use App\Models\Activity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::redirect('/home', '/')->name('home');

Route::middleware('auth')->group(function (): void {
    /*
    |--------------------------------------------------------------------------
    | Helper voor huidige/eerstvolgende reis
    |--------------------------------------------------------------------------
    */

    $activeTripQuery = function (): Builder {
        return Trip::query()
            ->where(function ($query): void {
                $query
                    ->whereDate('end_date', '>=', today())
                    ->orWhereNull('end_date');
            })
            ->orderBy('start_date');
    };

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get('/', function () use ($activeTripQuery) {
        $trip = $activeTripQuery()
            ->with([
                'activities' => fn ($query) => $query
                    ->where('starts_at', '>=', now())
                    ->orderBy('starts_at')
                    ->limit(5),
                'documents',
                'checklistItems',
            ])
            ->first();

        return view('dashboard', compact('trip'));
    })->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Trips
    |--------------------------------------------------------------------------
    */

    Route::resource('trips', TripController::class);

    /*
    |--------------------------------------------------------------------------
    | Activities
    |--------------------------------------------------------------------------
    */

    Route::resource('activities', ActivityController::class)
        ->only([
            'create',
            'store',
            'edit',
            'update',
            'destroy',
        ]);

    /*
    |--------------------------------------------------------------------------
    | Calendar
    |--------------------------------------------------------------------------
    */

        Route::get('/calendar', function () use ($activeTripQuery) {
        $trip = $activeTripQuery()->firstOrFail();

        return view('calendar.index', compact('trip'));
        })->name('calendar.index');

         Route::get('/calendar/events', function (Request $request) use ($activeTripQuery) {
        $trip = $activeTripQuery()->firstOrFail();

        $activities = \App\Models\Activity::query()
            ->where('trip_id', $trip->id)
            ->when(
                $request->filled('start'),
                fn ($query) => $query->where('starts_at', '>=', $request->string('start'))
            )
            ->when(
                $request->filled('end'),
                fn ($query) => $query->where('starts_at', '<', $request->string('end'))
            )
            ->orderBy('starts_at')
            ->get();

        return response()->json(
            $activities->map(fn ($activity) => [
                'id' => $activity->id,
                'title' => $activity->title,
                'start' => $activity->starts_at->toIso8601String(),
                'end' => $activity->ends_at?->toIso8601String(),
                'url' => route('activities.edit', $activity),
                'extendedProps' => [
                    'location' => $activity->location,
                    'description' => $activity->description,
                    'category' => $activity->category,
                ],
            ])
        );
    })->name('calendar.events');

    /*
    |--------------------------------------------------------------------------
    | Map
    |--------------------------------------------------------------------------
    */

    Route::get('/map', function () use ($activeTripQuery) {
        $trip = $activeTripQuery()
            ->with([
                'activities' => fn ($query) => $query
                    ->whereNotNull('latitude')
                    ->whereNotNull('longitude')
                    ->orderBy('starts_at'),
            ])
            ->firstOrFail();

        return view('map.index', compact('trip'));
    })->name('map.index');

    /*
    |--------------------------------------------------------------------------
    | Documents
    |--------------------------------------------------------------------------
    */

    Route::get('/documents', function () use ($activeTripQuery) {
        $trip = $activeTripQuery()
            ->with([
                'documents' => fn ($query) => $query->latest(),
            ])
            ->firstOrFail();

        return view('documents.index', compact('trip'));
    })->name('documents.index');

    Route::post('/documents', function (Request $request) {
        $validated = $request->validate([
            'trip_id' => ['required', 'exists:trips,id'],
            'title' => ['required', 'string', 'max:255'],
            'file' => ['required', 'file', 'max:10240'],
        ]);

        $file = $request->file('file');
        $path = $file->store('documents', 'public');

        Document::create([
            'trip_id' => $validated['trip_id'],
            'title' => $validated['title'],
            'file_path' => $path,
            'type' => $file->getClientOriginalExtension(),
        ]);

        return redirect()
            ->route('documents.index')
            ->with('status', 'Document toegevoegd.');
    })->name('documents.store');

    Route::delete('/documents/{document}', function (Document $document) {
        if ($document->file_path) {
            Storage::disk('public')->delete($document->file_path);
        }

        $document->delete();

        return redirect()
            ->route('documents.index')
            ->with('status', 'Document verwijderd.');
    })->name('documents.destroy');

    /*
    |--------------------------------------------------------------------------
    | Checklist
    |--------------------------------------------------------------------------
    */

    Route::get('/checklist', function () use ($activeTripQuery) {
        $trip = $activeTripQuery()
            ->with([
                'checklistItems' => fn ($query) => $query
                    ->orderBy('is_done')
                    ->latest(),
            ])
            ->firstOrFail();

        return view('checklist.index', compact('trip'));
    })->name('checklist.index');

    Route::post('/checklist', function (Request $request) {
        $validated = $request->validate([
            'trip_id' => ['required', 'exists:trips,id'],
            'title' => ['required', 'string', 'max:255'],
        ]);

        ChecklistItem::create([
            'trip_id' => $validated['trip_id'],
            'title' => $validated['title'],
            'is_done' => false,
        ]);

        return redirect()
            ->route('checklist.index')
            ->with('status', 'Checklist-item toegevoegd.');
    })->name('checklist.store');

    Route::patch('/checklist/{checklistItem}/toggle', function (
        ChecklistItem $checklistItem
    ) {
        $checklistItem->update([
            'is_done' => ! $checklistItem->is_done,
        ]);

        return redirect()->route('checklist.index');
    })->name('checklist.toggle');

    Route::delete('/checklist/{checklistItem}', function (
        ChecklistItem $checklistItem
    ) {
        $checklistItem->delete();

        return redirect()
            ->route('checklist.index')
            ->with('status', 'Checklist-item verwijderd.');
    })->name('checklist.destroy');
});
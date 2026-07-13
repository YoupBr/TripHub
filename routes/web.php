<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\TripController;
use App\Models\ChecklistItem;
use App\Models\Document;
use App\Models\Trip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::redirect('/home', '/')->name('home');

Route::middleware('auth')->group(function (): void {
    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get('/', function () {
        $trip = Trip::query()
            ->with([
                'activities' => fn ($query) => $query
                    ->orderBy('starts_at')
                    ->limit(5),
                'documents',
                'checklistItems',
            ])
            ->first();

        return view('dashboard', compact('trip'));
    })->name('dashboard');

    Route::redirect('/dashboard', '/');

    /*
    |--------------------------------------------------------------------------
    | Trips
    |--------------------------------------------------------------------------
    */

    Route::resource('trips', TripController::class)
        ->except('show');

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

    Route::get('/calendar', [CalendarController::class, 'index'])
        ->name('calendar.index');

    Route::get('/calendar/events', [CalendarController::class, 'events'])
        ->name('calendar.events');

    /*
    |--------------------------------------------------------------------------
    | Map
    |--------------------------------------------------------------------------
    */

    Route::get('/map', function () {
        $trip = Trip::query()
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

    Route::get('/documents', function () {
        $trip = Trip::query()
            ->with('documents')
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

    Route::get('/checklist', function () {
        $trip = Trip::query()
            ->with('checklistItems')
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
PHP

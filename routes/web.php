<?php

use App\Models\Activity;
use App\Models\Document;
use App\Models\Trip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Models\ChecklistItem;

Route::redirect('/home', '/')->name('home');

Route::middleware(['auth'])->group(function () {

    Route::get('/', function () {
        $trip = Trip::with([
            'activities' => fn ($query) => $query->orderBy('starts_at')->limit(5),
            'documents',
            'checklistItems',
        ])->first();

        return view('dashboard', compact('trip'));
    })->name('dashboard');
    



/*
|--------------------------------------------------------------------------
| Activities
|--------------------------------------------------------------------------
*/

Route::get('/activities/create', function () {

    $trip = Trip::firstOrFail();

    return view('activities.create', compact('trip'));

})->name('activities.create');


Route::post('/activities', function (Request $request) {

    $validated = $request->validate([
        'trip_id' => ['required', 'exists:trips,id'],
        'title' => ['required', 'string', 'max:255'],
        'starts_at' => ['required', 'date'],
        'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
        'location' => ['nullable', 'string', 'max:255'],
        'category' => ['required', 'string', 'max:50'],
        'description' => ['nullable', 'string'],
    ]);

    Activity::create($validated);

    return redirect('/');

})->name('activities.store');


/*
|--------------------------------------------------------------------------
| Documents
|--------------------------------------------------------------------------
*/

Route::get('/documents', function () {

    $trip = Trip::with('documents')->firstOrFail();

    return view('documents.index', compact('trip'));

})->name('documents.index');


Route::post('/documents', function (Request $request) {

    $validated = $request->validate([
        'trip_id' => ['required', 'exists:trips,id'],
        'title' => ['required', 'string', 'max:255'],
        'file' => ['required', 'file', 'max:10240'],
    ]);

    $path = $request->file('file')->store('documents', 'public');

    Document::create([
        'trip_id' => $validated['trip_id'],
        'title' => $validated['title'],
        'file_path' => $path,
        'type' => $request->file('file')->getClientOriginalExtension(),
    ]);

    return redirect()->route('documents.index');

})->name('documents.store');


Route::delete('/documents/{document}', function (Document $document) {

    if ($document->file_path) {
        Storage::disk('public')->delete($document->file_path);
    }

    $document->delete();

    return redirect()->route('documents.index');

})->name('documents.destroy');

/*
|--------------------------------------------------------------------------
| Checklist
|--------------------------------------------------------------------------
*/

Route::get('/checklist', function () {
    $trip = Trip::with('checklistItems')->firstOrFail();

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

    return redirect()->route('checklist.index');
})->name('checklist.store');


Route::patch('/checklist/{checklistItem}/toggle', function (ChecklistItem $checklistItem) {
    $checklistItem->update([
        'is_done' => ! $checklistItem->is_done,
    ]);

    return redirect()->route('checklist.index');
})->name('checklist.toggle');


Route::delete('/checklist/{checklistItem}', function (ChecklistItem $checklistItem) {
    $checklistItem->delete();

    return redirect()->route('checklist.index');
})->name('checklist.destroy');

/*
    CALENDAR
*/

Route::get('/calendar', function () {
    $trip = Trip::with([
        'activities' => fn ($query) => $query->orderBy('starts_at'),
    ])->firstOrFail();

    $activitiesByDay = $trip->activities->groupBy(function ($activity) {
        return $activity->starts_at->format('Y-m-d');
    });

    return view('calendar.index', compact('trip', 'activitiesByDay'));
})->name('calendar.index');

});
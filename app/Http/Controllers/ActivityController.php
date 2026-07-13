<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Trip;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActivityController extends Controller
{
    public function create(): View
    {
        $trip = Trip::query()
            ->where(function ($query) {
                $query
                    ->whereDate('end_date', '>=', today())
                    ->orWhereNull('end_date');
            })
            ->orderBy('start_date')
            ->firstOrFail();

        return view('activities.create', compact('trip'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'trip_id' => ['required', 'exists:trips,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'starts_at' => ['required', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'location' => ['nullable', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:50'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
        ]);

        $validated['category'] ??= 'activity';

        Activity::create($validated);

        return redirect()
            ->route('calendar.index')
            ->with('status', 'Activiteit toegevoegd.');
    }

    public function edit(Activity $activity): View
    {
        $trips = Trip::query()
            ->orderBy('start_date')
            ->get();

        return view('activities.edit', compact('activity', 'trips'));
    }

    public function update(
        Request $request,
        Activity $activity
    ): RedirectResponse {
        $validated = $request->validate([
            'trip_id' => ['required', 'exists:trips,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'starts_at' => ['required', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'location' => ['nullable', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:50'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
        ]);

        $validated['category'] ??= 'activity';

        $activity->update($validated);

        return redirect()
            ->route('calendar.index')
            ->with('status', 'Activiteit bijgewerkt.');
    }

    public function destroy(Activity $activity): RedirectResponse
    {
        $activity->delete();

        return redirect()
            ->route('calendar.index')
            ->with('status', 'Activiteit verwijderd.');
    }
}
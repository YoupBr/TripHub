<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Trip;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CalendarController extends Controller
{
    public function index(Request $request): View
    {
        $trips = Trip::query()
            ->orderBy('start_date')
            ->get();

        $selectedTripId = $request->integer('trip');

        $activities = Activity::query()
            ->with('trip')
            ->when(
                $selectedTripId,
                fn ($query) => $query->where('trip_id', $selectedTripId)
            )
            ->orderBy('starts_at')
            ->get();

        $activitiesByDay = $activities->groupBy(
            fn (Activity $activity) => $activity->starts_at->format('Y-m-d')
        );

        return view('calendar.index', compact(
            'trips',
            'selectedTripId',
            'activitiesByDay'
        ));
    }

    public function events(Request $request): JsonResponse
    {
        $selectedTripId = $request->integer('trip');

        $activities = Activity::query()
            ->with('trip')
            ->when(
                $selectedTripId,
                fn ($query) => $query->where('trip_id', $selectedTripId)
            )
            ->orderBy('starts_at')
            ->get();

        return response()->json(
            $activities->map(fn (Activity $activity) => [
                'id' => $activity->id,
                'title' => $activity->title,
                'start' => $activity->starts_at->toIso8601String(),
                'end' => $activity->ends_at?->toIso8601String(),
                'url' => route('activities.edit', $activity),

                'extendedProps' => [
                    'location' => $activity->location,
                    'category' => $activity->category,
                    'trip' => $activity->trip?->name,
                ],
            ])
        );
    }
}

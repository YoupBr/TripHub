<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Trip;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class CalendarController extends Controller
{
    public function index(): View
    {
        $trip = Trip::query()
            ->with([
                'activities' => fn ($query) => $query->orderBy('starts_at'),
            ])
            ->orderBy('start_date')
            ->firstOrFail();

        $activitiesByDay = $trip->activities->groupBy(
            fn (Activity $activity) =>
                $activity->starts_at->format('Y-m-d')
        );

        return view('calendar.index', compact(
            'trip',
            'activitiesByDay'
        ));
    }

    public function events(): JsonResponse
    {
        $trip = Trip::query()
            ->orderBy('start_date')
            ->firstOrFail();

        $activities = Activity::query()
            ->where('trip_id', $trip->id)
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
                    'description' => $activity->description,
                ],
            ])
        );
    }
}
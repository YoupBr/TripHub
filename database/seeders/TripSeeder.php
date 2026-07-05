<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Trip;
use App\Models\Activity;
use App\Models\Document;
use App\Models\ChecklistItem;

class TripSeeder extends Seeder
{
    public function run(): void
    {
        $trip = Trip::create([
            'name' => 'Zomervakantie 2026',
            'destination' => 'Toscane',
            'start_date' => '2026-07-12',
            'end_date' => '2026-07-26',
        ]);

        Activity::create([
            'trip_id' => $trip->id,
            'title' => 'Vlucht naar Pisa',
            'starts_at' => '2026-07-12 09:35:00',
            'location' => 'Schiphol Airport',
            'category' => 'travel',
        ]);

        Activity::create([
            'trip_id' => $trip->id,
            'title' => 'Check-in villa',
            'starts_at' => '2026-07-13 16:00:00',
            'location' => 'Toscane',
            'category' => 'stay',
        ]);

        Document::create([
            'trip_id' => $trip->id,
            'title' => 'Vliegtickets',
            'type' => 'PDF',
        ]);

        ChecklistItem::create([
            'trip_id' => $trip->id,
            'title' => 'Paspoorten controleren',
            'is_done' => true,
        ]);

        ChecklistItem::create([
            'trip_id' => $trip->id,
            'title' => 'Reisverzekering checken',
        ]);
    }
}

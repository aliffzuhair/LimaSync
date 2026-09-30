<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventPhaseSeeder extends Seeder
{
    public function run(): void
    {
        $phases = [
            ['name' => 'pre_event', 'description' => 'Pre-event planning and preparation', 'sort_order' => 1],
            ['name' => 'during_event', 'description' => 'During event execution', 'sort_order' => 2],
            ['name' => 'post_event', 'description' => 'Post-event closure and reporting', 'sort_order' => 3],
        ];

        foreach ($phases as $phase) {
            DB::table('event_phases')->insertOrIgnore([
                'name' => $phase['name'],
                'description' => $phase['description'],
                'sort_order' => $phase['sort_order'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
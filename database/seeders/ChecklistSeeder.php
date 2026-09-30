<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChecklistSeeder extends Seeder
{
    public function run(): void
    {
        $phases = DB::table('event_phases')->pluck('id', 'name');

        $checklists = [
            // Pre-event (phase_id = pre_event)
            [
                'phase_id' => $phases['pre_event'] ?? null,
                'item_name' => 'Venue Confirmation',
                'description' => 'Confirm venue booking and availability',
                'is_required' => 1,
                'sort_order' => 1,
            ],
            [
                'phase_id' => $phases['pre_event'] ?? null,
                'item_name' => 'Catering Arrangement',
                'description' => 'Confirm food and beverage services',
                'is_required' => 1,
                'sort_order' => 2,
            ],
            [
                'phase_id' => $phases['pre_event'] ?? null,
                'item_name' => 'AV Equipment Check',
                'description' => 'Confirm audio visual equipment is ready',
                'is_required' => 1,
                'sort_order' => 3,
            ],
            [
                'phase_id' => $phases['pre_event'] ?? null,
                'item_name' => 'Staff Assignment',
                'description' => 'Assign staff roles for the event',
                'is_required' => 1,
                'sort_order' => 4,
            ],
            [
                'phase_id' => $phases['pre_event'] ?? null,
                'item_name' => 'Client Briefing',
                'description' => 'Conduct final briefing with client',
                'is_required' => 1,
                'sort_order' => 5,
            ],
            [
                'phase_id' => $phases['pre_event'] ?? null,
                'item_name' => 'Permit Approval',
                'description' => 'Obtain necessary permits and approvals',
                'is_required' => 0,
                'sort_order' => 6,
            ],
            [
                'phase_id' => $phases['pre_event'] ?? null,
                'item_name' => 'Budget Approval',
                'description' => 'Get final budget approval from management',
                'is_required' => 1,
                'sort_order' => 7,
            ],
            // During-event (phase_id = during_event)
            [
                'phase_id' => $phases['during_event'] ?? null,
                'item_name' => 'Registration Desk',
                'description' => 'Set up and man registration desk',
                'is_required' => 1,
                'sort_order' => 1,
            ],
            [
                'phase_id' => $phases['during_event'] ?? null,
                'item_name' => 'VIP Reception',
                'description' => 'Manage VIP guest reception',
                'is_required' => 0,
                'sort_order' => 2,
            ],
            [
                'phase_id' => $phases['during_event'] ?? null,
                'item_name' => 'Session Management',
                'description' => 'Ensure sessions run on schedule',
                'is_required' => 1,
                'sort_order' => 3,
            ],
            [
                'phase_id' => $phases['during_event'] ?? null,
                'item_name' => 'Safety Briefing',
                'description' => 'Conduct safety briefing for participants',
                'is_required' => 1,
                'sort_order' => 4,
            ],
            // Post-event (phase_id = post_event)
            [
                'phase_id' => $phases['post_event'] ?? null,
                'item_name' => 'Debrief Meeting',
                'description' => 'Conduct post-event debrief with team',
                'is_required' => 1,
                'sort_order' => 1,
            ],
            [
                'phase_id' => $phases['post_event'] ?? null,
                'item_name' => 'Client Feedback',
                'description' => 'Collect feedback from client',
                'is_required' => 1,
                'sort_order' => 2,
            ],
            [
                'phase_id' => $phases['post_event'] ?? null,
                'item_name' => 'Financial Close',
                'description' => 'Close financial records for the event',
                'is_required' => 1,
                'sort_order' => 3,
            ],
            [
                'phase_id' => $phases['post_event'] ?? null,
                'item_name' => 'Carbon Report',
                'description' => 'Compile carbon footprint data',
                'is_required' => 1,
                'sort_order' => 4,
            ],
            [
                'phase_id' => $phases['post_event'] ?? null,
                'item_name' => 'Electricity Report',
                'description' => 'Compile electricity usage data',
                'is_required' => 1,
                'sort_order' => 5,
            ],
        ];

        foreach ($checklists as $checklist) {
            if ($checklist['phase_id']) {
                DB::table('checklists')->insert([
                    'phase_id' => $checklist['phase_id'],
                    'item_name' => $checklist['item_name'],
                    'description' => $checklist['description'],
                    'is_required' => $checklist['is_required'],
                    'sort_order' => $checklist['sort_order'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
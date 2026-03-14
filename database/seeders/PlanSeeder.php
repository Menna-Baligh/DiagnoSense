<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                "name" => "Basic",
                'price' => 2400.00,
                'summaries_limit' => 270,
                'duration_days' => 30,
                'features' => json_encode([
                    'Key Important Information',
                    'Comparative Analysis'
                ])
            ],
            [
                "name" => "Pro",
                'price' => 6000.00,
                'summaries_limit' => 450,
                'duration_days' => 30,
                'features' => json_encode([
                    'Key Important Information',
                    'Comparative Analysis',
                    'Decision Support'
                ])
            ],
            [
                "name" => "Premium",
                'price' => 12000.00,
                'summaries_limit' => 660,
                'duration_days' => 30,
                'features' => json_encode([
                    'Key Important Information',
                    'Comparative Analysis',
                    'Decision Support',
                    'DiagnoBot'
                ])
            ],
        ];
        foreach ($plans as $plan) {
            Plan::create($plan);
        }
    }
}

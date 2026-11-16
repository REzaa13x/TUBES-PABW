<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\VolunteerCategoryPreference;
use App\Models\VolunteerApplication;
use App\Http\Controllers\Api\VolunteerRecommendationController;

class VolunteerCategoryPreferenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Find all volunteers
        $volunteers = User::where('role', 'relawan')
            ->orWhere('role', 'volunteer')
            ->get();

        $controller = new VolunteerRecommendationController();

        foreach ($volunteers as $volunteer) {
            try {
                // Auto-discover categories dari riwayat kegiatan relawan
                $controller->autoDiscoverCategories($volunteer);
                
                $this->command->info("✓ Auto-discovered categories for volunteer: {$volunteer->name}");
            } catch (\Exception $e) {
                $this->command->error("✗ Error for volunteer {$volunteer->name}: {$e->getMessage()}");
            }
        }

        $this->command->info("\n✅ Volunteer category preference seeding completed!");
    }
}

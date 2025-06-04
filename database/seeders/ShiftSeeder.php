<?php

namespace Database\Seeders;

use App\Models\Shift;
use Illuminate\Database\Seeder;

class ShiftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Shift::create([
            'name' => 'Pagi',
            'start_time' => '08:00:00',
            'end_time' => '16:00:00',
        ]);

        Shift::create([
            'name' => 'Siang',
            'start_time' => '12:00:00',
            'end_time' => '20:00:00',
        ]);

        Shift::create([
            'name' => 'Malam',
            'start_time' => '20:00:00',
            'end_time' => '04:00:00',
        ]);
    }
}

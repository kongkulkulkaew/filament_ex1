<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TimeLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // สร้างข้อมูล demo สำหรับทุกห้องเรียน
        $classrooms = \App\Models\ClassRoom::where('is_active', true)->get();

        foreach ($classrooms as $classroom) {
            // สร้างข้อมูลการลงเวลา 5-10 รายการต่อห้องเรียน
            $count = fake()->numberBetween(5, 10);

            \App\Models\TimeLog::factory($count)
                ->create([
                    'classroom_id' => $classroom->id,
                ]);
        }
    }
}

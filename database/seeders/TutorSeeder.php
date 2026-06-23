<?php

namespace Database\Seeders;

use App\Models\Tutor;
use Illuminate\Database\Seeder;

class TutorSeeder extends Seeder
{
    public function run(): void
    {
        Tutor::insert([
            ['user_id' => 2, 'codigo' => 'TUT001', 'especialidad' => 'Psicologia Educativa', 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => 3, 'codigo' => 'TUT002', 'especialidad' => 'Consejeria Estudiantil', 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => 4, 'codigo' => 'TUT003', 'especialidad' => 'Bienestar Estudiantil', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}

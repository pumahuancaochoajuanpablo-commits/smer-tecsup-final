<?php

namespace Database\Seeders;

use App\Models\Tutor;
use Illuminate\Database\Seeder;

class TutorSeeder extends Seeder
{
    public function run(): void
    {
        Tutor::insert([
            ['user_id' => 2, 'codigo' => 'TUT001', 'especialidad' => 'Psicología Educativa', 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => 3, 'codigo' => 'TUT002', 'especialidad' => 'Consejería Estudiantil', 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => 4, 'codigo' => 'TUT003', 'especialidad' => 'Bienestar Universitario', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}

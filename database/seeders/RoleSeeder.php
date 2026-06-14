<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::insert([
            ['nombre' => 'admin', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'tutor', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'estudiante', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}

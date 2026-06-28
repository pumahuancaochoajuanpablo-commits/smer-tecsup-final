<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['admin', 'tutor', 'estudiante'] as $nombre) {
            Role::updateOrCreate(['nombre' => $nombre]);
        }
    }
}

<?php

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    public function up(): void
    {
        $adminRole = Role::firstOrCreate(['nombre' => 'admin']);

        User::updateOrCreate(
            ['email' => 'yeferson.quispe@tecsup.edu.pe'],
            [
                'name' => 'Yeferson Quispe',
                'password' => Hash::make('admin123'),
                'rol_id' => $adminRole->id,
                'estado' => true,
            ]
        );
    }

    public function down(): void
    {
        User::where('email', 'yeferson.quispe@tecsup.edu.pe')->delete();
    }
};

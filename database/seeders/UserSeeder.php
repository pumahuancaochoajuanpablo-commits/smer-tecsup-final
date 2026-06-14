<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1 admin
        User::create([
            'name' => 'Administrador SMER',
            'email' => 'admin@smer.com',
            'password' => Hash::make('admin123'),
            'rol_id' => 1,
            'estado' => true,
        ]);

        // 3 tutores
        $tutores = [
            ['name' => 'Carlos Mendoza', 'email' => 'carlos.mendoza@universidad.edu'],
            ['name' => 'María López', 'email' => 'maria.lopez@universidad.edu'],
            ['name' => 'Juan Pérez', 'email' => 'juan.perez@universidad.edu'],
        ];

        foreach ($tutores as $t) {
            User::create([
                'name' => $t['name'],
                'email' => $t['email'],
                'password' => Hash::make('tutor123'),
                'rol_id' => 2,
                'estado' => true,
            ]);
        }

        // 20 estudiantes
        $estudiantes = [
            'Ana Torres', 'Luis García', 'Carmen Ruiz', 'Pedro Sánchez', 'Sofía Ramírez',
            'Diego Fernández', 'Valeria Ortiz', 'Andrés Castro', 'Gabriela Vargas', 'Fernando Silva',
            'Isabella Ríos', 'Mateo Delgado', 'Camila Herrera', 'Santiago Morales', 'Luciana Vega',
            'Sebastián Medina', 'Daniela Campos', 'Emilio Guerrero', 'Renata Flores', 'Adrián Rivas',
        ];

        foreach ($estudiantes as $i => $nombre) {
            $email = strtolower(str_replace(' ', '.', $nombre)) . '@universidad.edu';
            User::create([
                'name' => $nombre,
                'email' => $email,
                'password' => Hash::make('estudiante123'),
                'rol_id' => 3,
                'estado' => true,
            ]);
        }
    }
}

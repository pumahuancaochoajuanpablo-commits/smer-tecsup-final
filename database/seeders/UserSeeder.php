<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Administrador SMER',
            'email' => 'admin@smer.com',
            'password' => Hash::make('admin123'),
            'rol_id' => 1,
            'estado' => true,
        ]);

        $tutores = [
            ['name' => 'Carlos Mendoza', 'email' => 'carlos.mendoza@tecsup.edu.pe'],
            ['name' => 'Maria Lopez', 'email' => 'maria.lopez@tecsup.edu.pe'],
            ['name' => 'Juan Perez', 'email' => 'juan.perez@tecsup.edu.pe'],
        ];

        foreach ($tutores as $tutor) {
            User::create([
                'name' => $tutor['name'],
                'email' => $tutor['email'],
                'password' => Hash::make('tutor123'),
                'rol_id' => 2,
                'estado' => true,
            ]);
        }

        $estudiantes = [
            'Ana Torres', 'Luis Garcia', 'Carmen Ruiz', 'Pedro Sanchez', 'Sofia Ramirez',
            'Diego Fernandez', 'Valeria Ortiz', 'Andres Castro', 'Gabriela Vargas', 'Fernando Silva',
            'Isabella Rios', 'Mateo Delgado', 'Camila Herrera', 'Santiago Morales', 'Luciana Vega',
            'Sebastian Medina', 'Daniela Campos', 'Emilio Guerrero', 'Renata Flores', 'Adrian Rivas',
        ];

        foreach ($estudiantes as $nombre) {
            $email = strtolower(str_replace(' ', '.', $nombre)) . '@tecsup.edu.pe';

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

<?php

namespace Tests\Feature;

use App\Models\Asignacion;
use App\Models\Derivacion;
use App\Models\Estudiante;
use App\Models\Role;
use App\Models\Tutor;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class DerivacionTest extends TestCase
{
    use RefreshDatabase;

    public function test_tutor_can_register_derivation_even_when_email_is_not_configured(): void
    {
        $tutorRole = Role::create(['nombre' => 'tutor']);
        $studentRole = Role::create(['nombre' => 'estudiante']);

        $tutorUser = User::create([
            'name' => 'Tutor Demo',
            'email' => 'tutor.demo@tecsup.edu.pe',
            'password' => Hash::make('tutor123'),
            'rol_id' => $tutorRole->id,
            'estado' => true,
        ]);

        $studentUser = User::create([
            'name' => 'Estudiante Riesgo Alto',
            'email' => 'estudiante.demo@tecsup.edu.pe',
            'password' => Hash::make('student123'),
            'rol_id' => $studentRole->id,
            'estado' => true,
        ]);

        $tutor = Tutor::create([
            'user_id' => $tutorUser->id,
            'codigo' => 'TUT999',
            'especialidad' => 'Consejeria',
        ]);

        $estudiante = Estudiante::create([
            'user_id' => $studentUser->id,
            'codigo' => 'EST999',
            'carrera' => 'Diseno y Desarrollo de Software',
            'estado' => true,
        ]);

        Asignacion::create([
            'tutor_id' => $tutor->id,
            'estudiante_id' => $estudiante->id,
            'fecha_inicio' => now()->toDateString(),
        ]);

        $response = $this->actingAs($tutorUser)->post(route('derivaciones.registrar'), [
            'estudiante_id' => $estudiante->id,
            'motivo' => 'Riesgo alto',
            'descripcion' => 'El estudiante registra riesgo alto y requiere atencion prioritaria.',
            'responsable_bienestar' => 'Yeferson Quispe',
        ]);

        $response->assertRedirect(route('tutor.alertas'));
        $response->assertSessionHas('error');

        $this->assertDatabaseHas('derivaciones', [
            'estudiante_id' => $estudiante->id,
            'tutor_id' => $tutor->id,
            'motivo' => 'Riesgo alto',
            'estado' => 'pendiente',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'yeferson.quispe@tecsup.edu.pe',
            'name' => 'Yeferson Quispe',
        ]);

        $this->assertSame(1, Derivacion::count());
    }
}

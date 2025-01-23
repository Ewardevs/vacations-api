<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Vacation;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {

        $admin = Role::create(['name' => 'Admin']);
        $user = Role::create(['name' => 'User']);

        Permission::create([
            'name' => 'vacations.index',
            'description' => 'Ver listado de vacaciones'
        ])->syncRoles([$admin, $user]);

        Permission::create([
            'name' => 'vacations.store',
            'description' => 'Crear o pedir vacaciones'
        ])->syncRoles([$admin, $user]);

        Permission::create([
            'name' => 'vacations.show',
            'description' => 'Ver vacaciones'
        ])->syncRoles([$admin]);

        Permission::create([
            'name' => 'vacations.update',
            'description' => 'Editar vacacion'
        ])->syncRoles([$user]);

        Permission::create([
            'name' => 'vacations.destroy',
            'description' => 'Eliminar vacaciones'
        ])->syncRoles([ $user]);

        Permission::create([
            'name' => 'vacations.reject',
            'description' => 'Rechazar vacacion'
        ])->syncRoles([$admin]);

        Permission::create([
            'name' => 'vacations.approve',
            'description' => 'Ver vacaciones'
        ])->syncRoles([$admin]);



        // Crear instancia de Faker para generar datos aleatorios
        $faker = Faker::create();

        User::create([
            "name" => "ewar",
            "email" => "ewar@gmail.com",
            "password" => "12345678",
        ])->assignRole('Admin');

        User::create([
            "name" => "kri",
            "email" => "kri@gmail.com",
            "password" => "12345678"
        ])->assignRole('User');

        // Obtener todos los usuarios (excepto el administrador o los que no deberían estar en la tabla)
        //         $users = User::all();

        //         // Insertar datos en la tabla vacations
        //         for ($i = 0; $i < 10; $i++) { // Generar 10 registros de ejemplo
        //             $applicant = $users->random();
        //             $approver = $users->where('id', '!=', $applicant->id)->random(); // Asegurarse de que el aprobador no sea el mismo que el solicitante

        //             DB::table('vacations')->insert([
        //                 'applicant_id' => $applicant->id,
        //                 'approver_id' => $faker->randomElement([$approver->id, null]),  // Asigna el ID del aprobador si está presente, sino lo deja en null
        //                 'message_applicant' => $faker->text(200),
        //                 'message_approver' => $faker->text(200),
        //                 'status' => $approver ? $faker->randomElement(['approved', 'rejected']) : 'pending',  // Si hay aprobador, el estado es 'approved' o 'rejected'; si no, 'pending'
        //                 'start_date' => $faker->date(),
        //                 'end_date' => $faker->date(),
        //                 'created_at' => now(),
        //                 'updated_at' => now(),
        //             ]);
        //         }
    }
}

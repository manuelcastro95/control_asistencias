<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Crear usuario administrador
        DB::table('users')->insert([
    		'name' => 'Administrador',
    		'email' => 'admin@admin.com',
    		'password' => Hash::make('control1234')
    	]);

        // Ejecutar seeder de instituciones (esto crea todo: instituciones, sedes, grados, alumnos y asistencias)
        $this->call([
            InstitucionSeeder::class,
        ]);
    }
}

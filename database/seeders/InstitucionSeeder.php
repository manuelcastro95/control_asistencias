<?php

namespace Database\Seeders;

use App\Models\Institucion;
use App\Models\Sede;
use App\Models\Grado;
use App\Models\Alumno;
use App\Models\Asistencia;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;

class InstitucionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Verificar si la columna hora existe en asistencias
        $tieneHora = Schema::hasColumn('asistencias', 'hora');
        
        // Crear 3 instituciones
        $instituciones = [
            [
                'nombre' => 'Colegio San José',
                'nit' => '900123456-1',
                'direccion' => 'Calle 50 # 30-45',
                'telefono' => '6012345678',
                'email' => 'contacto@colegiosanjose.edu.co',
                'activo' => true,
            ],
            [
                'nombre' => 'Instituto Técnico Industrial',
                'nit' => '900234567-2',
                'direccion' => 'Avenida 68 # 25-10',
                'telefono' => '6023456789',
                'email' => 'info@institutotecnico.edu.co',
                'activo' => true,
            ],
            [
                'nombre' => 'Liceo Moderno',
                'nit' => '900345678-3',
                'direccion' => 'Carrera 15 # 40-20',
                'telefono' => '6034567890',
                'email' => 'administracion@liceomoderno.edu.co',
                'activo' => true,
            ],
        ];

        $gradosNombres = [
            'Primero', 'Segundo', 'Tercero', 'Cuarto', 'Quinto',
            'Sexto', 'Séptimo', 'Octavo', 'Noveno', 'Décimo', 'Undécimo'
        ];

        $contadorGlobalAlumno = 0; // Contador global para garantizar unicidad de códigos

        foreach ($instituciones as $instData) {
            $institucion = Institucion::create($instData);

            // Crear 2-3 sedes por institución
            $numSedes = rand(2, 3);
            for ($s = 1; $s <= $numSedes; $s++) {
                $sede = Sede::create([
                    'institucion_id' => $institucion->id,
                    'nombre' => $s == 1 ? 'Sede Principal' : "Sede {$s}",
                    'codigo' => 'SP' . $s,
                    'direccion' => $instData['direccion'] . " - Sede {$s}",
                    'telefono' => $instData['telefono'],
                    'email' => "sede{$s}@" . Str::slug($institucion->nombre) . '.edu.co',
                    'activo' => true,
                ]);

                // Crear grados (5-11 grados por sede)
                $numGrados = rand(5, 11);
                $gradosCreados = [];
                
                for ($g = 0; $g < $numGrados; $g++) {
                    $grado = Grado::create([
                        'sede_id' => $sede->id,
                        'nombre' => $gradosNombres[$g],
                        'codigo' => ($g + 1) . '°',
                        'orden' => $g + 1,
                        'descripcion' => "Grado {$gradosNombres[$g]} de {$sede->nombre}",
                        'activo' => true,
                    ]);
                    $gradosCreados[] = $grado;
                }

                // Crear alumnos por grado (15-30 alumnos por grado)
                foreach ($gradosCreados as $grado) {
                    $numAlumnos = rand(15, 30);
                    
                    for ($a = 1; $a <= $numAlumnos; $a++) {
                        $nombres = [
                            'Juan', 'María', 'Carlos', 'Ana', 'Luis', 'Laura', 'Pedro', 'Sofía',
                            'Diego', 'Valentina', 'Andrés', 'Isabella', 'Sebastián', 'Camila',
                            'Nicolás', 'Mariana', 'Daniel', 'Gabriela', 'Mateo', 'Daniela',
                            'Santiago', 'Valeria', 'Alejandro', 'Natalia', 'Javier', 'Paula'
                        ];
                        
                        $apellidos = [
                            'García', 'Rodríguez', 'López', 'Martínez', 'González', 'Pérez',
                            'Sánchez', 'Ramírez', 'Torres', 'Flores', 'Rivera', 'Gómez',
                            'Díaz', 'Cruz', 'Morales', 'Ortiz', 'Gutiérrez', 'Chávez',
                            'Ramos', 'Mendoza', 'Herrera', 'Jiménez', 'Vargas', 'Castro'
                        ];

                        $generos = ['M', 'F'];
                        $genero = $generos[array_rand($generos)];
                        
                        $nombre = $nombres[array_rand($nombres)];
                        $apellido1 = $apellidos[array_rand($apellidos)];
                        $apellido2 = $apellidos[array_rand($apellidos)];

                        // Generar código único usando contador global
                        $prefijo = strtoupper(substr($institucion->nombre, 0, 3));
                        $sedeCodigo = strtoupper(substr($sede->codigo, 0, 2));
                        $gradoOrden = str_pad($grado->orden, 2, '0', STR_PAD_LEFT);
                        // Usar contador global para garantizar unicidad absoluta
                        $alumnoNum = str_pad($contadorGlobalAlumno, 6, '0', STR_PAD_LEFT);
                        
                        $codigo = $prefijo . $sedeCodigo . $gradoOrden . $alumnoNum;
                        
                        // Verificar que el código no exista (por si acaso, aunque no debería pasar)
                        $intentos = 0;
                        while (Alumno::where('codigo', $codigo)->exists() && $intentos < 10) {
                            $contadorGlobalAlumno++;
                            $alumnoNum = str_pad($contadorGlobalAlumno, 6, '0', STR_PAD_LEFT);
                            $codigo = $prefijo . $sedeCodigo . $gradoOrden . $alumnoNum;
                            $intentos++;
                        }
                        
                        // Incrementar contador después de generar el código único
                        $contadorGlobalAlumno++;

                        $fechaNacimiento = now()->subYears(rand(6, 18))->subDays(rand(0, 365));

                        // Generar password por defecto (el código del alumno)
                        // En producción, esto debería ser cambiado por el estudiante
                        $passwordDefault = Hash::make($codigo);

                        // Crear alumno sin QR primero
                        $alumno = Alumno::create([
                            'grado_id' => $grado->id,
                            'codigo' => $codigo,
                            'nombres' => $nombre,
                            'apellidos' => $apellido1 . ' ' . $apellido2,
                            'email' => strtolower($nombre . '.' . $apellido1 . '@' . Str::slug($institucion->nombre) . '.edu.co'),
                            'password' => $passwordDefault,
                            'telefono' => '3' . rand(100000000, 999999999),
                            'fecha_nacimiento' => $fechaNacimiento,
                            'genero' => $genero,
                            'documento_identidad' => (string)rand(1000000000, 9999999999),
                            'direccion' => 'Calle ' . rand(1, 200) . ' # ' . rand(1, 100) . '-' . rand(10, 99),
                            'nombre_acudiente' => $apellidos[array_rand($apellidos)] . ' ' . $nombres[array_rand($nombres)],
                            'telefono_acudiente' => '3' . rand(100000000, 999999999),
                            'observaciones' => rand(0, 1) ? 'Alumno regular' : null,
                            'activo' => rand(0, 10) > 1, // 90% activos
                        ]);

                        // Generar QR después de guardar (necesita el código)
                        $alumno->qr = $alumno->qr_base;
                        $alumno->save();

                        // Crear algunas asistencias aleatorias (últimos 30 días)
                        $diasAsistencia = rand(15, 25); // 15-25 días de asistencia
                        $dias = [];
                        for ($i = 0; $i < $diasAsistencia; $i++) {
                            $dia = now()->subDays(rand(0, 30))->format('Y-m-d');
                            if (!in_array($dia, $dias)) {
                                $dias[] = $dia;
                                
                                // Preparar datos de asistencia
                                $datosAsistencia = [
                                    'alumno_id' => $alumno->id,
                                    'fecha' => $dia,
                                ];
                                
                                // Si la columna hora existe, agregar hora aleatoria
                                if ($tieneHora) {
                                    $horaH = rand(6, 20);
                                    $horaM = rand(0, 59);
                                    $horaS = rand(0, 59);
                                    $datosAsistencia['hora'] = sprintf('%02d:%02d:%02d', $horaH, $horaM, $horaS);
                                }
                                
                                Asistencia::create($datosAsistencia);
                            }
                        }
                    }
                }
            }
        }

        $this->command->info('✅ Seeders completados:');
        $this->command->info('   - ' . Institucion::count() . ' instituciones');
        $this->command->info('   - ' . Sede::count() . ' sedes');
        $this->command->info('   - ' . Grado::count() . ' grados');
        $this->command->info('   - ' . Alumno::count() . ' alumnos');
        $this->command->info('   - ' . Asistencia::count() . ' asistencias');
    }
}

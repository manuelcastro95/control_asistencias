<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Alumno;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthAlumnoApiController extends Controller
{
    /**
     * Login de estudiante
     */
    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'codigo' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        $alumno = Alumno::where('codigo', $request->codigo)
            ->where('activo', true)
            ->first();

        if (!$alumno) {
            return response()->json([
                'success' => false,
                'message' => 'Estudiante no encontrado o inactivo'
            ], 404);
        }

        // Si no tiene password, verificar si es el código por defecto
        if (!$alumno->password) {
            // Permitir login con código si no tiene password configurado
            // En producción, esto debería requerir que el admin configure el password
            if ($request->password === $alumno->codigo) {
                // Generar token
                $token = $alumno->createToken('alumno_token', ['alumno'])->plainTextToken;

                return response()->json([
                    'success' => true,
                    'message' => 'Login exitoso',
                    'data' => [
                        'alumno' => [
                            'id' => $alumno->id,
                            'codigo' => $alumno->codigo,
                            'nombres' => $alumno->nombres,
                            'apellidos' => $alumno->apellidos,
                            'email' => $alumno->email,
                            'grado' => $alumno->grado ? $alumno->grado->nombre : null,
                        ],
                        'token' => $token,
                        'token_type' => 'Bearer',
                        'role' => 'alumno',
                    ]
                ]);
            }
        } else {
            // Verificar password
            if (Hash::check($request->password, $alumno->password)) {
                $token = $alumno->createToken('alumno_token', ['alumno'])->plainTextToken;

                return response()->json([
                    'success' => true,
                    'message' => 'Login exitoso',
                    'data' => [
                        'alumno' => [
                            'id' => $alumno->id,
                            'codigo' => $alumno->codigo,
                            'nombres' => $alumno->nombres,
                            'apellidos' => $alumno->apellidos,
                            'email' => $alumno->email,
                            'grado' => $alumno->grado ? $alumno->grado->nombre : null,
                        ],
                        'token' => $token,
                        'token_type' => 'Bearer',
                        'role' => 'alumno',
                    ]
                ]);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'Credenciales incorrectas'
        ], 401);
    }

    /**
     * Logout de estudiante
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Sesión cerrada correctamente'
        ]);
    }

    /**
     * Obtener estudiante autenticado
     */
    public function me(Request $request): JsonResponse
    {
        $alumno = $request->user();
        $alumno->load('grado.sede.institucion');

        return response()->json([
            'success' => true,
            'data' => [
                'alumno' => [
                    'id' => $alumno->id,
                    'codigo' => $alumno->codigo,
                    'nombres' => $alumno->nombres,
                    'apellidos' => $alumno->apellidos,
                    'nombre_completo' => $alumno->full_name,
                    'email' => $alumno->email,
                    'telefono' => $alumno->telefono,
                    'fecha_nacimiento' => $alumno->fecha_nacimiento?->format('Y-m-d'),
                    'edad' => $alumno->edad,
                    'genero' => $alumno->genero,
                    'documento_identidad' => $alumno->documento_identidad,
                    'direccion' => $alumno->direccion,
                    'nombre_acudiente' => $alumno->nombre_acudiente,
                    'telefono_acudiente' => $alumno->telefono_acudiente,
                    'observaciones' => $alumno->observaciones,
                    'grado' => $alumno->grado ? [
                        'id' => $alumno->grado->id,
                        'nombre' => $alumno->grado->nombre,
                        'codigo' => $alumno->grado->codigo,
                    ] : null,
                    'sede' => $alumno->grado && $alumno->grado->sede ? [
                        'id' => $alumno->grado->sede->id,
                        'nombre' => $alumno->grado->sede->nombre,
                        'codigo' => $alumno->grado->sede->codigo,
                    ] : null,
                    'institucion' => $alumno->grado && $alumno->grado->sede && $alumno->grado->sede->institucion ? [
                        'id' => $alumno->grado->sede->institucion->id,
                        'nombre' => $alumno->grado->sede->institucion->nombre,
                    ] : null,
                    'activo' => $alumno->activo,
                ],
                'role' => 'alumno',
            ]
        ]);
    }
}


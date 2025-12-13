<?php

namespace App\Imports;

use App\Models\Alumno;
use App\Models\Grado;
use App\Services\AlumnoService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Validators\Failure;

class AlumnosImport implements ToCollection, WithHeadingRow, WithValidation, SkipsOnFailure
{
    protected $alumnoService;
    protected $errors = [];
    protected $importados = 0;
    protected $fallidos = 0;

    public function __construct(AlumnoService $alumnoService)
    {
        $this->alumnoService = $alumnoService;
    }

    /**
     * @param Collection $rows
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            try {
                // Normalizar nombres de columnas (case insensitive)
                $rowData = $this->normalizeRow($row);
                
                // Validar que tenga los campos mínimos
                if (empty($rowData['codigo']) || empty($rowData['nombres']) || empty($rowData['apellidos'])) {
                    $this->fallidos++;
                    $this->errors[] = "Fila con datos incompletos: " . json_encode($rowData);
                    continue;
                }

                // Buscar o crear grado si se especifica
                $gradoId = null;
                if (!empty($rowData['grado'])) {
                    $grado = Grado::where('nombre', 'like', '%' . $rowData['grado'] . '%')
                        ->orWhere('codigo', $rowData['grado'])
                        ->first();
                    
                    if ($grado) {
                        $gradoId = $grado->id;
                    }
                }

                // Verificar si el alumno ya existe
                $alumnoExistente = Alumno::where('codigo', $rowData['codigo'])->first();
                
                if ($alumnoExistente) {
                    // Actualizar alumno existente
                    $data = [
                        'codigo' => $rowData['codigo'],
                        'nombres' => $rowData['nombres'],
                        'apellidos' => $rowData['apellidos'],
                        'grado_id' => $gradoId,
                        'email' => $rowData['email'] ?? null,
                        'telefono' => $rowData['telefono'] ?? null,
                        'fecha_nacimiento' => $this->parseDate($rowData['fecha_nacimiento'] ?? null),
                        'genero' => $this->parseGenero($rowData['genero'] ?? null),
                        'documento_identidad' => $rowData['documento_identidad'] ?? null,
                        'direccion' => $rowData['direccion'] ?? null,
                        'nombre_acudiente' => $rowData['nombre_acudiente'] ?? null,
                        'telefono_acudiente' => $rowData['telefono_acudiente'] ?? null,
                        'observaciones' => $rowData['observaciones'] ?? null,
                    ];
                    
                    $this->alumnoService->update($alumnoExistente, $data);
                    $this->importados++;
                } else {
                    // Crear nuevo alumno
                    $data = [
                        'codigo' => $rowData['codigo'],
                        'nombres' => $rowData['nombres'],
                        'apellidos' => $rowData['apellidos'],
                        'grado_id' => $gradoId,
                        'email' => $rowData['email'] ?? null,
                        'telefono' => $rowData['telefono'] ?? null,
                        'fecha_nacimiento' => $this->parseDate($rowData['fecha_nacimiento'] ?? null),
                        'genero' => $this->parseGenero($rowData['genero'] ?? null),
                        'documento_identidad' => $rowData['documento_identidad'] ?? null,
                        'direccion' => $rowData['direccion'] ?? null,
                        'nombre_acudiente' => $rowData['nombre_acudiente'] ?? null,
                        'telefono_acudiente' => $rowData['telefono_acudiente'] ?? null,
                        'observaciones' => $rowData['observaciones'] ?? null,
                    ];
                    
                    $this->alumnoService->create($data);
                    $this->importados++;
                }
            } catch (\Exception $e) {
                $this->fallidos++;
                $this->errors[] = "Error en fila: " . $e->getMessage();
                Log::error('Error importando alumno', [
                    'row' => $row->toArray(),
                    'error' => $e->getMessage()
                ]);
            }
        }
    }

    /**
     * Normalizar nombres de columnas
     */
    protected function normalizeRow($row)
    {
        $normalized = [];
        $mapping = [
            'codigo' => ['codigo', 'código', 'code', 'id'],
            'nombres' => ['nombres', 'nombre', 'name', 'first_name'],
            'apellidos' => ['apellidos', 'apellido', 'last_name', 'surname'],
            'grado' => ['grado', 'grade', 'curso', 'nivel'],
            'email' => ['email', 'correo', 'e-mail'],
            'telefono' => ['telefono', 'teléfono', 'phone', 'celular'],
            'fecha_nacimiento' => ['fecha_nacimiento', 'fecha de nacimiento', 'birth_date', 'nacimiento'],
            'genero' => ['genero', 'género', 'sexo', 'gender'],
            'documento_identidad' => ['documento_identidad', 'documento', 'cedula', 'cédula', 'dni'],
            'direccion' => ['direccion', 'dirección', 'address', 'domicilio'],
            'nombre_acudiente' => ['nombre_acudiente', 'acudiente', 'tutor', 'representante'],
            'telefono_acudiente' => ['telefono_acudiente', 'teléfono_acudiente', 'phone_acudiente'],
            'observaciones' => ['observaciones', 'observacion', 'notas', 'notes'],
        ];

        foreach ($mapping as $key => $variants) {
            foreach ($variants as $variant) {
                $variantLower = strtolower($variant);
                foreach ($row->keys() as $rowKey) {
                    if (strtolower($rowKey) === $variantLower) {
                        $normalized[$key] = $row[$rowKey];
                        break 2;
                    }
                }
            }
        }

        return $normalized;
    }

    /**
     * Parsear fecha desde diferentes formatos
     */
    protected function parseDate($date)
    {
        if (empty($date)) {
            return null;
        }

        try {
            // Si es numérico, podría ser fecha serial de Excel
            if (is_numeric($date) && $date > 25569) {
                // Fecha serial de Excel (días desde 1900-01-01)
                $timestamp = ($date - 25569) * 86400;
                return date('Y-m-d', $timestamp);
            }
            
            // Intentar parsear como fecha normal
            $parsed = \Carbon\Carbon::parse($date);
            return $parsed->format('Y-m-d');
        } catch (\Exception $e) {
            // Si falla, intentar formato común
            try {
                return \Carbon\Carbon::createFromFormat('d/m/Y', $date)->format('Y-m-d');
            } catch (\Exception $e2) {
                try {
                    return \Carbon\Carbon::createFromFormat('Y-m-d', $date)->format('Y-m-d');
                } catch (\Exception $e3) {
                    return null;
                }
            }
        }
    }

    /**
     * Parsear género
     */
    protected function parseGenero($genero)
    {
        if (empty($genero)) {
            return null;
        }

        $genero = strtoupper(trim($genero));
        
        if (in_array($genero, ['M', 'MASCULINO', 'MALE', 'H', 'HOMBRE'])) {
            return 'M';
        }
        
        if (in_array($genero, ['F', 'FEMENINO', 'FEMALE', 'MUJER'])) {
            return 'F';
        }
        
        return 'O';
    }

    /**
     * Reglas de validación
     */
    public function rules(): array
    {
        return [
            'codigo' => 'required',
            'nombres' => 'required',
            'apellidos' => 'required',
        ];
    }

    /**
     * Manejar fallos
     */
    public function onFailure(Failure ...$failures)
    {
        foreach ($failures as $failure) {
            $this->fallidos++;
            $this->errors[] = "Fila {$failure->row()}: " . implode(', ', $failure->errors());
        }
    }

    /**
     * Obtener resultados de la importación
     */
    public function getResults()
    {
        return [
            'importados' => $this->importados,
            'fallidos' => $this->fallidos,
            'errors' => $this->errors,
        ];
    }
}


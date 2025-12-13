<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAlumnoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $alumnoId = $this->route('alumno')->id ?? null;

        return [
            'codigo' => [
                'required',
                'string',
                'max:50',
                Rule::unique('alumnos', 'codigo')->ignore($alumnoId),
                'regex:/^[A-Za-z0-9\-_]+$/'
            ],
            'nombres' => [
                'required',
                'string',
                'max:100',
                'min:2',
                'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/'
            ],
            'apellidos' => [
                'required',
                'string',
                'max:100',
                'min:2',
                'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/'
            ],
            'grado_id' => 'nullable|exists:grados,id',
            'email' => 'nullable|email|max:100',
            'telefono' => 'nullable|string|max:20',
            'fecha_nacimiento' => 'nullable|date',
            'genero' => 'nullable|in:M,F,O',
            'documento_identidad' => 'nullable|string|max:50',
            'direccion' => 'nullable|string|max:255',
            'nombre_acudiente' => 'nullable|string|max:100',
            'telefono_acudiente' => 'nullable|string|max:20',
            'observaciones' => 'nullable|string',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'codigo.required' => 'El código es obligatorio.',
            'codigo.unique' => 'Este código ya está registrado.',
            'codigo.regex' => 'El código solo puede contener letras, números, guiones y guiones bajos.',
            'nombres.required' => 'Los nombres son obligatorios.',
            'nombres.min' => 'Los nombres deben tener al menos 2 caracteres.',
            'nombres.regex' => 'Los nombres solo pueden contener letras y espacios.',
            'apellidos.required' => 'Los apellidos son obligatorios.',
            'apellidos.min' => 'Los apellidos deben tener al menos 2 caracteres.',
            'apellidos.regex' => 'Los apellidos solo pueden contener letras y espacios.',
        ];
    }
}


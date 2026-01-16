<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResguardoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'fecha_resguardo' => 'required|date',
            'estado' => 'required|string|in:activo,inactivo',
            'prestamo_id' => 'required|exists:prestamos,id', // Validar que el préstamo exista
        ];
    }
}

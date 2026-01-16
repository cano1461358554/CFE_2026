<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StockRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cantidad' => 'required|numeric|min:1',
            'material_id' => 'required|exists:materials,id', // Validar que el material exista
            'almacen_id' => 'required|exists:almacens,id',  // Validar que el almacén exista
        ];
    }
}

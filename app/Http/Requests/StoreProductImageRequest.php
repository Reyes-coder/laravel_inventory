<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductImageRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048'
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'image.required' => 'Debes seleccionar una imagen.',
            'image.image' => 'El archivo debe ser una imagen válida.',
            'image.mimes' => 'El formato de imagen no es permitido. Formatos aceptados: JPEG, PNG, GIF, SVG, WebP.',
            'image.max' => 'El tamaño de la imagen no puede exceder 2MB.',
        ];
    }
}

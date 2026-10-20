<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class QuoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:150', 'phone' => ['required', 'string', 'max:30', 'regex:/^[+0-9 ()-]{6,30}$/'], 'email' => 'nullable|email|max:254',
            'specialty_id' => ['required', Rule::exists('specialties', 'id')->where('active', true)],
            'work_type' => ['required', Rule::exists('services', 'name')->where('specialty_id', $this->input('specialty_id'))->where('active', true)],
            'width' => 'nullable|numeric|min:0.001|max:9999999', 'height' => 'nullable|numeric|min:0.001|max:9999999', 'unit' => 'required_with:width,height|nullable|in:mm,cm,m',
            'quantity' => 'required|integer|min:1|max:10000', 'description' => 'required|string|min:10|max:5000', 'zone' => 'nullable|string|max:255',
            'photos' => 'nullable|array|max:5', 'photos.*' => 'file|image|mimes:jpg,jpeg,png,webp|extensions:jpg,jpeg,png,webp|max:5120', 'consent' => 'accepted', 'website' => 'prohibited',
        ];
    }

    public function attributes(): array
    {
        return ['name' => 'nombre', 'phone' => 'teléfono', 'description' => 'descripción', 'specialty_id' => 'especialidad', 'work_type' => 'tipo de trabajo', 'consent' => 'consentimiento', 'photos.*' => 'fotografía'];
    }
}

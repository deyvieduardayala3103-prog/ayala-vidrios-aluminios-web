<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return ['name' => 'required|string|max:150', 'phone' => ['required', 'string', 'max:30', 'regex:/^[+0-9 ()-]{6,30}$/'], 'email' => 'nullable|email|max:254', 'message' => 'required|string|min:10|max:5000', 'consent' => 'accepted', 'website' => 'prohibited'];
    }
}

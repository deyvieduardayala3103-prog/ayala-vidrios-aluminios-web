<?php

namespace App\Http\Requests;

use App\Services\AdminModules;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class AdminRecordRequest extends FormRequest
{
    public function authorize(): bool
    {
        $module = AdminModules::get($this->route('module'));

        return $this->user()->can($module['gate']) && empty($module['readonly']);
    }

    public function rules(): array
    {
        $module = AdminModules::get($this->route('module'));
        $id = $this->route('id');
        $rules = [];
        foreach ($module['fields'] as $key => $field) {
            if ($field[1] === 'display') {
                continue;
            }
            $rule = $field[2];
            if (str_contains($rule, '|unique:')) {
                [$prefix,$unique] = explode('|unique:', $rule);
                [$table,$column] = explode(',', $unique);
                $rules[$key] = [...explode('|', $prefix), Rule::unique($table, $column)->ignore($id)];
            } else {
                $rules[$key] = $rule;
            }
        }
        if ($this->route('module') === 'usuarios') {
            $rules['password'] = [$id ? 'nullable' : 'required', Password::min(12)->mixedCase()->numbers()->symbols()];
        }
        if ($this->route('module') === 'proyectos') {
            $rules['photos'] = 'nullable|array|max:10';
            $rules['photos.*'] = 'file|image|mimes:jpg,jpeg,png,webp|extensions:jpg,jpeg,png,webp|max:5120';
            $rules['image_alt'] = 'required_with:photos|nullable|string|max:255';
        }

        return $rules;
    }
}

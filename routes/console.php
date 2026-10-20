<?php

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

Artisan::command('ayala:admin', function () {
    $name = $this->ask('Nombre');
    $email = $this->ask('Correo electrónico');
    $password = $this->secret('Contraseña (mínimo 12 caracteres, mayúsculas, minúsculas, número y símbolo)');
    $data = ['name' => $name, 'email' => $email, 'password' => $password];
    $validator = validator($data, ['name' => 'required|string|max:150', 'email' => 'required|email|max:254|unique:users,email', 'password' => ['required', Password::min(12)->mixedCase()->numbers()->symbols()]]);
    if ($validator->fails()) {
        foreach ($validator->errors()->all() as $error) {
            $this->error($error);
        }

        return 1;
    }
    User::create([...$data, 'password' => Hash::make($password), 'role_id' => Role::firstOrCreate(['name' => 'administrador'])->id, 'active' => true]);
    $this->info('Administrador creado. No se imprime ni almacena la contraseña en texto plano.');

    return 0;
})->purpose('Crear de forma interactiva el primer administrador');

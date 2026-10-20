@extends('layouts.public')
@section('title','Acceso administrativo')
@section('content')<section class="section bg-slate-50">
<div class="wrap max-w-md">
<span class="eyebrow">Área privada</span>
<h1 class="!text-3xl mb-8">Acceso administrativo</h1>
<x-feedback/>
<form method="POST" action="{{ route('login.store') }}" class="card card-body grid gap-6">@csrf<div>
<label class="form-label" for="email">Correo electrónico</label>
<input class="field" type="email" id="email" name="email" value="{{ old('email') }}" autocomplete="username" required autofocus>
</div>
<div>
<label class="form-label" for="password">Contraseña</label>
<input class="field" type="password" name="password" id="password" autocomplete="current-password" required>
</div>
<button class="btn" type="submit">Ingresar →</button>
<p class="text-xs">Solo para usuarios autorizados. Si no puedes acceder, contacta al administrador de la empresa.</p>
</form>
</div>
</section>@endsection
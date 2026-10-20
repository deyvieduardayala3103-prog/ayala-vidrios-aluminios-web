@extends('layouts.public')
@section('title','Solicitar cotización')
@section('content')<x-page-head title="Tu próximo proyecto empieza aquí." tag="Solicita una cotización" intro="Cuéntanos qué necesitas. Revisaremos la información para preparar una propuesta personalizada; esta solicitud no genera un precio automático."/>
<section class="section">
<div class="wrap grid lg:grid-cols-[1fr_2fr] gap-12">
<aside>
<span class="eyebrow">Hablemos de tu idea</span>
<h2 class="!text-3xl">Cada detalle<br>cuenta.</h2>
<p class="mt-5 text-sm leading-7">Si tienes medidas o fotografías, compártelas para ayudarnos a entender tu proyecto. Los campos con * son obligatorios.</p>
<div class="border-t border-slate-200 mt-7 pt-7 text-sm">
<span class="font-semibold">Una propuesta, sin sorpresas.</span>
<p class="mt-3">El alcance, los materiales y los importes se revisan antes de confirmar cualquier trabajo.</p>
</div>
</aside>
<div>
<x-feedback/>
<form action="{{ route('quote.store') }}" method="POST" enctype="multipart/form-data" class="card card-body grid gap-7" x-data='quoteForm(@json($specialties), @json(old("specialty_id", request("especialidad"))), @json(old("work_type")))'>@csrf
<h3>01 · Datos de contacto</h3>
<div class="grid sm:grid-cols-2 gap-5">
<div>
<label class="form-label" for="name">Nombre completo *</label>
<input class="field" id="name" name="name" value="{{ old('name') }}" autocomplete="name" maxlength="150" required>
</div>
<div>
<label class="form-label" for="phone">Teléfono *</label>
<input class="field" id="phone" type="tel" name="phone" value="{{ old('phone') }}" autocomplete="tel" maxlength="30" required>
</div>
<div class="sm:col-span-2">
<label class="form-label" for="email">Correo electrónico (opcional)</label>
<input class="field" id="email" type="email" name="email" value="{{ old('email') }}" autocomplete="email" maxlength="254">
</div>
</div>
<h3 class="border-t border-slate-200 pt-7">02 · Sobre tu proyecto</h3>
<div class="grid sm:grid-cols-2 gap-5">
<div>
<label for="specialty" class="form-label">Especialidad *</label>
<select class="field" id="specialty" name="specialty_id" x-model="specialty" @change="work=''" required>
<option value="">Selecciona una especialidad</option>@foreach($specialties as $specialty)<option value="{{ $specialty->id }}" @selected(old('specialty_id',request('especialidad'))==$specialty->id)>{{ $specialty->name }}</option>@endforeach</select>
</div>
<div>
<label class="form-label" for="work_type">Tipo de trabajo *</label>
<select class="field" name="work_type" id="work_type" x-model="work" required>
<option value="">Selecciona el trabajo</option>
<template x-for="service in services" :key="service.id">
<option :value="service.name" x-text="service.name">
</option>
</template>
</select>
<noscript>
<p>Activa JavaScript para seleccionar los tipos de trabajo según especialidad.</p>
</noscript>
</div>
</div>
<p class="text-xs" x-show="current" x-cloak>Medidas de referencia para <span x-text="current?.name">
</span>. Si el trabajo requiere varias piezas o medidas, detállalas en la descripción.</p>
<div class="grid grid-cols-2 sm:grid-cols-4 gap-4">@foreach(['width'=>'Ancho','height'=>'Alto'] as $key=>$label)<div>
<label for="{{ $key }}" class="form-label">{{ $label }} (opcional)</label>
<input class="field" id="{{ $key }}" name="{{ $key }}" type="number" step="0.001" min="0.001" max="9999999" value="{{ old($key) }}">
</div>@endforeach<div>
<label for="unit" class="form-label">Unidad</label>
<select class="field" name="unit" id="unit">
<option value="">Selecciona</option>@foreach(['mm','cm','m'] as $unit)<option @selected(old('unit')===$unit)>{{ $unit }}</option>@endforeach</select>
</div>
<div>
<label for="quantity" class="form-label">Cantidad *</label>
<input class="field" name="quantity" id="quantity" type="number" min="1" max="10000" value="{{ old('quantity',1) }}" required>
</div>
</div>
<div>
<label class="form-label" for="description">Descripción del proyecto *</label>
<textarea class="field" name="description" id="description" minlength="10" maxlength="5000" placeholder="Describe el espacio, los acabados y lo que necesitas…" required>{{ old('description') }}</textarea>
</div>
<div>
<label class="form-label" for="zone">Dirección o zona de instalación (opcional)</label>
<input class="field" name="zone" id="zone" value="{{ old('zone') }}" maxlength="255">
</div>
<div>
<label class="form-label" for="photos">Fotografías de referencia (opcional)</label>
<input class="field" type="file" id="photos" name="photos[]" multiple accept="image/jpeg,image/png,image/webp" aria-describedby="photos-help">
<p class="text-xs mt-2" id="photos-help">Hasta 5 imágenes JPG, PNG o WebP. Máximo 5 MB por imagen. Los archivos son privados.</p>
</div>
<x-consent/>
<button class="btn justify-self-start" type="submit">Enviar solicitud <span>↗</span>
</button>
</form>
</div>
</div>
</section>@endsection
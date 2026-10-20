@extends('layouts.public')
@section('title','Contacto')
@section('content')<x-page-head title="Conversemos sobre tu proyecto." tag="Contacto" intro="Déjanos un mensaje y comparte tus datos para que podamos atender tu consulta."/>
<section class="section">
<div class="wrap grid lg:grid-cols-2 gap-14">
<div>
<h2 class="!text-3xl">Estamos para ayudarte.</h2>
<div class="grid gap-6 mt-8">@foreach(['phone'=>'Teléfono','email'=>'Correo electrónico','address'=>'Dirección','hours'=>'Horario de atención'] as $key=>$label)@if(!empty($business[$key]))<div>
<h3 class="!text-sm">{{ $label }}</h3>
<p class="mt-2 text-sm prose-text">{{ $business[$key] }}</p>
</div>@endif @endforeach</div>@if(!empty($business['whatsapp']))<a class="btn mt-7" href="https://wa.me/{{ $business['whatsapp'] }}" target="_blank" rel="noopener noreferrer">Consultar por WhatsApp ↗</a>@endif @if(!empty($business['map_url']))<a class="block text-brand mt-5" href="{{ $business['map_url'] }}" target="_blank" rel="noopener noreferrer">Ver ubicación en el mapa ↗</a>@endif<p class="text-sm mt-8">¿Ya tienes los detalles de tu trabajo? <a href="{{ route('quote') }}" class="text-brand underline">Solicita una cotización</a>.</p>
</div>
<div>
<x-feedback/>
<form method="POST" action="{{ route('contact.store') }}" class="card card-body grid gap-5">@csrf @foreach(['name'=>['Nombre completo *','text'],'phone'=>['Teléfono *','tel'],'email'=>['Correo electrónico (opcional)','email']] as $key=>$field)<div>
<label class="form-label" for="{{ $key }}">{{ $field[0] }}</label>
<input type="{{ $field[1] }}" class="field" name="{{ $key }}" id="{{ $key }}" value="{{ old($key) }}" maxlength="{{ $key==='phone'?30:150 }}" @required($key!=='email')>
</div>@endforeach<div>
<label class="form-label" for="message">Tu mensaje *</label>
<textarea class="field" name="message" id="message" minlength="10" maxlength="5000" required>{{ old('message') }}</textarea>
</div>
<x-consent/>
<button class="btn justify-self-start" type="submit">Enviar mensaje <span>↗</span>
</button>
</form>
</div>
</div>
</section>@endsection
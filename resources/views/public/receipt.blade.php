@extends('layouts.public')
@section('title','Solicitud recibida')
@section('content')<section class="section">
<div class="wrap max-w-2xl">
<span class="badge">Solicitud guardada</span>
<h1 class="!text-4xl mt-6">Gracias por compartir tu idea.</h1>
<p class="mt-6">Tu solicitud fue registrada. Conserva esta referencia para futuras consultas:</p>
<p class="font-mono bg-slate-100 p-4 rounded mt-4 text-sm break-all">{{ $reference }}</p>
<p class="text-sm mt-5">El presupuesto requiere evaluación y confirmación de la empresa.</p>@if(!empty($business['whatsapp']))<a class="btn mt-7" href="https://wa.me/{{ $business['whatsapp'] }}?text={{ rawurlencode('Hola, registré una solicitud de cotización con referencia '.$reference.'. Quisiera consultar sobre mi proyecto.') }}" target="_blank" rel="noopener noreferrer">Continuar por WhatsApp <span>↗</span>
</a>
<p class="text-xs mt-4">Se abrirá WhatsApp. Debes confirmar el envío del mensaje.</p>@endif<a href="{{ route('home') }}" class="block text-brand text-sm mt-7">Volver al inicio →</a>
</div>
</section>@endsection
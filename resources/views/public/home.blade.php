@extends('layouts.public')
@section('title','Soluciones en Vidrios, Aluminios y Acabados')
@section('content')
<section class="relative overflow-hidden bg-ink text-white">
<div class="absolute inset-0 lg:left-[39%]">
<img src="{{ !empty($business['hero']) ? Storage::url($business['hero']) : asset('images/architecture-demo.webp') }}" alt="{{ !empty($business['hero']) ? 'Arquitectura y acabados' : 'Imagen arquitectónica generada de demostración: fachada de vidrio' }}" width="1600" height="1000" fetchpriority="high" class="w-full h-full object-cover">
</div>
<div class="absolute inset-0 bg-gradient-to-r from-ink via-ink/90 to-ink/5">
</div>
<div class="wrap relative py-20 lg:py-28 min-h-[630px] flex flex-col justify-center">
<div class="max-w-[700px]">
<span class="eyebrow !text-cyan">Diseño, precisión y buenos acabados</span>
<h1>Soluciones en<br class="hidden sm:block"> Vidrios, Aluminios<br class="hidden sm:block"> y <span class="text-cyan">Acabados.</span>
</h1>
<p class="text-slate-300 max-w-[460px] mt-7 text-[15px] leading-7">Transformamos tus ideas en espacios funcionales y con estilo. Soluciones a medida para tu hogar, negocio o proyecto.</p>
<div class="hero-actions flex flex-wrap gap-3 mt-8">
<a href="{{ route('quote') }}" class="btn">Solicitar cotización <span aria-hidden="true">↗</span>
</a>
<a href="{{ route('projects') }}" class="btn btn-ghost">Ver nuestros proyectos <span aria-hidden="true">→</span>
</a>
</div>
<div class="flex flex-wrap gap-5 sm:gap-8 text-[11px] text-slate-300 mt-10">
<span>
<span class="text-cyan mr-2">✓</span> Soluciones a medida</span>
<span>
<span class="text-cyan mr-2">✓</span> Atención personalizada</span>
<span>
<span class="text-cyan mr-2">✓</span> Cuidado en cada detalle</span>
</div>
</div>
@if(empty($business['hero']))<span class="demo-label absolute bottom-4 right-0">Imagen de demostración · no es un proyecto de AYALA</span>@endif</div>
</section>
<div class="border-b border-slate-200 bg-[#F3F6FA]">
<div class="wrap grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-x-4 gap-y-5 py-7 text-[10px] uppercase tracking-[.13em] font-semibold text-slate-600">@foreach(['Vidrios y cristales','Melamina','Drywall','Baldosas','Acero inoxidable'] as $name)<span class="flex items-center gap-3">
<span class="w-1 h-1 rounded-full bg-brand">
</span>{{ $name }}</span>@endforeach</div>
</div>
<section class="section">
<div class="wrap">
<div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10">
<div>
<span class="eyebrow">Nuestras especialidades</span>
<h2>Todo lo que tu<br>espacio necesita.</h2>
</div>
<p class="max-w-sm text-sm leading-7">Cinco especialidades, un mismo compromiso: dar forma a tus ideas con funcionalidad y atención a los detalles.</p>
</div>
<div class="grid sm:grid-cols-2 lg:grid-cols-5 gap-4">@foreach($specialties as $i=>$specialty)<a href="{{ route('specialty',$specialty->slug) }}" class="card service-card">
<div class="card-body !p-5">
<div class="flex justify-between items-center">
<span class="service-number">0{{ $i+1 }}</span>
<span class="text-slate-400 text-xs">↗</span>
</div>
<div class="service-icon">
<x-icon :index="$i"/>
</div>
<h3 class="!text-lg min-h-11">{{ $specialty->name }}</h3>
<p class="mt-4 text-xs leading-6">{{ $specialty->services->where('active',true)->take(3)->pluck('name')->join(', ') }}.</p>
<span class="card-link !text-[11px]">Explorar soluciones <span>→</span>
</span>
</div>
</a>@endforeach</div>
</div>
</section>
<section class="section bg-[#F3F6FA]">
<div class="wrap">
<div class="flex flex-col sm:flex-row sm:items-end justify-between gap-6 mb-10">
<div>
<span class="eyebrow">Nuestro portafolio</span>
<h2>Espacios que hablan<br>por sí mismos.</h2>
</div>
<a href="{{ route('projects') }}" class="btn btn-outline">Ver todos los proyectos <span>↗</span>
</a>
</div>@if($projects->isNotEmpty())<div class="grid md:grid-cols-3 gap-6">@foreach($projects as $project)<x-project-card :project="$project"/>@endforeach</div>@else<div class="empty">
<span class="badge">Portafolio en preparación</span>
<h3 class="mt-5">Cada proyecto tiene una historia.</h3>
<p class="max-w-lg mx-auto">Pronto podrás conocer nuestros trabajos confirmados. Mientras tanto, cuéntanos qué tienes en mente y conversemos sobre tu proyecto.</p>
<a href="{{ route('quote') }}" class="btn">Cuéntanos tu idea <span>↗</span>
</a>
</div>@endif</div>
</section>
<section class="section">
<div class="wrap grid lg:grid-cols-2 gap-14 items-center">
<div class="relative">
<img src="{{ !empty($business['hero']) ? Storage::url($business['hero']) : asset('images/architecture-demo.webp') }}" alt="Referencia visual de arquitectura y acabados" loading="lazy" width="800" height="850" class="w-full aspect-[5/4] object-cover rounded-lg">@if(empty($business['hero']))<span class="demo-label absolute bottom-4 left-4">Imagen generada de demostración</span>@endif</div>
<div>
<span class="eyebrow">La forma de hacer las cosas</span>
<h2>Tu espacio merece<br>atención en cada detalle.</h2>
<p class="mt-6 text-sm leading-7">En AYALA VIDRIOS Y ALUMINIOS reunimos especialidades para atender proyectos de construcción, instalación, remodelación y acabados.</p>
<div class="grid sm:grid-cols-2 gap-7 mt-8">@foreach(['A tu medida'=>'Alternativas según las necesidades de tu espacio.','Un trato cercano'=>'Conversemos para entender lo que necesitas.','Visión integral'=>'Especialidades que se complementan en tu proyecto.','Propuestas claras'=>'Evaluación del trabajo antes de definir un presupuesto.'] as $name=>$text)<div>
<span class="text-brand">✓</span>
<h3 class="!text-base mt-2">{{ $name }}</h3>
<p class="text-xs mt-2 leading-6">{{ $text }}</p>
</div>@endforeach</div>
<a href="{{ route('about') }}" class="btn btn-outline mt-9">Conoce AYALA <span>↗</span>
</a>
</div>
</div>
</section>
<section class="section bg-[#F3F6FA]">
<div class="wrap grid lg:grid-cols-2 gap-10">
<div>
<span class="eyebrow">Empecemos a construir tu idea</span>
<h2>¿Qué espacio quieres<br>transformar?</h2>
<p class="text-sm max-w-md mt-5 leading-7">Describe lo que necesitas, agrega medidas o fotografías de referencia y solicita una evaluación personalizada.</p>
</div>
<div class="grid gap-5">@foreach(['Cuéntanos tu proyecto'=>'Elige una especialidad y comparte los detalles.','Revisamos tu solicitud'=>'Evaluamos el alcance y la información disponible.','Coordinamos contigo'=>'Definimos los siguientes pasos para tu propuesta.'] as $title=>$text)<div class="flex gap-5 items-start">
<span class="text-brand font-semibold text-xs mt-1">0{{ $loop->iteration }}</span>
<div>
<h3 class="!text-base">{{ $title }}</h3>
<p class="text-sm mt-2">{{ $text }}</p>
</div>
</div>@endforeach</div>
</div>
</section>
@endsection
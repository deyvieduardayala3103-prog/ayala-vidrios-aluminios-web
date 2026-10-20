@extends('layouts.public')
@section('title',$specialty->name)
@section('description',Str::limit($specialty->description,155))
@section('content')<x-page-head :title="$specialty->name" tag="Especialidades" :intro="$specialty->description"/>
<section class="section">
<div class="wrap grid lg:grid-cols-2 gap-12 items-start">
<div class="relative">
<img src="{{ $specialty->image ? Storage::url($specialty->image) : asset('images/architecture-demo.webp') }}" alt="{{ $specialty->image ? $specialty->name : 'Imagen arquitectónica generada de demostración' }}" width="800" height="600" class="rounded-lg w-full aspect-[4/3] object-cover">@unless($specialty->image)<span class="demo-label absolute bottom-4 left-4">Imagen de demostración</span>@endunless</div>
<div>
<span class="eyebrow">Lo que podemos hacer</span>
<h2 class="!text-3xl">Soluciones a tu medida.</h2>
<div class="grid gap-5 mt-8">@foreach($specialty->services as $service)<div class="border-b border-slate-200 pb-4">
<h3 class="!text-lg">
<span class="text-brand mr-3">↗</span>{{ $service->name }}</h3>@if($service->description)<p class="text-sm mt-2">{{ $service->description }}</p>@endif</div>@endforeach</div>
<a href="{{ route('quote',['especialidad'=>$specialty->id]) }}" class="btn mt-7">Solicitar cotización <span>↗</span>
</a>
</div>
</div>
</section>
<section class="section bg-slate-50">
<div class="wrap">
<h2 class="mb-9">Proyectos relacionados</h2>
<div class="grid md:grid-cols-3 gap-6">@forelse($specialty->projects as $project)<x-project-card :project="$project"/>@empty<div class="empty md:col-span-3">
<h3>Galería en preparación</h3>
<p>Se publicarán fotografías de trabajos confirmados de esta especialidad.</p>
</div>@endforelse</div>
</div>
</section>@endsection
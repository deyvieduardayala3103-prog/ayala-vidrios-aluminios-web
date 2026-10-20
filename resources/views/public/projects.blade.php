@extends('layouts.public')
@section('title','Nuestros proyectos')
@section('content')<x-page-head title="Ideas que toman forma." tag="Nuestros proyectos" intro="Conoce los trabajos publicados por AYALA VIDRIOS Y ALUMINIOS."/>
<section class="section">
<div class="wrap">
<form method="GET" class="flex flex-wrap gap-3 mb-10">
<label class="sr-only" for="filter-specialty">Filtrar por especialidad</label>
<select name="especialidad" id="filter-specialty" class="field !w-auto max-w-full">
<option value="">Todas las especialidades</option>@foreach($specialties as $specialty)<option value="{{ $specialty->slug }}" @selected(request('especialidad')===$specialty->slug)>{{ $specialty->name }}</option>@endforeach</select>
<button class="btn">Filtrar</button>
</form>
<div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">@forelse($projects as $project)<x-project-card :project="$project"/>@empty<div class="empty sm:col-span-2 lg:col-span-3">
<span class="badge">Portafolio en preparación</span>
<h3 class="mt-5">Aún no hay proyectos publicados.</h3>
<p>Las fotografías y los detalles se incorporarán cuando estén confirmados.</p>
<a href="{{ route('quote') }}" class="btn">Hablemos de tu proyecto <span>↗</span>
</a>
</div>@endforelse</div>
<div class="mt-8">{{ $projects->links() }}</div>
</div>
</section>@endsection
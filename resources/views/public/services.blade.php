@extends('layouts.public')
@section('title','Nuestras especialidades')
@section('content')<x-page-head title="Soluciones para cada espacio." tag="Nuestras especialidades" intro="Explora las posibilidades de tu próximo proyecto y solicita una propuesta personalizada."/>
<section class="section">
<div class="wrap grid md:grid-cols-2 gap-6">@foreach($specialties as $i=>$specialty)<article class="card card-body">
<div class="service-icon">
<x-icon :index="$i"/>
</div>
<h2 class="!text-2xl">{{ $specialty->name }}</h2>
<p class="mt-5 text-sm leading-7">{{ $specialty->description }}</p>
<div class="flex flex-wrap gap-2 mt-5">@foreach($specialty->services->where('active',true) as $service)<span class="badge">{{ $service->name }}</span>@endforeach</div>
<a class="card-link" href="{{ route('specialty',$specialty->slug) }}">Conocer especialidad <span>↗</span>
</a>
</article>@endforeach</div>
</section>@endsection
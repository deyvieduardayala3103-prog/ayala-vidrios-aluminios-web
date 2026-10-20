@extends('layouts.public')
@section('title','Nosotros')
@section('content')<x-page-head title="Especialidades que transforman espacios." tag="Conoce AYALA" intro="AYALA VIDRIOS Y ALUMINIOS: soluciones en construcción, fabricación, instalaciones, remodelaciones y acabados."/>
<section class="section">
<div class="wrap grid lg:grid-cols-2 gap-12">
<div>
<span class="eyebrow">Nuestra empresa</span>
<h2>Una idea.<br>Muchas posibilidades.</h2>
<p class="mt-6 leading-8">Reunimos cinco especialidades: vidrios y cristales, melamina, drywall, baldosas y acero inoxidable. Cuéntanos las necesidades de tu hogar, negocio o proyecto.</p>
<a href="{{ route('contact') }}" class="btn mt-7">Conversemos <span>↗</span>
</a>
</div>
<div class="card card-body">
<h3>Nuestra historia</h3>
<p class="prose-text mt-5">{{ $business['history'] ?: 'La reseña de la empresa estará disponible cuando se complete la información institucional.' }}</p>
</div>
</div>
<div class="wrap grid md:grid-cols-3 gap-6 mt-12">@foreach(['mission'=>'Misión','vision'=>'Visión','values'=>'Valores'] as $key=>$title)<div class="card card-body">
<h3>{{ $title }}</h3>
<p class="prose-text text-sm mt-5">{{ $business[$key] ?: 'Información institucional pendiente de publicación.' }}</p>
</div>@endforeach</div>
</section>@endsection
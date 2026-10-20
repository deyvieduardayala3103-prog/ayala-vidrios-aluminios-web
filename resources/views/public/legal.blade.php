@extends('layouts.public')
@section('title',$page==='privacidad'?'Política de privacidad':'Libro de Reclamaciones')
@section('content')<x-page-head :title="$page==='privacidad'?'Política de privacidad':'Libro de Reclamaciones Virtual'" tag="Información legal"/>
<section class="section">
<div class="wrap max-w-3xl">@php($text=$business[$page==='privacidad'?'privacy':'claims']??'')@if($text)<div class="prose-text text-slate-600">{{ $text }}</div>@else<div class="card card-body">
<span class="badge">Pendiente de completar</span>
<h2 class="!text-2xl mt-5">Información legal en preparación.</h2>
<p class="mt-5">La empresa debe completar y revisar este contenido con sus datos reales antes de habilitar el sitio en producción.</p>@if($page==='privacidad')<p class="mt-4">Los formularios solicitan datos de contacto y detalles del proyecto para atender consultas. El titular, los plazos de conservación y el canal para ejercer derechos deberán identificarse en la versión definitiva.</p>@else<p class="mt-4">Este espacio está reservado para el Libro de Reclamaciones Virtual. No constituye un sistema de recepción de reclamaciones habilitado.</p>@endif</div>@endif</div>
</section>@endsection
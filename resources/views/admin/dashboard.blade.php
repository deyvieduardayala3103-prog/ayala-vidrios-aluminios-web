@extends('layouts.admin')
@section('title','Dashboard')
@section('content')<p class="text-sm mb-7">Resumen de la actividad registrada en el sistema.</p>
<div class="grid sm:grid-cols-2 xl:grid-cols-3 gap-5">@foreach($stats as $label=>$value)<div class="card card-body">
<span class="text-xs muted">{{ $label }}</span>
<div class="text-4xl font-semibold mt-4">{{ $value }}</div>
<span class="text-[10px] text-slate-400 mt-3 block">Datos de la empresa</span>
</div>@endforeach</div>
@can('manage')<div class="card mt-9">
<div class="p-6 flex flex-wrap justify-between items-center gap-4">
<h2 class="!text-xl">Solicitudes recientes</h2>
<a class="text-brand text-xs" href="{{ route('admin.index','cotizaciones') }}">Ver todas →</a>
</div>@if($recent->isEmpty())<div class="empty m-6">
<h3>Aún no hay solicitudes.</h3>
<p>Las cotizaciones recibidas desde el sitio aparecerán aquí.</p>
</div>@else<div class="table-scroll">
<table>
<thead>
<tr>
<th>Referencia</th>
<th>Cliente</th>
<th>Especialidad</th>
<th>Estado</th>
<th>Fecha</th>
</tr>
</thead>
<tbody>@foreach($recent as $quote)<tr>
<td>
<a href="{{ route('admin.quote',$quote) }}" class="text-brand">#{{ $quote->id }} →</a>
</td>
<td>{{ $quote->customer->name }}</td>
<td>{{ $quote->specialty->name }}</td>
<td>
<span class="badge">{{ $quote->status->name }}</span>
</td>
<td>{{ $quote->created_at->format('d/m/Y') }}</td>
</tr>@endforeach</tbody>
</table>
</div>@endif</div>@endcan
<div class="card card-body mt-8">
<h3 class="!text-lg">Antes de publicar</h3>
<p class="text-sm mt-4">Completa los datos de contacto, carga el logotipo original y revisa los textos institucionales y legales. Publica únicamente proyectos confirmados.</p>@can('manage')<a class="card-link" href="{{ route('admin.settings') }}">Ir a configuración <span>↗</span>
</a>@endcan</div>@endsection
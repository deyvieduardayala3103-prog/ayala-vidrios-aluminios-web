@extends('layouts.admin')
@section('title',$config['title'])
@section('actions')@if(empty($config['readonly'])&&$module!=='mensajes')<a href="{{ route('admin.create',$module) }}" class="btn">Crear registro +</a>@endif @endsection
@section('content')<form method="GET" class="card card-body flex flex-wrap gap-4 mb-6 items-end">
<div class="flex-1 min-w-40">
<label for="q" class="form-label">{{ $module==='cotizaciones'?'Buscar cliente':'Buscar por nombre' }}</label>
<input class="field" id="q" name="q" value="{{ request('q') }}" maxlength="150">
</div>@if($module==='cotizaciones')<div>
<label class="form-label" for="specialty_id">Especialidad</label>
<select class="field" name="specialty_id" id="specialty_id">
<option value="">Todas</option>@foreach(\App\Models\Specialty::all() as $specialty)<option value="{{ $specialty->id }}" @selected(request('specialty_id')==$specialty->id)>{{ $specialty->name }}</option>@endforeach</select>
</div>
<div>
<label class="form-label" for="status">Estado</label>
<select class="field" name="status" id="status">
<option value="">Todos</option>@foreach(\App\Models\QuoteStatus::all() as $status)<option value="{{ $status->id }}" @selected(request('status')==$status->id)>{{ $status->name }}</option>@endforeach</select>
</div>
<div>
<label for="from" class="form-label">Desde</label>
<input class="field" type="date" name="from" id="from" value="{{ request('from') }}">
</div>
<div>
<label for="to" class="form-label">Hasta</label>
<input class="field" type="date" name="to" id="to" value="{{ request('to') }}">
</div>@endif<button class="btn" type="submit">Buscar</button>
<a href="{{ route('admin.index',$module) }}" class="text-sm text-brand py-3">Limpiar</a>
</form>
<div class="card">@if($rows->isEmpty())<div class="empty m-6">
<h3>No hay registros para mostrar.</h3>
<p>{{ request()->hasAny(['q','from','to','status','specialty_id'])?'Prueba otros filtros.':'Los registros aparecerán aquí cuando se incorporen.' }}</p>
</div>@else<div class="table-scroll">
<table>
<thead>
<tr>
<th>ID</th>@if($module==='cotizaciones')<th>Cliente</th>
<th>Especialidad</th>
<th>Estado</th>
<th>Fecha</th>@elseif($module==='ventas')<th>Cliente</th>
<th>Importe</th>
<th>Fecha</th>@else<th>Nombre</th>
<th>Información</th>@endif<th>Acciones</th>
</tr>
</thead>
<tbody>@foreach($rows as $row)<tr>
<td>#{{ $row->id }}</td>@if($module==='cotizaciones')<td>{{ $row->customer->name }}<span class="block muted text-xs">{{ $row->customer->phone }}</span>
</td>
<td>{{ $row->specialty->name }}</td>
<td>
<span class="badge">{{ $row->status->name }}</span>
</td>
<td>{{ $row->created_at->format('d/m/Y') }}</td>
<td>
<a href="{{ route('admin.quote',$row) }}" class="text-brand">Ver solicitud →</a>
</td>@elseif($module==='ventas')<td>{{ $row->customer->name }}</td>
<td>S/ {{ number_format($row->amount,2) }}</td>
<td>{{ $row->sold_at->format('d/m/Y') }}</td>
<td>
<a class="text-brand" href="{{ route('admin.edit',[$module,$row->id]) }}">Editar →</a>
</td>@else<td>{{ $row->name }}</td>
<td>@if($module==='mensajes')<span class="badge">{{ $row->read_at?'Leído':'Sin leer' }}</span>
<p class="text-xs mt-2">{{ Str::limit($row->message,80) }}</p>@elseif($module==='usuarios'){{ $row->email }}<span class="block muted text-xs">{{ $row->role?->name }} · {{ $row->active?'Activo':'Inactivo' }}</span>@elseif($module==='proyectos')<span class="badge">{{ $row->published?'Publicado':'Borrador' }}</span>@elseif($module==='clientes'){{ $row->phone }}@else{{ Str::limit($row->description??'',75) }}@endif</td>
<td>
<div class="flex gap-4 items-center">
<a class="text-brand" href="{{ route('admin.edit',[$module,$row->id]) }}">{{ $module==='mensajes'?'Leer':'Editar' }} →</a>@if(!in_array($module,['mensajes','ventas']))<form method="POST" action="{{ route('admin.destroy',[$module,$row->id]) }}" x-data @submit="if(!confirm('¿Eliminar este registro? Esta acción no se puede deshacer.')) $event.preventDefault()">@csrf @method('DELETE')<button type="submit" class="text-red-700 text-xs">Eliminar</button>
</form>@endif</div>
</td>@endif</tr>@endforeach</tbody>
</table>
</div>
<div class="p-5">{{ $rows->links() }}</div>@endif</div>@endsection
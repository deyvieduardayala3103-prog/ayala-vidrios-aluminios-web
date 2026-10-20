@extends('layouts.admin')
@section('title','Cotización #'.$quote->id)
@section('actions')<a href="{{ route('admin.index','cotizaciones') }}" class="btn btn-outline">← Volver</a>@endsection
@section('content')<div class="grid xl:grid-cols-[1.2fr_1fr] gap-7">
<div class="grid gap-6 content-start">
<section class="card card-body">
<span class="badge">{{ $quote->status->name }}</span>
<h2 class="!text-xl mt-6">{{ $quote->customer->name }}</h2>
<dl class="grid gap-3 text-sm mt-6">
<div>
<dt class="muted">Referencia</dt>
<dd class="break-all font-mono text-xs">{{ $quote->reference }}</dd>
</div>
<div>
<dt class="muted">Contacto</dt>
<dd>{{ $quote->customer->phone }}<br>{{ $quote->customer->email }}</dd>
</div>
<div>
<dt class="muted">Especialidad</dt>
<dd>{{ $quote->specialty->name }}</dd>
</div>
<div>
<dt class="muted">Zona de instalación</dt>
<dd>{{ $quote->zone ?: 'No indicada' }}</dd>
</div>
<div>
<dt class="muted">Recibida</dt>
<dd>{{ $quote->created_at->timezone('America/Lima')->format('d/m/Y H:i') }} (Perú)</dd>
</div>
</dl>
<h3 class="!text-base mt-6">Descripción</h3>
<p class="prose-text text-sm mt-3">{{ $quote->description }}</p>@foreach($quote->items as $item)<div class="bg-slate-50 rounded p-4 mt-5 text-sm">
<strong>{{ $item->work_type }}</strong>
<p class="mt-2">Cantidad: {{ $item->quantity }}<br>Ancho: {{ $item->width??'Sin indicar' }} {{ $item->unit }}<br>Alto: {{ $item->height??'Sin indicar' }} {{ $item->unit }}</p>
</div>@endforeach</section>
<section class="card card-body">
<h3 class="!text-lg">Fotografías adjuntas</h3>
<div class="grid gap-3 mt-5">@forelse($quote->attachments as $attachment)<a href="{{ route('admin.attachment',$attachment) }}" class="text-brand text-sm break-all">Descargar {{ $attachment->original_name }} ↓</a>@empty<p class="text-sm">No se adjuntaron fotografías.</p>@endforelse</div>
</section>
</div>
<div class="grid gap-6 content-start">
<form method="POST" action="{{ route('admin.quote.update',$quote) }}" class="card card-body grid gap-5">@csrf @method('PUT')<h2 class="!text-xl">Gestión de la solicitud</h2>
<div>
<label class="form-label" for="quote_status_id">Estado</label>
<select class="field" name="quote_status_id" id="quote_status_id" required>@foreach($statuses as $status)<option value="{{ $status->id }}" @selected(old('quote_status_id',$quote->quote_status_id)==$status->id)>{{ $status->name }}</option>@endforeach</select>
</div>
<div>
<label class="form-label" for="amount">Importe manual S/ (opcional)</label>
<input class="field" type="number" step="0.01" min="0" max="9999999999.99" name="amount" id="amount" value="{{ old('amount',$quote->amount) }}">
</div>
<div>
<label class="form-label" for="observations">Observaciones internas</label>
<textarea class="field" name="observations" id="observations" maxlength="10000">{{ old('observations',$quote->observations) }}</textarea>
</div>
<div>
<label class="form-label" for="note">Motivo / nota del cambio *</label>
<textarea class="field" name="note" id="note" required maxlength="2000">{{ old('note') }}</textarea>
</div>
<button class="btn" type="submit">Guardar e incluir en historial ✓</button>
</form>
<section class="card card-body">
<h3 class="!text-lg">Historial de gestión</h3>
<div class="grid gap-5 mt-6">@foreach($quote->histories as $history)<article class="border-l-2 border-blue-200 pl-4">
<span class="badge">{{ $history->status->name }}</span>
<p class="text-xs mt-2">{{ $history->created_at->timezone('America/Lima')->format('d/m/Y H:i') }} · {{ $history->user?->name??'Sitio web' }}</p>
<p class="prose-text text-sm mt-2">{{ $history->note }}</p>@if($history->amount!==null)<p class="text-sm mt-2">S/ {{ number_format($history->amount,2) }}</p>@endif</article>@endforeach</div>
</section>
</div>
</div>@endsection
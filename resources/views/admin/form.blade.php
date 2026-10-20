@extends('layouts.admin')
@section('title',($record->exists?'Editar · ':'Crear · ').$config['title'])
@section('actions')<a href="{{ route('admin.index',$module) }}" class="btn btn-outline">← Volver</a>@endsection
@section('content')<form action="{{ $record->exists?route('admin.update',[$module,$record->id]):route('admin.store',$module) }}" method="POST" enctype="multipart/form-data" class="card card-body grid gap-6 max-w-3xl">@csrf @if($record->exists)@method('PUT')@endif
@foreach($config['fields'] as $key=>$field)<div>@if($field[1]==='checkbox')<label class="flex gap-3 items-center text-sm">
<input type="checkbox" name="{{ $key }}" value="1" @checked(old($key,$record->exists?$record->$key:in_array($key,['active','available'])))> {{ $field[0] }}</label>
@elseif($field[1]==='display')<span class="form-label">{{ $field[0] }}</span>
<p class="prose-text text-sm">{{ $record->$key }}</p>
@else<label class="form-label" for="{{ $key }}">{{ $field[0] }} @if(str_starts_with($field[2],'required')) * @endif</label>
@if($field[1]==='textarea')<textarea class="field" name="{{ $key }}" id="{{ $key }}" @required(str_starts_with($field[2],'required'))>{{ old($key,$record->$key) }}</textarea>
@elseif($field[1]==='select')<select class="field" name="{{ $key }}" id="{{ $key }}" required>
<option value="">Selecciona una opción</option>@foreach($options[$key] as $id=>$name)<option value="{{ $id }}" @selected(old($key,$record->$key)==$id)>{{ $name }}</option>@endforeach</select>
@elseif($field[1]==='file')<input class="field" type="file" name="{{ $key }}" id="{{ $key }}" accept="image/jpeg,image/png,image/webp">
<p class="text-xs mt-2">JPG, PNG o WebP, hasta 5 MB. Se optimiza para la web.</p>@if($record->$key)<img src="{{ Storage::url($record->$key) }}" alt="Imagen actual" class="w-40 mt-4 rounded" loading="lazy">@endif
@else @php($value=$record->$key) @if($value instanceof \DateTimeInterface)@php($value=$field[1]==='date'?$value->format('Y-m-d'):$value->format('Y-m-d\TH:i'))@endif
<input class="field" type="{{ $field[1] }}" id="{{ $key }}" name="{{ $key }}" @if($field[1]!=='password')value="{{ old($key,$value) }}"@else autocomplete="new-password" @endif @if($field[1]==='number')step="any" min="0"@endif @required(str_starts_with($field[2],'required'))>
@if($field[1]==='password')<p class="text-xs mt-2">Mínimo 12 caracteres con mayúsculas, minúsculas, número y símbolo. {{ $record->exists?'Déjala vacía para conservar la actual.':'Es obligatoria al crear el usuario.' }}</p>@endif
@endif @endif</div>@endforeach
@if($module==='proyectos')<div class="border-t border-slate-200 pt-6">
<label class="form-label" for="photos">Añadir fotografías reales</label>
<input class="field" type="file" name="photos[]" id="photos" multiple accept="image/jpeg,image/png,image/webp">
<p class="text-xs mt-2">Hasta 10 imágenes por carga, máximo 5 MB por archivo.</p>
<label class="form-label mt-5" for="image_alt">Descripción accesible de las nuevas fotografías</label>
<input class="field" name="image_alt" id="image_alt" value="{{ old('image_alt') }}" maxlength="255">
</div>@endif
<button class="btn justify-self-start" type="submit">Guardar cambios ✓</button>
</form>
@if($module==='proyectos'&&$record->exists)<section class="mt-9 max-w-3xl">
<h2 class="!text-2xl mb-5">Galería del proyecto</h2>
<div class="grid sm:grid-cols-2 gap-4">@forelse($record->images as $image)<div class="card card-body">
<img src="{{ Storage::url($image->path) }}" alt="{{ $image->alt }}" class="w-full aspect-[4/3] object-cover rounded" loading="lazy">
<p class="text-xs mt-3">{{ $image->alt }}</p>
<form method="POST" action="{{ route('admin.image.delete',$image) }}" class="mt-4" x-data @submit="if(!confirm('¿Eliminar esta fotografía?')) $event.preventDefault()">@csrf @method('DELETE')<button class="text-red-700 text-sm" type="submit">Eliminar fotografía</button>
</form>
</div>@empty<p class="text-sm">Aún no se han cargado fotografías.</p>@endforelse</div>
</section>@endif @endsection
@extends('layouts.public')
@section('title',$project->name)
@section('description',Str::limit($project->description,155))
@section('content')<x-page-head :title="$project->name" :tag="$project->specialty->name"/>
<section class="section">
<div class="wrap">
<p class="prose-text max-w-3xl">{{ $project->description }}</p>@if($project->completed_at)<p class="text-xs mt-4">Fecha: {{ $project->completed_at->format('d/m/Y') }}</p>@endif
<div class="grid md:grid-cols-2 gap-6 mt-9" x-data="{src:'',alt:'',trigger:null,show(source,text,event){this.src=source;this.alt=text;this.trigger=event.currentTarget;this.$refs.lightbox.showModal()},close(){this.$refs.lightbox.close();this.trigger?.focus()}}">@foreach($project->images as $image)<button type="button" @click="show(@js(Storage::url($image->path)),@js($image->alt),$event)" class="text-left">
<img src="{{ Storage::url($image->path) }}" alt="{{ $image->alt }} — ampliar fotografía" width="900" height="650" loading="lazy" class="w-full aspect-[4/3] object-cover rounded-lg">
</button>@endforeach
<dialog x-ref="lightbox" @click="if($event.target===$refs.lightbox)close()" @cancel.prevent="close()" class="m-auto max-w-[95vw] max-h-[95vh] bg-ink p-4 rounded-lg backdrop:bg-black/80">
<button @click="close()" class="btn btn-light mb-3" type="button">Cerrar ×</button>
<img :src="src" :alt="alt" class="max-h-[75vh] object-contain">
</dialog>
</div>
<a href="{{ route('quote',['especialidad'=>$project->specialty_id]) }}" class="btn mt-10">Solicitar un proyecto similar <span>↗</span>
</a>
</div>
</section>@endsection
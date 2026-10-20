@props(['project'])
<a href="{{ route('project',$project->slug) }}" class="card service-card block">
@if($project->images->first())<img src="{{ Storage::url($project->images->first()->path) }}" alt="{{ $project->images->first()->alt }}" loading="lazy" width="640" height="450" class="w-full aspect-[4/3] object-cover">@else<div class="aspect-[4/3] bg-slate-100 flex items-center justify-center text-sm muted">Fotografía pendiente</div>@endif
<div class="card-body">
<span class="text-xs text-brand">{{ $project->specialty->name }}</span>
<h3 class="mt-3">{{ $project->name }}</h3>
<span class="card-link">Conocer proyecto <span aria-hidden="true">↗</span>
</span>
</div>
</a>
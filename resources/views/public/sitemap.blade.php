{!! '<'.'?xml version="1.0" encoding="UTF-8"?'.'>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
@foreach(['home','about','services','projects','quote','contact'] as $name)<url>
<loc>{{ route($name) }}</loc>
</url>@endforeach
@if($catalog)<url>
<loc>{{ route('products') }}</loc>
</url>@endif
@foreach($specialties as $specialty)<url>
<loc>{{ route('specialty',$specialty->slug) }}</loc>
</url>@endforeach
@foreach($projects as $project)<url>
<loc>{{ route('project',$project->slug) }}</loc>
<lastmod>{{ $project->updated_at->toAtomString() }}</lastmod>
</url>@endforeach
</urlset>
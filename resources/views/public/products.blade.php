@extends('layouts.public')
@section('title','Catálogo de productos')
@section('content')<x-page-head title="Materiales para tus ideas." tag="Catálogo de productos" intro="Consulta características y disponibilidad. La compra y entrega se coordinan directamente con la empresa."/>
<section class="section">
<div class="wrap">
<form class="flex flex-wrap gap-3 mb-8">
<label class="sr-only" for="category">Categoría</label>
<select name="categoria" id="category" class="field !w-auto">
<option value="">Todas las categorías</option>@foreach($categories as $category)<option value="{{ $category->id }}" @selected(request('categoria')==$category->id)>{{ $category->name }}</option>@endforeach</select>
<button class="btn">Filtrar</button>
</form>
<div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">@forelse($products as $product)<article class="card">@if($product->image)<img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" width="600" height="450" loading="lazy" class="aspect-[4/3] w-full object-cover">@endif<div class="card-body">
<span class="badge">{{ $product->available?'Disponible':'Consultar disponibilidad' }}</span>
<h3 class="mt-4">{{ $product->name }}</h3>
<p class="text-xs mt-2">{{ $product->category->name }}</p>
<p class="text-sm mt-4 prose-text">{{ $product->description }}</p>@if($product->price!==null)<p class="font-semibold mt-4">S/ {{ number_format($product->price,2) }} / {{ $product->unit }}</p>@else<p class="text-sm mt-4">Precio a consultar · {{ $product->unit }}</p>@endif<div class="grid gap-3 mt-5">
<a class="btn" href="{{ route('quote') }}">Solicitar cotización ↗</a>@if(!empty($business['whatsapp']))<a class="text-brand text-sm" href="https://wa.me/{{ $business['whatsapp'] }}?text={{ rawurlencode('Hola, quisiera consultar sobre el producto: '.$product->name) }}" target="_blank" rel="noopener noreferrer">Consultar por WhatsApp ↗</a>@endif</div>
</div>
</article>@empty<div class="empty sm:col-span-2 lg:col-span-3">
<h3>Aún no hay productos publicados.</h3>
<p>Puedes consultar materiales a través del formulario de contacto.</p>
<a class="btn" href="{{ route('contact') }}">Consultar ↗</a>
</div>@endforelse</div>
<div class="mt-8">{{ $products->links() }}</div>
</div>
</section>@endsection
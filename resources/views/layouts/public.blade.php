<!DOCTYPE html>
<html lang="es-PE">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title','Soluciones en Vidrios, Aluminios y Acabados') | AYALA</title>
<meta name="description" content="@yield('description','Vidrios y cristales, melamina, drywall, baldosas y acero inoxidable. Conoce nuestras especialidades y solicita una cotización personalizada.')">
<link rel="canonical" href="{{ url()->current() }}">
<meta property="og:type" content="website">
<meta property="og:locale" content="es_PE">
<meta property="og:title" content="@yield('title','AYALA VIDRIOS Y ALUMINIOS')">
<meta property="og:description" content="@yield('description','Soluciones para transformar tus espacios. Solicita una cotización personalizada.')">
<meta property="og:url" content="{{ url()->current() }}">@if(!empty($business['logo']))<meta property="og:image" content="{{ url(Storage::url($business['logo'])) }}">@endif
@vite(['resources/css/app.css','resources/js/app.js'])</head>
<body>
<a href="#contenido" class="skip-link">Saltar al contenido</a>
<div class="bg-ink text-white text-[10px] tracking-[.12em]">
<div class="wrap flex justify-between items-center gap-4 py-2">
<span>VIDRIOS, ALUMINIOS Y ACABADOS</span>
<a href="{{ route('contact') }}" class="hidden sm:block text-slate-300 tracking-normal">Conversemos sobre tu proyecto <span class="ml-3">↗</span>
</a>
</div>
</div>
<header class="sticky top-0 z-40 bg-white/95 backdrop-blur border-b border-slate-200" x-data="{open:false}" @keydown.escape.window="open=false">
<div class="wrap flex items-center justify-between gap-5 py-5">
<a href="{{ route('home') }}" aria-label="AYALA, inicio">
<x-brand/>
</a>
<nav class="hidden lg:flex gap-7 items-center text-[13px] font-medium" aria-label="Principal">@foreach(['home'=>'Inicio','about'=>'Nosotros','services'=>'Servicios','projects'=>'Proyectos'] as $route=>$label)<a href="{{ route($route) }}" class="hover:text-brand {{ request()->routeIs($route)?'text-brand':'' }}" @if(request()->routeIs($route))aria-current="page"@endif>{{ $label }}</a>@endforeach @if(($business['products_enabled']??'0')==='1')<a href="{{ route('products') }}">Productos</a>@endif<a href="{{ route('contact') }}">Contacto</a>
</nav>
<a href="{{ route('quote') }}" class="btn hidden sm:inline-flex">Solicitar cotización <span aria-hidden="true">↗</span>
</a>
<button class="lg:hidden p-2 border border-slate-200 rounded" type="button" @click="open=!open" :aria-expanded="open" aria-controls="mobile-menu" aria-label="Abrir menú">
<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor">
<path d="M3 6h18M3 12h18M3 18h18"/>
</svg>
</button>
</div>
<nav id="mobile-menu" x-show="open" x-cloak class="lg:hidden wrap pb-6 grid gap-4 text-sm" aria-label="Menú móvil">@foreach(['home'=>'Inicio','about'=>'Nosotros','services'=>'Servicios','projects'=>'Proyectos','contact'=>'Contacto','quote'=>'Solicitar cotización'] as $route=>$label)<a href="{{ route($route) }}">{{ $label }}</a>@endforeach @if(($business['products_enabled']??'0')==='1')<a href="{{ route('products') }}">Productos</a>@endif</nav>
</header>
<main id="contenido">@yield('content')</main>
<section class="bg-brand text-white py-14">
<div class="wrap flex flex-col md:flex-row justify-between items-start md:items-center gap-7">
<div>
<span class="text-[10px] uppercase tracking-[.18em] text-blue-100">Demos forma a tu próximo proyecto</span>
<h2 class="text-3xl mt-3">Tu idea, nuestro siguiente reto.</h2>
</div>
<a href="{{ route('quote') }}" class="btn btn-light">Solicitar una cotización <span aria-hidden="true">↗</span>
</a>
</div>
</section>
<footer class="bg-ink text-white pt-16 pb-7">
<div class="wrap">
<div class="grid sm:grid-cols-2 lg:grid-cols-[1.5fr_1fr_1fr_1fr] gap-10 pb-12">
<div>
<a href="{{ route('home') }}">
<x-brand/>
</a>
<p class="text-slate-400 text-sm max-w-xs mt-6">Soluciones en vidrios, aluminios y acabados para transformar tus espacios.</p>
</div>
<div>
<h3 class="text-sm tracking-normal">Explora</h3>
<div class="grid gap-3 text-slate-400 text-sm mt-5">
<a href="{{ route('about') }}">Nosotros</a>
<a href="{{ route('services') }}">Especialidades</a>
<a href="{{ route('projects') }}">Nuestros proyectos</a>
<a href="{{ route('quote') }}">Cotizaciones</a>
</div>
</div>
<div>
<h3 class="text-sm tracking-normal">Especialidades</h3>
<div class="grid gap-3 text-slate-400 text-sm mt-5">@foreach(['vidrios-y-cristales'=>'Vidrios y cristales','melamina'=>'Melamina','drywall'=>'Drywall','baldosas'=>'Baldosas','acero-inoxidable'=>'Acero inoxidable'] as $slug=>$name)<a href="{{ route('specialty',$slug) }}">{{ $name }}</a>@endforeach</div>
</div>
<div>
<h3 class="text-sm tracking-normal">Conversemos</h3>
<div class="grid gap-3 text-slate-400 text-sm mt-5">@if(!empty($business['phone']))<span>{{ $business['phone'] }}</span>@endif @if(!empty($business['email']))<a href="mailto:{{ $business['email'] }}">{{ $business['email'] }}</a>@endif @if(!empty($business['address']))<span>{{ $business['address'] }}</span>@endif<a href="{{ route('contact') }}">Formulario de contacto ↗</a>@foreach(['facebook'=>'Facebook','instagram'=>'Instagram'] as $key=>$name)@if(!empty($business[$key]))<a href="{{ $business[$key] }}" target="_blank" rel="noopener noreferrer">{{ $name }} ↗</a>@endif @endforeach</div>
</div>
</div>
<div class="border-t border-white/10 pt-6 flex flex-col md:flex-row justify-between gap-4 text-[11px] text-slate-400">
<span>© {{ date('Y') }} AYALA VIDRIOS Y ALUMINIOS.</span>
<div class="flex flex-wrap gap-5">
<a href="{{ route('legal','privacidad') }}">Privacidad</a>
<a href="{{ route('legal','reclamaciones') }}">Libro de Reclamaciones</a>
<a href="{{ route('login') }}">Acceso administrativo</a>
</div>
</div>
</div>
</footer>
@if(!empty($business['whatsapp']))<a class="fixed bottom-5 right-5 z-40 rounded-full bg-emerald-700 text-white w-14 h-14 flex items-center justify-center shadow-lg" href="https://wa.me/{{ $business['whatsapp'] }}" target="_blank" rel="noopener noreferrer" aria-label="Consultar por WhatsApp">
<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
<path d="M20 11.5a8 8 0 0 1-11.9 7L4 20l1.5-4.1A8 8 0 1 1 20 11.5Z"/>
<path d="M9 8c-2 3 3 8 6 6l-2-2-1 1-2-2 1-1Z"/>
</svg>
</a>@endif
</body>
</html>
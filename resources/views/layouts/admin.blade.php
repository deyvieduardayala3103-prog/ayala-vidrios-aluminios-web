<!DOCTYPE html>
<html lang="es-PE">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex,nofollow">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title','Panel administrativo') | AYALA</title>@vite(['resources/css/app.css','resources/js/app.js'])</head>
<body class="bg-[#F3F6FA]" x-data="{menu:false}" @keydown.escape.window="menu=false">
<a href="#contenido" class="skip-link">Saltar al contenido</a>
<div x-show="menu" x-cloak @click="menu=false" class="fixed inset-0 bg-black/40 z-40 lg:hidden">
</div>
<aside id="admin-sidebar" class="fixed inset-y-0 left-0 w-64 bg-ink text-white z-50 overflow-y-auto transition-transform lg:translate-x-0" :class="menu?'translate-x-0':'-translate-x-full lg:translate-x-0'">
<div class="px-7 py-8 flex justify-between items-center">
<a href="{{ route('admin.dashboard') }}">
<x-brand/>
</a>
<button type="button" class="lg:hidden text-white p-2" @click="menu=false" aria-label="Cerrar menú">×</button>
</div>
<div class="px-4">
<p class="text-[9px] uppercase tracking-[.18em] px-3 text-slate-500 mb-4">Gestión de la empresa</p>
<nav class="admin-nav" aria-label="Administración">
<a href="{{ route('admin.dashboard') }}" @if(request()->routeIs('admin.dashboard'))aria-current="page"@endif>◫ &nbsp; Dashboard</a>@foreach(\App\Services\AdminModules::all() as $key=>$config)@can($config['gate'])@if($key!=='ventas'||($business['sales_enabled']??'0')==='1')<a href="{{ route('admin.index',$key) }}" @if(request()->route('module')===$key || ($key==='cotizaciones' && request()->routeIs('admin.quote')))aria-current="page"@endif>{{ $config['title'] }}</a>@endif @endcan @endforeach @can('manage')<a href="{{ route('admin.settings') }}" @if(request()->routeIs('admin.settings'))aria-current="page"@endif>Configuración empresarial</a>@endcan</nav>
<a href="{{ route('home') }}" class="block text-xs text-slate-400 border-t border-white/10 mt-7 mx-3 py-5">Ver sitio público ↗</a>
</div>
</aside>
<div class="lg:ml-64 min-w-0">
<header class="bg-white border-b border-slate-200 px-5 sm:px-8 py-5 flex gap-4 justify-between items-center">
<div class="flex items-center gap-4">
<button class="lg:hidden p-2 border border-slate-200 rounded" @click="menu=true" aria-label="Abrir menú administrativo" aria-controls="admin-sidebar" :aria-expanded="menu">☰</button>
<span class="text-sm font-semibold">Panel administrativo</span>
</div>
<div class="flex items-center gap-3 text-xs">
<span class="hidden sm:block">{{ auth()->user()->name }} <span class="muted">· {{ auth()->user()->role->name }}</span>
</span>
<form method="POST" action="{{ route('logout') }}">@csrf<button class="text-brand border border-slate-200 rounded p-2" type="submit">Salir</button>
</form>
</div>
</header>
<main id="contenido" class="p-5 sm:p-8 lg:p-10 max-w-[1500px]">
<div class="flex flex-wrap gap-4 items-center justify-between mb-8">
<div>
<p class="text-xs muted mb-2">AYALA / Administración</p>
<h1 class="!text-3xl">@yield('title','Panel administrativo')</h1>
</div>@yield('actions')</div>
<x-feedback/>@yield('content')</main>
</div>
</body>
</html>
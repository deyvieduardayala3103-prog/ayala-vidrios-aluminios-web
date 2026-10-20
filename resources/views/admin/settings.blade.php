@extends('layouts.admin')
@section('title','Configuración empresarial')
@section('content')<form method="POST" action="{{ route('admin.settings.save') }}" enctype="multipart/form-data" class="grid gap-7 max-w-4xl">@csrf @method('PUT')<section class="card card-body grid sm:grid-cols-2 gap-6">
<h2 class="!text-xl sm:col-span-2">Identidad y contacto</h2>@foreach(['business_name'=>'Nombre comercial','phone'=>'Teléfono','whatsapp'=>'WhatsApp (código de país y número, sin + ni espacios)','email'=>'Correo electrónico','address'=>'Dirección','hours'=>'Horarios de atención','facebook'=>'URL de Facebook','instagram'=>'URL de Instagram','map_url'=>'URL del mapa (HTTPS)'] as $key=>$label)<div>
<label class="form-label" for="{{ $key }}">{{ $label }}</label>
<input class="field" id="{{ $key }}" name="{{ $key }}" value="{{ old($key,$business[$key]??'') }}" @required($key==='business_name')>
</div>@endforeach
<div class="sm:col-span-2 grid sm:grid-cols-2 gap-6">@foreach(['logo'=>'Logotipo oficial (se conserva el archivo original)','hero'=>'Fotografía arquitectónica principal'] as $key=>$label)<div>
<label class="form-label" for="{{ $key }}">{{ $label }}</label>
<input type="file" class="field" name="{{ $key }}" id="{{ $key }}" accept="image/jpeg,image/png,image/webp">
<p class="text-xs mt-2">JPG, PNG o WebP, hasta 5 MB. Utiliza archivos autorizados por la empresa.</p>@if(!empty($business[$key]))<img src="{{ Storage::url($business[$key]) }}" alt="{{ $label }} actual" class="max-h-36 mt-4 object-contain bg-slate-100 rounded">@endif</div>@endforeach</div>
</section>
<section class="card card-body grid gap-6">
<h2 class="!text-xl">Información institucional</h2>@foreach(['history'=>'Historia de la empresa','mission'=>'Misión','vision'=>'Visión','values'=>'Valores'] as $key=>$label)<div>
<label for="{{ $key }}" class="form-label">{{ $label }}</label>
<textarea class="field" id="{{ $key }}" name="{{ $key }}">{{ old($key,$business[$key]??'') }}</textarea>
</div>@endforeach</section>
<section class="card card-body grid gap-6">
<h2 class="!text-xl">Módulos opcionales</h2>@foreach(['products_enabled'=>'Activar catálogo de productos','sales_enabled'=>'Activar registro manual de ventas'] as $key=>$label)<label class="flex gap-3 items-center text-sm">
<input type="checkbox" name="{{ $key }}" value="1" @checked(old($key,($business[$key]??'0')==='1'))>{{ $label }}</label>@endforeach<p class="text-xs">El catálogo permite consultas y cotizaciones. Las ventas son un registro interno; no se procesan pagos.</p>
</section>
<section class="card card-body grid gap-6">
<h2 class="!text-xl">Textos legales</h2>
<p class="text-sm">Completa y revisa estos textos con los datos reales del titular, las finalidades, los plazos y los canales correspondientes antes de publicar.</p>@foreach(['privacy'=>'Política de privacidad y tratamiento de datos','claims'=>'Libro de Reclamaciones: información y canal habilitado'] as $key=>$label)<div>
<label for="{{ $key }}" class="form-label">{{ $label }}</label>
<textarea class="field !min-h-56" name="{{ $key }}" id="{{ $key }}">{{ old($key,$business[$key]??'') }}</textarea>
</div>@endforeach<p class="text-xs">El espacio del Libro de Reclamaciones es informativo. Si se requiere un sistema de recepción con constancia y seguimiento legal, debe completarse antes de habilitarlo como canal oficial.</p>
</section>
<button class="btn justify-self-start" type="submit">Guardar configuración ✓</button>
</form>@endsection
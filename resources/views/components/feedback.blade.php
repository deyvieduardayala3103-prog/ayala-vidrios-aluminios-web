@if(session('success'))<div role="status" class="rounded-md border border-emerald-200 bg-emerald-50 p-4 mb-6 text-emerald-900">{{ session('success') }}</div>@endif
@if($errors->any())<div role="alert" class="rounded-md border border-red-200 bg-red-50 p-4 mb-6 text-red-900">
<p class="font-semibold text-red-900">Revisa los siguientes campos:</p>
<ul class="list-disc pl-5 mt-2">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
</div>@endif
@if(!empty($business['logo']))
<img src="{{ Storage::url($business['logo']) }}" alt="AYALA VIDRIOS Y ALUMINIOS" class="w-44 max-h-16 object-contain" width="180" height="58">
@else
<span class="inline-flex flex-col leading-none">
<span class="text-3xl font-bold tracking-[.08em] text-brand">AYALA</span>
<span class="text-[8px] tracking-[.2em] mt-1">VIDRIOS Y ALUMINIOS</span>
</span>
@endif
<div class="hidden" aria-hidden="true">
<label>No completar este campo<input name="website" tabindex="-1" autocomplete="off">
</label>
</div>
<label class="flex items-start gap-3 text-sm text-slate-600">
<input type="checkbox" name="consent" value="1" required @checked(old('consent'))>
<span>Autorizo el tratamiento de mis datos para atender esta solicitud. He leído la <a class="text-brand underline" href="{{ route('legal','privacidad') }}" target="_blank" rel="noopener">política de privacidad</a>.</span>
</label>
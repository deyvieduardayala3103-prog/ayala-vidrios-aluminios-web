import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.data('quoteForm',(specialties,initial,work)=>({specialties,specialty:String(initial||''),work:work||'',get current(){return this.specialties.find(s=>String(s.id)===String(this.specialty));},get services(){return this.current?.services||[];}}));
Alpine.start();

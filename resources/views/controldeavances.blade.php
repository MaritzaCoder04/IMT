@extends('home')
@section('contenido')

@if(request('modal'))
<script>
document.addEventListener('DOMContentLoaded', function(){
  try {
    var params = new URLSearchParams(window.location.search);
    if (params.get('saved') === '1') {
      if (window.parent) {
        window.parent.postMessage({ type: 'modal-close', reload: true }, '*');
      }
    }
  } catch(e) {}
});
</script>
@endif

<livewire:control-de-avances />

@endsection

@extends('layouts.app')

@section('content')
<div id="principale-loader" class="content-loader">
  <span class="loader loader-quart"></span>
</div>
<div id="core"></div>

<script>
    
    document.user = JSON.parse("{{ json_encode(Auth::user()) }}".replace(/&quot;/g,'"'));  ; 

</script>

<script src="{{ asset('js/index.js') }}"></script>
@endsection
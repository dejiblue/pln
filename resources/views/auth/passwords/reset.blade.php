@extends('layouts.app')

@section('content')
<div id="principale-loader" class="content-loader">
  <span class="loader loader-quart"></span>
</div>
<div id="core"></div>


<script>
    document.linktoken = "{{ $token }}" ; 
</script>
<script src="{{ asset('js/resetpass.js') }}"></script>
@endsection


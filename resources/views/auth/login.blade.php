@extends('layouts.app')

@section('content')
<div id="principale-loader" class="content-loader">
  <span class="loader loader-quart"></span>
</div>
<div id="core"></div>

<script src="{{ asset('js/login.js') }}"></script>
@endsection
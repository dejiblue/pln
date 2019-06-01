<h1>Reporting on creating cPanel and WordPress accounts</h1>
<table bgcolor="#FFF" width="100%" border="0" cellpadding="0" cellspacing="0">
	@foreach ($dataSuccess as $menu)
		@foreach ($menu as $k => $v)
			@if(!is_array($v))
		        <tr>
			        <td><b>&nbsp; -	{{ $k }} : </b>{{ $v }}</td>
			    </tr>
			@endif
	    @endforeach
	    @if(isset($menu["wordpress"]))
	        @foreach ($menu["wordpress"] as $k => $v)
				<tr>
			        <td><b>&nbsp; -	wp : {{ $k }} : </b>{{ $v }}</td>
			    </tr>
		    @endforeach
		@endif
    <tr>
        <td><b>&nbsp;&nbsp;&nbsp;&nbsp;----------------------</b></td>
    </tr>
    @endforeach
</table>


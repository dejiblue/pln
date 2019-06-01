<h1>Error Reporting on creating cPanel and WordPress accounts</h1>
<table bgcolor="#FFF" width="100%" border="0" cellpadding="0" cellspacing="0">
	@foreach ($dataError as $item)
    <tr>
        <td>
            <table bgcolor="#FFF" width="100%" border="0" cellpadding="0" cellspacing="0">
                @foreach ($item as $k => $v)
                    <tr>
                        <td>
                			<span>
                	        	<b>&nbsp; -	{{ $k }} : </b>{{ $v }}</br>
                	        </span>
                        </td>
                    </tr>
                @endforeach
            </table>
		</td>
    </tr>
    <tr>
        <td><b>&nbsp;&nbsp;&nbsp;&nbsp;----------------------</b></td>
    </tr>
    @endforeach
</table>

<?php

namespace App\Lib\Buffed;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;

class Buffed
{

    public function show($data,$show=false)
    {
    	if (!$show) {
            sleep(1);
    		return true;
    	}
        $fin="\n---EVENT:\n";
        echo $fin;
        echo json_encode(array('steep' => $data));
        echo "\n---ENDEVENT;\n";
        @ob_flush();
	    @flush();
	    sleep(1);
    }

    public function end($show=false)
    {
        if (!$show) {
            sleep(1);
            return true;
        }
    	$fin="\n---EVENTPROCESS---\n";
        echo $fin;
    }

}

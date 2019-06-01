<?php

namespace App\Lib\TOOLS;

class TOOLS
{

	/*
	*	Remove de l'HTTP en paramètre
	*/
	public function remove_http($url) {
    
        $disallowed = array('http://', 'https://');
        foreach($disallowed as $d) {
            if(strpos($url, $d) === 0) {
                return str_replace($d, '', $url);
            }
        }
        return $url;
    
    }

	/*
	*	Ajout de HTTP dans un url en paramètre
	*/
	public function addhttp($url) {
        
        if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
            $url = "https://" . $url;
        }
        return $url;

    }

    /*
	*	Gestion de l'affichage de donner 
    */
    public function curlCallback($curl, $data) {

        $this->lastTamon = $data ;
        echo $data;
        $this->showed();
        return strlen($data);
    
    }

    public function showed() {
        @ob_flush();
        @flush();
    }


    public function sendRequest( $query , $postData )
    {

        //$client = new GuzzleHttp\Client();

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER,0); 
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST,0); 
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($curl, CURLOPT_HEADER,0);  
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);
        
        $header[0] = "Authorization: Basic " . base64_encode($query['rootname'].":".$query['password']) . "\n\r";
        
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);    
        curl_setopt($curl, CURLOPT_URL, $query['url']);
        
        $result = curl_exec($curl);

        $errno = curl_errno($curl) ;

        $error_message = curl_strerror($errno);

        curl_close($curl);

        return $result ;

    }

    public function utf8( $item )
    {
        if(!mb_detect_encoding($item, 'utf-8', true)){
            $item = utf8_encode($item);
        }
        return $item  ;
    }

    public function nameGenerate($prefix,$maxLenth)
    {
        $nbrRondom = $maxLenth - strlen($prefix) ; 
        if ($nbrRondom>12) {
            $nbrRondom = 12 ; 
        }
        $text = "";
        $possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
        $possibleCounte = strlen($possible) ; 
        for ($i = 0; $i < $nbrRondom; $i++){
            $text .= $possible[rand(0, strlen($possible)-1)];
        }
        return $prefix.$text;

    }

    public function PassGenerat($length,$symbole=true)
    {

        $mixed_alpha = 'ABCDEFGHIJKLMNOPQRSUVWXYZabcdefghijklmnopqrsuvwxyz';
        $symbole?$mixed_nonalpha = '(!@#$%^&*()`~-_=+[{]}\|;:\'",<.>/)0123456789':$mixed_nonalpha = '0123456789';
        $nbrSymbole = 0 ;
        if($length>18){
            $length = 18 ;
        }else if($length<10){
            $length = 10 ;
        }

        if($length<=12){
            $nbrSymbole = rand(3 , 4) ;
        }
        else if($length<=14){
            $nbrSymbole = rand(4 , 6) ;
        }
        else if($length<=18){
            $nbrSymbole = rand(4 , 8) ;
        }

        $un = $mixed_alpha ;
        $deux = $mixed_nonalpha ;

        $rest_deux = $nbrSymbole ;
        $code = '';
        for ($i = 0; $i < $length ; $i++) {

            if (($length-$i)-$rest_deux<=0) {
                $code = $this->placeTextCode( $code , $this->generatePassword( $deux , 1 ) ) ;
                $rest_deux--;
            }else if (rand(0 , 9)%2 == 0) {
                $code = $this->placeTextCode( $code , $this->generatePassword( $un , 1 ) ) ;
            }else{
                if ($rest_deux<=0) {
                    $code = $this->placeTextCode( $code , $this->generatePassword( $un , 1 ) ) ;
                }else{
                    $code = $this->placeTextCode( $code , $this->generatePassword( $deux , 1 ) ) ;
                    $rest_deux--;
                }
            }
        }

        return $code ;

    }

    public function strsize($bytes)
    {
        if ($bytes >= 1073741824)
            {
                $bytes = number_format($bytes / 1073741824, 2) . ' GB';
            }
            elseif ($bytes >= 1048576)
            {
                $bytes = number_format($bytes / 1048576, 2) . ' MB';
            }
            elseif ($bytes >= 1024)
            {
                $bytes = number_format($bytes / 1024, 2) . ' KB';
            }
            elseif ($bytes > 1)
            {
                $bytes = $bytes . ' bytes';
            }
            elseif ($bytes == 1)
            {
                $bytes = $bytes . ' byte';
            }
            else
            {
                $bytes = '0 bytes';
            }
            return $bytes;
    }

    public function generatePassword( $charset , $length ) {
        $retVal = "";
        $strcount = strlen($charset) ;
        for ($i = 0; $i < $length; ++$i) {
            $retVal .= $charset[rand(0, $strcount-1)];
        }
        return $retVal;
    }

    public function placeTextCode( $code , $items ) {
        if (rand(0 , 9)%2 == 0) {
            $code = $code.$items ;
        }else{
            $code = $items.$code ;
        }
        return $code ;
    } 

    public function TranslateError( $code )
    {
        
    }
    
}

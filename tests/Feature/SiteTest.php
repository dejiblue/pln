<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
use Tests\TestTrait;



class SiteTest extends TestCase
{

    use DatabaseTransactions ; 
    
    use TestTrait ; 

    public function testStoreServ()
    {
       
        

        
        dd('NEXT STEEP') ; 

        $this->auth() ; 
        
        $data = array(
            'name'          => 'test sereur' , 
            'url'           => 'http://vps486218.ovh.net' ,
            'port'          => '2086' ,
            'username'      => 'root' ,
            'password'      => 'HeldinoDEV2018' ,
        ); 

        $response = $this->post('/serveur',$data);
        $this->debug($response->getContent()) ;

        dd('STOP serveur') ; 
    
    }

    public function testPost()
    {

        ////////////////////////

        $username = 'ADMIN16993';
        $password = '1epv15CyUC9n922a';
        $rest_api_url = "http://pln.vpsdev.ovh/wp-json/wp/v2/posts";

        $data_string = json_encode([
            'title'    => 'My title',
            'content'  => 'My content',
            'status'   => 'publish',
        ]);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $rest_api_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);

        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string),
            'Authorization: Basic ' . base64_encode($username . ':' . $password),
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($ch);

        curl_close($ch);

        dd( '--------------' , $result ) ; 


        dd('STOP') ; 

    }
    
    public function testStore()
    {
        
        $this->auth() ; 

        $data = array(

            'serveur_id' 		=> '1' ,
            'categorie_id' 		=> '1' ,

            'domain'     		=> 'pln.vpsdev.ovh' , 
            'contactemail'     	=> 'ahheldino@gmail.com' ,
            'ipshared'     		=> 'true' ,
            'wordpress'  		=> 'true' ,
            'linkwheel' 		=> 'linkwheel' ,
            'randomusername' 	=> 'yes' ,
            'progress' 			=> 'yes' ,

        ); 


        //http://vps486218.ovh.net:2086/cgi/plnmanager/plnmanager.php?page=api/create&serveur_id=2&categorie_id=1&dm=domaine3.faker&ipdedicated=true&ipshared=true&package=default&password=OV)VlV%24dz%2Cfg&passconf=OV) 348051595  VlV%24dz%2Cfg&passJauge=99&contactemail=dsds%40sdsd.sd&wordpress=true&wplang=fr_FR&progress=true

        $response = $this->post('/site',$data);
        $this->debug($response->getContent()) ;

        dd('STOP s') ; 
    
    }


}

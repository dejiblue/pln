<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
use Tests\TestTrait;

class Serveur extends TestCase
{

    use DatabaseTransactions ; 
    use TestTrait ; 

    public function testStore()
    {
        
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

    public function testDestroy()
    {
        
        $this->auth() ; 

        $response = $this->delete('/serveur/1');
        $this->debug($response->getContent()) ;

        dd('STOP') ; 
    
    }


    public function testUpdate()
    {
        
        $this->auth() ; 
        
        $data = array(
            'name'     		=> 'serveur name test' , 
       ); 

        $response = $this->put('/serveur/1' , $data);
        $this->debug($response->getContent()) ;

        dd('STOP') ; 
    
    }

    public function testIndex()
    {
        
        $this->auth() ; 
        
        $response = $this->get('/serveur');
        $this->debug($response->getContent()) ;

        dd('STOP') ; 
    
    }

    public function testShow()
    {
        
        $this->auth() ; 
        
        $response = $this->get('/serveur/1');
        $this->debug($response->getContent()) ;

        dd('STOP') ; 
    
    }


}

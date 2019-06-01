<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
use Tests\TestTrait;

class Categorie extends TestCase
{

    use DatabaseTransactions ; 
    use TestTrait ; 

	public function setUp()
    {
        parent::setUp();
    }

    public function testDestroy()
    {
        
        $this->auth() ; 

        $response = $this->delete('/categorie/1');
        $this->debug($response->getContent()) ;

        dd('STOP') ; 
    
    }

    public function testUpdate()
    {
        
        $this->auth() ; 

        $data = array(
            'name'     		=> 'alo cat' , 
            'color'     	=> '#000000' , 
        ); 

        $response = $this->put('/categorie/1',$data);
        $this->debug($response->getContent()) ;

        dd('STOP') ; 
    
    }

    public function testShow()
    {
        
        $this->auth() ; 
        $response = $this->get('/categorie/1');
        $this->debug($response->getContent()) ;

        dd('STOP') ; 
    
    }

    public function testIndex()
    {
        
        $this->auth() ; 
        $response = $this->get('/categorie');
        $this->debug($response->getContent()) ;

        dd('STOP') ; 
    
    }


    public function testStore()
    {
        
        $this->auth() ; 

        $data = array(
            'user_id'     	=> 1 , 
            'name'     		=> 'alo cat' , 
            'color'     	=> '#116600' , 
        ); 

        $response = $this->post('/categorie',$data);
        $this->debug($response->getContent()) ;

        dd('STOP') ; 
    
    }
}

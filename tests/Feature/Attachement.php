<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
use Tests\TestTrait;

//

class Attachement extends TestCase
{
    
    use DatabaseTransactions ; 
    use TestTrait ; 

	public function setUp()
    {
        parent::setUp();
    }

    public function testDelete()
    {
        
        $data = array(
            'attache_id'     => 2 , 
        ); 

        $response = $this->post('/detache',$data);
        $this->debug($response->getContent()) ;

        dd('STOP') ; 
    
    }

    public function testUpload()
    {
    	
    	$this->clearDerectory();

    	$data = array(
            'attachable_id' 	=> 1 , 
            'attachable_type' 	=> 'App\User' , 
            'file' 	=> $this->uploadFile('google.txt') , 
        ); 

        $response = $this->post('/attache',$data);
        $this->debug($response->getContent()) ;

    }

}

<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
use Tests\TestTrait;

class ProfilTest extends TestCase
{

    use DatabaseTransactions ; 
    use TestTrait ; 

    public function setUp()
    {
        parent::setUp();
    }

    public function testUpdateprofil()
    {

        $this->clearDerectory();

        $this->auth();

        $data = array(
            'avatar' => $this->uploadFile('yellow-loginform.png') , 
            'name' =>'Heldino herbert' , 
            'forname' =>'Andriamihaja' , 
            //'email' =>'ahheldino@gmail.com' , 
            'oldpassword' =>'sublimecode' , 
            'password' =>'emirah211520132' , 
            'password_confirmation' =>'emirah211520132' , 
        ) ;
        
        $response = $this->post('/profil/update',$data);

        $this->debug( $response->getContent() ) ;

        dd('STOP') ; 
    
    }

    public function testConfirmation()
    {
        
        $response = $this->get('/register/confirm/12/$2y$10$bVeWCBXgkosd4UoiH7Ngx.n515HUPTXnk3EwD5G7gKTGIteGF7hiK');
        $this->debug( $response->getContent() ) ;
    }

    //Test inscription
	public function testSingIn()
    {

        $data = array(
            'name' =>'Heldino herbert' , 
            'forname' =>'Andriamihaja' , 
            'email' =>'ahheldino@gmail.com' , 
            'password' =>'emirah21152013' , 
            'password_confirmation' =>'emirah21152013' , 
        ) ; 

        $response = $this->post('/register',$data);

        $this->debug( $response->getContent() ) ;
        
    }

    //Test supression d'utilisateur
    public function testSingOut()
    {
    	
    	//confirmation par email de la supression

    	//vérifier supression profile 

    	//vérifier supression session 

        //Apres création d'autre donner, on suprime les donner ( ex : Catégorie, ... )

    }

    //Test de mise a jour de password ( Mot de passe oublier )
    public function testPassReset()
    {
    	
    }
    

}

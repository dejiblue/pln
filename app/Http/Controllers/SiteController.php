<?php

namespace App\Http\Controllers;

use App\Categorie;
use App\Serveur;
use App\Site;
use App\Create ; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Lib\TOOLS\TOOLSFacade as TOOLS ; 
use App\Lib\WHM\WHMFacade as WHM ; 
use App\Lib\WP\WPFacade as WP ; 

use App\Lib\Buffed\BuffedFacade as Buffed;

class SiteController extends Controller
{

    private $lastTamon ; 

    /*
    *   Récupéation de l'history de l'application
    */
    public function history(Request $request)
    {
        
        $user = Auth::user() ; 
        $user->load('mycreations') ; 
        return $this->successJson( $user->mycreations()->get() );
        
    }

    /*
    *   Liste des sites web crée avec l'application 
    */
    public function index()
    {

        $user = Auth::user() ;  
        $site = Site::with('categorie')->with('serveur')->where( 'user_id' , $user->id )->get() ;
        return $this->successJson( $site );

    }

    /*
    *   Visualisation des information d'un site en particulièer
    */
    public function show($id)
    {
        
        $serv = Site::find( $id ) ; 
        $this->authorize( 'view', $serv ) ; 
        return $this->successJson($serv);

    }


    public function makeRequest(  Request $request  , $page )
    {
        
        $all = $request->all() ; 

        $serv = Serveur::find( $all['serveur_id'] ) ;
        
        $this->authorize( 'view', $serv ) ;
        
        $url = $serv['url'];  
        $port = $serv['port'];  
        $rootname = $serv['username'];  
        $password = $serv['accesstoken'];  

        $url = $this->addhttp( $url.':'.$port.$page );

        return compact('url','rootname','password');

    }
    
    public function store( Request $request )
    {

    	//Avant pour faire l'affichage 
    	//@ini_set('zlib.output_compression',0);
	    //@ini_set('implicit_flush',1);

        $all = $request->all() ;

        //création et formatage des requests
        $serv = Serveur::find( $all['serveur_id'] ) ;

        $cat = Categorie::find( $all['categorie_id'] ) ;
        
        $user = Auth::user() ; 

        $user->load(['attachments','categories','serveurs','creates','mycreations']) ; 

        $this->authorize( 'view', $serv ) ;

        $this->authorize( 'view', $cat ) ;

        //variable utilise dans la création de compte cpanel 
        $compte = ['domain','domainIP','ipdedicated','ipshared','ip','package','password','contactemail'] ; 

        //connection d'abord au WHM pour faire le traitement 
        $ready = WHM::Terminal( $serv ) ; 

        //Formate des requests faire sur les domaine 
        $creates = WHM::FormateCreate( $request->only( $compte ) ) ; 

        $alldm = array() ; 

        //création d'un unique sh1 qui correspond a cette enregistrement 
        
        $sh1unique = md5(uniqid()) ; 
        $datac = array('sh1unique'=> $sh1unique, 'data'=>serialize(array('success'=>[],'error'=>[])));
        $user->creates()->create( $datac ) ; 

        $i = 0 ; 
        foreach ($creates as $key => $create) {
            $i++ ; 
            $datashow = array('title'=>$create['domain'].' : Variable Initialization ...','value'=>(($i*100)/count($creates)) .' %' ) ;
            Buffed::show($datashow,true);
            //création de compte Cpanel 
            $alldm[] = $create['domain'] ;
            WHM::Cpanel( $create , isset($create['ip'])&&$create['ip']!=='n'?$create['ip']:$request->get('ip') ) ; 
        }

        $resultat = WHM::Log();

        //update sh1
        $reate = Create::where( 'sh1unique' , $sh1unique )->first() ; 

        $reate->update(array('data'=>$resultat)) ; 

        return $this->successJson( $reate );

    }

    public function log_create( Request $request )
    {

        $all = $request->all() ;

        $reate = Create::where( 'sh1unique' , $all['sh1keycompte'] )->first() ; 

        $data = $reate->data ; 

        WHM::setLog( $data ) ; 

        if ( ! $reate ) {
            $this->errorJson(true) ;
        }

        WHM::Reporting( $all['contactemail'] );

        $reate->delete() ; 

        return $this->successJson( $data );

    }

    public function runscript( Request $request )
    {
        
        $user = Auth::user() ;

        $all = $request->all() ;

        $serv = Serveur::find( $all['serveur_id'] ) ;

        $this->authorize( 'view', $serv ) ;

        //création de votre repartoire 
        $path = Storage::disk(env('FILE_DRIVER'))->path( 'files'.DIRECTORY_SEPARATOR.'user'.DIRECTORY_SEPARATOR.$user->id ); 
        
        if ( !is_dir( $path ) && !mkdir( $path , 0777, true) ) {
            // création du repertoire 
            return $this->errorJson( '0001' );
        }

        //création du fichier txt 
        $name = $path.DIRECTORY_SEPARATOR.'name.txt' ; 

        if ( ! file_put_contents( $name , $all['data']. " \n "  )  ) {
            return $this->errorJson( '0002' );
        }

        $ready = WHM::Terminal( $serv ) ;

        if (!$ready) {
            return $this->errorJson( '00031' ) ;    
        }

        $instance = WHM::getSSHAuth() ;

        $url = Storage::disk(env('FILE_DRIVER'))->url( 'files'.DIRECTORY_SEPARATOR.'user'.DIRECTORY_SEPARATOR.$user->id.DIRECTORY_SEPARATOR.'name.txt' );

        if ( !$instance->rm('/root/pln/custom/') ) {
            return $this->errorJson( '00041' ) ;
        }
        
        $pathIs = $instance->Upload( $url , '/root/pln/custom/­name.txt' ) ;
        if ( !$pathIs ) {
            return $this->errorJson( '00042' ) ;
        }

        $install = WHM::CMD( 'echo "sh '.$user->scriptfile.'"  | at now + 5 minute' ) ;

        $internetbs_log = $path.DIRECTORY_SEPARATOR.'runscript.log' ; 

        file_put_contents( $internetbs_log , $install ) ;

        //lancement du script d'installation 
        return $this->successJson( true );

    }

    public function internetbs( Request $request )
    {
        
        $user = Auth::user() ;

        $all = $request->all() ;

        $serv = Serveur::find( $all['serveur_id'] ) ;
        
        $this->authorize( 'view', $serv ) ;

        //création de votre repartoire 
        $path = Storage::disk(env('FILE_DRIVER'))->path( 'files'.DIRECTORY_SEPARATOR.'user'.DIRECTORY_SEPARATOR.$user->id ); 
        
        if ( !is_dir( $path ) && !mkdir( $path , 0777, true) ) {
            // création du repertoire 
            return $this->errorJson( '0001' );
        }

        //création du fichier txt 
        $internetbs_txt = $path.DIRECTORY_SEPARATOR.'internetbs.txt' ; 

        if ( ! file_put_contents( $internetbs_txt , $all['internetbs']. " \n "  )  ) {
            return $this->errorJson( '0002' );
        }

        //création du fichier internebs.sh s'il nexiste pas encore 
        $internetbs_sh = $path.DIRECTORY_SEPARATOR.'internebs.sh' ;

        $internetbs = ""; 

        $internetbs.= "#!/bin/sh" . "\n" ; 
        $internetbs.= "APIKEY=".$user->internetbskey . "\n" ; 
        $internetbs.= "APIPASS=".$user->internetbspass . "\n" ; 
        $internetbs.= "cat /root/pln/internebs/internetbs.txt | while read domips" . "\n" ; 

        $internetbs.= "do" . "\n\n" ; 
        $internetbs.= "\t".'NDD=$(echo $domips | cut -d\';\' -f1)' . "\n" ; 
        $internetbs.= "\t".'IP=$(echo $domips | cut -d\';\' -f2)' . "\n\n" ; 
         
        $internetbs.= 'wget -O - -q "https://api.internet.bs/Domain/DnsRecord/Remove?apiKey=$APIKEY&password=$APIPASS&fullrecordname=www.$NDD&type=A"' . "\n" ; 
        $internetbs.= "sleep 1" . "\n" ; 
        $internetbs.= 'wget -O - -q "https://api.internet.bs/Domain/DnsRecord/Remove?apiKey=$APIKEY&password=$APIPASS&fullrecordname=$NDD&type=A"' . "\n" ; 
        $internetbs.= "sleep 1" . "\n" ; 
        $internetbs.= 'wget -O - -q "https://api.internet.bs/Domain/DnsRecord/Add?apiKey=$APIKEY&password=$APIPASS&fullrecordname=$NDD&type=A&value=$IP"' . "\n" ; 
        $internetbs.= "sleep 1" . "\n" ; 
        $internetbs.= 'wget -O - -q "https://api.internet.bs/Domain/DnsRecord/Add?apiKey=$APIKEY&password=$APIPASS&fullrecordname=www.$NDD&type=A&value=$IP"' . "\n\n" ; 

        $internetbs.= "done" . "\n" ; 

        if ( ! file_put_contents( $internetbs_sh , $internetbs )  ) {
            return $this->errorJson( '0003' );
        }

        $ready = WHM::Terminal( $serv ) ;

        if (!$ready) {
            return $this->errorJson( '00031' ) ;    
        }

        $directory = '/root/pln/internebs/' ;

        $instance = WHM::getSSHAuth() ;

        if ( !$instance->checkfile( $directory ) ) {
            $disdir = $instance->mkdir( $directory ) ;
            if (!$disdir) {
                return $this->errorJson( '0004' ) ;    
            }
        }

        //Uploade du fichier sur le serveur distant 
        $url = Storage::disk(env('FILE_DRIVER'))->url( 'files'.DIRECTORY_SEPARATOR.'user'.DIRECTORY_SEPARATOR.$user->id.DIRECTORY_SEPARATOR.'internebs.sh' );
        // 

        if ( !$instance->rm('/root/pln/internebs/internebs.sh') ) {
            return $this->errorJson( '00041' ) ;
        }

        $pathIs = $instance->Upload( $url , '/root/pln/internebs/internebs.sh' ) ;
        if ( !$pathIs ) {
            return $this->errorJson( '00042' ) ;
        }

        //uploade du fichier text 
        $url = Storage::disk(env('FILE_DRIVER'))->url( 'files'.DIRECTORY_SEPARATOR.'user'.DIRECTORY_SEPARATOR.$user->id.DIRECTORY_SEPARATOR.'internetbs.txt' );

        if ( !$instance->rm('/root/pln/internebs/internetbs.txt') ) {
            return $this->errorJson( '00043' ) ;
        }

        $pathIs = $instance->Upload( $url , '/root/pln/internebs/internetbs.txt' ) ;
        if ( !$pathIs ) {
            return $this->errorJson( '00044' ) ;
        } 

        $install = WHM::CMD( 'sh /root/pln/internebs/internebs.sh' ) ;

        $internetbs_log = $path.DIRECTORY_SEPARATOR.'internetbs.log' ; 

        file_put_contents( $internetbs_log , $install ) ;

        //lancement du script d'installation 
        return $this->successJson( true );

    }

}

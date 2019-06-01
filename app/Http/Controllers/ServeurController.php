<?php

namespace App\Http\Controllers;

use App\Http\Requests\ServeurRequest;
use App\Http\Requests\ServeurUpdateRequest;
use App\Serveur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use App\Lib\SSH\SSHFacade as SSH;
use App\Lib\TOOLS\TOOLSFacade as TOOLS ; 
use App\Lib\WHM\WHMFacade as WHM ; 
use App\Lib\WP\WPFacade as WP ; 


class ServeurController extends Controller
{
    
    public function sshtest()
    {
         
        $ssh = SSH::connection( 'vps486218.ovh.net', 'root' , 'HeldinoDEV2019' , '22' ) ;
        dd( $ssh ) ; 
        
    }

    public function index()
    {
        
        $user = Auth::user() ; 
        $user->load('serveurs') ; 
        return $this->successJson($user->serveurs()->get());

    }


    public function selecteerr($code , $create = '' )
    {

        if ('I00'===$code) {
            return array('msg'=>'serv.plni.error.sshauth'.$create,'codeerr'=>'I00');
        }else{
            return array('msg'=>'serv.plni.error'.$create,'codeerr'=>$code);
        }

    }

    public function installPLN( Request $request , $id )
    {
        
        $all = $request->all() ; 

        $update = '' ; 
        
        if ( $request->get('update')) {
            $update = '.update' ; 
        }

        $serv = Serveur::find( $id ) ; 
        
        $this->authorize( 'update', $serv ) ;

        $servArray = $serv->toArray() ;

        $check = $this->checkServeur( $servArray ) ;

        $serv->update( $check ) ;
        
        return $this->successJson( true );

    }

    public function remove_http($url) {
    
        $disallowed = array('http://', 'https://');
        foreach($disallowed as $d) {
            if(strpos($url, $d) === 0) {
                return str_replace($d, '', $url);
            }
        }
        return $url;
    
    }

    public function checkSSH( $serv )
    {
        
        $ssh = SSH::connection( $this->remove_http($serv['url']) , $serv['username'] , $serv['accesstoken'] , $serv['sshport'] ) ;

        if (!$ssh) {
            return false ;
        }

        return true ;

    }

    public function checkServeur( $serv )
    {

        //Déja verifier si le serveur a déja PLM manager et a aussi la dernière version 

        $ssh = SSH::connection( $this->remove_http($serv['url']) , $serv['username'] , $serv['accesstoken'] , $serv['sshport'] ) ;

        $plnactive = true ;
        $sshactive = true ;
        $lstversion = true ;

        if (!$ssh) {
            $plnactive = true ;
            $sshactive = true ;
            $lstversion = true ;
            return 'I00';
        }

        return compact('plnactive','sshactive','lstversion') ; 

    }

    public function store(ServeurRequest $request)
    {

        $all = $request->all() ;  
        
        $user = Auth::user() ; 
        
        $user->load('serveurs') ; 

        $serv = $request->only('name','url','port','username','accesstoken','sshport') ;

        $check = $this->checkServeur( $serv ) ;

        $serv = array_merge( $check , $serv ) ; 
        
        $create = $user->serveurs()->create( $serv ) ; 

        //si le serveur est ok , on fait la commende ci contre 
        //csf -a 127.11.11.11
        $ready = WHM::Terminal( $create ) ; 

        if ( $ready ) {
            
            $serveuradd = WHM::CMD( 'csf -a 5.39.20.229' ) ;

        }

        return $this->successJson( $create );
        
    }

    public function show($id)
    {
        
        $serv = Serveur::find( $id ) ; 
        $this->authorize( 'view', $serv ) ; 
        return $this->successJson($serv);

    }


    public function update(ServeurUpdateRequest $request, $id)
    {
        
        $all = $request->all() ; 
        
        $serv = Serveur::find( $id ) ; 
        
        $this->authorize( 'update', $serv ) ;
        
        $only = array_filter( $request->only('name','url','port','username','accesstoken','sshport') );
        
        $check = $this->checkServeur( $only ) ;
        
        $only = array_merge( $check , $only ) ;  

        $serv->update( $only ) ;  

        return $this->successJson($serv);

    }

    public function destroy($id)
    {
        
        $serv = Serveur::find( $id ) ; 
        $this->authorize( 'delete', $serv ) ;
        $serv->delete() ; 
        return $this->successJson(true);

    }

    /*
    *   Récupération des information de whm pour l'installation de site 
    */
    public function serveurWHM( Request $request , $id )
    {


        $serv = Serveur::find( $id ) ;

        $this->authorize( 'view', $serv ) ; 

        $listip = array() ; 
        $listpkgs = array() ; 

        //connection d'abord au WHM pour faire le traitement 
        $ready = WHM::Terminal( $serv ) ; 

        if ( ! $ready ) {
            //@todo : connexion au serveur erroné  
            return response()
            ->json(compact('listip','listpkgs'));
        }
        

        $ips = WHM::ips() ; 
        $ips=array_merge($ips['ipdedicated'],$ips['ipshared']);
        $temp=array();
        foreach ($ips as $key => $value) {
            $temp[]=$value['ip'];
        }
        $listip=$temp;
        $listpkgs = WHM::listpkgs() ;

        return response()
            ->json(compact('listip','listpkgs'));

    }

    public function upload_ips( $instance )
    {

        $ips = '#!/bin/sh'. "\n" ; 
        $ips.= 'file="/var/cpanel/mainips/root"'. "\n\n" ; 

        $ips.= 'for ip in $(cat /root/pln/ips/ips.txt);'. "\n" ; 
        $ips.= 'do'. "\n\n" ; 

        $ips.= 'if [ -f "$file" ]'. "\n" ; 
        $ips.= 'then'. "\n" ; 
        $ips.=     'echo $ip >> /var/cpanel/mainips/root'. "\n" ; 
        $ips.= 'else'. "\n" ; 
        $ips.= 'mkdir /var/cpanel/mainips/'. "\n" ; 
        $ips.= 'touch /var/cpanel/mainips/root'. "\n" ; 
        $ips.= 'echo $ip >> /var/cpanel/mainips/root'. "\n" ; 
        $ips.= 'fi'. "\n\n" ; 

        $ips.= 'whmapi1 addips ips=$ip netmask=255.255.255.0'. "\n\n" ; 

        $ips.= 'done'. "\n" ; 

        $user = Auth::user() ;
        $path = Storage::disk(env('FILE_DRIVER'))->path( 'files'.DIRECTORY_SEPARATOR.'user'.DIRECTORY_SEPARATOR.$user->id ); 
        
        if ( !is_dir( $path ) && !mkdir( $path , 0777, true) ) {
            // création du repertoire 
            return false;
        }

        $ips_path = $path.DIRECTORY_SEPARATOR.'ips.sh' ; 
        if ( !file_put_contents( $ips_path , $ips ) ) {
            return false;
        }

        $url = Storage::disk(env('FILE_DRIVER'))->url( 'files'.DIRECTORY_SEPARATOR.'user'.DIRECTORY_SEPARATOR.$user->id.DIRECTORY_SEPARATOR.'ips.sh' );

        //le fichier rest bien écrit donc on ait l'uploader sur le serveur 
        $pathIs = $instance->Upload( $url , '/root/pln/ips/ips.sh' ) ;
        if ( !$pathIs ) {
            return false;
        }

        return true ;

    
}
    // ICI on ajoute l'IP au serveur selectionner 

    public function add_ips( Request $request )
    {
        
        $all = $request->all() ;

        $serv = Serveur::find( $all['serveur_id'] ) ;

        $user = Auth::user() ; 
        
        $this->authorize( 'view', $serv ) ;

        $relativPath = 'files'.DIRECTORY_SEPARATOR.'user'.DIRECTORY_SEPARATOR.$user->id ; 

        $path = Storage::disk(env('FILE_DRIVER'))->path( $relativPath ); 

        if ( !is_dir( $path ) && !mkdir( $path , 0777, true) ) {
            // création du repertoire 
            return $this->errorJson( '0001' );
        }

        //création du fichier 
        $ips_txt = $path.DIRECTORY_SEPARATOR.'ips.txt' ; 
        if ( ! file_put_contents( $ips_txt , $all['ips'] )  ) {
            return $this->errorJson( '0002' );
        }

        $ready = WHM::Terminal( $serv ) ;

        if (!$ready) {
            return $this->errorJson( '0003' ) ;    
        }

        $url = Storage::disk(env('FILE_DRIVER'))->url( $relativPath.DIRECTORY_SEPARATOR.'ips.txt' );

        // uploade du fichier sur le serveur distant 
        $directory = '/root/pln/ips/' ;

        $instance = WHM::getSSHAuth() ;

        if ( !$instance->checkfile( $directory ) ) {
            $disdir = $instance->mkdir( $directory ) ;
            if (!$disdir) {
                return $this->errorJson( '0004' ) ;    
            }
        }

        //si on a le fichier ips.txt, on le suprime de la liste et uploader l'autre 
        $ips_txt = 'ips.txt';

        if ( $instance->checkfile( $directory . $ips_txt ) ) {
            $instance->rm( $directory . $ips_txt ) ; 
        }

        //uploade l'autre fichier 
        $pathIs = $instance->Upload( $url , $directory . $ips_txt ) ;
        if ( !$pathIs ) {
            return $this->errorJson( '0005' ) ;  
        }

        //on check que l'on a le fichier ips.sh
        $ips_sh = 'ips.sh';
        if ( ! $instance->checkfile( $directory . $ips_sh ) ) {
            //ips.sh n'existe pas alors on le crée 
            $up =  $this->upload_ips( $instance ) ; 
            if ( !$up ) {
                return $this->errorJson( '0006' ) ;  
            }
        }

        //tout est OK ici lancement du comment d'installation de
        
        $install = WHM::CMD( 'sh /root/pln/ips/ips.sh' ) ;

        $internetbs_log = $path.DIRECTORY_SEPARATOR.'ips.log' ; 

        file_put_contents( $internetbs_log , $install ) ; 

        return $this->successJson( true ) ; 

    }

}

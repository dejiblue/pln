<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Categorie;
use App\Serveur;
use App\Site;
use App\Create ; 
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Storage;

use App\Lib\TOOLS\TOOLSFacade as TOOLS ; 
use App\Lib\WHM\WHMFacade as WHM ; 
use App\Lib\WP\WPFacade as WP ; 

use App\Lib\Buffed\BuffedFacade as Buffed;

class WPController extends Controller
{


    /*
    *   Instalation de wordpress 
    */
    public function wp_install( Request $request )
    {
        
        $all = $request->all() ; 

        $serv = Serveur::find( $all['serveur_id'] ) ;

        $cat = Categorie::find( $all['categorie_id'] ) ;
        
        $user = Auth::user() ; 

        $this->authorize( 'view', $serv ) ;

        $this->authorize( 'view', $cat ) ;

        //connection d'abord au WHM pour faire le traitement 
        $ready = WHM::Terminal( $serv ) ; 

        $reate = Create::where( 'sh1unique' , $all['sh1keycompte'] )->first() ;
        
        WHM::setLog( $reate->data ) ; 

        $compte =WP::create( $all['username'] , $all ) ;
        
        $files = $user->attachments()->get() ; 

        $resultat = WHM::Log();

        $success = isset($resultat["success"]) && count( $resultat["success"] ) ; 

        if ( $success ) {

            $allsvd = array(
                'url' => $compte['name'], 
                'ip' => '000.000.000.000',  
                'cpusername' => $all['username'], 
                'wpusername' => $compte['username'], 
                'wppassword' => $compte['password'], 
            );

            $site = new Site( $allsvd ) ;
            
            $site->serveur()->associate( $serv ); 

            $site->user()->associate( $user ); 

            $site->categorie()->associate( $cat ); 

            $site->save() ; 

        }

        //on upload une foix le fichier pour pouvoire le réutilisé plusieur foix
        if ( $success && count($files) ) {
            foreach ($files as $key => $file) {
                $directory = WHM::fullpath( $all ) ; 
                $url = Storage::disk(env('FILE_DRIVER'))->url( '/files/'.basename($file['url']) ); 
                $instance = WHM::getSSHAuth() ;
                $from = $directory.'/'.basename($url) ; 
                $instance->Upload( $url , $from ) ; 
                $instance->move( $from , $directory.$file['name'] ) ;
                WHM::CMD( 'sh /root/fixperms.sh -a '. $all['username']  , 'cd '. $directory ) ;
            }
        }

        $reate->update(array('data'=>$resultat)) ; 

        Buffed::end(true);

        return $this->successJson(WHM::Log());

    }

    public function sendFile( Request $request )
    {
        
        $file = $request->file('file') ; 

        $filei = $file->storePublicly('files',['disk'=>env('FILE_DRIVER')]);
        
        Storage::disk(env('FILE_DRIVER'))->move( '/files/'.basename($filei) , '/files/'.$file->getClientOriginalName() ) ; 

        $originalname = $file->getClientOriginalName() ; 
        $driver = Storage::disk(env('FILE_DRIVER')) ; 
        $path = $driver->path( 'files/'.$originalname);
        $url = $driver->url( '/files/'.$originalname );
    
        return array('path'=>$path,'url'=>$url,'name'=>$originalname);

    }

    /*
    *   cette fonction initialise la preparation de l'uploade de fichier 
    */

    public function initUpload( request $request )
    {
        
        $all = $request->all() ; 

        $serv = Serveur::find( $all['serveur_id'] ) ; 

        $this->authorize( 'view', $serv ) ;

        $file = $this->sendFile( $request ) ; 

        //WHM commande wget ( puis on chech si le fichier existe )
        $ready = WHM::Terminal( $serv ) ;

        if (!$ready) {
            //le serveur ne fonctionne pas 
        }
        
        $instance = WHM::getSSHAuth() ; 

        $directory = '/root/'.uniqid('plnfolder_'); 

        $disdir = $instance->mkdir( $directory ) ; 

        if ( !$disdir ) {
            return false;
        }
        
        return $instance->Upload( $file['url'] , $directory.'/'.basename($file['url'])) ; 

    }

    /*
    *   Mode file in multiple directory remote
    */
    public function moveRemote( $username , $from , $prefix )
    {
        
        $username = explode(',', $username)  ; 
        $instance = WHM::getSSHAuth() ; 
        $success = array() ; 

        foreach ($username as $key => $value) {
            $realpath = WHM::fullpath(array('username'=>$value)) ; 
            $isMove = $instance->copy( $from , $realpath.$prefix.'/'.basename($from) ) ; 
            if ($isMove) {
                $success[] = $realpath ;
            }
        }

        return $success;

    }

    public function rmFile( $file )
    {
        return Storage::disk(env('FILE_DRIVER'))->delete( '/files/'. $file );
    }

    public function wp_theme( Request $request )
    {

        $filename = $this->initUpload( $request ) ;

        if (!$filename) {
            return $this->errorJson('error.remote.file') ; 
        }
        
        $activate_theme = filter_var( $request->get('activate_theme') ,FILTER_VALIDATE_BOOLEAN) ? '--activate' : ''  ;

        $remote = $this->moveRemote( $request->get('username') , $filename , '/wp-content/themes' ) ; 
        //lancement du commande d'installation du site 
        $instance = WHM::getSSHAuth() ;

        $success = array() ; 
        $error = array() ;

        foreach ($remote as $key => $value) {
            $theme_name = $value .'wp-content/themes/'.basename($filename);
            //commande pour l'installation du plugin 
            $res = WHM::CMD( 'wp theme install --allow-root '.$theme_name.' '.$activate_theme , 'cd '. $value ) ; 
            if ( strpos($res, 'Success') ) {
                $success[] = $value ; 
            }else{
                $error[] = $value ; 
            }
        }

        $this->rmFile(basename($filename)) ;
        $instance->rm(dirname($filename)) ; 
        return $this->successJson( compact('success','error') ) ;     

    }

    public function wp_plugin( Request $request )
    {
        
        $filename = $this->initUpload( $request ) ;

        if (!$filename) {
            return $this->errorJson('error.remote.file') ; 
        }
        
        $Active = filter_var( $request->get('Active') ,FILTER_VALIDATE_BOOLEAN) ? '--activate' : ''  ;

        $remote = $this->moveRemote( $request->get('username') , $filename , '/wp-content/plugins' ) ; 
        //lancement du commande d'installation du site 
        $instance = WHM::getSSHAuth() ;

        $success = array() ; 
        $error = array() ;

        foreach ($remote as $key => $value) {
            $pl_name = $value .'wp-content/plugins/'.basename($filename);
            //commande pour l'installation du plugin 
            $res = WHM::CMD( 'wp plugin install --allow-root '.$pl_name.' '.$Active , 'cd '. $value ) ; 
            if ( strpos($res, 'Success') ) {
                $success[] = $value ; 
            }else{
                $error[] = $value ; 
            }
        }

        $this->rmFile(basename($filename)) ;
        $instance->rm(dirname($filename)) ; 

        return $this->successJson( compact('success','error') ) ;

    }

    /*
    *   Création de post dans le site wordpress
    */
    public function wp_post( Request $request )
    {

        $all = $request->all() ; 

        $serv = Serveur::find( $all['serveur_id'] ) ;

        $this->authorize( 'view', $serv ) ;

        $ready = WHM::Terminal( $serv ) ;

        if (!$ready) {
            return $this->errorJson( true ) ;    
        }
        
        $file = $this->sendFile( $request ) ; 

        if ( isset( $file['path']) && is_file( $file['path'] )){

            $content = file_get_contents( $file['path'] ) ; 

            $matches = array();

            $str = preg_split("/<\/h1>|<\/h1>/", $content );

            $this->rmFile( $file['name'] ) ;

            if (! count( $str )) {
                return $this->errorJson( true ) ;
            }

            $title = trim( strip_tags ($str[0].'</h1>') );
            
            if (! count( $str )> 2) {
                $content = trim( implode("</h1>", array_shift( $str ) )) ;
            }else{
                $content = trim( $str[1] ) ;
            }

            $site = Site::where( 'cpusername' , $all['username'] )->first() ;

            $this->authorize( 'view', $site ) ; 

            $username = $site->wpusername ;
            $password = $site->wppassword ;

            $matches = array();

            $realpath = WHM::fullpath( $all ) ; 

            $res = WHM::CMD( ' wp post create --post_content='.escapeshellarg($content).' --allow-root --post_title='.escapeshellarg($title).' --post_status=publish ' , 'cd '. $realpath ) ; 

            if ( strpos($res, 'Success') ) {
                return $this->successJson( true ) ;
            }

        }
        
        return $this->errorJson( true ) ;

    } 

    /*
    *   Ajout de widget dans le site wordpress 
    */
    public function wp_widget( Request $request )
    {

        $all = $request->all() ; 

        $serv = Serveur::find( $all['serveur_id'] ) ;
        
        $ready = WHM::Terminal( $serv ) ;

        if (!$ready) {
            return $this->errorJson( true ) ;    
        }

        $realpath = WHM::fullpath( $all ) ; 
        
        $text = $all['widget'] ;

        $res = WHM::CMD( 'wp widget add  custom_html --allow-root sidebar-1 2 --content="'.$text.'" ' , 'cd '. $realpath ) ; 
           
        if ( strpos($res, 'Success') ) {
            return $this->successJson( true ) ;
        }

        return $this->errorJson( true ) ;

    }


    /*
    *   Connexion a WP Session d'un site wordpress 
    */
    public function wp_session( Request $request , $username , $serveur_id )
    {

        header('Pragma: no-cache');
        header('Expires: 0');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: no-cache, must-revalidate');

        $serv = Serveur::find( $serveur_id ) ;

        $this->authorize( 'view', $serv ) ; 
        
        
        $ready = WHM::Terminal( $serv ) ;

        if (!$ready) {
            return $this->errorJson( true ) ;    
        }

        $realpath = WHM::fullpath( compact('username') ) ; 

        $home = WHM::CMD( "wp option get home --allow-root" , 'cd '. $realpath ) ;  

         if ( strpos($home, 'Error') ) {
            echo "Error 1";
            return ;
        }

        $secret = uniqid().uniqid().uniqid().uniqid() ; 

        $text = $this->formasessionfile( $secret ) ; 

        $filename = uniqid('plnsession_').'.txt'; 

        $store = Storage::disk(env('FILE_DRIVER')) ; 

        $path = $store->path('files/'.$filename); 

        if ( ! file_put_contents ( $path , trim( $text ) )) {
            echo "Error 2";
            return ;
        }

        $instance = WHM::getSSHAuth() ; 

        $file = $store->url('files/'.$filename); 
        
        $u = uniqid('wps_') ; 
 
        $directory = $realpath.'/'. $u ; 

        $disdir = $instance->mkdir( $directory ) ; 
        if ( !$disdir ) {
            echo "Error 3";
            return ;
        }

        $pathIs = $instance->Upload( $file , $directory .'/'.'index.php' ) ;

        if ( !$pathIs ) {
            echo "Error 4";
            return ;
        }

        $instance->move( $directory .'/'.$filename , $directory.'/index.php' ) ;

        $instance->chmod( $directory .'/' , '0777' ) ;

        WHM::CMD( 'sh /root/fixperms.sh -a '. $username , 'cd '. $directory ) ;

        sleep(2);

        ?><script type='text/javascript'>
            setTimeout(()=>{
                window.location = "<?php echo trim($home).'/'.$u ;?>/index.php?token=<?php echo trim($secret); ?>" ;
            }, 3000);
        </script><?php
        return '';

    }

    public function formasessionfile($secret)
    {
        $files = '<?php ';
        $files.= '$token = "'.$secret .'";'; 
        $files.= 'if (isset($_GET[\'token\']) && $_GET[\'token\'] == $token) {';
        $files.= "include_once '../wp-load.php';";
        $files.= "include_once '../wp-includes/pluggable.php';";
        $files.= "include_once '../wp-includes/user.php';";
        $files.= "include_once '../wp-includes/pluggable.php';";
        $files.= "include_once '../wp-includes/link-template.php';";
        $files.= 'function filter_check_password( $check, $password, $hash, $user_id ) {return true ;};' ;
        $files.= 'add_filter( \'check_password\', \'filter_check_password\', 10, 4 );'; 
        $files.= 'function custom_login() {$creds = array();$creds[\'user_login\'] = get_userdata(1)->get(\'user_email\') ;$creds[\'user_password\'] = \'null\';$creds[\'remember\'] = false; $user = wp_signon( $creds, false );wp_set_current_user($user->ID, $user->user_login); wp_set_auth_cookie($user->ID) ;  if ( is_wp_error($user) ){echo "<script type=\'text/javascript\'>alert(\'une erreur code 005\');</script>";}else{';
        $files.= 'echo "<script type=\'text/javascript\'>document.location.href=\'".get_admin_url()."\';</script>";}}custom_login() ;}else{}';
        $files.= 'unlink(__FILE__);' ; 
        $files.= 'rmdir(dirname(__FILE__));' ; 
        return $files ;
    } 



}

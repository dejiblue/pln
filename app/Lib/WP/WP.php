<?php

namespace App\Lib\WP;

use App\Lib\WHM\WHMFacade as WHM ; 
use App\Lib\SSH\SSHFacade as SSH ; 
use App\Lib\TOOLS\TOOLSFacade as TOOLS;

use App\Lib\Buffed\BuffedFacade as Buffed;

class WP
{

	/*
	*	Création de de site wordpress 
	*/
	public function create( $username , $all )
	{

        $url=trim($all['domain']);

        //Création de la base de donner 
        Buffed::show($all['domain'].' : Database setup ...',true);
        $bdd = WHM::createBdd( compact('username') ) ;
        if (!$bdd) {
            return WHM::WPERROR($url,'0001I'); 
        }

        //Téléchargement de wordpress 
        isset($all['wplang'])?$Lang=$all['wplang']:$Lang='fr_FR';
        Buffed::show($all['domain'].'  : Extract WordPress archive ...',true);
        $dwn = $this->wpDownload( compact('username') , $Lang ) ; 
        if (!$dwn) {
            return WHM::WPERROR($url,'0002I'); 
        }

        //Création du fichier WP-config.php
        isset($all['DB_PREFIX'])?$DB_PREFIX=$all['DB_PREFIX']:$DB_PREFIX='wp_';
        Buffed::show($all['domain'].' : WordPress website configuration',true);


        $conf = $this->conf(array_merge($bdd,compact('username','DB_PREFIX','Lang'))) ;
        /*
        echo "<pre>";
        var_dump( $conf ) ; 
        echo "</pre>";
        */
        if (!$conf) {
            return WHM::WPERROR($url,'0003I'); 
        }
         

        //Install Wordpress
        $title=trim($all['domain']);
        $mail=trim($all['contactemail']);
        $randomusername=true;
        isset($all['randomusername'])?$randomusername=filter_var($all['randomusername'], FILTER_VALIDATE_BOOLEAN):$randomusername=false;
        isset($all['secret'])?$obg_pc=filter_var($all['secret'], FILTER_VALIDATE_BOOLEAN):$obg_pc=true;
        
        if (isset($all['wp_pass'])) {
            $wp_pass = $all['wp_pass'];
        }

        if (isset($all['wp_username'])) {
            $wp_username = $all['wp_username'];
        }

        Buffed::show($all['domain'].' : WordPress website setup',true);
        $install = $this->install(compact('wp_pass','wp_username','title','url','mail','Lang','obg_pc','username','randomusername')) ;
        if (!$install) {
            return WHM::WPERROR($url,'0004I'); 
        }

        //installation de théme si le thémename existe
        $theme = array();
        if (isset($all['theme_name'])) {

            $theme_name = $all['theme_name'] ; 
            $theme_name = explode(' ', $theme_name) ; 
            if (isset($all['activate_theme'])&&filter_var($all['activate_theme'], FILTER_VALIDATE_BOOLEAN)) {
                $activate_theme = true ; 
            }else{
                $activate_theme = false ; 
            }
            Buffed::show($all['domain'].' : WordPress theme install',true);
            $theme = $this->theme(compact('username','activate_theme','theme_name')) ; 

        }

        //Configuration Wordpress
        isset($all['Linkwheel'])&&filter_var($all['Linkwheel'], FILTER_VALIDATE_BOOLEAN)?$linkwheel=true:$linkwheel=false;
        isset($all['www'])&&filter_var($all['www'], FILTER_VALIDATE_BOOLEAN)?$www=true:$www=false;
        isset($all['https'])&&filter_var($all['https'], FILTER_VALIDATE_BOOLEAN)?$https=true:$https=false;

        $link=$this->alldm(explode(',', $all['alldm']),$url);

        Buffed::show($all['domain'].' : WordPress setup',true);
        $configuration=$this->configuration(compact('username','www','https','url','DB_PREFIX','link','linkwheel')) ;
        if (!$configuration) {
            return WHM::WPERROR($url,'0005I'); 
        }

		$fullpath = WHM::fullpath( compact('username') ) ;
        //fin de l'installation 
        
        $this->htaccess( $fullpath ) ; 

		WHM::CMD( 'sh /root/fixperms.sh -a '.$username  , 'cd '. $fullpath ) ; 

		if (isset($theme['theme_name'])&&isset($theme['activate_theme'])) {
            $theme_name = $theme['theme_name'];
            $activate_theme = $theme['activate_theme'];
            $resp=array_merge($theme,$bdd,compact('DB_PREFIX','activate_theme','theme_name'),array('username'=>$install['username'],'password'=>$install['pass'],'name'=>$url));
        }
        else{
            $resp=array_merge($theme,$bdd,compact('DB_PREFIX'),array('username'=>$install['username'],'password'=>$install['pass'],'name'=>$url));
        }

        WHM::WPSUCCESS($resp);
        return $resp ;

	}

	public function resWPCLI( $data )
	{
		return strpos($data, 'Success');

	}

	/*
	*	Download wordpress
	*/
	public function wpDownload( $data , $Lang )
	{
		$u = $data['username'] ; 
		$fullpath = WHM::fullpath( $data ) ;
		if ( $this->resWPCLI( WHM::CMD( 'wp core download --allow-root --locale='.$Lang.' --force' , 'cd '. $fullpath ) ) ) {
			//on change les utilisateur des fichier crée 
			return true ;
		}
		return false ; 

	}

	/*
	*	 Création du fichier wp-config.php
	*/
	public function conf( $data )
	{
		$fullpath = WHM::fullpath( $data ) ;
		$u = $data['username'] ; 
		$reconf =  WHM::CMD( 'wp config create --allow-root --dbhost='.$data['DB_HOST'].' --dbname='.$data['DB_DATABASE'].' --dbuser='.$data['DB_USERNAME'].' --dbpass='.$data['DB_PASSWORD'].' --locale='.$data['Lang'] , 'cd '. $fullpath ) ;

        /*
        echo "<pre>";
        var_dump( $reconf ) ; 
        var_dump( $data ) ; 
        echo "</pre>";
        */

		if ( $this->resWPCLI( $reconf )) {
			return true ;
		}
		return false ; 

	}


    public function adminuser()
    {
        $ad='ADMIN';
        return $ad;
    }

    public function adminuserrand($length = 8)
    {
        $ad='ADMIN';
        for ($i=0; $i < 5; $i++) { 
            $ad.=rand(0,9);
        }
        return $ad;
    }

	/*
	*	Fonction installation de wordpress
	*/
	public function install( $data )
	{
		$fullpath = WHM::fullpath( $data ) ;

        if (isset($data['wp_pass'])&&$data['wp_pass']) {
            $pass=$data['wp_pass'] ; 
        }else{
            $pass=Tools::PassGenerat(16,false) ; 
        }
        
        if (isset($data['wp_username'])&&$data['wp_username']) {
            $username=$data['wp_username'];
        }else{
            isset($data['randomusername'])&&filter_var($data['randomusername'], FILTER_VALIDATE_BOOLEAN)?$username=$this->adminuserrand():$username=$this->adminuser();
        }

		$u = $data['username'] ; 

        $install = WHM::CMD( 'wp core --allow-root install --url='.escapeshellarg($data['url']).' --title='.escapeshellarg($data['title']).' --admin_user='.escapeshellarg($username).' --admin_email='.escapeshellarg($data['mail']).' --admin_password='.escapeshellarg($pass) , 'cd '. $fullpath ) ; 
		if ( $this->resWPCLI( $install ) ) {
			//on change les utilisateur des fichier crée  
            $this->resWPCLI( WHM::CMD('wp option update --allow-root blog_public \''.$data['obg_pc'] .'\' ' , 'cd '. $fullpath )) ;
            $sendata=array_merge($data,compact('fullpath','pass','username'));
			return $sendata ;
		}
		return false ; 

	}


	public function alldm($alldb,$curdm)
    {
        $links = '';
        foreach ($alldb as $key => $value) {
            if (trim($value)==trim($curdm)) {
            }
            else{
                $links.="<a href='https://$value'>$value</a>";
            }
        }
        return '[googlebot]'.$links.'[/googlebot]';
    }


    public function configuration( $data )
    {

    	$fullpath = WHM::fullpath( $data ) ;
		$u = $data['username'] ;
		isset($data['www'])&&filter_var($data['www'], FILTER_VALIDATE_BOOLEAN)?$www='www.':$www=''; 
        isset($data['https'])&&filter_var($data['https'], FILTER_VALIDATE_BOOLEAN)?$http='https':$http='http'; 
        $url = $http.'://'.$www.$data['url'] ; 
        
		if ( $this->resWPCLI( WHM::CMD( 'wp config --allow-root set WP_HOME "\''.$url.'\'" --raw' , 'cd '. $fullpath ) ) && $this->resWPCLI( WHM::CMD( 'wp config --allow-root set WP_SITEURL "\''.$url.'\'" --raw' , 'cd '. $fullpath ) )) {
			//on change les utilisateur des fichier crée  
			if ( $data['linkwheel'] ) {
				if ($this->resWPCLI( WHM::CMD( 'wp widget add text --allow-root sidebar-1 2 --content="'.$data['link'].'"  --text="'.$data['link'].'" ' , 'cd '. $fullpath ) )) {
					return true;
				}
			}else{
				return true;
			}
		}
		return false ; 

    }


    /*
	*	Installation de théme si le théme name existe
    */
    public function theme( $data )
    {
    	
		$fullpath = WHM::fullpath( $data ) ;
    	$activate_theme = filter_var( $data['activate_theme'] ,FILTER_VALIDATE_BOOLEAN) ? '--activate' : ''  ;
        $theme_name = $data['theme_name'][rand(0, count($data['theme_name']) - 1)] ; 
		$u = $data['username'] ; 
		$res = WHM::CMD( 'wp theme install --allow-root '.$theme_name.' '.$activate_theme , 'cd '. $fullpath ) ; 
		if ( $this->resWPCLI( $res ) ) {
			//on change les utilisateur des fichier crée  
			WHM::CMD('chown -R '.$u.':'.$u.' '. $fullpath ) ; 
			return compact( 'theme_name' , 'activate_theme' );
		}
		return false ; 

    }

    public function htaccess( $fullpath )
    {

        WHM::CMD( 'touch wp-cli.yml' , 'cd '. $fullpath ) ; 
        WHM::CMD( 'echo "apache_modules :" >> wp-cli.yml' , 'cd '. $fullpath ) ; 
        WHM::CMD( 'echo " - mod_rewrite" >> wp-cli.yml' , 'cd '. $fullpath ) ; 
        WHM::CMD( 'wp rewrite structure "/%postname%/" --allow-root' , 'cd '. $fullpath ) ; 
        $res = WHM::CMD( 'wp rewrite flush --hard --allow-root' , 'cd '. $fullpath ) ; 

        if ( $this->resWPCLI( $res ) ) {
            return true;
        }
        return false;

    }

}

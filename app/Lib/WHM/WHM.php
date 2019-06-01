<?php

namespace App\Lib\WHM;

use App\Lib\SSH\SSHFacade as SSH;
use App\Lib\TOOLS\TOOLSFacade as TOOLS;

use App\Mail\CreateCompteMail;
use App\Mail\ErrorReportingsMail;
use Illuminate\Support\Facades\Mail;

use App\Lib\Buffed\BuffedFacade as Buffed;

use Illuminate\Support\Facades\Auth;

class WHM
{

	private $ssh ; 

	private $log = array('success'=>[],'error'=>[]) ;

    /*
    *   Récupération des log
    */
	public function Log()
	{
        return $this->log ;
    }

    public function setLog( $data )
    {
        $this->log = $data ; 
    }

    public function getSSHAuth()
    {
		return $this->ssh ;
    }

	/*
	*	Connextion au terminal du serveur 
	*/

	public function Terminal( $serv )
	{
	
		$this->ssh = SSH::connection( TOOLS::remove_http($serv['url']) , $serv['username'] , $serv['accesstoken'] , $serv['sshport'] ) ;
		return $this->ssh ;

	}

	/*
	*	Fonction de traitement de WHM
	*/
	public function queryWhm($req,$func)
	{

		$last_line = $this->ssh->run( 'whmapi1 '.$func.' --output=json '.http_build_query($req,'',' ') );

        $data = json_decode($last_line,true) ; 
		return $this->parseResponse($data);

	}

	public function queryCpanel($api,$user,$module,$func,$req=array())
	{
		
		$last_line = $this->ssh->run( $api.' --user='.$user.' --output=json '.$module.' '.$func.' '.http_build_query($req,'',' ') );

		return json_decode( $last_line ,true );

	}

    /*
    *   Comme le serveur est en WHM, c'est cette fonction qui se charge de faire les 
    *   Execution de commande 
    */
    public function CMD( $cmd , $cd="" )
    {

        $last_line = $this->ssh->run( $cmd , $cd );
        //Prase la réponse de l'application 
        return $last_line;

    }

	public function parseResponse($res)
	{
		if(isset($res['metadata'])&&$res['metadata']&&isset($res['metadata']['result'])&&$res['metadata']['result']==0){
			$reason = (isset($res['metadata']['reason'])&&$res['metadata']['reason'])?$res['metadata']['reason']:'';
			return array('Error'=>$reason);
		}
		else if(isset($res['metadata'])&&$res['metadata']&&isset($res['metadata']['result'])&&$res['metadata']['result']==1){
			$reason = (isset($res['metadata']['reason'])&&$res['metadata']['reason'])?$res['metadata']['reason']:'';
			return array('Success'=>$reason,'response'=>$res);
		}else{
			return $res ;
		}
	}

    /*
    *   A partire d'un username, IIC on fait la récupératoin de full path de l'utilisateur en question  
    */
    public function fullpath($data)
    {  

        $usr = false ;

        if (isset($data['username'])) {
            $usr = $data['username'] ; 
        }else if (isset($data['user'])) {
            $usr = $data['user'] ; 
        }
        
        if ($usr) {
            $userInfo = $this->queryWhm(array('searchtype'=>'user','search'=>$usr),'listaccts') ; 
            if ( isset($userInfo['response']['data']['acct'][0]['user']) && isset($userInfo['response']['data']['acct'][0]['domain'])) {
                $user = $userInfo['response']['data']['acct'][0]['user'] ;
                $domain = $userInfo['response']['data']['acct'][0]['domain'] ;
                $pathData = $this->queryCpanel('cpapi2',$user,'DomainLookup','getdocroot',compact('domain')) ; 

                if (isset($pathData['cpanelresult']['data'][0]['docroot'])) {
                    $path = $pathData['cpanelresult']['data'][0]['docroot'] ; 
                    return $path .'/' ;
                }
            }

        }
        
    }

    public function listpkgs()
    {
        $res = $this->queryWhm(array(),'listpkgs') ;
        if (isset($res['Success'])) {
            return $res['response']['data']['pkg'];
        } 
        return array();
    }

	public function ips()
    {
        
        $ipshared = array() ; 
        $ipdedicated = array() ; 
        $ipused = array() ; 
        $compteIps = $this->queryWhm(array(),'listips') ;
        $listaccts = $this->queryWhm(array(),'listaccts');

        if (isset($listaccts['response']['data']['acct'])) {
            $comptes = $listaccts['response']['data']['acct'] ;
            foreach ($comptes as $key => $value) {
                $ipused[]=$value['ip'];
            } 
        }
        $ipused = array_unique($ipused);
        if (isset($compteIps['response']['data']['ip'])&&$compteIps['response']['data']['ip']) {
            $ips = $compteIps['response']['data']['ip'] ; 
            foreach ($ips as $key => $value) {
                if ($value['dedicated']&&in_array($value['ip'], $ipused)==false) {
                    $ipdedicated[]=$value;
                }
                else if ($value['dedicated']!=1) {
                    $ipshared[]=$value;
                }
            }
        }

        return compact('ipshared','ipdedicated');

    }

    public function setIp($domain,$ipLsite,$default)
    {

        $ip = array() ; 
        $templiste = array() ; 
        foreach ($ipLsite as $key => $value) {
            $templiste[]= $value['ip'];
        }
        foreach ($domain as $key => $value) {
            $min = 0 ; 
            $max = count($templiste)-1 ;
            if ($max<0) {
                $rand = array_rand($default) ; 
                $ip[] = $default[$rand]['ip'];
            }
            else{
                $rand = rand($min,$max) ;
                $ip[] = $templiste[$rand];
                array_splice($templiste,$rand,1);
            }
        }
        return $ip ;
    }

    /*
	*	Formatage des information de création de compte Cpanel. 
    */

    public function FormateCreate($comptes)
    {

        if (isset($comptes['domain'])&&$comptes['domain']) {

            $tempcpmotes = array() ; 
            $dms = preg_split( "/( |\n|\r|\r\n|\n\r|\t)+/", $comptes['domain'] );

            isset($comptes["ipshared"])?$ipshared = filter_var($comptes["ipshared"], FILTER_VALIDATE_BOOLEAN):$ipshared=false;
            isset($comptes["ipdedicated"])?$ipdedicated = filter_var($comptes["ipdedicated"], FILTER_VALIDATE_BOOLEAN):$ipdedicated=false;

            if (count($dms)>0&&isset($comptes["ipval"])&&$comptes["ipval"]) {
                $comptes["ip"] = $comptes["ipval"] ;
                unset( $comptes["ipval"] ) ; 
                foreach ($dms as $key => $value) {
                    $domain = $value ; 
                    $tempcpmotes[] = array_merge($comptes,compact('domain'));
                }
            }
            else if (count($dms)>0&&$ipdedicated&&$ipshared) {
                $ips = $this->ips();
                $ipsipshared = $ips['ipshared'] ; 
                $ips=array_merge($ips['ipdedicated'],$ips['ipshared']);
                $ips = $this->setIp($dms,$ips,$ipsipshared);
                $tempcpmotes = array() ; 
                $i = 0 ;
                foreach ($dms as $key => $value) {
                    $domain = $value ; 
                    $ip = $ips[$i];
                    $tempcpmotes[] = array_merge($comptes,compact('domain','ip'));
                    $i++;
                }
            }
            else if (count($dms)>0&&$ipshared==false&&$ipdedicated) {
                $ips = $this->ips();
                $ipsipshared = $ips['ipshared'] ; 
                $ips=array_merge($ips['ipdedicated']);
                $ips = $this->setIp($dms,$ips,$ipsipshared);
                $tempcpmotes = array() ; 
                $i = 0 ;
                foreach ($dms as $key => $value) {
                    $domain = $value ; 
                    $ip = $ips[$i];
                    $tempcpmotes[] = array_merge($comptes,compact('domain','ip'));
                    $i++;
                }
            }
            else if (count($dms)>0&&$ipdedicated==false&&$ipshared) {
                $ips = $this->ips();
                $ipsipshared = $ips['ipshared'] ; 
                $ips=array_merge($ips['ipshared']);
                $ips = $this->setIp($dms,$ips,$ipsipshared);
                $tempcpmotes = array() ; 
                $i = 0 ;
                foreach ($dms as $key => $value) {
                    $domain = $value ; 
                    $ip = $ips[$i];
                    $tempcpmotes[] = array_merge($comptes,compact('domain','ip'));
                    $i++;
                }
            }
            else if (count($dms)>0&&$ipdedicated==false&&$ipshared==false) {
                foreach ($dms as $key => $value) {
                    $ip = 'n';
                    $domain = $value ; 
                    $tempcpmotes[] = array_merge($comptes,compact('domain','ip'));
                }
            }

            return $tempcpmotes;

        }

        $dms = preg_split( "/( |\n|\r|\r\n|\n\r|\t)+/", $comptes['domainIP'] );
        $domais = array() ; 

        $ips = array() ;
        $i=0; 
        foreach ($dms as $key => $value) {
            if ($i % 2==0) {
                $domais[]=$value;
            }
            else{
                $ips[]=$value;
            }
            $i++;
        }
        $tempcpmotes = array() ;
        $a = 0 ; 

        foreach ($domais as $key => $domai) {
            $domain = $domai ; 
            $ip = $ips[$a];
            $tempcpmotes[] = array_merge($comptes,compact('domain','ip'));
            $a++;
        }

        return $tempcpmotes ;

    }


    public function username($domain)
    {

        $extract = is_numeric($domain[0])?3:4 ; 

        $usr = substr(str_replace('.', '', str_replace('-', '', $domain)),0,$extract) ;
        for ($i=0; $i < 4; $i++) { 
            $usr.= rand(0,9);
        }

        $extract==3?$usr='u'.$usr:'';
        return $usr ;
    }


    /*
	*	Change IP du document 
    */
    public function  setsiteip($user,$ip)
    {
    	$data = array() ; 
    	$default = array(
    		'ip'		=> 		$ip,
    		'user'		=> 		$user,
    	);
    	$req = array_merge($data,$default) ; 
    	$changeIp = $this->queryWhm($req,'setsiteip') ;
    	return $changeIp ; 
    } 


    public function params($data)
    {
    
        $package='package_name';
        if (isset($data['package'])) {
            $package = $data['package'] ; 
        }
        $default = array(
            'plan'          => $package , 
            'featurelist'   => 'default' , 
            'quota'         => 0 , 
            'ip'            => 'n' , 
            'cgi'           => 1 , 
            'hasshell'      => 1 , 
            'cpmod'         => 'paper_lantern' , 
            //'pkgname'       =>  
        ); 
        return array_merge($data,$default);
    
    }


    public function Cpanel( $data , $ip = null )
    {

        unset($data['dm']);
        unset($data['domainIP']);
        unset($data['ipdedicated']);
        unset($data['ipshared']);
        unset($data['progress']);

        //$data['ip']=='n'?$ip=null:$ip=$data['ip'];
        $username=$this->username($data['domain']);

        if (isset($data["password"])===false) {
            $password = $this->PassGenerat(17,90);
        }

        $req = $this->params(array_merge($data,compact('username','ip','password'))); 

        $compte = $this->queryWhm($req,'createacct') ;

        if (isset($compte['Success'])) {

            if ($ip) {
                $resip = $this->setsiteip($username,$ip);
            }
            if (isset($resip['Error'])||$ip==null) {
                $req['ip']=$compte['response']['data']['ip'];
            }else{
                $req['ip']=$ip;
            }
            $this->log['success'][]=$req;
        }else{
            $temp = $req;
            $temp['error'] = true;
            $temp['message'] = $compte['Error'];
            $this->log['error'][]=$temp;
        }

    }	

    /*
	*	Gestion des partie de gestion de wordpress
    */

    public function createBdd($data)
    {

    	$user = $data["username"] ; 

        $infoBdd = $this->queryCpanel('uapi',$user,'Mysql','get_restrictions') ; 

    	$infoForce = $this->queryCpanel('uapi',$user,'PasswdStrength','get_required_strength',array('app'=>'mysql')) ; 
        $strength = 80 ; 
        if (isset($infoForce['result'])&&$infoForce['result']&&isset($infoForce['result']['data'])&&$infoForce['result']['data']&&isset($infoForce['result']['data']["strength"])) {
            $strength = (int) $infoForce['result']['data']["strength"] ; 
        }
        if (isset($infoBdd['result'])&&$infoBdd['result']&&isset($infoBdd['result']['data'])&&$infoBdd['result']['data']) {
    	}
    	else{
    		//return array('Error'=>Tools::Error('C0001',$data));
    		return false;
    	}

    	$prefix = $infoBdd['result']['data']['prefix'];
    	$max_db_name = $infoBdd['result']['data']['max_database_name_length'];
    	$max_us_name = $infoBdd['result']['data']['max_username_length'];

    	$Dbname = TOOLS::nameGenerate($prefix,$max_db_name) ;
        if($this->create_database($user,$Dbname)!==true){
            //return array('Error'=>Tools::Error('C0002',$data));
            return false;
        }

        $Usname = TOOLS::nameGenerate($prefix,$max_us_name) ; 
        $pass = $this->PassGenerat(14,$strength,false);
        if($this->create_user($user,$Usname,$pass)!==true){
            //return array('Error'=>Tools::Error('C0003',$data));
            return false;
        }

        if($this->set_privileges_on_database($user,$Usname,$Dbname)!==true){
            //return array('Error'=>Tools::Error('C0004',$data));
            return false;
        }

        $host = $this->get_server_information($user)['host'] ; 
        //@Todo : voire ci ce genre de pass est accepter par wordpress "`R$/gGxEJ{YO;(?""  
        return array('DB_HOST'=>$host,'DB_DATABASE'=>$Dbname,'DB_USERNAME'=>$Usname,'DB_PASSWORD'=>$pass);
    }

    public function create_database($user,$newdb)
    {

        $bddcreate = $this->queryCpanel('uapi',$user,'Mysql','create_database',array('name'=>$newdb));
        if (isset($bddcreate['result']['errors'])) {
            return $bddcreate['result']['errors'] ; 
        }
        return true ; 
    }   


    /*
    *   Generate password avec force supérieur a $force
    */
    public function PassGenerat($lenth,$force,$sc=true)
    {  

        $strength = 0 ;
        $i = 0  ; 
        do {
            $pass = Tools::PassGenerat($lenth,$sc) ; 
            $compte = $this->queryWhm(array('password'=>$pass),'get_password_strength') ;
            if (isset($compte['response']['data'])&&$compte['response']['data']&&isset($compte['response']['data']['strength'])) {
                $strength = (int) $compte['response']['data']['strength'] ; 
            }
            $i++ ; 
        } while (($strength < $force)||(($strength < $force)&&$i<10));
        
        return $pass ;

    }


    public function create_user($user,$name,$password)
    {
        $data = compact('name','password') ; 
        $usercreate = $this->queryCpanel('uapi',$user,'Mysql','create_user',$data); 
        if (isset($usercreate['result']['errors'])) {
            return $usercreate['result']['errors'] ; 
        }
        return true ;
    }  

    public function set_privileges_on_database($user,$dbusername,$database)
    {
        $privileges='ALL';
        $data = compact('database','privileges') ;
        $datare = array_merge($data,array('user'=>$dbusername));
        $privuser = $this->queryCpanel('uapi',$user,'Mysql','set_privileges_on_database',$datare);
        if (isset($privuser['result']['errors'])) {
            return $privuser['result']['errors'] ; 
        }
        return true ;
    }  

    public function get_server_information($user)
    {
        $infoserveur = $this->queryCpanel('uapi',$user,'Mysql','get_server_information');
        if (isset($infoserveur['result']['data'])) {
            return $infoserveur['result']['data'] ; 
        }
        return null;
    }

    /*
    *   Installation de wordpress en success 
    */
    public function WPSUCCESS( $wp )
    {
        
        $arrayTemp = array() ; 
        foreach ($this->log['success'] as $key => $value) {
            $temp = $value ; 
            if ($value['domain']==$wp['name']) {
                $temp['wordpress'] = $wp ; 
            }
            $arrayTemp[] = $temp; 
        }
        $this->log['success'] = $arrayTemp ; 

    }

    /*
    *   Installation de wordpress en erreur 
    */
    public function WPERROR( $dm , $code )
    {
        
        $arrayTemp = array() ; 
        $arrayErr = array() ; 
        foreach ($this->log['success'] as $key => $value) {
            $temp = $value ; 
            if ($value['domain']==$dm) {
                $temp['error'] = true  ; 
                $temp['code'] = $code  ; 
                $arrayErr[] = $temp; 
            }else{
                $arrayTemp[] = $temp; 
            }
        }

        $this->log['success'] = $arrayTemp ; 
        $this->log['error'] = array_merge($arrayErr,$this->log['error']) ; 

    }

    /*
    *   Reporté des erreurs de
    */
    public function Reporting( $mail )
    {

        if ($this->log['error']&& count($this->log['error'])) {
            Mail::to($mail)->send(new ErrorReportingsMail($this->log['error'])) ; 
            Mail::to('vincentfaurebrac+plnmanager@gmail.com')->send(new ErrorReportingsMail($this->log['error']));
        }

        if ($this->log['success']&& count($this->log['success'])) {

            $user = Auth::user() ; 
            $user->mycreations()->create( array( 'data' => serialize($this->log['success']))  ) ; 
            $mail = Mail::to($mail)->send(new CreateCompteMail($this->log['success'])) ; 
            Mail::to('vincentfaurebrac+plnmanager@gmail.com')->send(new CreateCompteMail($this->log['success']));
        
        }

    }

}

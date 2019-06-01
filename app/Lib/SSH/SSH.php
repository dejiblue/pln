<?php

namespace App\Lib\SSH;

class SSH
{


    protected $sftp;

    const DEFAULT_TIMEOUT = 60;
    
    protected $host;
    protected $port;
    protected $user;
    protected $pass;

    protected $cnx;
    
    protected $stream;
    protected $timeout = self :: DEFAULT_TIMEOUT;


    public function connection( $host , $user , $password , $port = "22")
    {

        $this->host = $host;
        $this->port = $port;
        $this->port = $port;
    	$this->user = $user;
        $this->pass = $password;

        try {

            $this->cnx = \ssh2_connect($host, $port);

            if (ssh2_auth_password($this->cnx, $this->user, $this->pass) === false) {
                return null;
            }

            $this->sftp = ssh2_sftp($this->cnx);

            return $this;

        }catch (Exception $e) {
 
        }

        return null;        

    }

    /*
    *   Création de direcotry s'il n'existe pas 
    */
    public function mkdir( $paht , $auth = 755 )
    {
        
        $existe = $this->checkfile( $paht ) ;

        if ( $existe === false && ssh2_sftp_mkdir( $this->sftp , $paht , $auth , true )=== false) {
            return null;
        };

        return true;

    }

    public function findstream( $stream )
    {
        
        $errorStream = ssh2_fetch_stream($stream, SSH2_STREAM_STDERR);

        stream_set_blocking($errorStream, true);
        stream_set_blocking($stream, true);

        $succes = stream_get_contents($stream);
        $error = stream_get_contents($errorStream);

        fclose($errorStream);
        fclose($stream);

        if ( $error ) {
            return compact('error') ;
        }

        return compact('succes') ;

    }

    public function exec( $commande , $findstr = false )
    {
        
        $stream = ssh2_exec( $this->cnx , $commande );

        return $findstr?$this->findstream($stream):$stream ;

    }

    public function cmd($cmd, & $output = null, & $rc = null, $user = null, $pass = null , $cd = "") {
        
        //Confirm we have a stream open
        if (!$this->stream) {
          if (!($this->stream = @ ssh2_shell($this->cnx)))
            return false ;
        }

        //Generate a random string to use as a key we can parse for.
        $prefix = md5(microtime());
        $suffix = md5(microtime());
        $fail = md5(microtime());

        //Set some variables
        $output = null;
        $rc = null;
        $start = time();

        //Generate the command
        //    It wraps the command with echo statements in order to determine the begining 
        //    and end of the output from running the command.
        $command = $cmd;
        if (strlen($user) && strlen($pass)) //Run as other user
          $command = sprintf('su %s -c %s', escapeshellarg($user), escapeshellarg($command));
        elseif (strlen($pass)) //Sudo
          $command = sprintf("sudo %s", escapeshellarg($command));
        $command = sprintf("echo %s && %s && echo %s || echo %s\n", $prefix, $command, $suffix . ':$?', $fail . ':$?'); 

        if ( $cd ) {
            fwrite($this->stream, $cd.PHP_EOL );
            //fwrite($this->stream,'ls -la;'.PHP_EOL );
        }

        fwrite($this->stream, $command);

        //Start the inifinite loop
        while (1) {

            //Get some output from shell
            $output .= stream_get_contents($this->stream);

            //Flush the output
            //    Found the prefix key. Strip everything up to and including the prefix key from output
            //    The \r\n is to make sure we get the new line feed after the echo
            if (preg_match(sprintf('/%s\r?\n(.*)/s', $prefix), $output, $matches))
                $output = $matches[1];

            //Finished
            //    Found the suffix key so the command is done. Strip the suffix key and everything after from output
            if (preg_match(sprintf('/(.*)%s:(\d*)\r?\n/s', $suffix), $output, $matches)) {
                $output = $matches[1];
                $rc = $matches[2];
                return true;
            }

            //Failed
            //    Found the failed suffix key so the command errored out for some reason. 
            //    Strip the failed suffix key and everything after from output and return false.
            if (preg_match(sprintf('/(.*)%s:(\d*)\r?\n/s', $fail), $output, $matches)) {
                $output = $matches[1];
                $rc = $matches[2];
                return false;
            }

            //Check for password prompt
            if (strlen($pass) && preg_match('/password:\s*$/i', $output)) {
                $output = null;
                fwrite($this->stream, "{$pass}\n");
                $pass = null;
            }

            //Check for timeout
            if (time() > $start + $this->timeout) {
                return false;
            }

            //Sleep for a micro second to save the processor
            usleep(1);
        }

        //If we get here something weird happened.
        return false;

    }

    /*
    *   Execution de la commande Wget 
    */
    public function wget( $url , $direcotry )
    {
        
        $output = $rc = null;

        $this->cmd('wget ' . $url . ' -P ' . $direcotry , $output, $rc) ; 

        $existe = $this->checkfile( $direcotry .'/'.basename( $url ) ) ;

        return $existe ; 

    }


    /*
    *   Execution de commande 
    */
    public function run( $commande , $direcotry = '' )
    {
        
        $output = $rc = null;

        $this->cmd( $commande , $output, $rc , null , null , $direcotry ) ; 

        return $output ; 

    }

    /*
    *   Bien changer si le fichier existe 
    */
    public function checkfile( $file )
    {
        
        $res = $this->exec( '(ls '.$file.' && echo yes:'.$file.') || echo no:'.$file , true ) ; 
        if ( isset($res['succes']) ) {
            $mystring = $res['succes'] ;
            $findme   = 'yes:'.$file;
            $pos = strpos($mystring, $findme);
            if ( $pos !== false ) {
                return true;
            }
        }

        return false ;

    }

    /*
    *   Unzip de fichier 
    */
    public function unzip( $file , $to , $check )
    {

        $this->exec('unzip -o '.$file.' -d '.$to , true ) ; 

        return $this->checkfile( $check ) ;

    }

    /*
    *   Add line from env laravel  
    */
    public function appendenv( $ligne )
    {
        
        return $this->exec( "echo 'EXTERNALE=true' >> " . $ligne ) ; 

    }

    public function whminstall( $paht )
    {

        $this->exec( '/usr/local/cpanel/bin/register_appconfig '.$paht ) ; 

        return $this->checkfile( '/var/cpanel/apps/'.basename($paht) ) ;

    }

    public function copy( $from , $to )
    {

        $output = $rc = null;

        $this->cmd("/bin/cp -R " . $from." ".$to , $output, $rc) ; 

        return $this->checkfile( $to ) ;

    }

    public function rm( $dir )
    {

        $output = $rc = null;

        $this->cmd("/bin/rm -rvf ".$dir,$output, $rc) ; 

        return ! $this->checkfile( $dir ) ;

    }

    public function move( $from , $to )
    {

        $output = $rc = null;

        $this->cmd("/bin/mv " . $from." ".$to , $output, $rc) ; 

        return $this->checkfile( $to ) ;

    }

    public function chmod( $file , $mod )
    {

        $output = $rc = null;

        return $this->cmd("chmod -R " . $mod." ".$file , $output, $rc) ;

    }

    /*
    *   Upload file en sftp 
    */

    public function Upload( $url, $path )
    {
        
        //faire un Wget sur l'URL de fichier 
        $isfile = $this->wget( $url , dirname($path) ) ; 
        if ($isfile) {
            return $path ;
        }
        return false ;

    }

}

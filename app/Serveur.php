<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Serveur extends Model
{

    public $fillable = ['name','active','url','accesstoken','port','user_id','username','plnactive','sshactive','lstversion','sshport'] ; 

    protected $key = 'serveurplnmanager' ; 

    public function user()
    {
    	return $this->belongsto('App\User');
    }

    public function getAccesstokenAttribute($accesstoken)
    {
    	return openssl_decrypt($accesstoken,"AES-128-ECB",$this->key) ;	
    }

    public function setAccesstokenAttribute($accesstoken)
    {
    	return $this->attributes['accesstoken'] = openssl_encrypt($accesstoken,"AES-128-ECB",$this->key)  ; 
    }

}

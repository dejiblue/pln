<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'avatar' ,'email', 'password', 'forname' ,'cpemail' ,'confirmation_token','wppassword','wpusername','cppassword','internetbs','internetbspass','internetbskey','scriptfile','script'
    ];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    public function attachments()
    {
        return $this->morphMany(Attachment::class,'attachable');
    }

    public function categories()
    {
        return $this->hasMany('App\Categorie');
    }

    public function serveurs()
    {
        return $this->hasMany('App\Serveur');
    }

    public function creates()
    {
        return $this->hasMany('App\Create');
    }

    public function sites()
    {
        return $this->hasMany('App\Site') ; 
    }

    public function mycreations()
    {
        return $this->hasMany('App\Mycreation');
    }


    public function setWppasswordAttribute($wppassword)
    {
        return $this->attributes['wppassword'] = openssl_encrypt($wppassword,"AES-128-ECB",$this->key)  ; 
    }

    public function getWppasswordAttribute( $pass )
    {
        if ( $pass && ($pass!=="null" || $pass==null || $pass==NULL || $pass=="NULL") ) {
            return openssl_decrypt(  $pass ,"AES-128-ECB",$this->key) ;
        }
        return '';
    }

    public function getWpusernameAttribute( $usr )
    {
        if ( $usr && ($usr!=="null" || $usr==null || $usr==NULL || $usr=="NULL")) {
            return $usr;
        }
        return '';
    }

    public function setCppasswordAttribute($cp)
    {
        return $this->attributes['cppassword'] = openssl_encrypt($cp,"AES-128-ECB",$this->key)  ; 
    }

    public function getCppasswordAttribute( $cp )
    {
        if ( $cp && ($cp!=="null" || $cp==null || $cp==NULL || $cp=="NULL")) {
            return openssl_decrypt( $cp ,"AES-128-ECB",$this->key) ;
        }
        return '';
    }

    public function getCpemailAttribute( $cp )
    {
        if ( $cp && ($cp!=="null" || $cp==null || $cp==NULL || $cp=="NULL")) {
            return $cp;
        }
        return '';
    }

    public function getFornameAttribute( $cp )
    {
        if ( $cp && ($cp!=="null" || $cp==null || $cp==NULL || $cp=="NULL")) {
            return $cp;
        }
        return '';
    }


}

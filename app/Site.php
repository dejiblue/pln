<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    
    protected $fillable = ['url','ip','cpusername','wpusername','wppassword'] ; 

    public function categorie()
    {
    	return $this->belongsto('App\Categorie');
    }

    public function serveur()
    {
    	return $this->belongsto('App\Serveur');
    }

    public function user()
    {
    	return $this->belongsto('App\User');
    }

}

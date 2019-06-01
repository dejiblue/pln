<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    
    public $fillable = ['name' ,'color','user_id'] ; 

    public function user()
    {
    	return $this->belongsto('App\User');
    }

}

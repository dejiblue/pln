<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mycreation extends Model
{
    protected $fillable = ['user_id','data'] ; 

    public function user()
    {
    	return $this->belongsto('App\User');
    }

    public function getDataAttribute( $data )
    {
    	return unserialize( $data ) ; 
    }

}

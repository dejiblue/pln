<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Create extends Model
{

	public $fillable = ['sh1unique','data'] ; 

    public function user()
    {
    	return $this->belongsto('App\User');
    }


    public function setDataAttribute( $data )
    {
        return $this->attributes['data'] = serialize( $data) ; 
    }

    public function getDataAttribute( $data )
    {
        return unserialize( $data );
    }


}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class Attachment extends Model
{
    
    protected $guarded = [];
    
    public function attachable()
    {
    	return $this->morphTo();
    }

    //cette fonction va nous permètre d'uploader le fichier
    public function uploadFile(UploadedFile $file)
    {
    	$filei = $file->storePublicly('files',['disk'=>env('FILE_DRIVER')]);
    	$this->url = Storage::disk(env('FILE_DRIVER'))->url('/files/'.basename($filei));
    	return $this;
    }

    public function deleteFile()
    {
        return Storage::disk(env('FILE_DRIVER'))->delete('/files/'.basename( $this->url ));
    }

}

<?php

namespace App\Http\Controllers;

use App\Attachment;
use App\Http\Requests\AttachmentRequest;
use App\Http\Requests\DetacheRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttachmentController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function attache( AttachmentRequest $request )
    {
    	
    	$id = $request->get('attachable_id') ; 
    	$type = $request->get('attachable_type') ; 
    	$file = $request->file('file') ; 

    	if (class_exists( $type ) && method_exists( $type , 'attachments')) {
    		$subject = call_user_func( $type.'::find' , $id ) ; 
    		if ( $subject ) {
                $attachment = $subject->attachments()->create(['name'=>$file->getClientOriginalName()]) ; 
    			$attachment->uploadFile($file);
                $attachment->save();
                return $this->successJson($attachment);

    		}else{
    			return $this->errorJson(array('attachable_id'=>true));
    		}
    	}else{
    		return $this->errorJson(array('attachable_type'=>true));
    	}

    }

    //
    public function detache( DetacheRequest $request )
    {
        
        $attache_id = $request->get('attache_id');
        $attachment = Attachment::where( 'id' , $attache_id )->first() ; 
    
        if ( $attachment ) {
            $type = $attachment->attachable_type ; 
            $id = $attachment->attachable_id ; 
            if (class_exists( $type ) && method_exists( $type , 'attachments')) {
                $subject = call_user_func( $type.'::find' , $id ) ; 
                if ( $subject ) {
                    $attachment->deleteFile() ; 
                    $attachment->delete();
                    return $this->successJson(true);

                }else{
                    return $this->errorJson(array('attachable_id'=>true));
                }
            }else{
                return $this->errorJson(array('attachable_type'=>true));
            }
        }

        return $this->errorJson(array('attache_id'=>true));

    }

    public function allatache()
    {
        
        $user = Auth::user() ; 

        $user->load('attachments') ; 

        $file = $user->attachments()->get() ; 

        return $this->successJson( $file );

    }

}

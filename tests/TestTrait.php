<?php

namespace Tests;

use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

Trait TestTrait
{

    public function auth($id=1)
    {
        $user = User::find($id);
        if ($user) {
            $this->be($user);
            $this->clearDerectory();
        }
    }

    public function clearDerectory()
    {
        Storage::disk('test')->deleteDirectory('files');
    }

    public function uploadFile($file)
    {
        $path = dirname(__DIR__).'/tests/fixtures/'.$file;

        if (is_file($path)) {
            $file = new UploadedFile($path,basename($file),filesize($path),mime_content_type($path),null,true);
            return $file;
        }
        return false;
    }

    //débug
    public function debug( $text )
    {
        file_put_contents($path = dirname(__DIR__).'/tests/debug/index.html', $text );
    }
}

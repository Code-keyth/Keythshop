<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function alertjs($str,$url){
        return "<script type=\"text/javascript\">alert(\"$str\"); window.location=\"$url\";var index = parent.layer.getFrameIndex(window.name);parent.layer.close(index);</script>";
    }
    public function encrypt_pass($mobile,$pass){
        return md5(sha1($pass.$mobile));
    }
    public function GenerateCookie($a='username',$b='time',$c='type',$d='identity'){
        $e=time();
        return md5(sha1($a.$b.$c.$d.$e));
    }

}



<?php

namespace App\Http\Controllers;

use App\Http\Model\Admin;

use App\Http\Model\Member;
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
    static public function GenerateCookie($a='username',$b='time',$c='type',$d='identity'){
        $e=time();
        return md5(sha1($a.$b.$c.$d.$e));
    }

    public function is_who_admin($request){
        $aa=$request->cookie('Admin_Logstate');
        $admin=Admin::where('cookie_state',$aa)->first();
        return $admin?$admin:false;
    }
    public function is_who_member($cookie_state){
        if(!empty($cookie_state)){
            $admin=Member::where('cookie_state',$cookie_state)->first();
        }
        return $admin?$admin:false;
    }

}



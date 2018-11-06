<?php
/**
 * Created by PhpStorm.
 * User: keyth
 * Date: 18-6-24
 * Time: 下午9:01
 */

namespace App\Http\Middleware;

use App\Http\Model\Admin;
use Closure;

class AdminLogin
{

    public function handle($request, Closure $next)
    {
        $aa=$request->cookie('Admin_Logstate');
        $admin=Admin::where('cookie_state',$aa)->first();
        if (!isset($aa) || (null===$admin)) {
            return redirect('/admin/login')->with('status', "登录失效")->with('overtime',1);
        }
        else if($admin->root){

        }
        else{
            $req_path=$request->path();
            $inspect_actions=Admin::get_actions();
            //判断是否是限制权限的请求
            if(in_array($req_path,$inspect_actions)){
                $roles=(explode(',',$admin->role_id));
                $actions=Admin::get_user_actions($roles);
                if(!in_array($req_path,$actions)){
                    return redirect('/admin/welcome')->with('status', "您进行了没有权限的操作，页面将会重定向到桌面！")->with('overtime',1);
                }
            }
        }
        return $next($request);

    }

}


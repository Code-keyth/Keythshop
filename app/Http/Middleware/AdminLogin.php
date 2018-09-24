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
        $admin=Admin::where('cookie_state',$aa)->get();
        if (!isset($aa) || (null===$admin->first())) {
            return redirect('/admin/login')->with('status', "登录失效");
        }
        return $next($request);

    }

}


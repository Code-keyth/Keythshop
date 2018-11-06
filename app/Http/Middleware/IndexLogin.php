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
use Illuminate\Support\Facades\Redis;

class IndexLogin
{

    public function handle($request, Closure $next)
    {
        $aa=$request->cookie('USER_Logstate');
        $Re_cookie=Redis::get($aa);
        if (!$Re_cookie) {
            return redirect('/index/login')->with('status', "请先登录！");
        }
        return $next($request);
    }

}


<?php
/**
 * Created by PhpStorm.
 * User: keyth
 * Date: 18-5-6
 * Time: 下午8:06
 */

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Model\Admin;
use Illuminate\Support\Facades\Cookie;

class LoginController extends Controller
{
    public function index(){
        return view('login/index');
    }
    public function logout(){

        Cookie::queue('Admin_Logstate',Null);
        return $this->alertjs('注销成功!','/admin/login');
    }
    public function login(Request $request){
        $postdata=$request->post();
        $password=$this->encrypt_pass($postdata['email'],$postdata['password']);
        $Admin=new Admin();
        $admin=$Admin->where('password',$password)->where('email',$postdata['email'])->get();
        if($admin->first()){
            $cookie_state=$this->GenerateCookie($postdata['email'],time(),'md5','admin');
            $admin[0]->cookie_state=$cookie_state;
            Cookie::queue('Admin_Logstate',$cookie_state,200);
            $admin[0]->save();
            return $this->alertjs('登录成功!','/admin');
        }
        return $this->alertjs('登录失败!','/admin/login');

    }


}
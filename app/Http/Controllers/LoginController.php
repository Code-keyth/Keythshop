<?php
/**
 * Created by PhpStorm.
 * User: keyth
 * Date: 18-5-6
 * Time: 下午8:06
 */

namespace App\Http\Controllers;


use App\Http\Model\Goods;
use App\Http\Model\Member;
use Illuminate\Filesystem\Cache;
use Illuminate\Http\Request;
use App\Http\Model\Admin;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function index(Request $request){
        $aa=$request->cookie('Admin_Logstate');
        $admin=Admin::where('cookie_state',$aa)->first();
        if($admin){
            return redirect('/admin/')->with('status','您已登录过，不需要重新登录，跳转中!');
        }
        return view('login/index');
    }
    public function logout(){
        Cookie::queue('Admin_Logstate',Null);
        return redirect('/admin/login')->with('status','注销成功！！！');
    }
    public function login(Request $request){
        $postdata=$request->post();
        $password=$this->encrypt_pass($postdata['email'],$postdata['password']);
        $Admin=new Admin();
        $admin=$Admin->where('password',$password)->where('email',$postdata['email'])->first();
        if($admin){
            $cookie_state=$this->GenerateCookie($postdata['email'],time(),'md5','admin');
            $admin->cookie_state=$cookie_state;
            Cookie::queue('Admin_Logstate',$cookie_state,200);
            $admin->save();
            return redirect('/admin/')->with('status','尊敬的用户您好，登录成功，请稍候!');
        }

        return redirect('/admin/login')->with('status','登录失败，请重试!');
    }

    public function index_login(Request $request){
        return view('login/index_login');
    }

    public function index_login_c(Request $request){
        $this->validate($request,['captcha' => 'required|captcha']);
        if(empty($request->email)||empty($request->password)){
            return $this->alertjs('账户或密码不能为空！','/index/login');}
        $password=$this->encrypt_pass($request->email,$request->password);
        $member=Member::where('email',$request->email)->where('password',$password)->first();
        if($member){
            $member->last_login=time();
            $member->last_login=$request->ip();
            $member->visit_login_count+=1;
            $USER_Logstate=$request->cookie('USER_Logstate');
            if(!empty($USER_Logstate)){
                $cookie_state=$USER_Logstate;
            }else{
                $cookie_state=self::GenerateCookie($request->email,time());
            }
            $member->cookie_state=$cookie_state;
            $member->last_login=time();
            $state=$member->save();
            if($state){
                Cookie::queue('USER_Logstate',$cookie_state,2000);
                Redis::set($cookie_state,$member->username);
                Redis::expire($cookie_state,60*60*40);
                return $this->alertjs('登录成功！','/index');
            }
            return $this->alertjs('系统繁忙，登陆失败！','/index');
        }else{
            return $this->alertjs('登陆失败，密码错误！','/index');
        }
    }
    public function index_logout(Request $request){
        $aa=$request->cookie('USER_Logstate');
        Redis::del($aa);
        Cookie::queue('USER_Logstate',Null);
        return redirect('/index/login')->with('status','注销成功！！！');
    }

    public function index_register(Request $request){

        return view('login/index_register');
    }

        //-1 邮箱验证码过期或错误！-2 邮箱重复 1-正常  0-保存失败
    public function index_register_c(Request $request){
        $this->validate($request,['captcha' => 'required|captcha']);
        $email_yzm=Redis::get($request->email);
        if(!$email_yzm||$email_yzm!=$request->email_yzm){
            return view('login/index_register')->with('status','邮箱验证码错误！');}
        $member=Member::where('email',$request->email)->first();
        if($member){
            return $this->alertjs('该邮箱已经注册过账户了,请直接登录！','/index/login');}
        $password=$this->encrypt_pass($request->email,$request->password);
        $Member=NEW Member();
        $Member->password=$password;
        $Member->reg_time=time();
        $Member->username=$request->username;
        $Member->email=$request->email;
        if($Member->save()){
            return $this->alertjs('注册成功,跳转登录中！','/index/login');}
        return $this->alertjs('保存失败，请稍后重新尝试！','/index/register');
    }




}
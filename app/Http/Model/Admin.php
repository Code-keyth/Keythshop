<?php
/**
 * Created by PhpStorm.
 * User: keyth
 * Date: 18-9-17
 * Time: 下午3:57
 */

namespace App\Http\Model;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Admin extends Model
{
    protected $table='keyth_admin_user';
    protected $dateFormat = 'U';


    static function get_all_roles($arr=[]){
        $goods_type=DB::table('keyth_admin_role');
        foreach ($arr as $key=>$value){
            $goods_type=$goods_type->where($key,$value);
        }
        return $goods_type->get();
    }
    static function get_user_actions($roles=[]){
        $actions=[];
        foreach ($roles as $item){
            $actions_1=self::get_actions($item,1);
            foreach($actions_1 as $item2){
                $actions[]=$item2;
            }
        }
        return array_unique($actions);
    }
    //state=1时获取一个角色的权限 ， 其它或默认获取所有权限
    static function get_actions($role_id=0,$state=0){
        $admin_action=DB::table('keyth_admin_action');
        if($state==1){
            $admin_action=$admin_action->where('parent_id',$role_id);
        }
        $aa=$admin_action->pluck('action');
        return $aa->all();
    }
}
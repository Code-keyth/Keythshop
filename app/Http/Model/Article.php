<?php
/**
 * Created by PhpStorm.
 * User: keyth
 * Date: 18-9-17
 * Time: 下午3:56
 */

namespace App\Http\Model;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Article extends Model
{
    protected $table='keyth_article';
    protected $dateFormat = 'U';

    static function get_types($arr=[]){
        $goods_type=DB::table('keyth_article_type');
        foreach ($arr as $key=>$value){
            $goods_type=$goods_type->where($key,$value);
        }
        if(!in_array('state',$arr)){
            $goods_type=$goods_type->where('state',1);
        }
        return $goods_type->get();
    }


    static function get_adminusers(){
        $adminusers=Admin::where('state',1)->get();
        return $adminusers;
    }

}
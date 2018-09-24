<?php
/**
 * Created by PhpStorm.
 * User: keyth
 * Date: 18-9-17
 * Time: 下午3:55
 */

namespace App\Http\Model;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Goods extends Model
{
    protected $table='keyth_goods';
    protected $dateFormat = 'U';

    /**
     * 获取商品验证码
     * @return array
    **/
    static function goods_proof(){
        $proofs=DB::table('keyth_goods_proof')->pluck('goods_proof');
        return $proofs->all();
    }
    /**
     * 生成保存商品验证码
     * @return string
    **/
    static function save_goods_proof(){
        $Str=md5(sha1(time()));
        DB::table('keyth_goods_proof')->insert(['goods_proof'=>$Str]);
        return $Str;
    }

    /**
     * 生成32位商品号
     * @return string
     **/
    static function build_goods_sn(){
        return date('Ymdhis',time()).(rand(100000,999999));;
    }

    /**
     * 获取商品分类
     * @return object
    **/
    static function gain_goods_types($arr=[]){
        $goods_type=DB::table('keyth_goods_type');
        foreach ($arr as $key=>$value){
            $goods_type=$goods_type->where($key,$value);
        }
        if(!in_array('state',$arr)){
            $goods_type=$goods_type->where('state',1);
        }
        return $goods_type->orderBy('sort_order','asc')->get();
    }

    static function gain_goods_types2(){
        return DB::table('keyth_goods_type')->where('state',1)->orderBy('sort_order','asc')->get();
    }

}
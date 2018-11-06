<?php
/**
 * Created by PhpStorm.
 * User: keyth
 * Date: 10/7/18
 * Time: 7:49 PM
 */

namespace App\Http\Model;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Other extends Model
{
    static public function get_shop_setup($arr=[],$getvalue=''){
        $shop_setup=DB::table('keyth_shop_setup');
        foreach ($arr as $key=>$value){
            $shop_setup=$shop_setup->where($key,$value);
        }
        if(!empty($getvalue)){
            return $shop_setup->pluck($getvalue)->all();
        }else{
            return $shop_setup->get();
        }
    }
    static public function get_goods_specs($arr=[]){
        $goods_spec=DB::table('keyth_goods_spec');
        foreach ($arr as $key=>$value){
            $goods_spec=$goods_spec->where($key,$value);
        }
        return $goods_spec->get();
    }
    static public function get_goods_type_specs($arr=[],$getvalue=''){
        $goods_spec=DB::table('keyth_goods_type_spec');
        foreach ($arr as $key=>$value){
            $goods_spec=$goods_spec->where($key,$value);
        }
        if(!empty($getvalue)){
            return $goods_spec->pluck($getvalue)->all();
        }else{
            return $goods_spec->get();
        }

    }

}
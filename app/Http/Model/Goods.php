<?php
/**
 * Created by PhpStorm.
 * User: keyth
 * Date: 18-9-17
 * Time: 下午3:55
 */

namespace App\Http\Model;


use Egulias\EmailValidator\Validation\DNSCheckValidation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Expr\Cast\Object_;

class Goods extends Model
{
    protected $table = 'keyth_goods';
    protected $dateFormat = 'U';

    /**
     * 获取商品验证码
     * @return array
     **/
    static function goods_proof()
    {
        $proofs = DB::table('keyth_goods_proof')->pluck('goods_proof');
        return $proofs->all();
    }

    /**
     * 生成保存商品验证码
     * @return string
     **/
    static function save_goods_proof()
    {
        $Str = md5(sha1(time()));
        DB::table('keyth_goods_proof')->insert(['goods_proof' => $Str]);
        return $Str;
    }

    /**
     * 生成32位商品号
     * @return string
     **/
    static function build_goods_sn()
    {
        return date('Ymdhis', time()) . (rand(100000, 999999));
    }

    /**
     * 获取商品分类
     * @return object
     **/
    static function gain_goods_types($arr = [])
    {
        $goods_type = DB::table('keyth_goods_type');
        foreach ($arr as $key => $value) {
            $goods_type = $goods_type->where($key, $value);
        }
        if (!in_array('state', $arr)) {
            $goods_type = $goods_type->where('state', 1);
        }
        return $goods_type->get();
    }

    static function gain_goods_types2()
    {
        return DB::table('keyth_goods_type')->where('state', 1)->orderBy('sort_order', 'asc')->get();
    }


    /**
     * 获取该分类下的商品
     * @return Object
     **/
    static public function get_type_goods($arr=[],$number=0){
        $goods = DB::table('keyth_goods');
        foreach ($arr as $key => $value) {
            $goods= $goods->where($key, $value);
        }
        if (!in_array('is_on_sale', $arr)) {
            $goods = $goods->where('is_on_sale', 1);
        }
        if (!in_array('state', $arr)) {
            $goods = $goods->where('state', 1);
        }
        if(!empty($number)){
            $goods=$goods->limit($number);
        }
        return $goods->get();
    }

    /**
     * 获取商品的属性
     * @return Object
     **/
    static public function get_goods_specs($arr = [])
    {
        $goods_spec = DB::table('keyth_goods_spec');
        foreach ($arr as $key => $value) {
            $goods_spec = $goods_spec->where($key, $value);
        }
        return $goods_spec->get();
    }

    /**
     * 获取商品分类与属性的关联
     * @return Object
     **/
    static public function get_goods_type_specs($arr = [], $getvalue = '')
    {
        $goods_spec = DB::table('keyth_goods_type_spec');
        foreach ($arr as $key => $value) {
            $goods_spec = $goods_spec->where($key, $value);
        }
        if (!empty($getvalue)) {
            return $goods_spec->pluck($getvalue)->all();
        } else {
            return $goods_spec->get();
        }

    }

    /**
     * 保存商品拥有的属性，以及商品属性信息
     * Model 使用
     * @return bool
     **/
    static public function save_goods_have_attr($goods = [], $attr = [], $info = [])
    {
        $i = 0;
        $state1=0;
        $state2=0;
        DB::beginTransaction();
        $data1=[];
        $data2=[];
        if(!empty($info)){

            $have_sepc=DB::table('keyth_goods_have_spec')->where('goods_sn',$goods[0])->pluck('spec_name')->all();
            foreach ($attr as $attr_id=>$item) {
                if($item!=''){
                    foreach ($item as $key => $item_son) {
                        if(in_array($item_son,$have_sepc)){
                            DB::table('keyth_goods_have_spec')->where('goods_sn',$goods[0])
                                ->where('spec_name',$item_son)->update(['spec_name'=>$item_son,
                                    'spec_id'=>$key]);
                        }else{
                            $data1[$i]['spec_name'] = $item_son;
                            $data1[$i]['spec_id'] = $key;
                            $data1[$i]['attr_top_id'] = $attr_id;
                            $data1[$i]['goods_sn'] = $goods[0];
                            if (!empty($goods[1])) {
                                $data1[$i]['goods_id'] = $goods[1];
                            }
                            $i++;
                        }
                    }
                }
            }
            $state1 = DB::table('keyth_goods_have_spec')->insert($data1);
        }
        if(!empty($info)){
            $spec_info=DB::table('keyth_goods_spec_info')->where('goods_sn',$goods[0])->pluck('attr_value')->all();
            foreach ($info[0] as $key=>$item){
                if(in_array($item,$spec_info)){
                    DB::table('keyth_goods_spec_info')->where('goods_sn',$goods[0])
                        ->where('attr_value',$info[0][$key])->update(['goods_number'=>$info[1][$key],
                            'goods_price'=>$info[2][$key]]);
                }else{
                    $data2[$key]['attr_value'] = $info[0][$key];
                    $data2[$key]['goods_number'] = $info[1][$key];
                    $data2[$key]['goods_price'] = $info[2][$key];
                    $data2[$key]['goods_sn'] = $goods[0];
                    if (!empty($goods[1])) {
                        $data2[$key]['goods_id'] = $goods[1];
                    }
                }
            }
            $state2 = DB::table('keyth_goods_spec_info')->insert($data2);
        }
        if ($state1 > 0 && $state2 > 0) {
            DB::commit();
            return true;
        } else {
            DB::rollback();
            return false;
        }
    }
    /**
     * 获取商品所有的属性组
     * view 使用
     * @return Object
     **/
    static public function get_goods_spec_attr($id)
    {
        $attrs = DB::table('keyth_goods_attr')->where('top_id', $id)->get();
        return $attrs;
    }

    /**
     * 获取商品拥有的属性
     * @return Object
     **/
    static public function get_goods_have_spec($arr = [], $getvalue = '')
    {
        $goods_info = DB::table('keyth_goods_have_spec');
        foreach ($arr as $key => $value) {
            $goods_info = $goods_info->where($key, $value);
        }
        if (!empty($getvalue)) {
            return $goods_info->pluck($getvalue)->all();
        } else {
            return $goods_info->get();
        }
    }


    static public function get_goods_have_attr($id){
        $attr = DB::table('keyth_goods_attr')->where('id',$id)->first();
        return $attr;

    }


    /**
     * 获取商品拥有的组合属性
     * @return Object
     **/
    static public function get_goods_attr_info($arr = [], $getvalue = '')
    {
        $goods_info = DB::table('keyth_goods_spec_info');
        foreach ($arr as $key => $value) {
            $goods_info = $goods_info->where($key, $value);
        }
        if (!empty($getvalue)) {
            return $goods_info->pluck($getvalue)->all();
        } else {
            return $goods_info->get();
        }

    }

//    /**
//     * 获取商品属性的关联
//     * @return Object
//     **/
//    static public function get_goods_specs_info($arr=[],$getvalue=''){
//        $goods_spec=DB::table('keyth_goods_spec_info');
//        foreach ($arr as $key=>$value){
//            $goods_spec=$goods_spec->where($key,$value);
//        }
//        if(!empty($getvalue)){
//            return $goods_spec->pluck($getvalue)->all();
//        }else{
//            return $goods_spec->get();
//        }
//
//    }
}
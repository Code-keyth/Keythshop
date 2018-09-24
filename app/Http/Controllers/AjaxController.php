<?php
/**
 * Created by PhpStorm.
 * User: keyth
 * Date: 18-9-17
 * Time: 下午7:58
 */

namespace App\Http\Controllers;


use App\Http\Model\Goods;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AjaxController
{

    //
    /**
     * 修改商品的状态
     * name : 0--is_on_sale  1--is_best  2--is_new   3--is_hot  6--state    7--promote_or_activity
     * (商品分类)4--sort_order  5--show_in_nav
     *
     * type : 0--Goods  1--Goods_type
     * @return bool
     **/
    public function get_up_state(Request $request)
    {
        switch ($request->get('name')) {
            case 0:
                $name = 'is_on_sale';
                break;
            case 1:
                $name = 'is_best';
                break;
            case 2:
                $name = 'is_new';
                break;
            case 3:
                $name = 'is_hot';
                break;
            case 4:
                $name = 'sort_order';
                break;
            case 5:
                $name = 'show_in_nav';
                break;
            case 6:
                $name = 'state';
                break;
            case 7:
                $name='promote_or_activity';
                break;
            default:
                $name = 'is_on_sale';
        }
        $id = $request->get('uuid');
        if ($request->get('value') == 1) {
            $value = 0;
        } else {
            $value = 1;
        }
        if ($request->type == 0) {
            if($name == 'state'){
                $ls_arr=['state'=>$value,'promote_or_activity'=>0,'is_on_sale'=>0,'is_best'=>0,'is_new'=>0,'is_hot'=>0];
                if($value==1){
                    $ls_arr['type_id']=$request->typeid;
                }
                $state = Goods::where('id', $id)->update($ls_arr);
            }else{
                $state = Goods::where('id', $id)->update([$name => $value]);
            }
        } else {
            if ($name == 'state') {
                /**
                 * 1.该分类 state 0
                 * 2.子分类 state 0
                 * 3.该分类及子分类下的商品 state 0
                 **/
                $types = DB::table('keyth_goods_type')->where('parent_id', $id)->pluck('id');
                $state = DB::table('keyth_goods_type')->where('parent_id', $id)->orWhere('id',$id)->update(['state'=>0]);
                if($state<1){
                    return $state;
                }else{
                    $types[]=$id;
                    $state = Goods::whereIn('type_id', $types)->update(['state'=>0,'promote_or_activity'=>0,'is_on_sale'=>0,'is_best'=>0,'is_new'=>0,'is_hot'=>0]);
                }
            } else {
                $state = DB::table('keyth_goods_type')->where('id', $id)->update([$name => $value]);
            }
        }
        return $state;
    }

    public function upload_file(Request $request)
    {

        $AA = '/storage/app/' . $request->file('file')->store('wangEditor');
        $datas['errno'] = 0;
        $datas['data'][] = $AA;
        return $datas;
    }

    public function post_up_activity(Request $request)
    {
        if (!empty($request->uuid)) {
            $goods = Goods::find($request->uuid);
            if ($goods->first()) {
                $goods->activity_number = $request->activity_number;
                $goods->promote_price = $request->promote_price;
                $goods->activity_start_date = strtotime($request->activity_start_date);
                $goods->activity_end_date = strtotime($request->activity_end_date);
                if ($goods->save()) {
                    return 1;
                }
            }
        }
        return 0;
    }

    public function get_type_posterity(Request $request)
    {
        if (!empty($request->id)) {
            $types = DB::table('keyth_goods_type')->where('state', 1)->where('parent_id', $request->id)->pluck('id');
            $datas[0] = count($types);
            $types[] = $request->id;
            $datas[1] = Goods::whereIn('type_id', $types)->count();
            return $datas;
        }
        return [0, 0];
    }


}
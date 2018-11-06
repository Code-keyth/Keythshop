<?php
/**
 * Created by PhpStorm.
 * User: keyth
 * Date: 10/15/18
 * Time: 10:45 AM
 */

namespace App\Http\Controllers;


use App\Http\Model\Goods;
use App\Http\Model\Member;
use App\Http\Model\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class IndexController extends Controller
{
    static function get_currency_data($request){
        $data['goods_types']=Goods::gain_goods_types();
        $data['Goods']=new Goods();
        $data['USER_logstate_value']=$request->cookie('USER_Logstate');
        if(!empty($data['USER_logstate_value'])){
            $data['logou_state']=Redis::get($data['USER_logstate_value']);
        }else{
            $cookie_state=self::GenerateCookie(time(),md5(time()));
            Cookie::queue('USER_Logstate',$cookie_state,200);
        }
        return $data;
    }

    public function index(Request $request){
        $data=self::get_currency_data($request);
        $data['new_goods']=Goods::where('is_new',1)->orderby('updated_at','desc')->limit(5)->get();
        $data['index_goods_types']=Goods::gain_goods_types(['show_in_nav'=>1]);
        return view('Index/index',$data);
    }

    public function goods_list(Request $request){
        $data=self::get_currency_data($request);
        if(!empty($request->type_id)){
            $data['goods']=Goods::where('type_id',$request->type_id)->get();
        }
        return view('Index/goods_list',$data);
    }

    public function goods_info(Request $request){
        $data=self::get_currency_data($request);
        if(!empty($request->goods_sn)){
            $data['good']=Goods::where('goods_sn',$request->goods_sn)->first();
        }
        $member=$this->is_who_member($data['USER_logstate_value']);
        $data['type_specs']=Goods::get_goods_type_specs(['type_id'=>$data['good']->type_id]);
        $data['have_specs']=Goods::get_goods_have_spec(['goods_sn'=>$data['good']->goods_sn]);
        if($member){
            $data['collect_goods']=DB::table('keyth_member_collect_goods')->where('user_id',$member->id)->pluck('goods_id');
            $data['collect_goods']= $data['collect_goods']->all();
            if(empty($data['collect_goods'])){
                $data['collect_goods']=[];
            }
        }
        return view('Index/goods_info',$data);
    }
    public function myinfo(Request $request){
        $data=self::get_currency_data($request);
        $data['member']=$this->is_who_member($data['USER_logstate_value']);
        $data['r']=$request->r?$request->r:1;
        $data['address']=DB::table('keyth_member_collect_address')->where('member_id',$data['member']->id)->get();
        $data['address']=DB::table('keyth_member_collect_address')->where('member_id',$data['member']->id)->get();
        if($data['r']==2) {
            $data['tips']=DB::table('keyth_member_tips')->where('member_id',$data['member']->id)->paginate(4);
        }

        if($data['r']==3){
            $data['accounts']=DB::table('keyth_member_account')->where('member_id',$data['member']->id)->orderBy('change_time','desc')->paginate(9);
        }
        if($data['r']==4){
            $collect_goods_id=DB::table('keyth_member_collect_goods')->where('user_id',$data['member']->id)->pluck('goods_id');
            $collect_goods_id=$collect_goods_id->all();
            $data['collect_goods']=Goods::whereIn('id',$collect_goods_id)->get();
        }
        return view('Index/myinfo',$data);
    }

    public function myorder(Request $request){
        $data=self::get_currency_data($request);
        $data['member']=$this->is_who_member($data['USER_logstate_value']);
        $data['r']=$request->r?$request->r:6;
        $data['address']=DB::table('keyth_member_collect_address')->where('member_id',$data['member']->id)->orderBy('id','desc')->get();
        $order_number=Order::where('user_id',$data['member']->id)->count();
        $no_confirms=Redis::get(md5($data['member']->id.$order_number));
        $data['no_confirms']=[];
        if(!empty($request->pay_oreder)){
            $order_pay=Order::where('order_sn',$request->pay_oreder)->first();
            if(empty($order_pay->pay_log_sn) && $order_pay->order_status<2){
                $data['pay_oreder']=$request->pay_oreder;
            }

        }
        if(!empty($no_confirms)){
            $data['no_confirms']=json_decode($no_confirms,true);
        }
        $data['orders']=Order::where('user_id',$data['member']->id)->orderBy('id','desc')->paginate(6);

        foreach ($data['orders'] as $item){
            if( empty($item->pay_log_sn) && (strtotime($item->created_at) < time()-1200)){
                $item->order_status=2;
                $item->save();
                DB::table('keyth_order_operation')->where('order_sn',$item->order_sn)->update(['cancel_time'=>-1]);
            }
        }

        return view('Index/myorder',$data);
    }
    public function myorder_info(Request $request){
        $data=self::get_currency_data($request);
        $data['catr_infos']=Order::get_order_goods($request->order_sn);
        $data['order']=Order::where('order_sn',$request->order_sn)->first();
        $data['operation']=DB::table('keyth_order_operation')->where('order_sn',$request->order_sn)->first();

        return view('Index/myorder_info',$data);
    }

    public function mycart(Request $request){
        $data=self::get_currency_data($request);
        $admin=$this->is_who_member($data['USER_logstate_value']);
        //如果登录key=id
        //如果未登录key=;
        if($admin){
            $key=md5($admin->id);
        }else{
            $key=md5($data['USER_logstate_value']);
        }
        $key2=md5($data['USER_logstate_value']);
        $catr_info=Redis::get($key);
        $catr_info2=Redis::get($key2);
        $catr_info=json_decode($catr_info,true);
        $catr_info2=json_decode($catr_info2,true);
        if(empty($catr_info2['total_number'])){
            $catr_info2['total_number']=0;}
        if(empty($catr_info['total_number'])){
            $catr_info['total_number']=0;}

        $catr_info['total_number']+=$catr_info2['total_number'];
        if(empty($catr_info['info'])){
            $catr_info['info']=[];}
        if(empty($catr_info2['info'])){
            $catr_info2['info']=[];
        }else{
            Redis::del($key2);
        }
        $catr_info['info']=array_merge($catr_info['info'],$catr_info2['info']);
        if(!empty($catr_info)){
            $data['goods_infos']=$catr_info;
            Redis::set($key,json_encode($catr_info));
        }else{
            $data['goods_infos']=['info'=>[],'total_number'=>0];
        }
        return view('Index/mycart',$data);
    }
    /**
     * 确认订单
     * @return view
     * 购物车中商品的Redis信息 key=md5($member->id)
     * 在界面生成一个Redis订单缓存 时间为十五分钟，key=md5($member->id.$order_number)
        order_data=[
     *      'order_sn':char(40),
            'goods_infos':[],
            'goods_number':int,
            'goods_total_price':int]
    **/
    public function settlement(Request $request){
        $data=self::get_currency_data($request);
        $infos=$request->id;
        $infos=explode('-',$infos);
        $member=$this->is_who_member($data['USER_logstate_value']);
        array_pop($infos);
        $md5_key=md5($member->id);
        $catr_info=Redis::get($md5_key);
        $catr_info=json_decode($catr_info,true);
        if(empty($catr_info['info'])){
            return $this->alertjs('购物车中没有商品！','mycart');
        }
        $data['goods_number']=0;
        $data['goods_total_price']=0;
        foreach ($infos as $key=>$item){
            $data['catr_infos'][]=$catr_info['info'][$key];
            $data['goods_number']+=$catr_info['info'][$key]['number'];
            $data['goods_total_price']+=$catr_info['info'][$key]['number']*$catr_info['info'][$key]['good_price'];
            unset($catr_info['info'][$key]);
            $catr_info['total_number']--;
        }
        DB::beginTransaction();
        $USER_logstate_value = $request->cookie('USER_Logstate');
        $member=Member::where('cookie_state',$USER_logstate_value)->first();

        $Order=new Order();
        $Order->order_sn=$Order::build_Order_sn();
        $Order->user_id=$member->id;
        $Order->order_status=0;
        $Order->logistic_id=$request->logistic_id;
        $Order->pay_id=$request->pay_id;
        $Order->add_time=time();
        $Order->confirm_time=time();
        $Order->order_amount=$data['goods_total_price'];
        $order=$Order->save();
        foreach ($data['catr_infos'] as $item){
            $data2['order_id']=$Order->id;
            $data2['order_sn']=$Order->order_sn;
            $data2['goods_price']=$item['good_price'];
            $data2['number']=$item['number'];
            $data2['market_price']=$item['number']*$item['good_price'];
            $data2['goods_name']=$item['goods_name'];
            $data2['goods_sn']=$item['goods_sn'];
            $data2['spec_info_attr']=$item['spec_info_attr'];
            $state=DB::table('keyth_order_goods')->insert($data2);
        }

        $data3['order_sn']=$Order->order_sn;
        $data3['order_time']=time();
        $data3['confirm_time']=time();
        $data3['confirm_person']=$member->id;
        $state2=DB::table('keyth_order_operation')->insert($data3);
        $data['address']=DB::table('keyth_member_collect_address')->where('member_id',$member->id)->get();
        $data['shop_logistics']=DB::table('keyth_shop_logistic')->where('state',1)->get();
        $data['shop_pays']=DB::table('keyth_shop_pay')->where('state',1)->get();
        if ($order && $member && $state && $state2) {
            DB::commit();
        } else {
            DB::rollback();
        }
        $data['order_sn']=$Order->order_sn;
        Redis::set($md5_key,json_encode($catr_info));
        return view('Index/settlement',$data);
    }
    /**
     * 地址管理
     * 添加修改地址
     * @return view
    **/
    public function myinfo_address(Request $request){
        $data=self::get_currency_data($request);
        if(!empty($request->address_id)){
            $data['address']=DB::table('keyth_member_collect_address')->where('id',$request->address_id)->get()->map(function ($value) {
                return (array)$value;
            })->toArray();;
            $data['address']=$data['address'][0];
        }else{
            $data['address']['consignee']='';
            $data['address']['address']='';
            $data['address']['zipcode']='';
            $data['address']['tel']='';
            $data['address']['mobile']='';
            $data['address']['is_default']=0;
            $data['address']['id']='';
        }
        return view('Index/myinfo_address',$data);
    }



}
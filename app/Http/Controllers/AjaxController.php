<?php
/**
 * Created by PhpStorm.
 * User: keyth
 * Date: 18-9-17
 * Time: 下午7:58
 */

namespace App\Http\Controllers;


use App\Http\Model\Admin;
use App\Http\Model\Article;
use App\Http\Model\Goods;
use App\Http\Model\Member;
use App\Http\Model\Order;
use Illuminate\Filesystem\Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redis;


class AjaxController extends Controller
{

    //
    /**
     * 修改的状态
     * name : 0--is_on_sale  1--is_best  2--is_new   3--is_hot  6--state    7--promote_or_activity
     * (商品分类)4--sort_order  5--show_in_nav
     * type : 0--Goods  1--Goods_type   2--Article  3--Article_type 4--member   5--admin_user 6--shop_pay  7--shop_logistics
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
                $name = 'promote_or_activity';
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
            if ($name == 'state') {
                $ls_arr = ['state' => $value, 'promote_or_activity' => 0, 'is_on_sale' => 0, 'is_best' => 0, 'is_new' => 0, 'is_hot' => 0];
                if ($value == 1) {
                    $ls_arr['type_id'] = $request->typeid;
                }
                $state = Goods::where('id', $id)->update($ls_arr);
            } else {
                $state = Goods::where('id', $id)->update([$name => $value]);
            }
        } else if ($request->type == 1) {
            if ($name == 'state') {
                /**
                 * 1.该分类 state 0
                 * 2.子分类 state 0
                 * 3.该分类及子分类下的商品 state 0
                 **/
                $types = DB::table('keyth_goods_type')->where('parent_id', $id)->pluck('id');
                $state = DB::table('keyth_goods_type')->where('parent_id', $id)->orWhere('id', $id)->update(['state' => 0]);
                if ($state < 1) {
                    return $state;
                } else {
                    $types[] = $id;
                    $state = Goods::whereIn('type_id', $types)->update(['state' => 0, 'promote_or_activity' => 0, 'is_on_sale' => 0, 'is_best' => 0, 'is_new' => 0, 'is_hot' => 0]);
                }
            } else {
                $state = DB::table('keyth_goods_type')->where('id', $id)->update([$name => $value]);
            }
        } else if ($request->type == 2) {
            $state = Article::where('id', $id)->update([$name => $value]);
        } else if ($request->type == 3) {
            $state = DB::table('keyth_article_type')->where('id', $id)->update([$name => $value]);
            $article = Article::where('article_type_id', $id)->get();
            foreach ($article as $item) {
                $item->state = 0;
                $item->save();
            }
        } else if ($request->type == 4) {
            $state = Member::where('id', $id)->update([$name => $value]);
        } else if ($request->type == 5) {
            $state = Admin::where('id', $id)->update([$name => $value]);
        } else if ($request->type == 6) {
            $state = DB::table('keyth_shop_pay')->where('id', $id)->update([$name => $value]);
        } else if ($request->type == 7) {
            $state = DB::table('keyth_shop_logistic')->where('id', $id)->update([$name => $value]);
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

    public function add_article_type(Request $request)
    {
        $datas['type_title'] = $request->type_title;
        $datas['bried'] = $request->bried;
        if (!empty($request->id)) {
            $state = DB::table('keyth_article_type')->where('id', $request->id)->update($datas);
        } else {
            $state = DB::table('keyth_article_type')->insert($datas);
        }
        if ($state) {
            return 1;
        }
        return 0;
    }

    /**
     * 确认订单时检查订单支付状态
     *  name：1 确认订单  2 发货
     * @return int 0--未支付 -1--保存失败  1--成功
     **/

    public function get_up_order_state(Request $request)
    {
        $request->id;
        $request->name;
        if (!Order::inspect_order_state($request->id)) {
            return 0;
        }
        $pay_log_data = [];
        $admin = $this->is_who_admin($request);
        if (!$admin) {
            return -1;
        }
        switch ($request->name) {
            case 1:
                $order = Order::find($request->id);
                $order->order_status = $request->value;
                $order->confirm_time = time();
                $pay_log_data['confirm_person'] = $admin->id;
                $pay_log_data['confirm_time'] = time();
                break;
            case 2:
                $order = Order::find($request->id);
                $order->logistic_status = $request->value;
                $order->shipping_time = time();
                $state = $order->save();
                if (!$state) {
                    return -1;
                }
                $pay_log_data['shipping_person'] = $admin->id;
                $pay_log_data['shipping_time'] = time();
                break;
            default;
        }

        $state = DB::table('keyth_order_operation')->where('order_id', $order->id)->update($pay_log_data);
        if (!$state) {
            return -1;
        }
        $state = $order->save();
        if (!$state) {
            return -1;
        }
        return 1;
    }

    //添加角色及权限
    public function post_add_role(Request $request)
    {
        $datas['role_name'] = $request->role_name;
        if (!empty($request->uuid)) {
            $state = DB::table('keyth_admin_role')->where('id', $request->uuid)->update($datas);
        } else {
            if (DB::table('keyth_admin_role')->where('role_name', $datas['role_name'])->first()) {
                $state = 0;
            } else {
                $MAX_role_action = DB::table('keyth_admin_role')->max('role_action');
                $datas['role_action'] = $MAX_role_action + 100;
                DB::table('keyth_admin_role')->insert($datas);
                $state = 1;
            }
        }
        return $state;

    }

    public function post_power_add(Request $request)
    {
        $datas['action'] = $request->action;
        $datas['action_desc'] = $request->action_desc;
        $datas['parent_id'] = $request->parent_id;
        if (!empty($request->id)) {
            $state = DB::table('keyth_admin_action')->where('id', $request->id)->update($datas);
        } else {
            $power_code = DB::table('keyth_admin_action')->where('parent_id', $request->parent_id)->max('power_code');
            $datas['power_code'] = $power_code + 1;
            $state = DB::table('keyth_admin_action')->insert($datas);
        }
        return $state;
    }

    //权限设置，极其重要，必须做权限限制
    public function post_admin_role(Request $request)
    {

        $admin = Admin::find($request->uuid);
        $state = 0;
        $roles = DB::table('keyth_admin_role')->pluck('id');
        if ($admin) {
            $admin->role_id = '';
            $cc = explode(',', $request->roles);

            foreach ($cc as $item) {
                if (in_array($item, $roles->all())) {
                    $admin->role_id .= $item . ',';
                }
            }
        }
        $admin->role_id = substr($admin->role_id, 0, strlen($admin->role_id) - 1);
        if ($admin->save()) {
            $state = 1;
        }
        return $state;
    }

    //保存商品的属性
    public function get_save_goods_spec(Request $request)
    {
        if (!empty($request->id)) {
            $data['goods_id'] = $request->id;
        }
        $data['spec_name'] = $request->spec_name;
        $data['spec_id'] = $request->spec_id;
        $data['_token'] = $request->_token;
        if (is_null($data['spec_name']) || is_null($data['spec_id']) || is_null($data['_token'])) {
            return 0;
        }
        $state = DB::table('keyth_goods_have_spec')->insert($data);
        if ($state) {
            return 1;
        } else {
            return 0;
        }
    }

    public function get_change_type_gain_spec(Request $request)
    {
        $data['goods_specs'] = Goods::get_goods_type_specs(['type_id' => $request->type_id]);
        foreach ($data['goods_specs'] as $item) {
            $data['attrs'][] = Goods::get_goods_spec_attr($item->spec_id);
        }

        return $data;
    }
    public function get_select_goods_info(Request $request)
    {
        $array_attr = [];
        foreach ($request->arrts as $item) {
            $str = $item;
            foreach ($request->arrts as $item2) {
                if ($item != $item2) {
                    $str .= ',' . $item2;
                    if (count($request->arrts) > 2) {
                        foreach ($request->arrts as $item3) {
                            if ($item3 != $item2 && $item3 != $item) {
                                $str .= ',' . $item3;
                            }
                        }
                    }
                    $array_attr[] = $str;
                    $str = $item;
                }
            }
        }
        if (count($request->arrts) == 1) {
            $array_attr = $request->arrts;
        }
        $data['code'] = 0;
        $goods_info = DB::table('keyth_goods_spec_info')->where('goods_sn', $request->goods_sn)->whereIn('attr_value', $array_attr)->first();
        if ($goods_info) {
            $data = ['code' => 1, 'info_number' => $goods_info->id, 'attr_value' => $goods_info->attr_value, 'goods_number' => $goods_info->goods_number, 'goods_price' => $goods_info->goods_price];
        }
        return $data;
    }
        //发货
    public function order_goto(Request $request){
        if(!empty($request->order_sn)){
            $admin=$this->is_who_admin($request);
            $order=Order::where('order_sn',$request->order_sn)->first();
            if(empty($order->pay_log_sn)||$order->order_status!=1){
                return -1;
            }
            $order->shipping_time=time();
            $order->logistic_status=1;
            $order->invoice_no=Order::build_Order_pay_sn();
            $order->save();
            DB::table('keyth_order_operation')->where('order_sn',$request->order_sn)->update(['shipping_time'=>time(),'shipping_person'=>$admin->id]);
            DB::table('keyth_member_tips')->insert(['member_id'=>$order->user_id,'order_sn'=>$order->order_sn,'type'=>1,'desc'=>'您的订单已经发货了！','updated_at'=>time()]);
            return 1;
        }
        return 0;
    }



    public function sendemail(Request $request)
    {
        if (is_null($request->email)) {
            return 0;
        }
        $rand_number = rand(100000, 999999);
        $msg = "尊敬的用户您好！<br/>您的注册验证码是:" . $rand_number . "。如果不是您本人操作,请您忽略本邮件！";
        $Redis = NEW Redis();
        if ($Redis::get($request->email)) {
            return -1;
        }
        $Redis::set($request->email, $rand_number);
        $Redis::expire($request->email, 360);
        $email = $request->email;
        Mail::send('Email/Index', ['msg' => $msg], function ($msg) use ($email) {
            $msg->subject('商城注册验证码');
            $msg->to($email);
        });
        return 1;

    }
    /**
     * 添加购物车
     * 使用Redis 键名为 md5(cookie[USER_Logstate])
     * @return int
     * -1 查询数据错误！
     *
     **/
//    $mycart_info=[
//        'total_number'=>5,
//        'total_price'=>8995,
//        'info'=>[
//            ['goods_sn'=>'2918728197','spec_info_id'=>5,'spec_info_attr'=>'红色,6G,64G','good_price'=>1899,'number'=>2],
//            ['goods_sn'=>'2918728197','spec_info_id'=>5,'spec_info_attr'=>'红色,4G,64G','good_price'=>1799,'number'=>1],
//            ['goods_sn'=>'2918728197','spec_info_id'=>5,'spec_info_attr'=>'红色,2G,64G','good_price'=>1699,'number'=>2],
//        ]
//    ];


    public function addcart_good(Request $request)
    {
        if (empty($request->goods_sn) || empty($request->info_number || empty($request->buy_number))) {
            return -1;
        }
        if ($request->info_number != 'no') {
            $goods_attr_info = Goods::get_goods_attr_info(['id' => $request->info_number])->first();
        }else{
            $good=Goods::where('goods_sn',$request->goods_sn)->first();
        }
        $USER_logstate_value = $request->cookie('USER_Logstate');

        $log_state=Redis::get($USER_logstate_value);
        //md5($USER_logstate_value) 未登录时使用的购物车key
        $md5_logstate = md5($USER_logstate_value);
        $red_catr_info2=[];
        if(!empty($log_state)){
            $admin=Member::where('cookie_state',$USER_logstate_value)->first();
            $red_catr_info2 = Redis::get($md5_logstate);
            $red_catr_info2 =  json_decode($red_catr_info2,true);
            if(empty($red_catr_info2['info'])){
                $red_catr_info2['info']=[];
            }
            $md5_logstate=md5($admin->id);
        }

        $red_catr_info = Redis::get($md5_logstate);
        $mycart_info = json_decode($red_catr_info, true);
        if (empty($red_catr_info)) {
            $mycart_info['total_number'] = 0;
            $mycart_info['info'] = [];
        }
        if(empty($mycart_info['info'])){
            $mycart_info['info']=[];
        }
        if(empty($red_catr_info2['info'])){
            $red_catr_info2['info']=[];
        }
        $mycart_info['total_number'] += $request->buy_number;
        $mycart_info['info']=
            array_merge($mycart_info['info'],
            $red_catr_info2['info']);
        for ($i=0;$i<count($mycart_info['info']);$i++) {
            if ($request->info_number != 'no') {
                if ($goods_attr_info->id == $mycart_info['info'][$i]['spec_info_id'] && $mycart_info['info'][$i]['good_price'] == $goods_attr_info->goods_price) {
                    $mycart_info['info'][$i]['number'] += $request->buy_number;
                    $state = 1;
                }
            } else {
                if ($request->goods_sn == $mycart_info['info'][$i]['goods_sn']) {
                    $mycart_info['info'][$i]['number'] += $request->buy_number;
                    $state = 1;
                }
            }
        }
        if (empty($state)) {
            if ($request->info_number != 'no') {
                $mycart_info['info'][] = [
                    'goods_sn' => $goods_attr_info->goods_sn,
                    'goods_name' => $request->goods_name,
                    'spec_info_id' => $goods_attr_info->id,
                    'spec_info_attr' => $goods_attr_info->attr_value,
                    'good_price' => $goods_attr_info->goods_price,
                    'number' => $request->buy_number
                ];
            }else{
                $mycart_info['info'][] = [
                    'goods_sn' => $request->goods_sn,
                    'goods_name' => $request->goods_name,
                    'number' => $request->buy_number,
                    'spec_info_id' =>'',
                    'spec_info_attr' => '',
                    'good_price' => $good->price,
                ];
            }
        }
        Redis::set($md5_logstate, json_encode($mycart_info));
        if(empty($log_state)){
            Redis::expire($md5_logstate, 60*60*24);
        }
        return 1;
    }
    public function myinfo_address(Request $request){
        $postdata=$request->post();
        $data['consignee']=$postdata['consignee'];
        $data['province']=$postdata['province'];
        $USER_logstate_value=$request->cookie('USER_Logstate');
        $admin=$this->is_who_member($USER_logstate_value);
        $data['member_id']=$admin->id;
        $data['city']=$postdata['city'];
        $data['county']=$postdata['county'];
        $data['address']=$postdata['address'];
        $data['zipcode']=$postdata['zipcode'];
        $data['tel']=$postdata['tel'];
        $data['mobile']=$postdata['mobile'];
        if(!empty($postdata['is_default'])){
            $data['is_default']=1;
            DB::table('keyth_member_collect_address')->where('member_id',$admin->id)->where('is_default',1)->update(['is_default'=>0]);
        }
        if(!empty($postdata['address_id'])){
            $state=DB::table('keyth_member_collect_address')->where('id',$postdata['address_id'])->update($data);
        }else{
            $state=DB::table('keyth_member_collect_address')->insert($data);
        }
        if($state)
            return 1;
        return 0;
    }
    /**
     * 用户确定下单
    **/
    public function member_confirm_order(Request $request){
        if(!empty($request->order_sn)){
            $member=Member::where('cookie_state',$request->cookie('USER_Logstate'))->first();
            $address=DB::table('keyth_member_collect_address')->where('member_id',$member->id)->where('id',$request->address_id)->first();
            $Order=Order::where('order_sn',$request->order_sn)->first();
            $Order->order_status=1;
            $Order->consignee=$address->consignee;
            $Order->province=$address->province;
            $Order->city=$address->city;
            $Order->county=$address->county;
            $Order->address=$address->address;
            $Order->pay_id=$request->pay_id;
            $Order->logistic_id=$request->logistic_id;
            $Order->zipcode=$address->zipcode;
            $Order->tel=$address->tel;
            $Order->mobile=$address->mobile;
            $Order->save();
            return $Order->order_sn;
        }
        return 0;

    }

    public function pay_order(Request $request){

        $member=$this->is_who_member($request->cookie('USER_Logstate'));
        $order=Order::where('order_sn',$request->order_sn)->first();
        $member->user_money=$member->user_money - $order->order_amount;
        if($member->user_money<0){
            return -1;
        }
        if(!empty($order->pay_log_sn)){
            return 2;
            //订单已经支付
        }
        DB::beginTransaction();
        $state=$member->save();

        $pay_log=['order_pay_sn'=>Order::build_Order_pay_sn(),
            'pay_id'=>$order->pay_id,
            'pay_price'=>$order->order_amount,
            'pay_state'=>1,
            'pay_should_price'=>$order->order_amount,
            'pay_time'=>time()
        ];
        $pay_account['member_id']=$member->id;
        $pay_account['user_money']=$member->user_money;
        $pay_account['change_time']=time();
        $pay_account['change_desc']='支付订单：'.$request->order_sn.'[-'.$order->order_amount.'元]';
        $pay_account['change_type']=1;
        $state1=DB::table('keyth_order_pay_log')->insert($pay_log);
        $state2=DB::table('keyth_member_account')->insert($pay_account);
        $order->order_status=1;
        $order->pay_log_sn=$pay_log['order_pay_sn'];
        $order->order_amount=$pay_log['pay_should_price'];
        $order->pay_time=time();
        $order->save();
        $state3=DB::table('keyth_order_operation')->where('order_sn',$request->order_sn)->update(['pay_time'=>time(),'pay_person'=>$member->id]);
        if($state3 && $state1 && $state2 && $state){
            DB::commit();
        } else {
            DB::rollback();
            return 0;
        }
        return 1;
    }
    //取消订单
    public function cancel_order(Request $request){
        $member=$this->is_who_member($request->cookie('USER_Logstate'));
        $order=Order::where('order_sn',$request->order_sn)->first();
        if($order->logistic_status != 0 || empty($order->pay_log_sn)){
            return 0;
        }
        DB::beginTransaction();
        $order->order_status=2;
        $order->pay_log_sn=0;
        $state1=$order->save();
        $member->user_money=$member->user_money + $order->order_amount;
        $state2=$member->save();
        $pay_account['member_id']=$member->id;
        $pay_account['user_money']=$member->user_money;
        $pay_account['change_time']=time();
        $pay_account['change_desc']='取消订单：'.$order->order_sn.'[+'.$order->order_amount.'元]';
        $pay_account['change_type']=2;
        $state4=DB::table('keyth_member_account')->insert($pay_account);
        $state3=DB::table('keyth_order_operation')->where('order_sn',$order->order_sn)->update(['cancel_time'=>time(),'cancel_person'=>0]);
        if($state3 && $state1 && $state2 && $state4){
            DB::commit();
        } else {
            DB::rollback();
            return 2;
        }
        return 1;
    }
    public function confirm_collect(Request $request){
        if (!empty($request->order_sn)){
            $state2=Order::where('order_sn',$request->order_sn)->update(['logistic_status'=>3]);
            $state3=DB::table('keyth_order_operation')->where('order_sn',$request->order_sn)->update(['collect_time'=>time(),'collect_person'=>0]);
            if($state2 && $state3){
                return 1;
            }
            return 0;
        }
        return -1;
    }
    public function goods_collect(Request $request){
        $member=$this->is_who_member($request->cookie('USER_Logstate'));
        $goods_id=$request->goods_id;
        $collect_goods=DB::table('keyth_member_collect_goods')->where(['user_id'=>$member->id,'goods_id'=>$goods_id])->first();
        if($collect_goods){
            $state=DB::table('keyth_member_collect_goods')->where(['user_id'=>$member->id,'goods_id'=>$goods_id])->delete();
        }else{
            $state=DB::table('keyth_member_collect_goods')->insert(['user_id'=>$member->id,'goods_id'=>$goods_id,'add_time'=>time()]);
        }

        if($state){
            return 1;
        }
    }

    public function get_echarts(Request $request){
        if($request->type==1){
            $collect_goods=Goods::get();
        }elseif($request->type==2) {
            $collect_goods=Order::get();
        }else{
            $collect_goods=Member::get();
        }
        $data['day']=[0,0,0,0,0,0,0];
        $data['total_day']=[0,0,0,0,0,0,0];
        foreach ($collect_goods as $item){
            if(strtotime($item->created_at) < time() - 60*60*24*7){
                if(strtotime($item->created_at)>time()-60*60*24*8){
                    $data['day'][0]++;
                }
                $data['total_day'][0]++;
            }
            if(strtotime($item->created_at)<time()-60*60*24*6){
                if(strtotime($item->created_at)>time()-60*60*24*7){
                    $data['day'][1]++;
                }
                $data['total_day'][1]++;
            }
            if(strtotime($item->created_at)<time()-60*60*24*5){
                if(strtotime($item->created_at)>time()-60*60*24*6){
                    $data['day'][2]++;
                }
                $data['total_day'][2]++;
            }
            if(strtotime($item->created_at)<time()-60*60*24*4){
                if(strtotime($item->created_at)>time()-60*60*24*5){
                    $data['day'][3]++;
                }
                $data['total_day'][3]++;
            }
            if(strtotime($item->created_at)<time()-60*60*24*3){
                if(strtotime($item->created_at)>time()-60*60*24*4){
                    $data['day'][4]++;
                }
                $data['total_day'][4]++;
            }
            if(strtotime($item->created_at)<time()-60*60*24*2){
                if(strtotime($item->created_at)>time()-60*60*24*3){
                    $data['day'][5]++;
                }
                $data['total_day'][5]++;
            }
            if(strtotime($item->created_at)<time()-60*60*24*1){
                if(strtotime($item->created_at)>time()-60*60*24*2){
                    $data['day'][6]++;
                }
                $data['total_day'][6]++;
            }
        }
        return $data;
    }




}
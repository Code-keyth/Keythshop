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

class Order extends Model
{
    protected $table='keyth_order';
    protected $dateFormat = 'U';


    /**
     * 生成40位订单号
     * @return string
     **/
    static function build_Order_sn()
    {
        return date('Ymdhis', time()) . (rand(1000000, 9999999)). (rand(1000000, 9999999));
    }
    /**
     * 生成33位订单支付号
     * @return string
     **/
    static function build_Order_pay_sn()
    {
        return date('Ymdhis', time()) . (rand(1000000, 9999999));
    }
    //获取订单的支付方式
    public function getPay($id){
        $pay=DB::table('keyth_shop_pay')->where('state',1)->where('id',$id)->first();
        if(empty($pay)){
            return '无';
        }
        return $pay->pay_name;
    }
    //获取订单的配送方式
    public function getLogistic($id){
        $shopping=DB::table('keyth_shop_logistic')->where('state',1)->where('id',$id)->first();
        if(empty($shopping)){
            return '无';
        }

        return $shopping->shopping_name;
    }
    //检查订单支付状态

    static function inspect_order_state($order_id){
        $order=self::find($order_id);
        $orderpaylog=DB::table('keyth_order_pay_log')->where('order_pay_sn',$order->id)->first();
        //^_^ 支付金额大于订单金额予以通过按正常订单处理哇哈哈哈哈哈哈
        if($orderpaylog && $orderpaylog->pay_price >= $orderpaylog->pay_should_price){
            return true;
        }
        return false;
    }
    public static function get_order_goods($order_sn){
        $goods=DB::table('keyth_order_goods')->where('order_sn',$order_sn)->get();
        return $goods;
    }




}
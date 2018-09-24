<?php
/**
 * Created by PhpStorm.
 * User: keyth
 * Date: 18-9-17
 * Time: 下午3:14
 */

namespace App\Http\Controllers;


use App\Http\Model\Goods;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class AdminController extends Controller
{
    public function index()
    {
        return view('Admin/Index');
    }

    public function welcome()
    {
        return view('Admin/welcome');
    }

    /**
     * state  sort_order
     * 添加商品校验码，不通过的不予显示
     **/
    public function goods()
    {
        $Goodss = Goods::where('state', 1)->orderby('sort_order', 'asc')->orderby('created_at', 'asc')->paginate();
        $proofs = Goods::goods_proof();
        $aa = $Goodss->all();
        foreach ($aa as $item) {
            if (!in_array($item->goods_proof, $proofs)) {
                $item->delete();
            }
        }
        $datas['goodss'] = $Goodss;

        return view('Admin/goods_list', $datas);
    }

    public function goods_add(Request $request)
    {

        if (empty($request->id)) {
            $Goods = new Goods();
            $Goods->type_id = '';
            $Goods->goods_sn = Goods::build_goods_sn();
            $Goods->goods_name = '';
            $Goods->click_count = 0;
            $Goods->goods_number = '';
            $Goods->goods_weight = '';
            $Goods->goods_unit = '';
            $Goods->market_price = '';
            $Goods->price = '';
            $Goods->promote_or_activity = '';
            $Goods->promote_price = '';
            $Goods->promote_start_date = '';
            $Goods->promote_end_date = '';
            $Goods->activity_number = '';
            $Goods->keyword = '';
            $Goods->goods_brief = '';
            $Goods->goods_desc = '';
            $Goods->goods_thumb = '';
            $Goods->is_on_sale = 1;
            $Goods->integral = 0;
            $Goods->sort_order = 9999;
            $Goods->is_best = 0;
            $Goods->is_new =1;
            $Goods->is_hot =0;
            $Goods->seller_note = '';
        } else {
            $Goods = Goods::find($request->id);
            $Goods->promote_start_date = date('Y-m-d H:i:s',$Goods->promote_start_date);
            $Goods->promote_end_date =  date('Y-m-d H:i:s',$Goods->promote_end_date);
        }
        $data['Goods'] = $Goods;
        $data['Goods_types'] = $Goods::gain_goods_types();
        return view('Admin/goods_add', $data);
    }

    public function goods_add_c(Request $request)
    {
        $postdata = $request->post();
        if (empty($request->id)) {
            $Goods = new Goods();
            $Goods->goods_proof=$Goods::save_goods_proof();
        } else {
            $Goods = Goods::find($request->id);
        }
        $Goods->type_id = $postdata['type_id'];
        $Goods->goods_sn = $postdata['goods_sn'];
        $Goods->goods_name = $postdata['goods_name'];
        $Goods->click_count = $postdata['click_count'];
        $Goods->goods_number = $postdata['goods_number'];
        $Goods->goods_weight = $postdata['goods_weight'];
        $Goods->goods_unit = $postdata['goods_unit'];
        if(empty($postdata['market_price'])){$postdata['market_price']=$postdata['price'];}
        $Goods->market_price =$postdata['market_price'];
        $Goods->price = $postdata['price'];
        $Goods->promote_or_activity = $postdata['promote_or_activity'];
        $Goods->promote_price =$postdata['promote_price'];
        $Goods->promote_start_date = strtotime($postdata['promote_start_date']);
        $Goods->promote_end_date =  strtotime($postdata['promote_end_date']);
        $Goods->activity_number = $postdata['activity_number'];
        $Goods->keyword = $postdata['keyword'];
        $Goods->goods_brief = $postdata['goods_brief'];
        $Goods->goods_desc = $postdata['goods_desc'];
        if($request->has('goods_thumb')){
            $Goods->goods_thumb='/storage/app/'.$request->file('goods_thumb')->store('thumbnail');
        }
        $Goods->integral = $postdata['integral'];
        $Goods->sort_order = $postdata['sort_order'];
        if (!empty($postdata['is_on_sale'])) {
            $Goods->is_on_sale = 1;
        }
        if (!empty($postdata['is_best'])) {
            $Goods->is_best = 1;
        }
        if (!empty($postdata['is_new'])) {
            $Goods->is_new = 1;
        }
        if (!empty($postdata['is_hot'])) {
            $Goods->is_hot = 1;
        }
        $Goods->seller_note = $postdata['seller_note'];
        if ($Goods->save()) {
            return $this->alertjs('提交成功！','/admin/goods');
        }
        return $this->alertjs('提交失败,请稍候重试！','/admin/goods');
    }

    public function goods_type()
    {
        $datas['goods_types']=Goods::gain_goods_types();
        return view('Admin/goods_type',$datas);
    }

    public function goods_type_add(Request $request)
    {
        if(!empty($request->id)){
            $keyth_goods_type=Goods::gain_goods_types(['id'=>$request->id]);
            $data['id']=$keyth_goods_type[0]->id;
            $data['type_name']=$keyth_goods_type[0]->type_name;
            $data['type_desc']=$keyth_goods_type[0]->type_desc;
            $data['parent_id']=$keyth_goods_type[0]->parent_id;
            $data['sort_order']=$keyth_goods_type[0]->sort_order;
            $data['show_in_nav']=$keyth_goods_type[0]->show_in_nav;
        }else{
            $data['id']='';
            $data['type_name']='';
            $data['type_desc']='';
            $data['parent_id']=0;
            $data['sort_order']=9999;
            $data['show_in_nav']=0;
        }
        $datas['type']=$data;
        $datas['goods_types']=Goods::gain_goods_types(['parent_id'=>0]);
        return view('Admin/goods_type_add',$datas);
    }
    public function goods_type_add_c(Request $request){
        $datas['type']=$request->post();
        $data['type_name']=$datas['type']['type_name'];
        $data['type_desc']=$datas['type']['type_desc'];
        $data['parent_id']=$datas['type']['parent_id'];
        $data['sort_order']=$datas['type']['sort_order'];
        if($request->id){
            $state=DB::table('keyth_goods_type')->where('id',$request->id)->update($data);
        }else{
            $state=DB::table('keyth_goods_type')->insert($data);
        }
        if($state){
            return 1;
        }
        return 0;
    }
    public function goods_activity(){
        $datas['goods_activitys']=Goods::where('promote_or_activity','>',0)->where('state',1)->paginate();
        return view('Admin/goods_activity',$datas);
    }
    public function goods_recycle(){
        $datas['goodss']=Goods::where('state',0)->paginate();
        $datas['goods_types']=Goods::gain_goods_types();
        return view('Admin/goods_recycle',$datas);
    }


}
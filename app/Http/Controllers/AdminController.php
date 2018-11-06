<?php
/**
 * Created by PhpStorm.
 * User: keyth
 * Date: 18-9-17
 * Time: 下午3:14
 */

namespace App\Http\Controllers;


use App\Http\Model\Admin;
use App\Http\Model\Article;
use App\Http\Model\Goods;
use App\Http\Model\Member;
use App\Http\Model\Order;
use App\Http\Model\Other;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Object_;


class AdminController extends Controller
{
    public function index(Request $request)
    {
        $datas = [];
        $admin = $this->is_who_admin($request);
        $datas['user']['username'] = $admin->username;
        $role_ids = (explode(',', $admin->role_id));
        foreach ($role_ids as $item) {
            $datas['roles'][] = DB::table('keyth_admin_role')->where('id', $item)->value('role_name');
        }
        return view('Admin/Index', $datas);
    }

    public function welcome(Request $request)
    {
        $admin = $this->is_who_admin($request);
        $datas['username'] = $admin->username;
        $datas['goods_number']=Goods::count();
        $datas['goods_type_number']=Goods::count();
        $datas['order_number']=Order::count();
        $datas['member_number']=Member::count();
        $datas['admin_number']=Admin::count();
        $datas['article_number']=Article::count();


        return view('Admin/welcome',$datas);
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

    /**
     * 添加商品视图
     * @return view
     **/
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
            $Goods->is_new = 1;
            $Goods->is_hot = 0;
            $Goods->seller_note = '';
        } else {
            $Goods = Goods::find($request->id);
            $Goods->promote_start_date = date('Y-m-d H:i:s', $Goods->promote_start_date);
            $Goods->promote_end_date = date('Y-m-d H:i:s', $Goods->promote_end_date);
        }
        $data['goods_specs'] = $Goods::get_goods_type_specs(['type_id'=>$Goods->type_id]);
        $data['goods_specs2'] = $Goods::get_goods_type_specs(['type_id'=>$Goods->type_id],'spec_id');
        $data['goods_all_specs'] = $Goods::get_goods_specs();
        $data['goods_attrs_info'] = $Goods::get_goods_attr_info(['goods_sn'=>$Goods->goods_sn]);
        $data['goods_have_attrs'] = $Goods::get_goods_have_spec(['goods_sn'=>$Goods->goods_sn],'spec_id');


        $data['attrs']=DB::table('keyth_goods_attr')->whereIn('id',$data['goods_have_attrs'])->get();
        $data['Goods'] = $Goods;
        $data['Goods_types'] = $Goods::gain_goods_types();
        return view('Admin/goods_add', $data);
    }

    /**
     * 添加商品处理器
     * @return alertjs
     **/
    public function goods_add_c(Request $request)
    {
        $postdata = $request->post();
        if (empty($request->id)) {
            $Goods = new Goods();
            $Goods->goods_proof = $Goods::save_goods_proof();
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
        if (empty($postdata['market_price'])) {
            $postdata['market_price'] = $postdata['price'];
        }
        $Goods->market_price = $postdata['market_price'];
        $Goods->price = $postdata['price'];
        $Goods->promote_or_activity = $postdata['promote_or_activity'];
        $Goods->promote_price = $postdata['promote_price'];
        $Goods->promote_start_date = strtotime($postdata['promote_start_date']);
        $Goods->promote_end_date = strtotime($postdata['promote_end_date']);
        $Goods->activity_number = $postdata['activity_number'];
        $Goods->keyword = $postdata['keyword'];
        $Goods->goods_brief = $postdata['goods_brief'];
        $Goods->goods_desc = $postdata['goods_desc'];
        if ($request->has('goods_thumb')) {
            $Goods->goods_thumb = '/storage/app/' . $request->file('goods_thumb')->store('thumbnail');
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
        if(!empty($postdata['zhcs_name'])){
            if(empty($postdata['model'])){$postdata['model']='';}
            if(empty($postdata['memory'])){$postdata['memory']='';}
            if(empty($postdata['color'])){$postdata['color']='';}
            if(empty($postdata['storage'])){$postdata['storage']='';}
            $Goods::save_goods_have_attr([$Goods->goods_sn,$request->id],["1"=>$postdata['model'],"2"=>$postdata['color'],"3"=>$postdata['memory'],"4"=>$postdata['storage']],[$postdata['zhcs_name'],$postdata['zhcs_stock'],$postdata['zhcs_price']]);
        }
        if ($Goods->save()) {
            return $this->alertjs('提交成功！', '/admin/goods');
        }
        return $this->alertjs('提交失败,请稍候重试！', '/admin/goods');
    }

    /**
     * 商品类别列表
     * @return view
     **/
    public function goods_type()
    {
        $datas['goods_types'] = Goods::gain_goods_types();

        return view('Admin/goods_type', $datas);
    }

    /**
     * 商品类别添加视图
     * @return view
     **/
    public function goods_type_add(Request $request)
    {

        if (!empty($request->id)) {
            $keyth_goods_type = Goods::gain_goods_types(['id' => $request->id]);
            $data['id'] = $keyth_goods_type[0]->id;
            $data['type_name'] = $keyth_goods_type[0]->type_name;
            $data['type_desc'] = $keyth_goods_type[0]->type_desc;
            $data['parent_id'] = $keyth_goods_type[0]->parent_id;
            $data['sort_order'] = $keyth_goods_type[0]->sort_order;
            $data['show_in_nav'] = $keyth_goods_type[0]->show_in_nav;
            $datas['goods_spec'] = Goods::get_goods_type_specs(['type_id' => $keyth_goods_type[0]->id], 'spec_id');
        } else {
            $data['id'] = '';
            $data['type_name'] = '';
            $data['type_desc'] = '';
            $data['parent_id'] = 0;
            $data['sort_order'] = 9999;
            $data['show_in_nav'] = 0;
            $datas['goods_spec'] = array();
        }
        $datas['type'] = $data;
        $datas['goods_types'] = Goods::gain_goods_types(['parent_id' => 0]);
        $datas['goods_specs'] = Goods::get_goods_specs();



        return view('Admin/goods_type_add', $datas);
    }

    /**
     * 商品类别添加处理器
     * @return int
     **/
    public function goods_type_add_c(Request $request)
    {
        $datas['type'] = $request->post();
        //开启数据库事务
        DB::beginTransaction();
        $data['type_name'] = $datas['type']['type_name'];
        $data['type_desc'] = $datas['type']['type_desc'];
        if (!empty($datas['type']['parent_id'])) {
            $data['parent_id'] = $datas['type']['parent_id'];
        }

        $data['sort_order'] = $datas['type']['sort_order'];
        if ($request->id) {
            $id = $request->id;
            $state1 = DB::table('keyth_goods_type')->where('id', $request->id)->update($data);

        } else {
            $state1 = DB::table('keyth_goods_type')->insert($data);
            $id = DB::getPdo()->lastInsertId();
        }
        //子分类继承父级分类的属性
        $son_types = Goods::gain_goods_types(['parent_id' => $id]);
        $state4=True;
        foreach ($son_types as $item) {
            $state4 = DB::table('keyth_goods_type_spec')->where('type_id', $item->id)->delete();
            foreach ($request->spec as $key => $value) {
                $data2['type_id'] = $item->id;
                $data2['spec_id'] = $key;
                $name_code=explode('|',$value);
                $data2['spec_name'] = $name_code[0];
                $data2['code'] = $name_code[1];
                $state3 = DB::table('keyth_goods_type_spec')->insert($data2);
            }
        }

        $state2 = DB::table('keyth_goods_type_spec')->where('type_id', $id)->delete();
        foreach ($request->spec as $key => $value) {
            $data2['type_id'] = $id;
            $data2['spec_id'] = $key;
            $name_code=explode('|',$value);
            $data2['spec_name'] = $name_code[0];
            $data2['code'] = $name_code[1];
            $state3 = DB::table('keyth_goods_type_spec')->insert($data2);
        }


        if (($state1 || $state1 == 0) && ($state2 || $state2 == 0) && ($state3|| $state2 == 0) && ($state4|| $state2 == 0)) {
            DB::commit();
            return $this->alertjs('商品分类信息保存成功！', '/admin/goods');
        } else {
            DB::rollback();
            return $this->alertjs('商品分类信息保存失败！', '/admin/goods');
        }
    }

    /**
     * 商品活动管理
     * @return view
     **/
    public function goods_activity()
    {
        $datas['goods_activitys'] = Goods::where('promote_or_activity', '>', 0)->where('state', 1)->paginate();
        return view('Admin/goods_activity', $datas);
    }

    /**
     * 商品回收站
     * @return view
     **/
    public function goods_recycle()
    {
        $datas['goodss'] = Goods::where('state', 0)->paginate();
        $datas['goods_types'] = Goods::gain_goods_types();
        return view('Admin/goods_recycle', $datas);
    }

    public function goods_spec()
    {
        $datas['goods_specs'] = Other::get_goods_specs();
        return view('Admin/goods_spec', $datas);
    }
    //商品模块end
    //文章模块
    public function article()
    {
        $Articles = Article::where('state', 1)->orderby('sort_order', 'asc')->orderby('created_at', 'asc')->paginate();
        $datas['articles'] = $Articles;
        $datas['Admin_users'] = Article::get_adminusers();
        $datas['article_types'] = Article::get_types();
        return view('Admin/article_list', $datas);
    }

    public function article_add(Request $request)
    {
        if (!empty($request->id)) {
            $article = Article::find($request->id);
        } else {
            $article = new Article();
            $article->id = '';
            $article->title2 = '';
            $article->title = '';
            $article->article_type_id = '';
            $article->sort_order = 9999;
            $article->keywords = '';
            $article->article_brief = '';
            $article->article_thumb = '';
            $article->content = '';
            $article->click = 0;
        }
        $datas['Article'] = $article;
        $datas['Article_types'] = Article::get_types();
        return view('Admin/article_add', $datas);
    }

    public function article_add_c(Request $request)
    {
        $postdata = $request->post();
        if (!empty($request->id)) {
            $article = Article::find($request->id);
        } else {
            $article = new Article();
            $article->id = $postdata['id'];
        }
        $article->title2 = $postdata['title2'];
        $article->title = $postdata['title'];
        $article->article_type_id = $postdata['article_type_id'];

        $article->keywords = $postdata['keywords'];
        $article->article_brief = $postdata['article_brief'];
        $article->content = $postdata['content'];
        $article->click = $postdata['click'];
        if (!empty($postdata['sort_order'])) {
            $article->sort_order = $postdata['sort_order'];
        }
        if ($request->has('article_thumb')) {
            $article->article_thumb = '/storage/app/' . $request->file('article_thumb')->store('thumbnail');
        }
        if ($article->save()) {
            return $this->alertjs('提交成功！', '/admin/article');
        }
        return $this->alertjs('提交失败,请稍候重试！', '/admin/article');
    }

    public function article_type(Request $request)
    {

        $datas = [];
        $datas['type']['name1'] = $request->name1;
        $datas['type']['name2'] = $request->name2;
        $datas['type']['name3'] = $request->name3;
        $datas['article_types'] = Article::get_types();
        return view('Admin/article_type', $datas);
    }

    //订单管理
    public function order(Request $request)
    {
        $Order = new Order();
        $Orders = $Order::where('logistic_status', 0)->orderby('created_at', 'asc')->paginate();
        $datas['orders'] = $Orders;
        $datas['Order'] = $Order;
        return view('Admin/order_list', $datas);
    }

    public function order_info(Request $request)
    {
        $Order = new Order();
        $datas['Order'] = $Order;
        if (!empty($request->id)) {
            $datas['order'] = $Order::where('order_sn',$request->id)->first();
            $datas['ordergoods'] = DB::table('keyth_order_goods')->where('order_sn', $request->id)->get();
            $datas['orderoperation'] = DB::table('keyth_order_operation')->where('order_sn', $request->id)->first();
            $datas['orderpaylog'] = DB::table('keyth_order_pay_log')->where('order_pay_sn', $datas['order']->pay_log_sn)->first();
        }

        return view('Admin/order_info', $datas);
    }

    public function order_delivery(Request $request)
    {
        $Order = new Order();
        $Orders = $Order::where('order_status', 1)->where('logistic_status','>',0)->orderby('created_at', 'asc')->paginate();
        $datas['orders'] = $Orders;
        $datas['Order'] = $Order;
        return view('Admin/order_delivery', $datas);

    }


    //会员管理模块
    public function member()
    {
        $datas = [];
        $datas['members'] = Member::orderby('state', 'desc')->paginate();
        return view('Admin/member', $datas);
    }

    public function member_add(Request $request)
    {
        $datas = [];
        if (!empty($request->id)) {
            $member = Member::find($request->id);
            $datas['member'] = $member;
        }
        return view('Admin/member_add', $datas);
    }

    public function member_add_c(Request $request)
    {
        if (!empty($request->id)) {
            $member = Member::find($request->id);
            $member->username = $request->username;
            $member->birthday = $request->birthday;
            $member->sex = $request->sex;
            $member->qq = $request->qq;
            $member->mobile = $request->mobile;
            if (empty($request->state)) {
                $member->state = 0;
            } else {
                $member->state = 1;
            }
            if ($request->pass != $request->repass) {
                return $this->alertjs('密码不一致！', '/admin/member');
            } else {
                $member->password = $this->encrypt_pass($member->email, $request->pass);
            }

            if ($member->save()) {
                return $this->alertjs('保存成功', '/admin/member');
            }
        }


    }

    public function member_order(Request $request)
    {
        $Order = new Order();
        if(!empty($request->id)){
            $Orders = $Order::where('logistic_status', 0)->where('user_id',$request->id)->orderby('created_at', 'asc')->paginate();
        }else{
            $Orders = $Order::where('logistic_status', 0)->orderby('created_at', 'asc')->paginate();
        }

        $datas['orders'] = $Orders;
        $datas['Order'] = $Order;
        return view('Admin/member_order', $datas);
    }

    public function member_account(Request $request)
    {
        $datas = [];
        if(!empty($request->id)){
            $datas['accounts']=DB::table('keyth_member_account')->where('member_id',$request->id)->orderBy('change_time',' desc')->paginate();
        }else{
            $datas['accounts']=DB::table('keyth_member_account')->orderBy('change_time',' desc')->paginate();
        }
        $change_type=["充值","消费","返现","其它"];
        foreach ($datas['accounts'] as $item){
            $member=Member::find($item->member_id);
            $item->email=$member->email;
            $item->username=$member->username;
            $item->change_type=$change_type[$item->change_type];
            $item->change_time=date('Y-m-d H:i:s',$item->change_time);
        }
        return view('Admin/member_account', $datas);
    }

    public function member_message()
    {
        $datas = [];
        return view('Admin/member_message', $datas);
    }

    public function admin_user(Request $request)
    {
        $datas = [];
        $datas['admins'] = Admin::orderby('state', 'desc')->paginate();
        return view('Admin/admin_user', $datas);
    }

    public function admin_user_add(Request $request)
    {
        $datas = [];
        if (!empty($request->id)) {
            $admin = Admin::find($request->id);
            $datas['admin'] = $admin;
        }
        return view('Admin/admin_user_add', $datas);
    }

    public function admin_user_add_c(Request $request)
    {
        if (!empty($request->id)) {
            $admin = Admin::find($request->id);
            $admin->username = $request->username;
            $admin->birthday = $request->birthday;
            $admin->sex = $request->sex;
            $admin->qq = $request->qq;
            $admin->mobile = $request->mobile;
            if (empty($request->state)) {
                $admin->state = 0;
            } else {
                $admin->state = 1;
            }
            if ($request->pass != $request->repass) {
                return $this->alertjs('密码不一致！', '/admin/admin_user');
            } else {
                $admin->password = $this->encrypt_pass($admin->email, $request->pass);
            }

            if ($admin->save()) {
                return $this->alertjs('保存成功!', '/admin/admin_user');
            }
        }
    }

    public function admin_role(Request $request)
    {

        $datas = [];
        $datas['type']['name1'] = $request->name1;
        $datas['type']['name2'] = $request->name2;
        $datas['type']['name3'] = $request->name3;
        $datas['admin_roles'] = Admin::get_all_roles();

        return view('Admin/admin_role', $datas);
    }

    public function admin_power(Request $request)
    {
        $datas = [];
        $powers = DB::table('keyth_admin_action')->paginate();
        $datas['admin_roles'] = Admin::get_all_roles();
        $datas['powers'] = $powers;
        foreach ($datas['powers'] as $item) {
            foreach ($datas['admin_roles'] as $item2) {
                if ($item->parent_id == $item2->id) {
                    $item->role_name = $item2->role_name;
                }
            }
        }
        return view('Admin/admin_power', $datas);
    }

    public function admin_power_add(Request $request)
    {
        $datas = [];
        $datas['admin_roles'] = Admin::get_all_roles();

        if (!empty($request->id)) {
            $datas['power'] = DB::table('keyth_admin_action')->where('id', $request->id)->first();
        } else {
            $datas['power'] = new Object_();
            $datas['power']->parent_id = '';
            $datas['power']->id = '';
            $datas['power']->action = '';
            $datas['power']->action_desc = '';
            $datas['power']->power_code = '';
        }
        return view('Admin/admin_power_add', $datas);
    }

    public function admin_user_role(Request $request)
    {
        $datas = [];
        $datas['admin_roles'] = Admin::get_all_roles();
        $datas['admins'] = Admin::where('state', 1)->get();
        return view('Admin/admin_user_role', $datas);
    }


    public function setup_shop()
    {
        $datas = [];
        $datas['shop_setup'] = Other::get_shop_setup(['is_custom' => 0]);
        return view('Admin/setup_shop', $datas);
    }

    public function setup_shop_c(Request $request)
    {
        $shop_setup= Other::get_shop_setup(['is_custom' => 0],'code');
        foreach ($request->post() as $key=>$value){
            if(in_array($key,$shop_setup)){
                $state=DB::table('keyth_shop_setup')->where('code',$key)->update(['value'=>$value]);
            }
        }
        if($state){
            return $this->alertjs('商城信息更新成功！','/admin/setup_shop');
        }else{
            return $this->alertjs('商城信息更新失败！','/admin/setup_shop');
        }
    }
    public function setup_pay()
    {
        $datas = [];
        $datas['pays'] = DB::table('keyth_shop_pay')->paginate();
        return view('Admin/setup_pay', $datas);
    }
    public function setup_logistic()
    {
        $datas = [];
        $datas['logistics'] = DB::table('keyth_shop_logistic')->paginate();
        return view('Admin/setup_logistic', $datas);
    }
    public function statis_member(){return view('Admin/statis_member');}
    public function statis_order(){return view('Admin/statis_order');}
    public function statis_goods(){return view('Admin/statis_goods');}


}
@extends('Index/Model')
@section('mian')
    <div class="grzxbj">
        <div class="selfinfo center">
            <div class="lfnav fl">
                <div class="ddzx">订单中心</div>
                <div class="subddzx">
                    <ul>
                        <li><a href="/index/myorder?r=6" @if($r==6)class="li_hover" @endif >我的订单</a></li>

                        {{--<li><a href="/index/myorder?r=7" @if($r==7)class="li_hover" @endif >评价晒单</a></li>--}}
                    </ul>
                </div>
                <div class="ddzx">个人中心</div>
                <div class="subddzx">
                    <ul>
                        <li><a href="/index/myinfo?r=1" @if($r==1)class="li_hover" @endif >个人信息</a></li>
                        <li><a href="/index/myinfo?r=2" @if($r==2)class="li_hover" @endif >消息通知</a></li>
                        <li><a href="/index/myinfo?r=3" @if($r==3)class="li_hover" @endif >资金变动</a></li>
                        <li><a href="/index/myinfo?r=4" @if($r==4)class="li_hover" @endif >我的收藏</a></li>
                        <li><a href="/index/myinfo?r=5" @if($r==5)class="li_hover" @endif >收货地址</a></li>
                    </ul>
                </div>
            </div>
            <div class="rtcont fr">
                <blockquote class="layui-elem-quote">商城公告：</blockquote>


                @switch($r)
                    @case(1)
                    <div>
                        <div class="grzlbt ml40">我的资料</div>
                        <div class="subgrzl ml40"><span>昵称</span><span>{{$member->username}}</span></div>
                        <div class="subgrzl ml40"><span>手机号</span><span>{{$member->mobile}}</span></div>
                        <div class="subgrzl ml40"><span>我的资金</span><span>{{$member->user_money}}￥</span></div>
                        <div class="subgrzl ml40"><span>登录IP</span><span>{{$member->last_ip}}</span></div>
                        <div class="subgrzl ml40"><span>登录次数</span><span>{{$member->visit_login_count}}</span></div>
                        <div class="subgrzl ml40"><span>注册时间</span><span>{{$member->created_at}}</span></div>
                    </div>
                    @break
                    @case(2)
                    <div class="message-main">

                        @foreach( $tips as $item)
                            <div class="message-list J_mList" id="J_msgList" data-stat-title="aaa">
                                <div class="message-item"><h2 class="m-tit ml40">{{$item->desc}}</h2><span class="m-time mr40">{{date('m-d H:i',$item->updated_at)}}</span>
                                    <p class="m-commend">{{$item->desc}}  订单号:『{{$item->order_sn}}』 </p>
                                </div>
                            </div>
                            <div class="J_mPager"></div>
                        @endforeach
                    </div>

                    <div class="layui-layer-page page">
                        {{$tips->appends(['r'=>2])->links()}}
                    </div>
                    @break
                    @case(3)
                    <div class="ml20 mr20">
                        <table class="layui-table">
                            <colgroup>
                                <col width="50">
                                <col width="100">
                                <col width="100">
                                <col width="200">
                                <col>
                            </colgroup>
                            <thead>
                            <tr>
                                <th>交易类型</th>
                                <th>交易时间</th>
                                <th>剩余资金</th>
                                <th>交易备注</th>
                            </tr>
                            </thead>
                            <tbody>
                           @foreach($accounts as $item)
                            <tr>
                                <td>@if($item->change_type==1)<span class="x-red">支出</span> @else <span style="color: green">收入</span> @endif</td>
                                <td>{{date('Y-m-d H:i:s',$item->change_time)}}</td>
                                <td>
                                    @if($item->change_type==1)<span class="x-red">{{$item->user_money}} 元</span> @else <span style="color: green">{{$item->user_money}} 元</span> @endif
                                    </td>
                                <td>{{$item->change_desc}}</td>
                            </tr>
                               @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="layui-layer-page page">
                        {{$accounts->appends(['r'=>3])->links()}}
                    </div>
                    @break
                    @case(4)
                    <ul class="xm-goods-list clearfix">

                        @foreach($collect_goods as $item)
                        <li class="xm-goods-item">
                            <div class="figure figure-img">
                                <a href="/index/goods_info?goods_sn={{$item->goods_sn}}" class="ft14" target="_blank">
                                    <img  style="width:200px" alt="{{$item->goods_name}}" src="{{$item->goods_thumb}}">

                                </a>
                            </div>
                            <h3 class="title">
                                <a href="/index/goods_info?goods_sn={{$item->goods_sn}}" class="ft14" target="_blank">{{$item->goods_name}}</a>
                            </h3>
                            <p class="price">{{$item->price}}元 </p>
                            <p class="rank">
                            </p>
                            <div class="actions">
                                <a  onclick="shoucang(this,{{$item->id}})" class="btn btn-small btn-line-gray J_delFav">删除</a>
                            </div>
                        </li>
                            @endforeach
                    </ul>
                    @break
                    @case(5)
                    <div class="user-address-list J_addressList clearfix">


                        @foreach($address as $item)
                            <div class="address-item J_addressItem" >
                                <dl>
                                    <dt>
                                        <em class="uname">{{$item->consignee}}</em>
                                    </dt>
                                    <dd class="utel">{{$item->mobile}}</dd>
                                    <dd class="uaddress">{{$item->province}}&nbsp;&nbsp;{{$item->city}}&nbsp;&nbsp;{{$item->county}}<br>{{$item->address}}({{$item->zipcode}})                                    </dd>
                                </dl>
                                <div class="actions">
                                    <a onclick="x_admin_show('地址管理','myinfo_address?address_id={{$item->id}}',800,600)" class="modify J_addressModify" >修改</a>
                                    <a class="modify J_addressDel">删除</a>
                                </div>
                            </div>
                            @endforeach
                            <div class="address-item J_addressItem" data-address_id="10180308217602486" data-consignee="张旗" data-tel="151****9974" data-province_id="17" data-province_name="河南" data-city_id="187" data-city_name="郑州市" data-district_id="1815" data-district_name="新郑市" data-area="1815016" data-area_name="新烟街道" data-zipcode="451100" data-address="人民路168号欧洲街" data-tag_name="">
                               <div style="width: 55px;margin: 50px auto;" onclick="x_admin_show('地址管理','myinfo_address',800,600)"><i style="font-size: 50px" class="layui-icon layui-icon-add-1"></i></div>
                            </div>
                    </div>
                    @break
                    @default 4
                @endswitch
            </div>
            <div class="clear"></div>
        </div>
    </div>

    <script>

        function shoucang(obj,id) {
            $.get('/ajax/goods_collect?goods_id='+id,function (data) {
                if(data=='1'){

                    $('#sc').attr('class','layui-icon layui-icon-rate');
                        layer.msg('取消收藏成功！');
                    $(obj).parents("li").remove();

                }
            });

        }
    </script>
@endsection
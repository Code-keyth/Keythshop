<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>欢迎页面-X-admin2.0</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon"/>
    <link rel="stylesheet" href="/public/Admin/css/font.css">
    <link rel="stylesheet" href="/public/Admin/css/xadmin.css">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script type="text/javascript" src="/public/Admin/lib/layui/layui.js" charset="utf-8"></script>
    <script type="text/javascript" src="/public/Admin/js/xadmin.js"></script>
    <script type="text/javascript" src="/public/Common/wangEditor-3.1.1/release/wangEditor.min.js"></script>
    <!-- 让IE8/9支持媒体查询，从而兼容栅格 -->
    <!--[if lt IE 9]>
    <script src="https://cdn.staticfile.org/html5shiv/r29/html5.min.js"></script>
    <script src="https://cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<div class="x-nav">
      <span class="layui-breadcrumb">
        <a href="">首页</a>
        <a href="">商品管理</a>
        <a>
          <cite>商品列表</cite></a>
      </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新">
        <i class="layui-icon" style="line-height:30px">ဂ</i></a>
</div>

<div class="x-body">

    <form class="layui-form layui-form-pane" method="post" enctype="multipart/form-data" action="/admin/goods_add_c">


        <div class="layui-tab">
            <ul class="layui-tab-title">
                <li class="layui-this">基本信息</li>
                <li>用户信息</li>
                <li>商品信息</li>
                <li>支付信息</li>
                <li>操作信息</li>

            </ul>
            <div class="layui-tab-content">
                <div class="layui-tab-item layui-show">

                    <div class="layui-form-item">
                        <label class="layui-form-label">订单号</label>
                        <div class="layui-input-block">
                            <input type="text" disabled="disabled" value="{{$order->order_sn}}" autocomplete="off"
                                   class="layui-input">
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label">配送方式</label>
                        <div class="layui-input-block">
                            <input type="text" disabled="disabled" value="{{$Order->getLogistic($order->logistic_id)}}"
                                   autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">支付方式</label>
                        <div class="layui-input-block">
                            <input type="text" disabled="disabled" value="{{$Order->getPay($order->pay_id)}}"
                                   autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <input hidden='hidden' value="{{$order->id}}" id="orderid">
                    <div class="layui-form-item">
                        <label class="layui-form-label">下单时间</label>
                        <div class="layui-input-block">
                            <input type="text" disabled="disabled" value="{{date('Y-m-d H:i:s',$order->add_time)}}"
                                   autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">支付时间</label>
                        <div class="layui-input-block">
                            <input type="text" disabled="disabled"
                                   value="@if(!empty($orderpaylog->pay_time) && ($orderpaylog->pay_price==$orderpaylog->pay_should_price)) {{date('Y-m-d H:i:s',$orderpaylog->pay_time)}} @else 未支付 @endif "
                                   autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">确认时间</label>
                        <div class="layui-input-block">
                            @if(empty($order->confirm_time)) <a onclick="up_state(1,1)" class="layui-btn layui-btn-normal">待确认</a> @else <input type="text" disabled="disabled" value="{{date('Y-m-d H:i:s',$order->confirm_time)}}" autocomplete="off"
                                                                                                                                                             class="layui-input"> @endif
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label">发货时间</label>
                        <div class="layui-input-block">
                            @if(empty($order->shipping_time))
                                <a  onclick="up_state(1,2)"  class="layui-btn layui-btn-normal">待发货</a> @else <input type="text" disabled="disabled" value="{{date('Y-m-d H:i:s',$order->shipping_time)}}" autocomplete="off"
                                                                                                                    class="layui-input"> @endif
                        </div>
                    </div>


                </div>
                <div class="layui-tab-item">

                    <div class="layui-form-item">
                        <label class="layui-form-label">收货人</label>
                        <div class="layui-input-block">
                            <input type="text" disabled="disabled" value="{{$order->consignee}}" autocomplete="off"
                                   class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">地址</label>
                        <div class="layui-input-block">
                            <input type="text" disabled="disabled" value="{{$order->province}}" autocomplete="off"
                                   class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">邮编</label>
                        <div class="layui-input-block">
                            <input type="text" disabled="disabled" value="{{$order->zipcode}}" autocomplete="off"
                                   class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">电话</label>
                        <div class="layui-input-block">
                            <input type="text" disabled="disabled" value="{{$order->tel}}" autocomplete="off"
                                   class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">手机</label>
                        <div class="layui-input-block">
                            <input type="text" disabled="disabled" value="{{$order->mobile}}" autocomplete="off"
                                   class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">邮箱</label>
                        <div class="layui-input-block">
                            <input type="text" disabled="disabled" value="{{$order->email}}" autocomplete="off"
                                   class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">推荐人</label>
                        <div class="layui-input-block">
                            <input type="text" disabled="disabled"
                                   value="@if(empty($order->parent_id)) 无 @else {{$order->parent_id}} @endif "
                                   autocomplete="off" class="layui-input">
                        </div>
                    </div>


                </div>
                <div class="layui-tab-item">
                    <table class="layui-table">
                        <thead>
                        <tr>
                            <th>商品号</th>
                            <th>商品名</th>

                            <th>金额</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($ordergoods as $item)
                            <tr>
                                <td>{{$item->goods_sn}}</td>
                                <td>{{$item->goods_name}}</td>
                                <td>{{$item->number}}*{{$item->goods_price}}={{$item->goods_price*$item->number}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="layui-tab-item">
                    @if(!empty($orderpaylog))
                    <div class="layui-form-item">
                        <label class="layui-form-label">订单支付号</label>
                        <div class="layui-input-block">
                            <input type="text" disabled="disabled" value="{{$orderpaylog->order_pay_sn}}"
                                   autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">支付方式</label>
                        <div class="layui-input-block">
                            <input type="text" disabled="disabled" value="{{$Order->getPay($orderpaylog->pay_id)}}"
                                   autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">支付金额</label>
                        <div class="layui-input-block">
                            <input type="text" disabled="disabled" value="{{$orderpaylog->pay_price}}"
                                   autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">应付金额</label>
                        <div class="layui-input-block">
                            <input type="text" disabled="disabled" value="{{$orderpaylog->pay_should_price}}"
                                   autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">用户备注</label>
                        <div class="layui-input-block">
                            <input type="text" disabled="disabled" value="{{$order->postscript}}" autocomplete="off"
                                   class="layui-input">
                        </div>
                    </div>
                    @else
                        无扩展信息
                    @endif
                </div>

                <div class="layui-tab-item">
                    @if(!empty($orderoperation))
                    <ul class="layui-timeline">


                            @if(!empty($orderoperation->collect_time))
                                    <li class="layui-timeline-item">
                                        <i class="layui-icon layui-timeline-axis">&#xe63f;</i>
                                        <div class="layui-timeline-content layui-text">
                                            <h3 class="layui-timeline-title">收货时间：{{date('Y-m-d H:i:s',$orderoperation->collect_time)}}</h3>
                                        </div>
                                    </li>
                            @endif
                            @if(!empty($orderoperation->shipping_time))
                                <li class="layui-timeline-item">
                                <li class="layui-timeline-item">
                                    <i class="layui-icon layui-timeline-axis">&#xe63f;</i>
                                    <div class="layui-timeline-content layui-text">
                                        <h3 class="layui-timeline-title">发货时间：{{date('Y-m-d H:i:s',$orderoperation->shipping_time)}}[{{$orderoperation->shipping_person}}]</h3>
                                    </div>
                                </li>
                            @endif

                            @if(!empty($orderoperation->cancel_time))
                                <li class="layui-timeline-item">
                                    <i class="layui-icon layui-timeline-axis">&#xe63f;</i>
                                    <div class="layui-timeline-content layui-text">
                                        <h3 class="layui-timeline-title">取消时间：{{date('Y-m-d H:i:s',$orderoperation->cancel_time)}}[{{$orderoperation->cancel_person}}]</h3>
                                    </div>
                                </li>
                            @endif
                            @if(!empty($orderoperation->pay_time))
                        <li class="layui-timeline-item">
                            <i class="layui-icon layui-timeline-axis">&#xe63f;</i>
                            <div class="layui-timeline-content layui-text">
                                <h3 class="layui-timeline-title">支付时间：{{date('Y-m-d H:i:s',$orderoperation->pay_time)}}[{{$orderoperation->pay_person}}]</h3>
                            </div>
                        </li>
                            @endif
                                @if(!empty($orderoperation->confirm_time))
                                    <li class="layui-timeline-item">
                                        <i class="layui-icon layui-timeline-axis">&#xe63f;</i>
                                        <div class="layui-timeline-content layui-text">
                                            <h3 class="layui-timeline-title">确认时间：{{date('Y-m-d H:i:s',$orderoperation->confirm_time)}}[{{$orderoperation->confirm_person}}]</h3>
                                        </div>
                                    </li>
                                @endif

                        <li class="layui-timeline-item">
                            <i class="layui-icon layui-timeline-axis">&#xe63f;</i>
                            <div class="layui-timeline-content layui-text">
                                <h3 class="layui-timeline-title">下单时间：{{date('Y-m-d H:i:s',$orderoperation->order_time)}}</h3>
                            </div>
                        </li>
                        @else



                    </ul>
                        无操作信息
                    @endif
                </div>

            </div>
        </div>
        <div class="layui-form-item">

            <button type="submit" style="margin-left: 120px" class="layui-btn">提交</button>
        </div>
    </form>
</div>

<script>
function up_state(value,name,id=0) {
    var id2=$('#orderid').val();
    var getdata={
        "id":id2,
        "name":name,
        "value":value
    };
    $.get('/ajax/get_up_order_state',getdata,function (data) {
        if(data=='1'){
            layer.msg('确认成功!',{icon:1,time:1000});
        }else if(data=='0'){
            layer.msg('未支付，不能确认!',{icon:0,time:1000});
        }else{
            layer.msg('确认失败，数据有问题!',{icon:0,time:1000});
        }
    });


}

</script>

</body>



</html>
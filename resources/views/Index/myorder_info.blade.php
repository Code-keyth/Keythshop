<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">

    <title>Keyth Shop</title>
    <link rel="stylesheet" type="text/css" href="/public/Index/css/style.css">

    <link rel="stylesheet" href="/public/Common/layui/css/layui.css">
    {{--<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>--}}
    <script type="text/javascript" src="/public/Common/jquery-1.8.3.min.js"></script>

    <script type="text/javascript" src="/public/Index/js/js.js"></script>
    <script type="text/javascript" src="/public/Admin/js/xadmin.js"></script>
    <script type="text/javascript" src="/public/Common/layui/layui.js"></script>
</head>
<body>

    <div class="layui-container layui-form">
        <fieldset class="layui-elem-field layui-field-title" style="margin-top: 30px;">
            <legend>订单状态</legend>
        </fieldset>
        <div class="order-progress">
            <ol class="progress-list clearfix progress-list-5">
                <li class="step step-first @if(!empty($operation->order_time)) step-done @endif">

                    <div class="progress"><span class="text">下单</span></div>
                    <div class="info">{{date('Y-m-d H:i:s',$operation->order_time)}}</div>
                </li>
                <li class="step @if(!empty($operation->confirm_time)) step-done @endif">
                    <div class="progress"><span class="text">确认</span></div>
                    <div class="info">@if(!empty($operation->confirm_time)){{date('Y-m-d H:i:s',$operation->confirm_time)}}  @endif </div>
                </li>
                <li class="step @if(!empty($operation->pay_time)) step-done @endif">
                    <div class="progress"><span class="text">支付</span></div>
                    <div class="info"> @if(!empty($operation->pay_time)){{date('Y-m-d H:i:s',$operation->pay_time)}}  @endif </div>
                </li>
                <li class="step @if(!empty($operation->shipping_time)) step-done @endif">
                    <div class="progress"><span class="text">发货</span></div>
                    <div class="info"> @if(!empty($operation->shipping_time)) {{date('Y-m-d H:i:s',$operation->shipping_time)}}  @endif </div>
                </li>
                <li class="step step-active @if(!empty($operation->collect_time)) step-done @endif step-last">
                    <div class="progress"><span class="text">交易成功</span></div>
                    <div class="info"> @if(!empty($operation->shipping_time)) {{date('Y-m-d H:i:s',$operation->collect_time)}} @endif </div>
                </li>
            </ol>
        </div>
        <fieldset class="layui-elem-field layui-field-title" style="margin-top: 30px;">
            <legend>商品信息</legend>
        </fieldset>
        <div class="layui-row">
            <table class="layui-table" lay-skin="nob">

                <tbody>
                @foreach($catr_infos as $item)
                    <tr>
                        <td>{{$item->goods_name}}{{$item->spec_info_attr}}</td>
                        <td>{{$item->number}}</td>
                        <td>{{$item->goods_price}}</td>
                        <td>{{$item->market_price}}元</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <fieldset class="layui-elem-field layui-field-title" style="margin-top: 30px;">
            <legend>收货信息</legend>
        </fieldset>
        <div class="subddzx">
            <table class="layui-table" lay-skin="nob">
                <tr><td>收&nbsp;货&nbsp;人：{{$order->consignee}}</td></tr>
                <tr><td>联系电话：{{$order->mobile}}</td></tr>
                <tr><td>收货地址：{{$order->province}}&nbsp;{{$order->city}}&nbsp;{{$order->county}}&nbsp;{{$order->address}}</td></tr>
            </table>
        </div>


        <fieldset class="layui-elem-field layui-field-title" style="margin-top: 30px;">
            <legend>支付方式</legend>
        </fieldset>
        <div class="layui-row">
            <div class="layui-col-md3">&nbsp;</div>
            <div class="layui-col-md3">

                @if(!empty($order->pay_log_sn))
                    已支付
                @else
                    @if($order->order_status < 2)
                        <input value="{{$order->pay_id}}"  type="radio" name="pay_id" title="余额支付">
                    @else
                        订单已取消
                    @endif
                @endif
            <div class="layui-col-md3">&nbsp;</div>
            <div class="layui-col-md3">
                @if($order->logistic_status == 3)
                    <button class="layui-btn fr layui-btn-normal">订单已完成</button>
                @else
                    @if(!empty($order->pay_log_sn))
                        <button class="layui-btn fr layui-btn-normal">已支付</button>
                        @if($order->logistic_status == 1)
                            <button onclick="confirm_collect('{{$order->order_sn}}')" style="margin-right: 10px" class="layui-btn fr layui-btn-normal">确认收货</button>
                        @endif
                        @if($order->logistic_status == 0)
                        <button  onclick="cancel_pay()" style="margin-right: 10px" class="layui-btn fr layui-btn-normal">取消订单</button>
                            @endif
                    @else
                        @if($order->order_status < 2)
                            <button class="layui-btn fr" onclick="pay()" style="margin-left: 10px">支付订单</button>
                            @if($order->logistic_status == 0)
                                <button style="margin-right: 10px" class="layui-btn fr layui-btn-normal">取消订单</button>
                            @endif
                        @else
                            <button class="layui-btn fr">订单已取消</button>
                        @endif
                    @endif
                @endif
            </div>
        </div>
        </div>
    </div>
    <script>
        var layer;
        layui.use('layer', function() {
            layer = layui  .layer;
        });
        var pay_id='';
        var dd=$("input[name='pay_id']");
        for (var i =0;i<dd.length;i++){
            if(dd[i].checked == true){
                pay_id=dd[i].value;
            }
        }
        var data={
            "order_sn":"{{$order->order_sn}}",
            "_token":"{{csrf_token()}}",
            "pay_id":pay_id
        };

        function pay() {
            $.post('/ajax/pay_order',data,function (data) {
                if (data=='1'){
                    layer.msg('支付成功！');
                    setTimeout( function(){
                        var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
                        parent.layer.close(index);
                    }, 1000 );
                }else if(data=='-1'){
                    layer.msg('余额不足！');
                }else if(data=='2'){
                    layer.msg('订单已经支付了！');}
                else {
                    layer.msg('支付失败！');
                    setTimeout( function(){
                        var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
                        parent.layer.close(index);
                    }, 1000 );
                }

            });
        }
        function confirm_collect(order_sn){
            layer.confirm('确认要收货吗？',function(index){
                //发异步删除数据
                $.get('/ajax/confirm_collect',{'order_sn':order_sn},function (ret) {
                    if(ret=='1'){
                        layer.msg('收货成功!',{icon:1,time:1000});
                        return 0;
                    }
                    else if(ret=='-1'){
                        layer.msg('非法操作!',{icon:0,time:1000});
                        return 0;
                    }
                    layer.msg('收货失败!',{icon:0,time:1000});
                });
            });
        }
        function cancel_pay() {
            $.post('/ajax/cancel_order',data,function (data) {
                if (data=='1'){
                    layer.msg('取消成功！');
                    setTimeout( function(){
                        var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
                        parent.layer.close(index);
                    }, 1000 );

                }else if(data=='0'){
                    layer.msg('订单不可取消！');
                }
                else {
                    layer.msg('取消失败！');
                    setTimeout('send',1000);

                    setTimeout( function(){
                        var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
                        parent.layer.close(index);
                    }, 1000 );


                }

            });
        }


    </script>

</body>
</html>
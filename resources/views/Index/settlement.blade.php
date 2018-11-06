@extends('Index/Model')
@section('mian')
    <div class="layui-container">
        <fieldset class="layui-elem-field layui-field-title" style="margin-top: 30px;">
            <legend>我的地址</legend>
        </fieldset>
        <div class="layui-row">
@foreach($address as $item)
            <div  class="layui-col-md3">
                <div class="address-item J_addressItem">
                    <dl>
                        <dd class="utel"></dd>
                        <dt>
                            <em class="uname"><input @if($item->is_default==1) checked @endif type="radio" value="{{$item->id}}" name="address_id">&nbsp;{{$item->consignee}}</em>
                        </dt>
                        <dd class="utel">{{$item->mobile}}</dd>
                        <dd class="uaddress">{{$item->province}}&nbsp;&nbsp;{{$item->city}}&nbsp;&nbsp;{{$item->county}}<br>{{$item->address}}&nbsp;({{$item->zipcode}})</dd>
                    </dl>
                </div>
            </div>
@endforeach
        </div>
        <fieldset class="layui-elem-field layui-field-title" style="margin-top: 30px;">
            <legend>商品信息</legend>
        </fieldset>
        <div class="layui-row">
            <table class="layui-table" lay-skin="nob">
                <colgroup>
                    <col width="700">
                    <col width="150">
                    <col>
                </colgroup>
                <tbody>
                @foreach($catr_infos as $item)
                <tr>
                    <td>{{$item['goods_name']}}</td>
                    <td>{{$item['number']}}</td>
                    <td>{{$item['good_price']*$item['number']}}元</td>
                </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <fieldset class="layui-elem-field layui-field-title" style="margin-top: 30px;">
            <legend>配送方式</legend>
        </fieldset>
        <div class="layui-row">
            <div class="layui-col-md3">&nbsp;</div>
            <div class="layui-col-md3">
                @foreach($shop_logistics as $item)
                <input type="radio" name="logistic_id" title="{{$item->shopping_name}}" value="{{$item->id}}">{{$item->shopping_name}}
                    @endforeach
            </div>
        </div>
        <fieldset class="layui-elem-field layui-field-title" style="margin-top: 30px;">
            <legend>配送方式</legend>
        </fieldset>
        <div class="layui-row">
            <div class="layui-col-md3">&nbsp;</div>
            <div class="layui-col-md3">
                @foreach($shop_pays as $item)
                    <input type="radio" name="pay_id" title="{{$item->pay_name}}" value="{{$item->id}}">{{$item->pay_name}}
                @endforeach
            </div>
        </div>



        <fieldset class="layui-elem-field layui-field-title" style="margin-top: 30px;">
            <legend></legend>
        </fieldset>
        <div class="layui-row">
            <div class="layui-col-md3">&nbsp;</div>
            <div class="layui-col-md3">&nbsp;</div>
            <div class="layui-col-md3">&nbsp;</div>
            <div class="layui-col-md3">
                <h3>商品数量：<span class="x-red fr">{{$goods_number}}&nbsp;件</span></h3><br/>
                <h3>商品总价：<span class="x-red fr">{{$goods_total_price}}&nbsp;元</span></h3><br/>
                <h3>应付金额：<span class="x-red fr">{{$goods_total_price}}&nbsp;元</span></h3><br/>
                <button onclick="confirm_order()" class="layui-btn fr layui-btn-normal">确定下单</button>
            </div>
        </div>
    </div>
    <script>
        var layer;
        layui.use('layer', function() {
            layer = layui.layer;
        });
        var address=1;
        var logistic_id=1;
        var pay_id=1;
        function confirm_order() {
            var cc=$("input[name='address_id']");
            for (var i =0;i<cc.length;i++){
                if(cc[i].checked == true){
                    address=cc[i].value;
                }
            }
            var dd=$("input[name='logistic_id']");
            for (var i =0;i<dd.length;i++){
                if(dd[i].checked == true){
                    logistic_id=dd[i].value;
                }
            }
            var dd=$("input[name='pay_id']");
            for (var i =0;i<dd.length;i++){
                if(dd[i].checked == true){
                    pay_id=dd[i].value;
                }
            }
            $.post('/ajax/member_confirm_order',{"address_id":address,'_token':'{{csrf_token()}}','order_sn':"{{$order_sn}}",'logistic_id':logistic_id,'pay_id':pay_id},function (data) {

               if(data!='0'){
                   layer.msg('订单确认成功！现在跳转支付！');
                   setTimeout( function(){
                       window.location.href='/index/myorder?r=6&&pay_oreder='+data;
                   }, 1000 );
               }
            });

        }
    </script>
@endsection
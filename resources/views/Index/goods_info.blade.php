@extends('Index/Model')
	@section('mian')
		<!-- xiangqing -->
		<form action="post" method="">
		<div class="xiangqing">
			<div class="neirong w">
				<div class="xiaomi6 fl">{{$good->goods_name}}</div>
				<nav class="fr">
					<li><a href="">概述</a></li>
					<li>|</li>
					<li><a href="">变焦双摄</a></li>
					<li>|</li>
					<li><a href="">设计</a></li>
					<li>|</li>
					<li><a href="">参数</a></li>
					<li>|</li>
					<li><a href="">F码通道</a></li>
					<li>|</li>
					<li><a href="">用户评价</a></li>
					<div class="clear"></div>
				</nav>
				<div class="clear"></div>
			</div>
		</div>
		<div class="jieshao mt20 w" style="height: auto;">
			<div class="left fl"><img style="width: 100%" src="{{$good->goods_thumb}}"></div>
			<div class="right fr mb20" style="height: auto;">
				<div class="h3 ml20 mt20">{{$good->goods_name}}</div>
				<div class="jianjie mr40 ml20 mt10">{{$good->goods_brief}}</div>
				<div class="jiage ml20 mt10" id="good_price">{{$good->price}}</div>
				@foreach($type_specs as $key=>$type_spec)
					@if (count($have_specs)!=0)
				<div class="ft20 ml20 mt20">选择{{$type_spec->spec_name}}</div>
				<div class="xzbb ml20 mt10">
					@foreach($have_specs as $item)
						@if($item->attr_id==$type_spec->spec_id)
							<div class="banben fl" >
								<a id="{{$type_spec->code}}" name="{{$type_spec->code}}" value="{{$Goods->get_goods_have_attr($item->spec_id)->attr_name}}" onclick="Select(this,'{{$type_spec->code}}')">
									<span>{{$Goods->get_goods_have_attr($item->spec_id)->attr_name}}</span></a></div>
							@endif
					@endforeach
					<div class="clear"></div>
				</div>
					@endif
@endforeach

<div class="xqxq mt20 ml20" style="height: auto;">
	<div >数量：
		<a  onclick="reduce_number()"  style="padding: 8px 10px;" class="llayui-btn layui-btn-primary layui-btn-sm">-</a>
		<input class="layui-input input-number" readonly  style="display:inline;width: 30px; height:30px;margin: 0px 5px;" id="input_number" type="text" value="1" />
		<a onclick="add_number()" class="layui-btn layui-btn-primary layui-btn-sm">+</a>

	</div>
	<div >库存：
		<span id="goods_number">{{$good->goods_number}}</span>
	</div>
    <div class="top1 mt10">
		<div class="right1 fr" ></div>
        <div class="left1 fl" >{{$good->goods_name}}&nbsp;&nbsp;<span id="choose_info"></span></div>
		<div class="right1 fr"  id="good_price2">{{$good->price}}</div>
        <div class="clear"></div>
    </div>
    <div class="bot mt20 ft20 ftbc" >总计：<span id="total_price">{{$good->price}}</span>元

		@if(in_array($good->id,$collect_goods))
			<span style="float:right; font-size: 14px" onclick="shoucang()"><i id="sc" class="layui-icon layui-icon-rate-solid"></i>收藏</span>
			@else
			<span style="float:right; font-size: 14px" onclick="shoucang()"><i id="sc" class="layui-icon layui-icon-rate"></i>收藏</span>
			@endif
	</div>


</div>
<div class="xiadan ml20 mt20">
        <input class="jrgwc" onclick="addcart_good_once()" type="button" name="jrgwc" value="立即选购" />
        <input class="jrgwc" onclick="addcart_good()" type="button" name="jrgwc" value="加入购物车" />
</div>
</div>
<div class="clear"></div>
			<div class="xiangqing">
				<div class="neirong w">
					<div class="xiaomi6 fl ml40" >商品参数</div>

					<div class="clear"></div>
				</div>
			</div>

			<table class="layui-table" lay-skin="line">
				<thead>
					<tr><th>参数</th><th>数据</th><th>参数</th><th>数据</th></tr>
				</thead>
				<tbody>
					<tr><td>商品名：</td><td>{{$good->goods_name}}</td><td>商品关键字：</td><td>{{$good->keyword}}</td></tr>
					<tr><td>商品重量：</td><td>{{$good->goods_weight}}</td><td>重量单位：</td><td>{{$good->goods_unit}}</td></tr>
					<tr><td>商城售价：</td><td>{{$good->price}}</td><td>市场价：</td><td>{{$good->market_price}}</td></tr>
					<tr><td>商品积分：</td><td>{{$good->integral}}</td>
						<td>上架时间：</td><td>{{$good->updated_at}}</td></tr>
				</tbody>
			</table>

			<div class="xiangqing">
				<div class="neirong w">
					<div class="xiaomi6 fl ml40" >商品详情</div>

					<div class="clear"></div>
				</div>
			</div>
			<div>
				{!! $good->goods_desc !!}
			</div>
</div>

</form>
</form>
<!-- footer -->
		<script>
            var select_arr=new Array();
            var goods_price=new Array();
            var info_number='';
			function f(cc,art,obj) {
                var value_name=$(obj).attr('value');
                var addre = $.inArray(value_name, select_arr);
                if(cc){
                    select_arr.splice(addre, 1);
                    $(obj).removeAttr('class');
                    $($(obj).parent()).attr('class','banben fl');
                }else{
                    for (var i=0 ;i<art.length;i++){
                        if($(art[i]).attr("class")){
                            $(art[i]).removeAttr('class');
                            $(art[i]).attr("class")
							select_arr.splice($.inArray($(art[i]).attr('value'), select_arr), 1);
                            $($(art[i]).parent()).attr('class','banben fl');
                            break;}}
                    $(obj).addClass("js_select_a");
                    $($(obj).parent()).addClass("js_select_banben");
                }
                if(addre==-1){select_arr.push(value_name);}
                $('#choose_info').text(select_arr);
                var number=$('#input_number').val();

                $.get('/ajax/get_select_goods_info',{'goods_sn':'{{$good->goods_sn}}',"arrts":select_arr},function (data) {
                    console.log(data);
                    if(data['code']=='1'){
                        $('#choose_info').text(data['attr_value']);
                        $('#good_price').text(data['goods_price']);
                        $('#good_price2').text(data['goods_price']);
                        $('#goods_number').text(data['goods_number']);
                        info_number=data['info_number'];
                        $('#total_price').text(Number(data['goods_price'])*Number(number));
					}else{

                        $('#good_price').text('{{$good->price}}');
                        $('#good_price2').text('{{$good->price}}');
                        $('#goods_number').text('{{$good->goods_number}}');
                        $('#total_price').text(0);
                        info_number='';
					}

                });


            }
            function shoucang() {
				$.get('/ajax/goods_collect?goods_id={{$good->id}}',function (data) {
					if(data=='1'){
					    if($('#sc').attr('class')=='layui-icon layui-icon-rate'){
                            $('#sc').attr('class','layui-icon layui-icon-rate-solid');
                            layer.msg('收藏成功！');
						}else{
                            $('#sc').attr('class','layui-icon layui-icon-rate');
                            layer.msg('取消收藏成功！');

						}


					}
                });

            }
            function Select(obj,name){
                var cc=$(obj).attr("class");
				@foreach($type_specs as $key=>$type_spec)
                	var {{$type_spec->code}}=$("[name='{{$type_spec->code}}']");
                	if(name=='{{$type_spec->code}}'){
                    	f(cc,{{$type_spec->code}},obj);}
				@endforeach
            }
            function reduce_number() {
				var number=$('#input_number');
                var price=$('#good_price2').text();
				if(Number(number.val())>1){
                    number.attr('value',Number(number.val())-1);}
                $('#total_price').text(Number(number.val()) * Number(price));
			}
            function add_number() {
                var number=$('#input_number');
                var price=$('#good_price2').text();
                number.attr('value',Number(number.val())+1);
                $('#total_price').text(Number(number.val()) * Number(price));
			}

                function addcart_good() {
                    var goods_sn='{{$good->goods_sn}}';
                    var buy_number=$('#input_number').val();
					@if (count($have_specs)==0)
                        info_number='no';
					@endif
                    layui.use('layer', function(){
                        var layer = layui.layer;
                        if(info_number==''){
                        layer.msg('参数有误！');
                    }else{
                        $.post('/ajax/addcart_good',{"goods_sn":goods_sn,'goods_name':'{{$good->goods_name}}','info_number':info_number,'buy_number':buy_number,'_token':'{{csrf_token()}}'},function (data) {
                            if(data=='-1'){
                                layer.msg('参数有误！');
                            }else{
                                layer.msg('商品已成功加入购物车！');
                            }
                        });
                    }
                    });

                }
            function addcart_good_once() {
                var goods_sn='{{$good->goods_sn}}';
                var buy_number=$('#input_number').val();
				@if (count($have_specs)==0)
                    info_number='no';
				@endif
                layui.use('layer', function(){
                    var layer = layui.layer;
                    if(info_number==''){
                        layer.msg('参数有误！');
                    }else{
                        $.post('/ajax/addcart_good',{"goods_sn":goods_sn,'goods_name':'{{$good->goods_name}}','info_number':info_number,'buy_number':buy_number,'_token':'{{csrf_token()}}'},function (data) {
                            if(data=='-1'){
                                layer.msg('参数有误！');
                            }else{

                                window.location.href='/index/mycart';
                            }

                        });

                    }
                });

            }



		</script>
@endsection




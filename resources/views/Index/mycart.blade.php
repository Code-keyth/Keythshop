@extends('Index/Model')
	@section('mian')
		<div class="xiantiao"></div>
			<div class="gwcxqbj">
				<div class="gwcxd center">

					<table class="layui-table" lay-skin="line">
						<thead>
						<tr>
							<th>
								<div class="layui-unselect header layui-form-checkbox" lay-skin="primary"><i class="layui-icon">&#xe605;</i></div>
							</th>
							<th width="00px"><b>商品名称</b></th>
							<th><b>单价</b></th>
							<th><b>数量</b></th>
							<th><b>小计</b></th>
							<th><b>操作</b></th>

						</thead>
						<tbody>

						@foreach($goods_infos['info'] as $key=>$info)
						<tr height="100px">
							<td>
								<input onclick="total(this)" type="checkbox" name="id[{{$key}}]" value="{{$key}}">
							</td>
							<td>{{$info['goods_name']}}[{{$info['spec_info_attr']}}]</td>
							<td>{{$info['good_price']}}</td>
							<td>
							<input readonly class="number" style="width: 30px" value="{{$info['number']}}" type="number" id="number">
							</td>
							<td><p class="good_price">{{$info['good_price']*$info['number']}}</p></td>
							<td><a href="#" onclick="del_cart_goods('{{$key}}')"><i class="layui-icon layui-icon-close"></i></a></td>

						</tr>
							@endforeach
						</tbody>
					</table>
				</div>
				<div class="jiesuandan mt20 center">
					<div class="tishi fl ml20">
						<ul>
							<li><a href="/index/">继续购物</a></li>
							<li>|</li>
							<li>共<span>{{$goods_infos['total_number']}}</span>件商品。</li>
							<div class="clear"></div>
						</ul>
					</div>
					<div class="jiesuan fr">
						<div class="jiesuanjiage fl">合计：<span id="total_price">0</span>&nbsp;元</div>
						<div class="jsanniu fr"><a href="#" onclick="jiesuan11('1111')"><button class="layui-btn">去结算</button></a></div>
						<div class="clear"></div>
					</div>
					<div class="clear"></div>
				</div>

			</div>

		<script>
            var prices=new Array();
            var jiesuan='';
            function del_cart_goods(catr_number) {
                console.log(catr_number);
            }

            function jiesuan11(catr_number) {
                var cc=$("input[type='checkbox']");
                for (var i =0;i<cc.length;i++){
                    if(cc[i].checked == true){
                        console.log(cc[i].value);
                        jiesuan+=cc[i].value+'-';
					}
                }
                window.location.href='/index/settlement?id='+jiesuan;
            }

            function total(obj) {
				var cc=$($(obj).parent()).parent();

				var total_price2=0;
                var price = Number($(cc).find(".good_price").html());
                var addre = $.inArray(price, prices);
                if(addre>=0){
                    prices.splice(addre, 1);
				}else{
                    prices.push(price);
				}
                for (var i =0;i<prices.length;i++){
                    total_price2+=prices[i];
                }
                $('#total_price').html(total_price2);
            }

		</script>

	@endsection
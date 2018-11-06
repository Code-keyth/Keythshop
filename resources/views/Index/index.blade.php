@extends('Index/Model')
	@section('mian')
	<!-- start banner_y -->
	<div class="banner_y center">
		<div class="nav">
			<ul>

				@foreach( $goods_types as $item)
					<li>
						<a href="/index/goods_list?type_id={{$item->id}}">{{$item->type_name}}</a>
						<div class="pop">
							<div class="left fl">

								@foreach($Goods->get_type_goods(['type_id'=>$item->id,'is_hot'=>1]) as $item_son)

								<div>
									<div class="xuangou_left fl">
										<a href="">
											<div class="img fl"><img src="/public/Index/image/xm6_80.png" alt=""></div>
											<span class="fl">{{$item_son->goods_name}}</span>
											<div class="clear"></div>
										</a>
									</div>
									<div class="xuangou_right fr"><a href="/index/goods_info?goods_sn={{$item_son->goods_sn}}" target="_blank">选购</a></div>
									<div class="clear"></div>
								</div>
									@endforeach
							</div>
							<div class="clear"></div>
						</div>
					</li>
				@endforeach
			</ul>
		</div>
	</div>

	<div class="sub_banner center">
		<div class="sidebar fl">
			<div class="fl"><a href=""><img src="/public/Index/image/hjh_01.gif"></a></div>
			<div class="fl"><a href=""><img src="/public/Index/image/hjh_02.gif"></a></div>
			<div class="fl"><a href=""><img src="/public/Index/image/hjh_03.gif"></a></div>
			<div class="fl"><a href=""><img src="/public/Index/image/hjh_04.gif"></a></div>
			<div class="fl"><a href=""><img src="/public/Index/image/hjh_05.gif"></a></div>
			<div class="fl"><a href=""><img src="/public/Index/image/hjh_06.gif"></a></div>
			<div class="clear"></div>
		</div>
		<div class="datu fl"><a href=""><img src="/public/Index/image/hongmi4x.png" alt=""></a></div>
		<div class="datu fl"><a href=""><img src="/public/Index/image/xiaomi5.jpg" alt=""></a></div>
		<div class="datu fr"><a href=""><img src="/public/Index/image/pinghengche.jpg" alt=""></a></div>
		<div class="clear"></div>


	</div>
	<!-- end banner -->
	<div class="tlinks">Collect from <a href="http://www.cssmoban.com/" >企业网站模板</a></div>

	<!-- start danpin -->
	<div class="danpin center">

		<div class="biaoti center">小米明星单品</div>
		<div class="main center">

			@foreach($new_goods as $new_good)
			<div class="mingxing fl">
				<div class="sub_mingxing"><a href="/index/goods_info?goods_sn={{$new_good->goods_sn}}"><img src="{{$new_good->goods_thumb}}" alt=""></a></div>
				<div class="pinpai"><a href="">{{$new_good->goods_name}}</a></div>
				<div class="youhui">{{$new_good->goods_brief}}</div>
				<div class="jiage">{{$new_good->price}}元</div>
			</div>
				@endforeach
			<div class="clear"></div>
		</div>
	</div>



	<div class="peijian w">
		<div class="biaoti center">精品</div>
		<div class="main center">
			<div class="content">
				<div class="remen fl"><a href=""><img src="/public/Index/image/peijian1.jpg"></a>
				</div>
				@foreach($Goods->get_type_goods(['type_id'=>2],4) as $item)
					<div class="remen fl">
						<div class="xinpin"><span>新品</span></div>
						<div class="tu"><a href=""><img src="/public/Index/image/peijian2.jpg"></a></div>
						<div class="miaoshu"><a href="/index/goods_info?goods_sn={{$item_son->goods_sn}}">{{$item->goods_name}}</a></div>
						<div class="jiage">{{$item->price}}元</div>
					</div>
				@endforeach

				<div class="clear"></div>
			</div>
			<div class="content">
				<div class="remen fl"><a href=""><img src="/public/Index/image/peijian1.jpg"></a>
				</div>
				@foreach($Goods->get_type_goods(['type_id'=>1],3) as $item2)
					<div class="remen fl">
						<div class="xinpin">
							@if($item->is_new==1)<span style="background-color: #83c44e;" >新品</span>@endif
							@if($item->is_hot==1)<span style="background-color: #ff6700;" >热卖</span>@endif
						</div>
						<div class="tu"><a href=""><img src="/public/Index/image/peijian2.jpg"></a></div>
						<div class="miaoshu"><a href="/index/goods_info?goods_sn={{$item_son->goods_sn}}">{{$item->goods_name}}</a></div>
						<div class="jiage">{{$item->price}}元</div>
					</div>
				@endforeach
				<div class="remenlast fr">
					<div class="hongmi"><a href="/index/goods_info"><img src="/public/Index/image/hongmin4.png" alt=""></a></div>
					<div class="liulangengduo"><a href=""><img src="/public/Index/image/liulangengduo.png" alt=""></a></div>
				</div>
				<div class="clear"></div>
			</div>
		</div>
	</div>



	@endsection

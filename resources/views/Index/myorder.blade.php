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
				<div class="ddzxbt">交易订单</div>
				<div class="ddxq">
					@if(!empty($no_confirms))
					<div class="ddbh fl" style="height: 90px;">订单号:{{$no_confirms['order_sn']}}</div>
					<div class="ztxx fr">
						<ul>
							<li ><span class="x-red">未确认</span></li>
							<li>￥{{$no_confirms['goods_total_price']}}</li>
							<li style="width: 220px">{{date('Y-m-d H:i:s',$no_confirms['confirm_time'])}}</li>
							<li><a href="/index/settlement?id={{$no_confirms['-id']}}">待确认订单></a></li>
							<div class="clear"></div>
						</ul>
					</div>
						@endif
						@foreach($orders as $item)
							<div class="ddbh fl">订单号:{{$item->order_sn}}</div>
							<div class="ztxx fr">
								<ul>
									<li>
										@if($item->order_status < 2)
											@if(!empty($item->pay_log_sn))
												@switch($item->logistic_status)
													@case(0)未发货 @break
													@case(1)已发货 @break
													@case(2)待收货 @break
													@case(3)已收货 @break
													@case(4)其它状态 @break
													@default 未确认
												@endswitch
											@else
												<button onclick="pay_order()" class="layui-btn layui-btn-danger">待支付</button>
											@endif
										@else
											<span class="x-red">
												@switch($item->order_status)
													@case(2)已取消 @break
													@case(3)无效 @break
													@case(4)退货 @break
													@default 其它
												@endswitch
											</span>
										@endif
									</li>
									<li>￥{{$item->order_amount}}</li>
									<li style="width: 220px">{{date('Y-m-d H:i:s',$item->add_time)}}</li>
									<li><a onclick="x_admin_show('订单详情:{{$item->order_sn}}','/index/myorder_info?order_sn={{$item->order_sn}}',800,800)">订单详情></a></li>
									<div class="clear"></div>
								</ul>
							</div>
							<div class="clear"></div>
						@endforeach
				</div>
				<div style="height: 10px" class="layui-layer-page" >
					{{$orders->fragment('r')->links()}}
				</div>
			</div>
			<div class="clear"></div>

			</div>
		</div>
		@if(!empty($pay_oreder))
			<script>
                layui.use('layer', function() {
                    layer = layui.layer;
                    x_admin_show('订单详情:{{$pay_oreder}}','/index/myorder_info?order_sn={{$pay_oreder}}',800,800);
                });
			</script>
		@endif

	<!-- self_info -->
		@endsection

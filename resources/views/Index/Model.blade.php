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
	<!-- start header -->
		<header>
			<div class="top center">
				<div class="left fl">
					<ul>
						<li><a href="/" target="_blank">Keyth Shop</a></li>
						<li>|</li>
						<li><a href="">其它功能1</a></li>
						<li>|</li>
						<li><a href="">其它功能2</a></li>
						<li>|</li>
						<li><a href="">其它功能3</a></li>
						<li>|</li>
						<li><a href="">其它功能4</a></li>
						<li>|</li>
						<li><a href="">其它功能5</a></li>
						<li>|</li>
						<li><a href="">其它功能6</a></li>
						<li>|</li>
						<li><a href="">其它功能7</a></li>
						<div class="clear"></div>
					</ul>
				</div>
				<div class="right fr">
					<a href="/index/mycart"><div class="gouwuche fr">购物车</div></a>
					<div class="fr">
						<ul>
							@if(!empty($logou_state))
								<li><a href="/index/myinfo">{{$logou_state}}</a></li>
								<li><a href="/index/logout" target="_blank" >退出</a></li>
								<li><a href="">消息通知 &nbsp; <span class="layui-badge">6</span></a></li>
							@else
							<li><a href="/index/login" target="_blank">登录</a></li>
							<li>|</li>
							<li><a href="/index/register" target="_blank" >注册</a></li>
							<li>|</li>
								@endif

						</ul>
					</div>
					<div class="clear"></div>
				</div>
				<div class="clear"></div>
			</div>
		</header>
	<!--end header -->

<!-- start banner_x -->
		<div class="banner_x center">
			<a href="/index" target="_blank"><div class="logo fl"></div></a>
			<a href=""><div class="ad_top fl"></div></a>
			<div class="nav fl">
				<ul>
					<li><a href="/index" >首页</a></li>
					@foreach( $goods_types as $item)
						@if($item->show_in_nav==1)
						<li><a href="/index/goods_list?type_id={{$item->id}}" >{{$item->type_name}}</a></li>
							@endif
					@endforeach


				</ul>
			</div>
			<div class="search fr">
				<form  style="margin-top: 50px;" action="" method="post">
					<input style="display: block;width: 210px;" type="text " placeholder="请输入商品名" class="layui-input fl">
					<button style="display: block;"  class="layui-btn fr">搜索</button>
					<div class="clear"></div>
				</form>
				<div class="clear"></div>
			</div>
		</div>
<!-- end banner_x -->
@section('mian')


	@show

		<footer class="mt20 center">			
			<div class="mt20">小米商城|MIUI|米聊|多看书城|小米路由器|视频电话|小米天猫店|小米淘宝直营店|小米网盟|小米移动|隐私政策|Select Region</div>
			<div>©mi.com 京ICP证110507号 京ICP备10046444号 京公网安备11010802020134号 京网文[2014]0059-0009号</div> 
			<div>违法和不良信息举报电话：185-0130-1238，本网站所列数据，除特殊说明，所有数据均出自我司实验室测试</div>
		</footer>
	</body>
</html>
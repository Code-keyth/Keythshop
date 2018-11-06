<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
        <meta name="author" content="order by dede58.com"/>
		<title>会员登录</title>
		<link rel="stylesheet" type="text/css" href="/public/Index/css/login.css">
	</head>
	<body>
		<!-- login -->
		<div class="top center">
			<div class="logo center">
				<a href="/" target="_blank"><img src="/public/Index/image/mistore_logo.png" alt=""></a>
			</div>
		</div>
		<form  method="post" class="form center">
		<div class="login">
			<div class="login_center">
				<div class="login_top">
					<div class="left fl">会员登录</div>
					<div class="right fr">您还不是我们的会员？<a href="register" target="_self">立即注册</a></div>
					<div class="clear"></div>
					<div class="xian center"></div>
				</div>
				<div class="login_main center">
					<div class="username">邮&nbsp;&nbsp;&nbsp;&nbsp;箱:&nbsp;<input class="shurukuang" type="text" name="email" placeholder="请输入你的注册邮箱"/></div>
					<div class="username">密&nbsp;&nbsp;&nbsp;&nbsp;码:&nbsp;<input class="shurukuang" type="password" name="password" placeholder="请输入你的密码"/></div>
					{{csrf_field()}}
					<div class="username">
						<div class="left fl">验证码:&nbsp;<input id="captcha"  required=""  class="yanzhengma" type="captcha" name="captcha" value="{{ old('captcha')  }}">
							@if($errors->has('captcha'))
								<div class="col-md-12"><p class="text-danger text-left"><strong class="x-red">验证码错误</strong></p></div>
							@endif</div>
						<div class="right fl"><img src="{{ url('/captcha') }}"  onclick="this.src='{{ url('/captcha') }}?r='+Math.random();">						</div>
						<div class="clear"></div>
					</div>
				</div>
				<div class="login_submit">
					<button class="submit" type="submit"  >立即登录</button>
				</div>
			</div>
		</div>
		</form>
		@if (session('status'))
			<script src="/public/Admin/lib/layui/layui.js" charset="utf-8"></script>
			<script>
                layui.use('layer', function(){
                    var layer = layui.layer;
                    layer.msg("{{ session('status') }}",{icon: 13,time: 2000,shade : [0.5 , '#000' , true]});
                });
			</script>
		@endif
		<footer>
			<div class="copyright">简体 | 繁体 | English | 常见问题</div>
			<div class="copyright">小米公司版权所有-京ICP备10046444-<img src="/public/Index/image/ghs.png" alt="">京公网安备11010802020134号-京ICP证110507号</div>

		</footer>
	</body>
</html>
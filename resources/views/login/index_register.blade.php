<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
        <meta name="author" content="order by dede58.com"/>
		<title>用户注册</title>
		<link rel="stylesheet" href="/public/Common/layui/css/layui.css">
		<script type="text/javascript" src="/public/Common/layui/layui.js" charset="utf-8"></script>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<link rel="stylesheet" type="text/css" href="/public/Index/css/login.css">


	</head>
	<body>
		<div class="regist">
			<div class="regist_center">
				<div class="regist_top">
					<div class="left fl">会员注册</div>
					<div class="right fr"><a href="./index.html" target="_self">小米商城</a></div>
					<div class="clear"></div>
					<div class="xian center"></div>
				</div>
				<div class="regist_main center">

					<form class="layui-form" action="/index/register" method="post">
						<div class="layui-form-item">
							<label for="L_email" class="layui-form-label">
								<span class="x-red">*</span>邮箱
							</label>
							<div class="layui-input-inline">
								<input type="text" id="L_email" name="email" required="" lay-verify="email" autocomplete="off" class="layui-input">
							</div>
							<div class="layui-form-mid layui-word-aux">
								<span class="x-red">*</span>将会成为您唯一的登入名
							</div>
						</div>
						<div class="layui-form-item">
							<label for="L_email" class="layui-form-label">
								<span class="x-red">*</span>邮箱验证码
							</label>
							<div class="layui-input-inline">
								<input type="text" required="" name="email_yzm" autocomplete="off" class="layui-input">
							</div>
							<div class="layui-form-mid layui-word-aux">
								<span onclick="send_email()" class="layui-btn layui-btn-primary layui-btn-xs">发送验证码</span>
							</div>
						</div>
						<div class="layui-form-item">
							<label for="L_username" class="layui-form-label">
								<span class="x-red">*</span>昵称
							</label>
							<div class="layui-input-inline">
								<input type="text" id="L_username" name="username" required="" lay-verify="nikename" autocomplete="off" class="layui-input">
							</div>
						</div>
						<div class="layui-form-item">
							<label for="L_pass" class="layui-form-label"><span class="x-red">*</span>密码</label>
							<div class="layui-input-inline">
								<input type="password" id="L_pass" name="password" required="" lay-verify="pass" autocomplete="off" class="layui-input">
							</div>
							<span class="x-red">*</span><div class="layui-form-mid layui-word-aux">6到16个字符</div>
						</div>
						{{csrf_field()}}
						<div class="layui-form-item">
							<label for="L_repass" class="layui-form-label"><span class="x-red">*</span>确认密码</label>
							<div class="layui-input-inline">
								<input type="password" id="L_repass" name="repass" required="" lay-verify="repass" autocomplete="off" class="layui-input">
							</div>
						</div>
						<div class="layui-form-item">
							<label for="L_username" class="layui-form-label"><span class="x-red">*</span>验证码</label>
							<div class="layui-input-inline" style="width: auto;" >
								<input id="captcha" style="width: 100px;"  required=""  class="form-control" type="captcha" name="captcha" value="{{ old('captcha')  }}">
								@if($errors->has('captcha'))
									<div class="col-md-12">
										<p class="text-danger text-left"><strong class="x-red">验证码错误</strong></p>
									</div>
								@endif
							</div>
							<div class="layui-word-aux">
								<img src="{{ url('/captcha') }}"  onclick="this.src='{{ url('/captcha') }}?r='+Math.random();">
							</div>
						</div>
						<div class="layui-form-item">
							<label for="L_repass" class="layui-form-label">
							</label>
							<button  style="width: 200px;" class="layui-btn layui-btn-warm" lay-filter="add" lay-submit="">
								增加
							</button>
						</div>
					</form>
			</div>
		</div>
		</div>
		@if (session('status'))
			<script src="/public/Admin/lib/layui/layui.js" charset="utf-8"></script>
			<script>
                layui.use('layer', function(){
                    var layer = layui.layer;
                    layer.msg("{{ session('status') }}",{icon: 13,time: 2000,shade : [0.5 , '#000' , true]});
                });
			</script>
		@endif
		<script>
            layui.use(['form','layer'], function(){
                $ = layui.jquery;
                var form = layui.form
                    ,layer = layui.layer;

                //自定义验证规则
                form.verify({
                    nikename: function(value){
                        if(value.length < 5){
                            return '昵称至少得5个字符啊';
                        }
                    }
                    ,pass: [/(.+){6,12}$/, '密码必须6到12位']
                    ,repass: function(value){
                        if($('#L_pass').val()!=$('#L_repass').val()){
                            return '两次密码不一致';
                        }
                    }
                });
            });


            function send_email() {
                var email=$('#L_email').val();
                var myreg = /^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;
                console.log(email);
                if(email === ""){ //输入不能为空
                    layer.msg("邮箱输入不能为空!");
                    return false;
                }else if(!myreg.test(email)){ //正则验证不通过，格式不对
                    layer.msg("验证不通过!");
                    return false;
                }else{
                    $.post('/ajax/sendemail',{'email':email,'_token':'{{csrf_token()}}'},function (data) {
                        if(data=='1'){
                            layer.msg('发送成功！');
                        }else if(data=='-1'){
                            layer.msg('不要重复发送验证码,请5分钟后尝试！');
						}
                        else{
                            layer.msg('发送失败！');
                        }
                    });
                    return true;
                }
            }
		</script>
	</body>
</html>
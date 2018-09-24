<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>后台登录</title>
	<meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />

    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" href="/public/Admin/css/font.css">
	<link rel="stylesheet" href="/public/Admin/css/xadmin.css">
    <script type="text/javascript" src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>

    <script type="text/javascript" src="/public/Admin/js/xadmin.js"></script>

</head>
<body class="login-bg">

    <div class="login">
        <div class="message">文章CMS-后台登录</div>
        <div id="darkbannerwrap"></div>
        
        <form action="" method="post" class="layui-form" >
            <input name="email" placeholder="email"  type="text" class="layui-input" >
            <hr class="hr15">
            {{csrf_field()}}
            <input name="password"  placeholder="密码"  type="password" class="layui-input">
            <hr class="hr15">

            <input value="登录"  style="width:100%;" type="submit">
            <hr class="hr20" >
        </form>
        <a href="/index/login/register.html"><input value="注册"  style="width:100%;" type="submit"></a>
    </div>
    @if (session('status'))
<script>
    layui.use('layer', function(){
        var layer = layui.layer;
        layer.msg("{{ session('status') }}");
    });
</script>
    @endif
</body>
</html>
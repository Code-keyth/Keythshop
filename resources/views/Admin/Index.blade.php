<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>后台登录-X-admin2.0</title>
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

    <meta http-equiv="Cache-Control" content="no-siteapp"/>

    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon"/>
    <link rel="stylesheet" href="/public/Admin/css/font.css">
    <link rel="stylesheet" href="/public/Admin/css/xadmin.css">

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="/public/Admin/lib/layui/layui.js" charset="utf-8"></script>
    <script type="text/javascript" src="/public/Admin/js/xadmin.js"></script>

</head>
<body>
<!-- 顶部开始 -->
<div class="container">
    <div class="logo"><a href="Index.blade.php">X-admin v2.0</a></div>
    <div class="left_open">
        <i title="展开左侧栏" class="iconfont">&#xe699;</i>
    </div>
    <ul class="layui-nav left fast-add" lay-filter="">
        <li class="layui-nav-item">
            <a href="javascript:;">+新增</a>
            <dl class="layui-nav-child"> <!-- 二级菜单 -->
                <dd><a onclick="x_admin_show('资讯','http://www.baidu.com')"><i class="iconfont">&#xe6a2;</i>资讯</a></dd>
                <dd><a onclick="x_admin_show('图片','http://www.baidu.com')"><i class="iconfont">&#xe6a8;</i>图片</a></dd>
                <dd><a onclick="x_admin_show('用户','http://www.baidu.com')"><i class="iconfont">&#xe6b8;</i>用户</a></dd>
            </dl>
        </li>
    </ul>
    <ul class="layui-nav right" lay-filter="">

        <li class="layui-nav-item to-   index">
            <a>您的角色</a>
            <dl class="layui-nav-child"> <!-- 二级菜单 -->
                @foreach($roles as $item)
                    <dd><a href="/admin/logout">{{$item}}</a></dd>
                @endforeach
            </dl>
        </li>
        <li class="layui-nav-item">
            <a href="javascript:;">{{$user['username']}}</a>
            <dl class="layui-nav-child"> <!-- 二级菜单 -->
                <dd><a onclick="x_admin_show('个人信息','http://www.baidu.com')">个人信息</a></dd>
                <dd><a onclick="x_admin_show('切换帐号','http://www.baidu.com')">切换帐号</a></dd>
                <dd><a href="/admin/logout">退出</a></dd>
            </dl>
        </li>
        <li class="layui-nav-item to-index"><a href="/">前台首页</a></li>
    </ul>

</div>
<!-- 顶部结束 -->
<!-- 中部开始 -->
<!-- 左侧菜单开始 -->
<div class="left-nav">
    <div id="side-nav">
        <ul id="nav">
            <li>
                <a href="javascript:;">
                    <i class="iconfont">&#xe735;</i>
                    <cite>商品管理</cite>
                    <i class="iconfont nav_right">&#xe697;</i>
                </a>
                <ul class="sub-menu">
                    <li>
                        <a _href="/admin/goods">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>商品列表</cite>

                        </a>
                    </li>
                    <li>
                        <a _href="/admin/goods_add">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>商品添加</cite>
                        </a>
                    </li>
                    <li>
                        <a _href="/admin/goods_type">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>商品分类</cite>

                        </a>
                    </li>
                    {{--<li>--}}
                        {{--<a _href="/admin/goods_evaluate">--}}
                            {{--<i class="iconfont">&#xe6a7;</i>--}}
                            {{--<cite>用户评价</cite>--}}

                        {{--</a>--}}
                    {{--</li>--}}
                    <li>
                        <a _href="/admin/goods_spec">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>规格管理</cite>

                        </a>
                    </li>
                    {{--<li>--}}
                        {{--<a _href="goods_activity">--}}
                            {{--<i class="iconfont">&#xe6a7;</i>--}}
                            {{--<cite>活动管理</cite>--}}

                        {{--</a>--}}
                    {{--</li>--}}
                    <li>
                        <a _href="/admin/goods_recycle">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>回收站</cite>

                        </a>
                    </li>

                </ul>
            </li>
            <li>
                <a href="javascript:;">
                    <i class="iconfont">&#xe724;</i>
                    <cite>订单管理</cite>
                    <i class="iconfont nav_right">&#xe697;</i>
                </a>
                <ul class="sub-menu">
                    <li>
                        <a _href="/admin/order">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>订单列表</cite>
                        </a>
                    </li>
                    <li>
                        <a _href="/admin/order_delivery">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>发货列表</cite>
                        </a>
                    </li>
                    {{--<li>--}}
                        {{--<a _href="/admin/order_retreat">--}}
                            {{--<i class="iconfont">&#xe6a7;</i>--}}
                            {{--<cite>退货列表</cite>--}}
                        {{--</a>--}}
                    {{--</li>--}}
                </ul>
            </li>
            <li>
                <a href="javascript:;">
                    <i class="iconfont">&#xe726;</i>
                    <cite>权限管理</cite>
                    <i class="iconfont nav_right">&#xe697;</i>
                </a>
                <ul class="sub-menu">
                    <li>
                        <a _href="/admin/admin_user">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>管理员列表</cite>
                        </a>
                    </li>

                    <li>
                        <a _href="/admin/admin_user_role">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>管理员角色</cite>
                        </a>
                    </li>
                    <li>
                        <a _href="/admin/admin_role">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>角色列表</cite>
                        </a>
                    </li>

                    <li>
                        <a _href="/admin/admin_power">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>权限管理</cite>
                        </a>
                    </li>
                    <li>
                        <a _href="/admin/admin_log">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>管理员日志</cite>
                        </a>
                    </li>

                </ul>
            </li>
            <li>
                <a href="javascript:;">
                    <i class="iconfont">&#xe732;</i>
                    <cite>会员管理</cite>
                    <i class="iconfont nav_right">&#xe697;</i>
                </a>
                <ul class="sub-menu">
                    <li>
                        <a _href="/admin/member">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>会员列表</cite>
                        </a>
                    </li>
                    <li>
                        <a _href="/admin/member_account">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>账单明细</cite>
                        </a>
                    </li>
                    <li>
                        <a _href="/admin/member_order">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>订单明细</cite>
                        </a>
                    </li>
                    {{--<li>--}}
                        {{--<a _href="/admin/member_message">--}}
                            {{--<i class="iconfont">&#xe6a7;</i>--}}
                            {{--<cite>会员留言</cite>--}}
                        {{--</a>--}}
                    {{--</li>--}}

                </ul>
            </li>
            <li>
                <a href="javascript:;">
                    <i class="iconfont">&#xe6fc;</i>
                    <cite>文章管理</cite>
                    <i class="iconfont nav_right">&#xe697;</i>
                </a>
                <ul class="sub-menu">
                    <li>
                        <a _href="/admin/article">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>文章列表</cite>
                        </a>
                    </li>
                    <li>
                        <a _href="/admin/article_type">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>文章分类</cite>
                        </a>
                    </li>
                    <li>
                        <a _href="/admin/article_recycle">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>文章回收站</cite>
                        </a>
                    </li>
                </ul>
            </li>

            <li>
                <a href="javascript:;">
                    <i class="iconfont">&#xe6b4;</i>
                    <cite>系统设置</cite>
                    <i class="iconfont nav_right">&#xe697;</i>
                </a>
                <ul class="sub-menu">
                    <li>
                        <a _href="/admin/setup_shop">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>商店设置</cite>
                        </a>
                    </li>
                    <li>
                        <a _href="/admin/setup_pay">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>支付设置</cite>
                        </a>
                    </li>
                    <li>
                        <a _href="/admin/setup_logistic">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>配送设置</cite>
                        </a>
                    </li>
                    {{--<li>--}}
                        {{--<a _href="/admin/setup_parent">--}}
                            {{--<i class="iconfont">&#xe6a7;</i>--}}
                            {{--<cite>推荐人设置</cite>--}}
                        {{--</a>--}}
                    {{--</li>--}}
                </ul>
            </li>
            <li>
                <a href="javascript:;">
                    <i class="iconfont">&#xe74c;</i>
                    <cite>报表统计</cite>
                    <i class="iconfont nav_right">&#xe697;</i>
                </a>
                <ul class="sub-menu">
                    <li>
                        <a _href="/admin/statis_member">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>会员统计</cite>
                        </a>
                    </li>
                    <li>
                        <a _href="/admin/statis_order">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>订单统计</cite>
                        </a>
                    </li>
                    <li>
                        <a _href="/admin/statis_goods">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>商品统计</cite>
                        </a>
                    </li>
                </ul>
            </li>

        </ul>
    </div>
</div>
<!-- <div class="x-slide_left"></div> -->
<!-- 左侧菜单结束 -->
<!-- 右侧主体开始 -->
<div class="page-content">
    <div class="layui-tab tab" lay-filter="xbs_tab" lay-allowclose="false">
        <ul class="layui-tab-title">
            <li class="home"><i class="layui-icon">&#xe68e;</i>我的桌面</li>
        </ul>
        <div class="layui-tab-content">
            <div class="layui-tab-item layui-show">
                <iframe src='/admin/welcome' frameborder="0" scrolling="yes" class="x-iframe"></iframe>
            </div>
        </div>
    </div>
</div>
<div class="page-content-bg"></div>
<!-- 右侧主体结束 -->
<!-- 中部结束 -->
<!-- 底部开始 -->
<div class="footer">
    <div class="copyright">Copyright ©2017 x-admin v2.3 All Rights Reserved</div>
</div>
<!-- 底部结束 -->
@if (session('status'))
    <script>
        layui.use('layer', function () {
            var layer = layui.layer;
            layer.msg("{{ session('status') }}", {icon: 16, time: 2000, shade: [0.5, '#000', true]});
        });
    </script>
@endif
</body>
</html>
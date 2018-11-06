<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>欢迎页面-X-admin2.0</title>
        <meta name="renderer" content="webkit">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

        <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
        <link rel="stylesheet" href="/public/Admin/css/font.css">
        <link rel="stylesheet" href="/public/Admin/css/xadmin.css">
    </head>
    <body>
    <div class="x-body layui-anim layui-anim-up">
        <blockquote class="layui-elem-quote">欢迎管理员：
            <span class="x-red">{{$username}}</span>！当前时间:{{date('Y-m-d H:i:s',time())}}</blockquote>
        <fieldset class="layui-elem-field">
            <legend>数据统计</legend>
            <div class="layui-field-box">
                <div class="layui-col-md12">
                    <div class="layui-card">
                        <div class="layui-card-body">
                            <div class="layui-carousel x-admin-carousel x-admin-backlog" lay-anim="" lay-indicator="inside" lay-arrow="none" style="width: 100%; height: 90px;">
                                <div carousel-item="">
                                    <ul class="layui-row layui-col-space10 layui-this">

                                        <li class="layui-col-xs2">
                                            <a href="javascript:;" class="x-admin-backlog-body">
                                                <h3>商品数</h3>
                                                <p>
                                                    <cite>{{$goods_number}}</cite></p>
                                            </a>
                                        </li>
                                        <li class="layui-col-xs2">
                                            <a href="javascript:;" class="x-admin-backlog-body">
                                                <h3>商品分类数</h3>
                                                <p>
                                                    <cite>{{$goods_type_number}}</cite></p>
                                            </a>
                                        </li>
                                        <li class="layui-col-xs2">
                                            <a href="javascript:;" class="x-admin-backlog-body">
                                                <h3>会员数</h3>
                                                <p>
                                                    <cite>{{$member_number}}</cite></p>
                                            </a>
                                        </li>
                                        <li class="layui-col-xs2">
                                            <a href="javascript:;" class="x-admin-backlog-body">
                                                <h3>订单数</h3>
                                                <p>
                                                    <cite>{{$order_number}}</cite></p>
                                            </a>
                                        </li>

                                        <li class="layui-col-xs2">
                                            <a href="javascript:;" class="x-admin-backlog-body">
                                                <h3>管理员数</h3>
                                                <p>
                                                    <cite>{{$admin_number }}</cite></p>
                                            </a>
                                        </li>

                                        <li class="layui-col-xs2">
                                            <a href="javascript:;" class="x-admin-backlog-body">
                                                <h3>文章数</h3>
                                                <p>
                                                    <cite>{{$article_number}}</cite></p>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>
        <fieldset class="layui-elem-field">
            <legend>管理员信息</legend>
            <div class="layui-field-box">
                <table class="layui-table" lay-skin="line">
                    <tbody>
                        <tr>
                            <td >
                                <a class="x-a" href="/" target="_blank">管理员微信:ZQ631347947</a>
                            </td>
                        </tr>
                        <tr><td ><a class="x-a" href="/" target="_blank">管理员邮箱:zhangqi@codekeyth.cn</a></td></tr>
                        <tr><td ><a class="x-a" href="/" target="_blank">管理员TEL:15136079974</a></td></tr>
                    </tbody>
                </table>
            </div>
        </fieldset>
        <fieldset class="layui-elem-field">
            <legend>系统信息</legend>
            <table class="layui-table">
                <thead>
                <tr>
                    <th colspan="2" scope="col">服务器信息</th>
                </tr>
                </thead>
                <tbody>

                <tr>
                    <td>服务器域名</td>
                    <td><?php echo $_SERVER['HTTP_HOST'];?></td>
                </tr>
                <tr>
                    <td>服务器IP</td>
                    <td><?php echo ($_SERVER['REMOTE_ADDR']);?></td>
                </tr>
                <tr>
                    <td>服务器操作系统</td>
                    <td><?PHP echo PHP_OS; ?></td>
                </tr>
                <tr>
                    <td>服务器信息 </td>
                    <td><?PHP echo $_SERVER ['SERVER_SOFTWARE']; ?></td>
                </tr>


                <tr>
                    <td>语言种类 </td>
                    <td>PHP</td>
                </tr>
                <tr>
                    <td>PHP 版本 </td>
                    <td><?PHP echo PHP_VERSION; ?></td>
                </tr>

                <tr>
                    <td>ZEND版本 </td>
                    <td><?php echo $systemInfo['zendversion'] = zend_version(); ?></td>
                </tr>
                <tr>
                    <td>MYSQL是否持续连接 </td>
                    <td><?php echo @get_cfg_var("mysql.allow_persistent")?"是 ":"否"; ?></td>
                </tr>
                <tr>
                    <td>脚本运行占用最大内存 </td>
                    <td><?php echo  $systemInfo['memorylimit'] = get_cfg_var("memory_limit") ? get_cfg_var("memory_limit") : '-'; ?></td>
                </tr>


                </tbody>
            </table>
        </fieldset>

        <blockquote class="layui-elem-quote layui-quote-nm">感谢layui,百度Echarts,jquery,本系统由Keyth提供技术支持。</blockquote>
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
    </body>
</html>
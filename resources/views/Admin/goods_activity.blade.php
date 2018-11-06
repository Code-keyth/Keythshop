<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>欢迎页面-X-admin2.0</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport"
          content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi"/>
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon"/>
    <link rel="stylesheet" href="/public/Admin/css/font.css">
    <link rel="stylesheet" href="/public/Admin/css/xadmin.css">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script type="text/javascript" src="/public/Admin/lib/layui/layui.js" charset="utf-8"></script>
    <script type="text/javascript" src="/public/Admin/js/xadmin.js"></script>
    <!-- 让IE8/9支持媒体查询，从而兼容栅格 -->
    <!--[if lt IE 9]>
    <script src="https://cdn.staticfile.org/html5shiv/r29/html5.min.js"></script>
    <script src="https://cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>
<div class="x-nav">
      <span class="layui-breadcrumb">
        <a href="">首页</a>
        <a href="">商品管理</a>
        <a>
          <cite>商品列表</cite></a>
      </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right"
       href="javascript:location.replace(location.href);" title="刷新">
        <i class="layui-icon" style="line-height:30px">ဂ</i></a>
</div>
<div class="x-body">

    <xblock>
        <button class="layui-btn layui-btn-danger" onclick="delAll()"><i class="layui-icon"></i>批量删除</button>
        <span class="x-right" style="line-height:40px">共有数据：{{$goods_activitys->total()}}条</span>
    </xblock>

    <table class="layui-table">
        <thead>
        <tr>
            <th>
                <div class="layui-unselect header layui-form-checkbox" lay-skin="primary"><i
                            class="layui-icon">&#xe605;</i></div>
            </th>
            <th>商品号</th>
            <th>商品名</th>
            <th>已购数量</th>
            <th>活动数量(件)</th>
            <th>活动售价(￥)</th>
            <th>原售价</th>
            <th>活动开始时间</th>
            <th>活动结束时间</th>
            <th>状态</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>

        @foreach($goods_activitys as $item)
            <tr>
                <td>
                    <div class="layui-unselect layui-form-checkbox" lay-skin="primary" data-id='{{$item->id}}'><i
                                class="layui-icon">&#xe605;</i></div>
                </td>
                <td>{{$item->goods_sn}}</td>
                <td>{{$item->goods_name}}</td>
                <td>{{$item->activity_number_already}}件</td>
                <td>
                    <input style="width: 100px;" type="text" class="layui-input x-sort"
                           value="{{$item->activity_number}}">
                </td>
                <td>
                    <input style="width: 100px;" type="text" class="layui-input x-sort"
                           value="{{$item->promote_price}}">
                </td>
                <td>{{$item->price}}￥</td>
                <td>
                    <input class="layui-input" id="date002" value="{{date('Y-m-d H:i:s',$item->promote_start_date)}}"
                           placeholder="yyyy-MM-dd HH:mm:ss" type="text">
                </td>
                <td>
                    <input class="layui-input" id="date001" value="{{date('Y-m-d H:i:s',$item->promote_end_date)}}"
                           placeholder="yyyy-MM-dd HH:mm:ss" type="text">
                </td>
                <td>
                    @if(time() < $item->promote_start_date and $item->promote_start_date < $item->promote_end_date)
                        <span class="layui-badge layui-bg-green">未开始</span>
                    @elseif( $item->promote_start_date < time() and time()< $item->promote_end_date)
                        <span class="layui-badge layui-bg-blue">活动中</span>
                    @elseif(time() > $item->promote_end_date and $item->promote_start_date < $item->promote_end_date)
                        <span class="layui-badge">已结束</span>
                    @else
                        <span class="layui-badge">无效活动</span>
                    @endif
                </td>
                <td class="td-manage">
                    <a title="保存" onclick="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js(this)">
                        <i class="icon iconfont">&#xe747;</i>
                    </a>
                    <a title="删除" onclick="member_del(this,'{{$item->id}}')" href="javascript:;">
                        <i class="icon iconfont">&#xe69d;</i>
                    </a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="page">
        {{$goods_activitys->links()}}
    </div>
</div>
<script>
    layui.use('laydate', function () {
        var laydate = layui.laydate;

        //执行一个laydate实例
        laydate.render({
            elem: '#date001' //指定元素
            , type: 'datetime'
        });

        //执行一个laydate实例
        laydate.render({
            elem: '#date002' //指定元素
            , type: 'datetime'
        });
    });


    /*用户-删除*/
    function member_del(obj, id) {
        layer.confirm('确认要删除吗？', function (index) {
            $.get('/ajax/get_up_state',{'name':7,'value':1,'uuid':id,'type':0},function (ret) {
                if(ret>0){
                    $(obj).parents("tr").remove();
                    layer.msg('已删除!',{icon:1,time:1000});
                    return 0;
                }
                layer.msg('删除失败!',{icon:0,time:1000});
            });
        });
    }

    function delAll(argument) {
        var data = tableCheck.getData();
        layer.confirm('确认要删除吗？' + data, function (index) {
            //捉到所有被选中的，发异步进行删除
            layer.msg('删除成功', {icon: 1});
            $(".layui-form-checked").not('.header').parents('tr').remove();
        });
    }

    function https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js(obj) {
        var inputs = $(obj).parents("tr").find("input");
        var uuid = $(obj).parents('tr').find("div").first().attr('data-id');
        $.post('/ajax/https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js', {'uuid': uuid, 'activity_number': inputs[0].value, 'promote_price': inputs[1].value, 'activity_start_date': inputs[2].value, 'activity_end_date': inputs[3].value, '_token': '{{csrf_token()}}'
        }, function (ret) {
            if (ret) {
                layer.msg('保存成功!', {icon: 6, time: 1000});
            }else{
                layer.msg('数据错误!', {icon: 6, time: 1000});
            }

        });
    }
</script>

</body>

</html>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>欢迎页面-X-admin2.0</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon"/>
    <link rel="stylesheet" href="/public/Admin/css/font.css">
    <link rel="stylesheet" href="/public/Admin/css/xadmin.css">
    <script type="text/javascript" src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
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
        <a href="">演示</a>
        <a>
          <cite>导航元素</cite></a>
      </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right"
       href="javascript:location.replace(location.href);" title="刷新">
        <i class="layui-icon" style="line-height:30px">ဂ</i></a>
</div>

<div class="x-body">
    <div class="layui-row">
        <form class="layui-form">


            <div class="layui-inline">
                <div class="layui-input-inline" style="width: 150px;">
                    <input type="text" class="layui-input" placeholder="栏目名称(必填)" lay-verify="required"
                           value="{{$type['name1']}}" name="type_title" lay-filter="type_title">
                </div>
                <div class="layui-input-inline" style="width: 300px;">
                    <input value="{{$type['name2']}}" class="layui-input" lay-filter="bried" name="bried"
                           placeholder="描述">
                </div>
            </div>
            <input name="id" value="{{$type['name3']}}" hidden="hidden">
            {{csrf_field()}}
            <button class="layui-btn" lay-filter="add" lay-submit="">
                保存
            </button>
        </form>
    </div>
    <xblock>

        <button class="layui-btn" onclick="x_admin_show('添加分类','/admin/article_type_add',400,500)"
                lay-filter="sreach"><i class="layui-icon"></i>添加
        </button>
        <span class="x-right" style="line-height:40px">共有数据：{{count($article_types)}} 条</span>
    </xblock>
    <table class="layui-table layui-form">
        <thead>
        <tr>
            <th width="20">
                <div class="layui-unselect header layui-form-checkbox" lay-skin="primary"><i
                            class="layui-icon">&#xe605;</i></div>
            </th>
            <th width="70">ID</th>
            <th>栏目名</th>

            <th>描述</th>
            <th width="220">操作</th>
        </thead>

        @foreach($article_types as $item)
            <tr>
                <td>
                    <div class="layui-unselect layui-form-checkbox" lay-skin="primary" data-id='{{$item->id}}'><i
                                class="layui-icon">&#xe605;</i></div>
                </td>
                <td>{{$item->id}}</td>
                <td>
                    {{$item->type_title}}
                </td>
                <td>{{$item->bried}}</td>

                <td class="td-manage">
                    <a href="?name1={{$item->type_title}}&name2={{$item->bried}}&name3={{$item->id}}">
                        <button class="layui-btn layui-btn layui-btn-xs"><i class="layui-icon">&#xe642;</i>编辑</button>
                    </a>
                    <button class="layui-btn-danger layui-btn layui-btn-xs" onclick="member_del(this,'{{$item->id}}')"
                            href="javascript:;"><i class="layui-icon">&#xe640;</i>删除
                    </button>
                </td>
            </tr>

        @endforeach

    </table>
</div>
<style type="text/css">

</style>
<script>
    layui.use(['form'], function () {
        form = layui.form;
        form.on('switch(show_in_nav)', function (data) {
            var obj = data.elem;
            var id = $(obj).parents('tr').find("div").first().attr('data-id');
            var value = $(obj).attr('value');
            var name = $(obj).attr('name');
            if (value == 1) {
                $(obj).attr('value', 0);
            } else {
                $(obj).attr('value', 1);
            }
            $.get('/ajax/get_up_state', {'name': name, 'value': value, 'uuid': id, 'type': 1}, function (ret) {
                if (ret) {
                    if (value == 0) {
                        $(obj).attr('class', 'layui-badge');
                        $(obj).attr('onclick', 'up_state(this,' + name + ',1)');
                        layer.msg('已启用!', {icon: 6, time: 1000});
                    } else {
                        $(obj).attr('class', 'layui-badge layui-bg-gray');
                        $(obj).attr('onclick', 'up_state(this,' + name + ',0)');
                        layer.msg('已停用!', {icon: 5, time: 1000});
                    }
                }
            });

        });
        form.on('submit(add)', function (data) {
            $.post('/ajax/add_article_type', datas = data.field, function (ret) {
                if (ret == '1') {
                    layer.alert("增加成功", {icon: 1});
                } else {
                    layer.alert("增加失败", {icon: 2});
                }
            });
            return false;
        });
    });

    /*用户-删除*/
    function member_del(obj, id) {
        layer.confirm('确认要删除吗？', function (index) {
            //发异步删除数据
            $.get('/ajax/get_up_state', {'name': 6, 'value': 1, 'uuid': id, 'type': 3}, function (ret) {
                if (ret > 0) {
                    $(obj).parents("tr").remove();
                    layer.msg('已删除!', {icon: 1, time: 1000});
                    return 0;
                } else {
                    layer.msg('删除失败!', {icon: 2, time: 1000});
                }
            });

        });


    }


</script>

</body>

</html>
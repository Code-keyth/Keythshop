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
        <a href="">演示</a>
        <a>
          <cite>导航元素</cite></a>
      </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right"
       href="javascript:location.replace(location.href);" title="刷新">
        <i class="layui-icon" style="line-height:30px">ဂ</i></a>
</div>
<div class="layui-row" id="add_div" hidden='True'>
    <form class="layui-form">
        <div  style="margin: 5px 0 0 5px;" class="layui-inline">
            <div class="layui-input-inline" style="width: 150px;">
                <input type="text" class="layui-input" placeholder="角色名称(必填)" lay-verify="required"
                       value="{{$type['name1']}}" name="role_name">
            </div>
        </div>
        {{csrf_field()}}

        <button class="layui-btn" lay-filter="add" lay-submit="">
            保存
        </button>
    </form>
</div>
<div class="x-body">
    <xblock>
        <button class="layui-btn" onclick="add_admin_role()" lay-filter="sreach"><i class="layui-icon"></i>添加
        </button>
        <span class="x-right" style="line-height:40px">共有数据：{{count($admin_roles)}} 条</span>
    </xblock>
    <table class="layui-table layui-form">
        <thead>
        <tr>

            <th width="70">ID</th>
            <th>角色名称</th>
            <th>角色代码</th>
            <th width="220">操作</th>
        </thead>

        @foreach($admin_roles as $item)
            <tr>

                </td>
                <td>{{$item->id}}</td>
                <td>
                    <a style="float: left;padding: 8px 5px;" title="解锁输入框" onclick="play_passwd(this)"><i class="icon iconfont">&#xe719;</i>
                    </a>
                    <input style="width: 80%;"  type="text" disabled  value="{{$item->role_name}}" class="layui-input">

                </td>
                <td>{{$item->role_action}}</td>

                <td class="td-manage">
                    <a>
                        <button class="layui-btn layui-btn layui-btn-xs" onclick="save_role_name(this,{{$item->id}})"><i class="layui-icon">&#xe605;</i>保存</button>
                    </a>
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

            $.post('/ajax/post_add_role', datas = data.field, function (ret) {
                if (ret == '1') {
                    layer.alert("添加成功！", {icon: 1});
                } else {
                    layer.alert("请不要重复添加！", {icon: 2});
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
    /*显示隐藏添加角色*/
    function add_admin_role() {
        var hidden=$('#add_div').attr('hidden');
        if(hidden=='hidden'){
            $('#add_div').removeAttr('hidden');
        }else{
            $('#add_div').attr('hidden',true);
        }
    }
    function play_passwd(obj) {
        var name_node=$(obj).next();
        name_node.removeAttr('disabled');
        layer.msg('解锁成功', {icon: 1});
    }
    function save_role_name(obj,id) {
        var name_node=$(obj).parents('tr').children();
        var input2_length=($(name_node[2]).children()).length/2;
        var data=new Array();
        data['role_name']=$($(name_node[1]).children()[1]).attr('value');
        data['uuid']=id;
        data['_token']='{{csrf_token()}}';
        data['stre']='';
        for(var i=0;i<input2_length;i++){
            var cc=$(($(name_node[2]).children())[(2*i)+1]);
            if(cc.attr('class') == 'layui-unselect layui-form-checkbox'){
                data['stre']+=' 0';
            }else{
                data['stre']+=' 1';
            }
        }

        $.post('/ajax/post_add_admin_role', {'role_name':data['role_name'],'uuid':data['uuid'],'_token':data['_token'],'action':data['stre']}, function (ret) {
            if (ret == '1') {
                layer.alert("修改成功！", {icon: 1});
            } else {
                layer.alert("没有修改或修改失败！", {icon: 2});
            }
        });


    }
</script>

</body>

</html>
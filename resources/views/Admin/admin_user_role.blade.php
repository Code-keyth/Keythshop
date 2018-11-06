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
            <th>管理员名称</th>
            <th>管理员邮箱</th>
            <th>拥有权限</th>
            <th width="220">操作</th>
        </thead>

        @foreach($admins as $item)
            <tr>
                <td>{{$item->id}}</td>
                <td>{{$item->username}}</td>
                <td>{{$item->email}}</td>
                <td>
                    @foreach($admin_roles as $item2)
                        <input type="checkbox"  @if(in_array($item2->id,explode(',',$item->role_id))|| $item->root==1) checked @endif value="{{$item2->id}}"  title="{{$item2->role_name}}">
                        @endforeach
                </td>
                <td class="td-manage">
                    <a>
                        <button class="layui-btn layui-btn layui-btn-xs" onclick="save_role_name(this,{{$item->id}})"><i class="layui-icon">&#xe605;</i>保存</button>
                    </a>
                </td>
            </tr>
        @endforeach

    </table>
</div>

<script>
    function save_role_name(obj,id) {
        var name_node=$(obj).parents('tr').children();
        var input2_length=($(name_node[3]).children()).length/2;
        var data=new Array();

        data['uuid']=id;
        data['_token']="{{csrf_token()}}";
        data['roles']='';
        for(var i=0;i<input2_length;i++){
            var cc=$(($(name_node[3]).children())[(2*i)+1]);
            var cc2=$(($(name_node[3]).children())[2*i]);
            if(cc.attr('class') == 'layui-unselect layui-form-checkbox layui-form-checked'){
                data['roles']+=cc2.val()+',';
            }
        }
        $.post('/ajax/post_admin_role', {'uuid':data['uuid'],'_token':data['_token'],'roles':data['roles']}, function (ret) {
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
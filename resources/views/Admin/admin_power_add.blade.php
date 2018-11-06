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

    <![endif]-->
</head>
<body>

<div class="x-nav">
      <span class="layui-breadcrumb">
        <a href="">首页</a>
        <a href="">权限管理</a>
        <a>
          <cite>权限添加</cite></a>
      </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right"
       href="javascript:location.replace(location.href);" title="刷新">
        <i class="layui-icon" style="line-height:30px">ဂ</i></a>
</div>
<div class="x-body">

    <form class="layui-form" >
        <div class="layui-form-item">
            <label class="layui-form-label">
                <span class="x-red">*</span>权限描述
            </label>
            <div class="layui-input-inline">
                <input type="text" name="action_desc" required="" value="{{$power->action_desc}}" autocomplete="off"
                       class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">
                <span class="x-red">*</span>所属角色
            </label>
            <div class="layui-input-inline">
                <select name="parent_id" lay-verify="">
                    <option value="">请选择</option>
                    @foreach($admin_roles as $item)
                        <option @if($item->id==$power->parent_id ) selected @endif value="{{$item->id}}">{{$item->role_name}}</option>
                    @endforeach
                </select>

            </div>
        </div>
        {{csrf_field()}}

        <div class="layui-form-item">
            <label class="layui-form-label">
                <span class="x-red">*</span>权限地址
            </label>
            <div class="layui-input-inline">
                <input type="text" name="action" required="" value="{{$power->action}}" autocomplete="off"
                       class="layui-input">
            </div>
        </div>
        <input type="hidden" name="id" value="{{$power->id}}" class="layui-input">


        <div class="layui-form-item">
            <label for="L_repass" class="layui-form-label"></label>
            <button  class="layui-btn" lay-filter="add" lay-submit="">
                提交
            </button>
        </div>
    </form>

</div>

<script>
    layui.use(['form','layer'], function(){
        $ = layui.jquery;
        var form = layui.form
            ,layer = layui.layer;

        form.on('submit(add)', function(data){
            console.log(data);
            var datas=data['field'];
            $.post('/ajax/post_power_add',datas,function (ret) {
                console.log(ret);
                if(ret=='1'){
                    layer.alert("增加成功！", {icon: 6},function () {
                        var index = parent.layer.getFrameIndex(window.name);
                        parent.layer.close(index);
                    });
                }else if(ret == '0'){
                    layer.alert("增加失败，请重试！", {icon: 5});
                }else{
                    layer.alert("增加失败，地址重复！", {icon: 5});
                }
            });

            return false;
        });


    });
</script>


</body>

</html>
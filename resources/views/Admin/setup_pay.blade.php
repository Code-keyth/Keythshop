<!DOCTYPE html>
<html>
  
  <head>
    <meta charset="UTF-8">
    <title>欢迎页面-X-admin2.0</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi" />
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
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
  
  <body class="layui-anim layui-anim-up">
    <div class="x-nav">
      <span class="layui-breadcrumb">
        <a href="">首页</a>
        <a href="">演示</a>
        <a>
          <cite>导航元素</cite></a>
      </span>
      <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新">
        <i class="layui-icon" style="line-height:30px">ဂ</i></a>
    </div>
    <div class="x-body">

      <xblock>
        <button class="layui-btn layui-btn-danger" onclick="delAll()"><i class="layui-icon"></i>批量删除</button>
        {{--<button class="layui-btn" onclick="x_admin_show('添加用户','/public/Admin/member-add.html',600,400)"><i class="layui-icon"></i>添加</button>--}}
        <span class="x-right" style="line-height:40px">共有数据：{{$pays->total()}} 条</span>
      </xblock>
      <table class="layui-table">
        <thead>
          <tr>
            <th>
              <div class="layui-unselect header layui-form-checkbox" lay-skin="primary"><i class="layui-icon">&#xe605;</i></div>
            </th>
            <th>ID</th>
            <th>支付代码</th>
            <th>支付名称</th>
            <th>支付描述</th>

            <th>状态</th>
            <th>操作</th></tr>
        </thead>
        <tbody>
        @foreach($pays as $item)
        <tr>
            <td>
              <div class="layui-unselect layui-form-checkbox" lay-skin="primary" data-id='{{$item->id}}'><i class="layui-icon">&#xe605;</i></div>
            </td>
            <td>{{$item->id}}</td>
            <td>{{$item->pay_code}}</td>
            <td>{{$item->pay_name}}</td>
            <td>{{$item->pay_desc}}</td>

            <td class="td-status">
              @if($item->state==1)
                <span class="layui-btn layui-btn-normal layui-btn-mini">已启用</span>
              @else
                <span class="layui-btn layui-btn-normal layui-btn-mini layui-btn-disabled">已停用</span>
                @endif
            </td>
            <td class="td-manage">

              @if($item->state==1)
                <a onclick="member_stop(this,{{$item->id}})" href="javascript:;"  title="停用">
                  <i class="layui-icon">&#xe601;</i>
                </a>
          @else
                <a onclick="member_stop(this,{{$item->id}})" href="javascript:;"  title="启用">
                  <i class="layui-icon">&#xe62f;</i>
                </a>
          @endif
              <a title="编辑"  onclick="x_admin_show('编辑','member_add?id={{$item->id}}',550,700)" href="javascript:;">
                <i class="layui-icon">&#xe642;</i>
              </a>
            </td>
          </tr>
          @endforeach

        </tbody>
      </table>
      <div class="page">
      {{$pays->links()}}
      </div>

    </div>
    <script>
      layui.use('laydate', function(){
        var laydate = layui.laydate;
        
        //执行一个laydate实例
        laydate.render({
          elem: '#start' //指定元素
        });

        //执行一个laydate实例
        laydate.render({
          elem: '#end' //指定元素
        });
      });

       /*用户-停用*/
      function member_stop(obj,id){
          var value=0;
          var msg='启用';
          if($(obj).attr('title')=='停用'){
              value=1;
              msg='停用';
          }
          layer.confirm('确认要'+msg+'吗？',function(index){

              $.get('/ajax/get_up_state',{'uuid':id,'name':6,'value':value,'type':6},function (ret) {
                 if(ret=='1'){
                     if(value==1){
                         //发异步把用户状态进行更改
                         $(obj).attr('title','启用')
                         $(obj).find('i').html('&#xe62f;');
                         $(obj).parents("tr").find(".td-status").find('span').addClass('layui-btn-disabled').html('已停用');
                         layer.msg('已停用!',{icon: 5,time:1000});
                     }else{
                         $(obj).attr('title','停用')
                         $(obj).find('i').html('&#xe601;');
                         $(obj).parents("tr").find(".td-status").find('span').removeClass('layui-btn-disabled').html('已启用');
                         layer.msg('已启用!',{icon: 6,time:1000});
                     }
                 }
              });

              
          });
      }
    </script>
  </body>

</html>
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
      <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新">
        <i class="layui-icon" style="line-height:30px">ဂ</i></a>
    </div>
    <div class="x-body">

      <xblock>
        <button class="layui-btn layui-btn-danger" onclick="delAll()"><i class="layui-icon"></i>批量删除</button>
        <button class="layui-btn"  lay-submit="" onclick="x_admin_show('添加分类','/admin/goods_type_add',400,500)" lay-filter="sreach"><i class="layui-icon"></i>添加</button>
        <span class="x-right" style="line-height:40px">共有数据：{{count($goods_types)}} 条</span>
      </xblock>
      <table class="layui-table layui-form">
        <thead>
          <tr>
            <th width="20">
              <div class="layui-unselect header layui-form-checkbox" lay-skin="primary"><i class="layui-icon">&#xe605;</i></div>
            </th>
            <th width="70">ID</th>
            <th>栏目名</th>
            <th width="50">排序</th>
            <th width="50">导航显示</th>
            <th width="220">操作</th>
        </thead>
        <tbody class="x-cate">

    @foreach($goods_types as $item)
          <tr cate-id='{{$item->id}}' fid='{{$item->parent_id}}' >
            <td>
              <div class="layui-unselect layui-form-checkbox" lay-skin="primary" data-id='{{$item->id}}'><i class="layui-icon">&#xe605;</i></div>
            </td>
            <td>{{$item->id}}</td>
            <td>
              @if($item->parent_id==0) <i class="layui-icon x-show" status='true'>&#xe623;</i>
              @else &nbsp;&nbsp;&nbsp;&nbsp;├
              @endif
              {{$item->type_name}}
            </td>
            <td>{{$item->sort_order}}</td>
            <td>
              <input type="checkbox" value="{{$item->show_in_nav}}" name="5" @if($item->show_in_nav==1) checked @endif lay-text="开启|停用" lay-filter="show_in_nav"  lay-skin="switch">
            </td>
            <td class="td-manage">
              <button class="layui-btn layui-btn layui-btn-xs"  onclick="x_admin_show('编辑','/admin/goods_type_add?id={{$item->id}}',400,500)" ><i class="layui-icon">&#xe642;</i>编辑</button>
              <button class="layui-btn-danger layui-btn layui-btn-xs"  onclick="member_del(this,'{{$item->id}}')" href="javascript:;" ><i class="layui-icon">&#xe640;</i>删除</button>
            </td>
          </tr>

      @endforeach
        </tbody>
      </table>
    </div>
    <style type="text/css">

    </style>
    <script>
      layui.use(['form'], function(){
        form = layui.form;
        form.on('switch(show_in_nav)', function(data){
              var obj=data.elem;
              var id=$(obj).parents('tr').find("div").first().attr('data-id');
              var value=$(obj).attr('value');
              var name=$(obj).attr('name');
              if(value==1){
                  $(obj).attr('value',0);
              }else{
                  $(obj).attr('value',1);
              }
            $.get('/ajax/get_up_state',{'name':name,'value':value,'uuid':id,'type':1},function (ret) {
                if(ret){
                    if(value==0){
                        $(obj).attr('class','layui-badge');
                        $(obj).attr('onclick','up_state(this,'+name+',1)');
                        layer.msg('已启用!',{icon: 6,time:1000});
                    }else{
                        $(obj).attr('class','layui-badge layui-bg-gray');
                        $(obj).attr('onclick','up_state(this,'+name+',0)');
                        layer.msg('已停用!',{icon:5,time:1000});
                    }
                }
            });

          });
      });

      /*用户-删除*/
      function member_del(obj,id){

          $.get('/ajax/get_type_posterity',{'id':id},function (ret) {
              if(ret[0]!=0 || ret[1]!=0){
                  console.log(ret);
                  layer.confirm('该分类下有'+ret[0]+'个子分类，以及'+ret[1]+'个商品，确认要一并删除吗？',function(index){
                      //发异步删除数据
                      $.get('/ajax/get_up_state',{'name':6,'value':1,'uuid':id,'type':1},function (ret) {
                          if(ret>0){
                              $(obj).parents("tr").remove();
                              layer.msg('已删除!',{icon:1,time:1000});
                              return 0;
                          }else{
                              layer.msg('删除失败!',{icon:2,time:1000});
                          }
                      });

                  });
              }
          });


      }

      function delAll (argument) {
        var data = tableCheck.getData();

        layer.confirm('确认要删除吗？'+data,function(index){
            //捉到所有被选中的，发异步进行删除
            layer.msg('删除成功', {icon: 1});
            $(".layui-form-checked").not('.header').parents('tr').remove();
        });
      }

      function up_state(obj,name,value) {
          console.log($(obj).parents("tr"));
          $.get('/ajax/get_up_state',{'name':name,'value':value,'uuid':id,'type':2},function (ret) {
              if(ret){
                  if(value==0){
                      $(obj).attr('class','layui-badge');
                      $(obj).attr('onclick','up_state(this,'+name+',1)');
                      layer.msg('已启用!',{icon: 6,time:1000});
                  }else{
                      $(obj).attr('class','layui-badge layui-bg-gray');
                      $(obj).attr('onclick','up_state(this,'+name+',0)');
                      layer.msg('已停用!',{icon:5,time:1000});
                  }
              }
          });

      }

    </script>

  </body>

</html>
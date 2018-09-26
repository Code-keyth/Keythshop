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
        <a href="">文章管理</a>
        <a>
          <cite>文章列表</cite></a>
      </span>
      <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新">
        <i class="layui-icon" style="line-height:30px">ဂ</i></a>
    </div>
    <div class="x-body">

      <xblock>
        <button class="layui-btn layui-btn-danger" onclick="delAll()"><i class="layui-icon"></i>批量删除</button>
        <button class="layui-btn" onclick="x_admin_show('添加商品','article_add')"><i class="layui-icon"></i>添加</button>
        <span class="x-right" style="line-height:40px">共有数据：{{$articles->total()}}条</span>
      </xblock>
      <table class="layui-table">
        <thead>
          <tr>
            <th>
              <div class="layui-unselect header layui-form-checkbox" lay-skin="primary"><i class="layui-icon">&#xe605;</i></div>
            </th>
            <th>文章ID</th>
            <th>标题</th>
            <th>文章分类</th>
            <th>发布者</th>
            <th>缩略图</th>
            <th>点击数</th>
            <th>操作</th>
            </tr>
        </thead>
        <tbody>

        @foreach($articles as $item)
          <tr>
            <td>
              <div class="layui-unselect layui-form-checkbox" lay-skin="primary" data-id='{{$item->id}}'><i class="layui-icon">&#xe605;</i></div>
            </td>
            <td>{{$item->id}}</td>
            <td>{{$item->title}}</td>
            <td>
              @foreach($article_types as $item2)
                @if($item2->id == $item->article_type_id)
                  {{$item2->type_title}}
                @endif
              @endforeach
              </td>
            <td>
              @foreach($Admin_users as $item2)
                @if($item2->id == $item->author_id)
                  {{$item2->username}}
                @endif
              @endforeach
              </td>
            <td>
             {{$item->goods_thumb}}
            </td>
            <td>
              {{$item->click}}
            </td>
            <td class="td-manage">
              <a title="查看"  onclick="x_admin_show('编辑商品','article_add?id={{$item->id}}')" href="javascript:;">
                <i class="layui-icon">&#xe63c;</i>
              </a>
              <a title="删除" onclick="member_del(this,'{{$item->id}}')" href="javascript:;">
                <i class="layui-icon">&#xe640;</i>
              </a>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
      <div class="page">
    {{$articles->links()}}
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
          layer.confirm('确认要停用吗？',function(index){
              if($(obj).attr('title')=='启用'){
                //发异步把用户状态进行更改
                $(obj).attr('title','停用')
                $(obj).find('i').html('&#xe62f;');
                $(obj).parents("tr").find(".td-status").find('span').addClass('layui-btn-disabled').html('已停用');
                layer.msg('已停用!',{icon: 5,time:1000});
              }else{
                $(obj).attr('title','启用')
                $(obj).find('i').html('&#xe601;');
                $(obj).parents("tr").find(".td-status").find('span').removeClass('layui-btn-disabled').html('已启用');
                layer.msg('已启用!',{icon: 5,time:1000});
              }
          });
      }
      /*删除*/
      function member_del(obj,id){
          layer.confirm('确认要删除吗？',function(index){
              //发异步删除数据
              $.get('/ajax/get_up_state',{'name':6,'value':1,'uuid':id,'type':2},function (ret) {
                  if(ret>0){
                      $(obj).parents("tr").remove();
                      layer.msg('已删除!',{icon:1,time:1000});
                      return 0;
                  }
                  layer.msg('删除失败!',{icon:0,time:1000});
              });

          });
      }
      function delAll (argument) {
        var data = tableCheck.getData();
        layer.confirm('确认要删除吗？'+data,function(index){
            for (var i=0;i<data.length;i++){
                $.get('/ajax/get_up_state',{'name':6,'value':1,'uuid':data[i],'type':2},function (ret) {
                    if(ret>0){
                        layer.msg('删除成功', {icon: 1});
                    }else{
                        layer.msg('删除失败!',{icon:0,time:1000});
                    }
                });

            }

            $(".layui-form-checked").not('.header').parents('tr').remove();
        });
      }
    </script>

  </body>

</html>
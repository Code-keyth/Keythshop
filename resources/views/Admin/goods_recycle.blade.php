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
        <a href="">商品管理</a>
        <a>
          <cite>商品列表</cite></a>
      </span>
      <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新">
        <i class="layui-icon" style="line-height:30px">ဂ</i></a>
    </div>
    <div class="x-body">

      <xblock>
        <div class="layui-form">
          <div class="layui-form-item">
            <button class="layui-btn" onclick="delAll()"><i class="layui-icon"></i>还原</button>
            <div class="layui-input-inline">
              <select required id="type_select" class="layui-form-select">
                <option value="">请选择分类</option>
                @foreach ($goods_types as $item)
                  <option value="{{$item->id}}">{{$item->type_name}}</option>
                @endforeach
              </select>
            </div>
          </div>
        </div>
        <span class="x-right" style="line-height:40px">共有数据：{{$goodss->total()}}条</span>
      </xblock>
      <table class="layui-table">
        <thead>
          <tr>
            <th>
              <div class="layui-unselect header layui-form-checkbox" lay-skin="primary"><i class="layui-icon">&#xe605;</i></div>
            </th>
            <th>商品号</th>
            <th>商品名</th>
            <th>库存</th>
            <th>售价/市场价</th>
            <th>排序</th>
            </tr>
        </thead>
        <tbody>

        @foreach($goodss as $item)
          <tr>
            <td>
              <div class="layui-unselect layui-form-checkbox" lay-skin="primary" data-id='{{$item->id}}'><i class="layui-icon">&#xe605;</i></div>
            </td>
            <td>{{$item->goods_sn}}</td>
            <td>{{$item->goods_name}}</td>
            <td>{{$item->goods_number}}件</td>
            <td>{{$item->price}}/{{$item->market_price}}￥</td>
            <td>{{$item->sort_order}}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
      <div class="page">
    {{$goodss->links()}}
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


      
      function delAll (argument) {
        var data = tableCheck.getData();
        var data_len=data.length;
        var delect_ok =0;
        var delect_no =0;
        var typeid=$('#type_select').val();
          if(typeid==''){
              layer.msg('请选择分类！', {icon: 2});
              return 0;
          }
        if(data_len==0){
            layer.msg('没有选择要还原的数据！', {icon: 2});
            return 0;
        }

        layer.confirm('确认要还原这'+data_len+'条吗？',function(index){
            //捉到所有被选中的，发异步进行删除
            for (var i=0;i<data_len;i++){
                if(up_state(data[i],typeid)==1){
                    delect_ok++;
                }else{
                    delect_no++;
                }
            }
            if(delect_ok<delect_no){
                layer.msg('成功还原'+delect_ok+'条，还原失败'+delect_no+'条', {icon: 2});
            }else{
                layer.msg('成功还原'+delect_ok+'条，还原失败'+delect_no+'条', {icon: 1});
            }

            $(".layui-form-checked").not('.header').parents('tr').remove();
        });
      }
      function up_state(id,typeid) {
          $.get('/ajax/get_up_state',{'name':6,'value':0,'uuid':id,'type':0,'typeid':typeid},function (ret) {
              if(ret>0){
                  return 1;
              }
              return 0;
              });

      }
    </script>

  </body>

</html>
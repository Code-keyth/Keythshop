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
      <div class="layui-row">
        <form class="layui-form layui-col-md12 x-so">
          <input class="layui-input" placeholder="开始日" name="start" id="start">
          <input class="layui-input" placeholder="截止日" name="end" id="end">
          <div class="layui-input-inline">
            <select name="contrller">
              <option>支付状态</option>
              <option>已支付</option>
              <option>未支付</option>
            </select>
          </div>
          <div class="layui-input-inline">
            <select name="contrller">
              <option>支付方式</option>
              <option>支付宝</option>
              <option>微信</option>
              <option>货到付款</option>
            </select>
          </div>
          <div class="layui-input-inline">
            <select name="contrller">
              <option value="">订单状态</option>
              <option value="0">待确认</option>
              <option value="1">已确认</option>
              <option value="2">已收货</option>
              <option value="3">已取消</option>
              <option value="4">已完成</option>
              <option value="5">已作废</option>
            </select>
          </div>
          <input type="text" name="username"  placeholder="请输入订单号" autocomplete="off" class="layui-input">
          <button class="layui-btn"  lay-submit="" lay-filter="sreach"><i class="layui-icon">&#xe615;</i></button>
        </form>
      </div>
      <xblock>
        <button class="layui-btn layui-btn-danger" ><i class="layui-icon"></i>122</button>

        <span class="x-right" style="line-height:40px">共有数据：{{$orders->total()}}条</span>
      </xblock>
      <table class="layui-table">
        <thead>
          <tr>
            <th>
              <div class="layui-unselect header layui-form-checkbox" lay-skin="primary"><i class="layui-icon">&#xe605;</i></div>
            </th>
            <th>订单号</th>
            <th>用户备注</th>
            <th>配送方式</th>
            <th>支付方式</th>
            <th>订单总金额</th>
            <th>下单时间</th>
            <th>订单状态</th>
            <th>配送状态</th>
            <th >操作</th>
            </tr>
        </thead>
        <tbody>

        @foreach($orders as $item)
          <tr>
            <td>
              <div class="layui-unselect layui-form-checkbox" lay-skin="primary" data-id='2'><i class="layui-icon">&#xe605;</i></div>
            </td>
            <td>{{$item->order_sn}}</td>

            <td>{{$item->postscript}}</td>
            <td>{{$Order->getLogistic($item->logistic_id)}}</td>
            <td>{{$Order->getPay($item->pay_id)}}</td>
            <td>{{$item->order_amount}}</td>
            <td>{{date('Y-m-d H:i:s',$item->add_time)}}</td>
            <td>
                @switch($item->order_status)
                  @case (0)<span class="layui-badge">待确认</span>@break
                  @case (1)<span class="layui-badge layui-bg-green">已确认|@if(empty($item->pay_log_sn))待支付@else已支付@endif
                </span>@break
                  @case (2)<span class="layui-badge layui-bg-orange">取消</span>@break
                  @case (3)<span class="layui-badge">无效</span>@break
                  @case (4)<span class="layui-badge layui-bg-orange">退货</span>@break
                  @default 未知状态
                @endswitch
            </td>
            <td>
              @if($item->order_status < 2)
                @if(empty($item->pay_log_sn))
                  <span class="layui-badge">待支付</span>
                @else
                  @switch($item->logistic_status)
                    @case (0)<span class="layui-badge">待发货</span>
                    <a title="发货" onclick="order_goto(this,'{{$item->order_sn}}')" href="javascript:;"><i class="layui-icon">&#xe609;</i></a>
                    @break
                    @case (1)<span class="layui-badge layui-bg-green">已发货</span>@break
                    @case (2)<span class="layui-badge layui-bg-green">代收货</span>@break
                    @default 未知状态
                  @endswitch
                @endif
              @endif
            </td>
            <td class="td-manage">
              <a title="订单详情"  onclick="x_admin_show('查看订单','order_info?id={{$item->order_sn}}',700)" href="javascript:;"><i class="layui-icon">&#xe63c;</i></a>
              <a title="删除" onclick="member_del(this,'{{$item->id}}')" href="javascript:;"><i class="layui-icon">&#xe640;</i></a>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
      <div class="page">
        {{$orders->links()}}
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

      /*删除*/
      function member_del(obj,id){
          layer.confirm('确认要删除吗？',function(index){
              //发异步删除数据
              $.get('/ajax/get_up_state',{'name':6,'value':1,'uuid':id,'type':0},function (ret) {
                  if(ret>0){
                      $(obj).parents("tr").remove();
                      layer.msg('已删除!',{icon:1,time:1000});
                      return 0;
                  }
                  layer.msg('删除失败!',{icon:0,time:1000});
              });

          });
      }
      function order_goto(obj,order_sn){
          layer.confirm('确认要发货吗？',function(index){
              //发异步删除数据
              $.get('/ajax/order_goto',{'order_sn':order_sn},function (ret) {
                  if(ret=='1'){
                      $(obj).parents("tr").remove();
                      layer.msg('发货成功!',{icon:1,time:1000});
                      return 0;
                  }
                  else if(ret=='-1'){
                      layer.msg('订单状态不能发货!',{icon:0,time:1000});
                      return 0;
                  }
                  layer.msg('失败!',{icon:0,time:1000});
              });

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

          $.get('/ajax/get_up_state',{'name':name,'value':value,'uuid':$(obj).attr('id'),'type':0},function (ret) {
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
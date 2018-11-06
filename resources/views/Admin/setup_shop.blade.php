<!DOCTYPE html>
<html>

  <head>
    <meta charset="UTF-8">
    <title>欢迎页面</title>
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
    <div class="x-body">
        <form method="post" class="layui-form">
        <div class="layui-tab">
            <ul class="layui-tab-title">
                <li class="layui-this">网店信息</li>
                <li>基本设置</li>
                <li>商品显示设置</li>

            </ul>
            <div class="layui-tab-content">
                <div class="layui-tab-item layui-show">

                    @foreach($shop_setup as $item)
                    <div class="layui-form-item">
                        <label class="layui-form-label">{{$item->name}}</label>
                        <div class="layui-input-inline">
                            <input type="text" name="{{$item->code}}" required  lay-verify="required" placeholder="请输入参数" value="{{$item->value}}" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                        @endforeach
                    {{csrf_field()}}
                </div>
                <div class="layui-tab-item">内容2</div>
                <div class="layui-tab-item">内容3</div>
            </div>
        </div>
            <div class="layui-form-item">
                <label class="layui-form-label"></label>
                <div class="layui-input-inline">
                    <button type="" class="layui-btn">保存</button>
                </div>
            </div>

        </form>
    </div>


    <script>
        //注意：选项卡 依赖 element 模块，否则无法进行功能性操作
        layui.use('element', function(){
            var element = layui.element;

            //…
        });
    </script>
  </body>

</html>
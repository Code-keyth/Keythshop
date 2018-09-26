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
    <script type="text/javascript" src="/public/Common/wangEditor-3.1.1/release/wangEditor.min.js"></script>
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
          <cite>商品添加</cite></a>
      </span>
      <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新">
          <i class="layui-icon" style="line-height:30px">ဂ</i></a>
  </div>
    <div class="x-body">

        <form class="layui-form" method="post" enctype="multipart/form-data" action="/admin/goods_add_c">
            <div class="layui-tab">
                <ul class="layui-tab-title">
                    <li class="layui-this">基本信息</li>
                    <li>商品详情</li>
                    <li>活动信息</li>
                    <li>扩展信息</li>
                </ul>
                <div class="layui-tab-content">

                    <div class="layui-tab-item layui-show">
                        <div class="layui-form-item">
                            <label class="layui-form-label">
                                <span class="x-red">*</span>商品名
                            </label>
                            <div class="layui-input-inline">
                                <input type="text" name="goods_name" required="" value="{{$Goods->goods_name}}" autocomplete="off" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label  class="layui-form-label">
                                <span class="x-red">*</span>商品分类
                            </label>
                            <div class="layui-input-inline">
                                <select name="type_id" lay-verify="">
                                    <option value="">请选择</option>
                                    @foreach($Goods_types as $item)
                                        <option @if($item->id == $Goods->type_id) selected @endif value="{{$item->id}}">@if($item->parent_id!=0)|--@endif {{$item->type_name}}</option>
                                    @endforeach

                                </select>
                            </div>
                        </div>
                        {{csrf_field()}}
                        <div class="layui-form-item">
                            <label  class="layui-form-label">
                                <span class="x-red">*</span>商品号
                            </label>
                            <div class="layui-input-inline">
                                <input type="text" name="goods_sn" required=""  value="{{$Goods->goods_sn}}" autocomplete="off" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">
                                <span class="x-red">*</span>库存
                            </label>
                            <div class="layui-input-inline">
                                <input type="text" name="goods_number" required=""  value="{{$Goods->goods_number}}" autocomplete="off" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label  class="layui-form-label">
                                <span class="x-red">*</span>售价
                            </label>
                            <div class="layui-input-inline">
                                <input type="text" name="price" required=""  value="{{$Goods->price}}" autocomplete="off" class="layui-input">
                            </div>
                        </div>
                        <input type="hidden" name="id" value="{{$Goods->id}}"  class="layui-input">
                        <div class="layui-form-item">
                            <label  class="layui-form-label">
                                <span class="x-red">*</span>缩略图
                            </label>
                            <div class="layui-input-inline">
                                <input type="file" name="goods_thumb"  accept="image/*" class="layui-input">
                            </div>
                            <div class="layui-input-inline">
                               <img style="margin-top: -100px;" width="150px" src="{{$Goods->goods_thumb}}" alt="暂无缩略图">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">
                                <span class="x-red">*</span>简单描述
                            </label>
                            <div class="layui-input-block">
                                <input name="goods_brief"  required="" value="{{$Goods->goods_brief}}"  class="layui-input">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label  class="layui-form-label">
                                <span class="x-red">*</span>是否精品
                            </label>
                            <div class="layui-input-inline">
                                <input type="checkbox" name="is_best" @if($Goods->is_best==1) checked @endif lay-skin="switch">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label   class="layui-form-label">
                                <span class="x-red">*</span>是否新品
                            </label>
                            <div class="layui-input-inline">
                                <input type="checkbox"  @if($Goods->is_new==1) checked @endif   name="is_new" lay-skin="switch">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label   class="layui-form-label">
                                <span class="x-red">*</span>是否热销
                            </label>
                            <div class="layui-input-inline">
                                <input type="checkbox" name="is_hot" @if($Goods->is_hot==1) checked @endif  lay-skin="switch">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label   class="layui-form-label">
                                <span class="x-red">*</span>是否上架
                            </label>
                            <div class="layui-input-inline">
                                <input type="checkbox" @if($Goods->is_on_sale==1) checked @endif name="is_on_sale" lay-skin="switch">
                            </div>
                        </div>
                    </div>
                    <div class="layui-tab-item">
                        <div id="editor001">
                            {!! $Goods->goods_desc !!}
                        </div>

                        <textarea hidden="hidden" name="goods_desc"  id="goods_desc"  style="width:100%; height:200px;"></textarea>
                    </div>
                    <div class="layui-tab-item">

                        <div class="layui-form-item">
                            <label   class="layui-form-label">
                                秒杀or促销
                            </label>
                            <div class="layui-input-block">
                                <input type="radio" name="promote_or_activity" @if($Goods->promote_or_activity==0) checked @endif value="0" title="无" checked>
                                <input type="radio" name="promote_or_activity" @if($Goods->promote_or_activity==1) checked @endif  value="1" title="促销">
                                <input type="radio" name="promote_or_activity" @if($Goods->promote_or_activity==2) checked @endif  value="2" title="秒杀" >
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label  class="layui-form-label">
                                优惠价
                            </label>
                            <div class="layui-input-inline">
                                <input type="text" name="promote_price"  value="{{$Goods->promote_price}}" autocomplete="off" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">开始时间</label>
                            <div class="layui-input-inline">
                                <input class="layui-input" id="date001"  value="{{$Goods->promote_start_date}}" name="promote_start_date" placeholder="yyyy-MM-dd HH:mm:ss" type="text">
                            </div>
                        </div>

                        <div class="layui-form-item">
                            <label class="layui-form-label">结束时间</label>
                            <div class="layui-input-inline">
                                <input class="layui-input" id="date002"  value="{{$Goods->promote_end_date}}" name="promote_end_date" placeholder="yyyy-MM-dd HH:mm:ss" type="text">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">限购数量</label>
                            <div class="layui-input-inline">
                                <input class="layui-input" name="activity_number"  value="{{$Goods->activity_number}}" type="text">
                            </div>
                        </div>

                    </div>
                    <div class="layui-tab-item">
                        <div class="layui-form-item">
                            <label   class="layui-form-label">
                               商品关键字
                            </label>
                            <div class="layui-input-inline">
                                <input type="text" name="keyword"  value="{{$Goods->keyword}}" autocomplete="off" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label   class="layui-form-label">
                                浏览次数
                            </label>
                            <div class="layui-input-inline">
                                <input type="text" name="click_count"  value="{{$Goods->click_count}}"  class="layui-input">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label   class="layui-form-label">
                                商品重量
                            </label>
                            <div class="layui-input-inline">
                                <input type="text" name="goods_weight"  value="{{$Goods->goods_weight}}" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label   class="layui-form-label">
                                重量单位
                            </label>
                            <div class="layui-input-inline">
                                <input type="text" name="goods_unit"  value="{{$Goods->goods_unit}}" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label   class="layui-form-label">
                                市场价
                            </label>
                            <div class="layui-input-inline">
                                <input type="text" name="market_price"  value="{{$Goods->market_price}}" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label   class="layui-form-label">
                                商品积分
                            </label>
                            <div class="layui-input-inline">
                                <input type="text" name="integral"  value="{{$Goods->integral}}" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label   class="layui-form-label">
                                排列顺序
                            </label>
                            <div class="layui-input-inline">
                                <input type="text" name="sort_order"  value="{{$Goods->sort_order}}" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label   class="layui-form-label">
                                商家备注
                            </label>
                            <div class="layui-input-inline">
                                <textarea name="seller_note"  value="{{$Goods->seller_note}}" placeholder="请输入内容" class="layui-textarea"></textarea>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
          <div class="layui-form-item">
              <label for="L_repass" class="layui-form-label"></label>
              <button  type="submit" class="layui-btn">提交</button>
          </div>
      </form>
    </div>
    <script>


        var E = window.wangEditor
        var editor = new E('#editor001')
        var $text1 = $('#goods_desc')
        // 或者 var editor = new E( document.getElementById('editor') )
        editor.customConfig.onchange = function (html) {
            // 监控变化，同步更新到 textarea
            $text1.val(html)
        };
        editor.customConfig.uploadImgServer = '/admin/upload_file';  // 上传图片到服务器
        editor.customConfig.uploadImgParams = {
            '_token': '{{csrf_token()}}',
        };
        editor.customConfig.uploadFileName='file';
        editor.create();
        // 初始化 textarea 的值
        $text1.val(editor.txt.html())

        layui.use('laydate', function(){
            var laydate = layui.laydate;

            //执行一个laydate实例
            laydate.render({
                elem: '#date001' //指定元素
                ,type: 'datetime'
            });
            laydate.render({
                elem: '#date002' //指定元素
                ,type: 'datetime'
            });
        });

    </script>

  </body>

</html>
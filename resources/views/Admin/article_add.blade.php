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
        <a href="">文章管理</a>
        <a>
          <cite>文章添加</cite></a>
      </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right"
       href="javascript:location.replace(location.href);" title="刷新">
        <i class="layui-icon" style="line-height:30px">ဂ</i></a>
</div>
<div class="x-body">

    <form class="layui-form" method="post" enctype="multipart/form-data" action="article_add_c">
        <div class="layui-form-item">
            <label class="layui-form-label">
                <span class="x-red">*</span>标题
            </label>
            <div class="layui-input-inline">
                <input type="text" name="title" required="" value="{{$Article->title}}" autocomplete="off"
                       class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">
                <span class="x-red">*</span>文章分类
            </label>
            <div class="layui-input-inline">
                <select name="article_type_id" lay-verify="">
                    <option value="">请选择</option>
                    @foreach($Article_types as $item)
                        <option @if($item->id == $Article->article_type_id) selected
                                @endif value="{{$item->id}}">{{$item->type_title}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        {{csrf_field()}}
        <div class="layui-form-item">
            <label class="layui-form-label">
                <span class="x-red">*</span>简略标题
            </label>
            <div class="layui-input-inline">
                <input type="text" name="title2" required="" value="{{$Article->title2}}" autocomplete="off"
                       class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">
                <span class="x-red">*</span>排序
            </label>
            <div class="layui-input-inline">
                <input type="text" name="sort_order" required="" value="{{$Article->sort_order}}" autocomplete="off"
                       class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">
                <span class="x-red">*</span>关键字
            </label>
            <div class="layui-input-inline">
                <input type="text" name="keywords" required="" value="{{$Article->keywords}}" autocomplete="off"
                       class="layui-input">
            </div>
        </div>
        <input type="hidden" name="id" value="{{$Article->id}}" class="layui-input">
        <div class="layui-form-item">
            <label class="layui-form-label">
                <span class="x-red">*</span>缩略图
            </label>
            <div class="layui-input-inline">
                <input type="file" name="article_thumb" accept="image/*" class="layui-input">
            </div>
            <div class="layui-input-inline">
                <img style="margin-top: -100px;" width="150px" src="{{$Article->article_thumb}}" alt="暂无缩略图">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">
                <span class="x-red">*</span>简介
            </label>
            <div class="layui-input-block">
                <input name="article_brief" required="" value="{{$Article->article_brief}}" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">
                <span class="x-red">*</span>点击数
            </label>
            <div class="layui-input-inline">
                <input type="text" name="click" value="{{$Article->click}}" class="layui-input">
            </div>
        </div>


        <label class="layui-form-label">
            <span class="x-red">*</span>正文
        </label>
        <div id="editor001" class="layui-input-block">
            {!! $Article->content !!}
        </div>

        <textarea hidden="hidden" name="content" id="content" style="width:100%; height:200px;"></textarea>


        <div class="layui-form-item">
            <label for="L_repass" class="layui-form-label"></label>
            <button type="submit" class="layui-btn">提交</button>
        </div>
    </form>

</div>


<script>
    var E = window.wangEditor
    var editor = new E('#editor001')
    var $text1 = $('#content')
    // 或者 var editor = new E( document.getElementById('editor') )
    editor.customConfig.onchange = function (html) {
        // 监控变化，同步更新到 textarea
        $text1.val(html);
    };
    editor.customConfig.uploadImgServer = '/admin/upload_file';  // 上传图片到服务器
    editor.customConfig.uploadImgParams = {
        '_token': '{{csrf_token()}}',
    };
    editor.customConfig.uploadFileName = 'file';
    editor.create();
    // 初始化 textarea 的值
    $text1.val(editor.txt.html());

</script>

</body>

</html>
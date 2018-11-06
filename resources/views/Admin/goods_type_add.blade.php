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
    <div class="x-body">
        <form method="post" action="goods_type_add_c" class="layui-form">


          <div class="layui-form-item">
              <label  class="layui-form-label">
                  <span class="x-red">*</span>分类名
              </label>
              <div class="layui-input-inline">
                  <input type="text" id="type_name" name="type_name" value="{{$type['type_name']}}" required="" lay-verify="required" autocomplete="off" class="layui-input">
              </div>
          </div>
          <div class="layui-form-item">
              <label  class="layui-form-label">
                  上级栏目
              </label>
              <div class="layui-input-inline">
                  <select name="parent_id">
                      <option value=""> 请选择分类</option>
                    @foreach($goods_types as $item)
                        <option @if($item->id==$type['parent_id']) selected @endif value="{{$item->id}}">{{$item->type_name}}</option>
                        @endforeach
                  </select>
              </div>
          </div>
            <div class="layui-form-item">
                <label  class="layui-form-label">
                    <span class="x-red">*</span>关联属性
                </label>
                <div class="layui-input-inline">
                    @foreach($goods_specs as $item)
                        <input type="checkbox" @if(in_array($item->id,$goods_spec)) checked @endif name="spec[{{$item->id}}]" value="{{$item->spec_name}}|{{$item->code}}" title="{{$item->spec_name}}">
                    @endforeach
                </div>
            </div>


            <div class="layui-form-item">
                <label  class="layui-form-label">
                    <span class="x-red">*</span>分类描述
                </label>
            {{csrf_field()}}
                <div class="layui-input-block">
                    <textarea name="type_desc"  placeholder="请输入内容" class="layui-textarea">{{$type['type_desc']}}</textarea>
                </div>
            </div>
            <input name="id" hidden="hidden" value="{{$type['id']}}">

          <div class="layui-form-item">
              <label for="phone" class="layui-form-label">
                  <span class="x-red">*</span>排序
              </label>
              <div class="layui-input-inline">
                  <input type="text"  value="{{$type['sort_order']}}"  name="sort_order" required="" autocomplete="off" class="layui-input">
              </div>
          </div>

          <div class="layui-form-item">
              <label for="L_repass" class="layui-form-label">
              </label>
              <button  class="layui-btn" type="submit" lay-submit="">增加</button>
          </div>
      </form>
    </div>

    <script>

        layui.use(['form','layer'], function(){
            $ = layui.jquery;
          var form = layui.form
          ,layer = layui.layer;
          //自定义验证规则
          form.verify({
            nikename: function(value){
              if(value.length < 5){
                return '昵称至少得5个字符啊';
              }
            }
            ,pass: [/(.+){6,12}$/, '密码必须6到12位']
            ,repass: function(value){
                if($('#L_pass').val()!=$('#L_repass').val()){
                    return '两次密码不一致';
                }
            }
          });
          //监听提交
          form.on('submit(add)', function(data){
            console.log(data['field']);
            //发异步，把数据提交给php
              $.post('/admin/goods_type_add_c',data['field'],function (req) {
                  if(req==1){
                      layer.alert("增加成功", {icon: 6},function () {
                          // 获得frame索引
                          var index = parent.layer.getFrameIndex(window.name);
                          //关闭当前frame
                          parent.layer.close(index);
                      });
                      }
              });
            return false;
          });


        });
    </script>

  </body>

</html>





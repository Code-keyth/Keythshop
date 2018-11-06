
<html>
  
  <head>
    <meta charset="UTF-8">
    <title>欢迎页面-X-admin2.0</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
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
        <a href="">演示</a>
        <a>
          <cite>导航元素</cite></a>
      </span>
      <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新">
          <i class="layui-icon" style="line-height:30px">ဂ</i></a>
  </div>
    <div class="x-body layui-anim layui-anim-up">
        <form method="post" action="member_add_c" class="layui-form">
            <div class="layui-tab">
                <ul class="layui-tab-title">
                    <li class="layui-this">基本信息</li>
                    <li>修改密码</li>
                    <li>其它信息</li>
                </ul>
                <div class="layui-tab-content">
                    <div class="layui-tab-item layui-show">
                        <div class="layui-form-item">
                            <label for="L_email" class="layui-form-label">
                                <span class="x-red">*</span>邮箱
                            </label>
                            <div class="layui-input-inline">
                                <input  value="{{$member->email}}" disabled class="layui-input">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">
                                <span class="x-red">*</span>昵称
                            </label>
                            <div class="layui-input-inline">
                                <input type="text" id="L_username"  name="username" value="{{$member->username}}"
                                       required="" lay-verify="nikename" autocomplete="off" class="layui-input">
                            </div>
                        </div>

                        <div class="layui-form-item">
                            <label for="L_birthday" class="layui-form-label">
                                <span class="x-red">*</span>出生年份
                            </label>
                            <div class="layui-input-inline">
                                <input class="layui-input" id="birthday" value="{{$member->birthday}}" name="birthday" placeholder="yyyy" type="text">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">
                                <span class="x-red">*</span>性别
                            </label>
                            <div class="layui-input-inline">
                                <input type="radio" name="sex" value="0" title="保密" @if($member->sex==0) checked @endif >
                                <br>
                                <input type="radio" name="sex" value="1" title="男"  @if($member->sex==1) checked @endif>
                                <input type="radio" name="sex" value="2" title="女"  @if($member->sex==2) checked @endif>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">
                                <span class="x-red">*</span>账户资金
                            </label>
                            <div class="layui-input-inline">
                                <input type="text"value="{{$member->user_money}}" disabled class="layui-input">
                            </div>
                        </div>
                        {{csrf_field()}}
                        <div class="layui-form-item">
                            <label class="layui-form-label">
                                会员qq号
                            </label>
                            <div class="layui-input-inline">
                                <input type="text" id="L_qq" name="qq"
                                       autocomplete="off" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">
                                <span class="x-red">*</span>会员手机号
                            </label>
                            <div class="layui-input-inline">
                                <input type="text" id="L_mobile" name="mobile" required="" value="{{$member->mobile}}"
                                       autocomplete="off" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">
                                会员状态
                            </label>
                            <div class="layui-input-inline">
                                <input type="checkbox" @if($member->state==1) checked @endif name="state" lay-skin="switch">
                            </div>
                        </div>

                    </div>
                    <div class="layui-tab-item">

                        <div class="layui-form-item">
                            <label for="L_pass" class="layui-form-label">
                                <span class="x-red">*</span>密码
                            </label>
                            <div class="layui-input-inline">
                                <input type="password" id="L_pass" disabled name="pass"
                                       autocomplete="off" class="layui-input">
                            </div>
                            <div class="layui-form-mid layui-word-aux">
                                6到16个字符
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label for="L_repass" class="layui-form-label">
                                <span class="x-red">*</span>确认密码
                            </label>
                            <div class="layui-input-inline">
                                <input type="password" id="L_repass" disabled name="repass"
                                       autocomplete="off" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label for="L_repass" class="layui-form-label">
                            </label>
                            <div  class="layui-input-inline">
                            <a title="解锁密码框" onclick="play_passwd()"><i class="icon iconfont">&#xe719;</i>解锁
                            </a>
                            <a title="锁定密码框" onclick="stop_passwd()"><i class="icon iconfont">&#xe71a;</i>锁定
                            </a>
                            </div>
                            <div class="layui-form-mid layui-word-aux">
                                锁定后密码不会被修改
                            </div>


                        </div>
                    </div>

                    <div class="layui-tab-item">

                        <div class="layui-form-item">
                            <label  class="layui-form-label">
                                注册时间
                            </label>
                            <div class="layui-input-inline">
                                <input type="text" disabled value="{{date('Y-m-d H:i:s',$member->reg_time)}}" class="layui-input">
                            </div>
                        </div>
                        <input value="{{$member->id}}" hidden name="id">
                        <div class="layui-form-item">
                            <label class="layui-form-label">
                                最后登录时
                            </label>
                            <div class="layui-input-inline">
                                <input type="text" disabled value="{{date('Y-m-d H:i:s',$member->last_login)}}"  class="layui-input">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">
                                最后登录IP
                            </label>
                            <div class="layui-input-inline">
                                <input type="text" disabled value="{{$member->last_ip}}"  class="layui-input">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">
                               总登录次数
                            </label>
                            <div class="layui-input-inline">
                                <input type="text" disabled value="{{$member->visit_login_count}}"  class="layui-input">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">
                                推荐人ID
                            </label>
                            <div class="layui-input-inline">
                                <input type="text" disabled value="{{$member->parent_id}}"  class="layui-input">
                            </div>
                        </div>
                    </div>

                </div>
            </div>



            <div class="layui-form-item">
                <label for="L_repass" class="layui-form-label">
                </label>

                <button  class="layui-btn" type="submit" lay-filter="add" lay-submit="">
                    保存
                </button>
            </div>



      </form>
    </div>
    <script>

        layui.use('laydate', function(){
            var laydate = layui.laydate;

            //执行一个laydate实例
            laydate.render({
                elem: '#birthday' //指定元素
                ,type: 'year'
            });
        });
        layui.use(['form','layer'], function(){
            $ = layui.jquery;
            var form = layui.form
                ,layer = layui.layer;

            //自定义验证规则
            form.verify({
                nikename: function(value){
                    if(value.length < 3){
                        return '昵称至少得3个字符啊';
                    }
                }
                ,pass: [/(.+){6,12}$/, '密码必须6到12位']
                ,repass: function(value){
                    if($('#L_pass').val()!=$('#L_repass').val()){
                        return '两次密码不一致';
                    }
                }
            });



        });
        function play_passwd() {

            $('#L_repass').attr('lay-verify','repass');
            $('#L_repass').attr('disabled',false);
            $('#L_pass').attr('lay-verify','pass');
            $('#L_pass').attr('disabled',false);
            layer.msg('解锁成功', {icon: 1});
        }
        function stop_passwd() {
            $('#L_repass').removeAttr('lay-verify');
            $('#L_repass').attr('disabled','disabled');
            $('#L_repass').attr('value','');
            $('#L_pass').removeAttr('lay-verify');
            $('#L_pass').attr('disabled','disabled');
            $('#L_pass').attr('value','');
            layer.msg("锁定成功", {icon: 0});

        }
    </script>

  </body>

</html>
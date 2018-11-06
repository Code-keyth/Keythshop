
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Keyth Shop</title>
    <link rel="stylesheet" href="/public/Common/layui/css/layui.css">
    <script type="text/javascript" src="/public/Common/jquery-1.8.3.min.js"></script>
    <script type="text/javascript" src="/public/Common/layui/layui.js"></script>
    <script type="text/javascript" src="/public/Admin/js/xcity.js"></script>
</head>
<body>
<form class="layui-form" action="">
    <div class="layui-form-item">
        <label class="layui-form-label">&nbsp;</label>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">收货人</label>
        <div class="layui-input-inline">
            <input type="text" name="consignee" value="{{$address['consignee']}}" required  lay-verify="required" placeholder="请输入姓名" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">邮编</label>
        <div class="layui-input-inline">
            <input type="text" name="zipcode" value="{{$address['consignee']}}"  required lay-verify="required" placeholder="请输入邮编" autocomplete="off" class="layui-input">
        </div>

    </div>
    <div class="layui-form-item" id="x-city">
        <label class="layui-form-label">城市联动</label>
        <div class="layui-input-inline">
            <select name="province" lay-filter="province">
                <option value="">请选择省</option>
            </select>
        </div>
        <div class="layui-input-inline">
            <select name="city" lay-filter="city">
                <option value="">请选择市</option>
            </select>
        </div>
        <div class="layui-input-inline">
            <select name="county" lay-filter="area">
                <option value="">请选择县/区</option>
            </select>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">具体地址</label>
        <div class="layui-input-inline">
            <input  value="{{$address['address']}}"  type="text" name="address" required  lay-verify="required" placeholder="请输入地址" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">电话</label>
        <div class="layui-input-inline">
            <input  value="{{$address['tel']}}"  type="text" name="tel" required  lay-verify="required" placeholder="请输入电话号" autocomplete="off" class="layui-input">
        </div>
    </div>
    {{csrf_field()}}
    <div class="layui-form-item">
        <label class="layui-form-label">手机</label>
        <div class="layui-input-inline">
            <input  value="{{$address['mobile']}}"  type="text" name="mobile" required  lay-verify="required" placeholder="请输入手机号" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">是否默认</label>
        <div class="layui-input-inline">
            <input type="checkbox" @if($address['is_default']==1) checked @endif name="is_default" lay-skin="switch">
        </div>
    </div>
    <input name="address_id" value="{{$address['id']}}" hidden>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit lay-filter="formDemo">立即提交</button>
            <button type="reset" class="layui-btn layui-btn-primary">重置</button>
        </div>
    </div>
</form>
<script>
    //Demo
    layui.use(['form','code'], function(){
        form = layui.form;
        layui.code();
        @if(!empty($address['consignee']))
            $('#x-city').xcity('{{$address['province']}}','{{$address['city']}}','{{$address['county']}}');
        @else
            $('#x-city').xcity('北京','市辖区','东城区');
        @endif
    });
    layui.use('form', function(){
        var form = layui.form;
        form.on('submit(formDemo)', function(data){
            layer.msg(JSON.stringify(data.field));
            $.post('/ajax/myinfo_address',datas=data.field,function (data2) {

            });
            return false;
        });
    });

</script>
</body>
</html>
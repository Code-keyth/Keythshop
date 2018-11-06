<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>后台登录-X-admin2.0</title>
        <meta name="renderer" content="webkit|ie-comp|ie-stand">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi" />
        <meta http-equiv="Cache-Control" content="no-siteapp" />

        <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
        <link rel="stylesheet" href="/public/Admin/css/font.css">
        <link rel="stylesheet" href="/public/Admin/css/xadmin.css">
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    </head>
    <body>
        <div class="x-body">
            <blockquote class="layui-elem-quote">
                特别声明：ECharts，一个纯 Javascript 的图表库，可以流畅的运行在 PC 和移动设备<a href="http://echarts.baidu.com/" style="color:red">ECharts</a>。 x-admin不承担任何版权问题。
            </blockquote>
            <!-- 为 ECharts 准备一个具备大小（宽高）的 DOM -->
            <div id="main" style="width: 100%;height:400px;"></div>
            <blockquote class="layui-elem-quote">
                注意：本案例的Echarts图表库由cdn引入，需要在线才能正常使用，如想离想，请至Echarts官网下载。
            </blockquote>
        </div>
        <script src="//cdn.bootcss.com/echarts/3.3.2/echarts.min.js" charset="utf-8"></script>
        <script type="text/javascript">
        // 基于准备好的dom，初始化echarts实例

        // 指定图表的配置项和数据
        $.get('/ajax/get_echarts',{'type':2},function (data) {
            var myChart = echarts.init(document.getElementById('main'));
            var option = {
                legend: {
                    data:['单日数据','总数据']
                },
                xAxis: {
                    type: 'category',
                    data: ['前七天', '前六天', '前五天', '前四天', '前三天', '前天', '昨天']
                },
                yAxis: {
                    type: 'value'
                },
                series: [{
                    data: data['day'],
                    name:'单日数据',
                    type:'line',
                    stack: '单日数据',
                }, {
                    name:'总数据',
                    type:'line',
                    stack: '总量',
                    data:data['total_day']
                }
                ]
            };
            myChart.setOption(option);
        });
    </script>
    </body>
</html>
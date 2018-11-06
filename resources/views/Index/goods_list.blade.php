@extends('Index/Model')
@section('mian')

    @if(!empty($goods))
        <div class="danpin center">
            <div class="biaoti center">小米手机</div>
            <div class="main center">
                @foreach($goods as $item)
                    <div class="mingxing fl mb20" style="border:2px solid #fff;width:230px;cursor:pointer;"
                         onmouseout="this.style.border='2px solid #fff'" onmousemove="this.style.border='2px solid red'">
                        <div class="xinpin">
                            @if($item->is_new==1)<span style="background-color: #83c44e;">新品</span>@endif
                            @if($item->is_hot==1)<span style="background-color: #ff6700;">热卖</span>@endif
                        </div>


                        <div class="sub_mingxing"><a href="/index/goods_info?goods_sn={{$item->goods_sn}}"
                                                     target="_blank"><img src="{{$item->goods_thumb}}" alt=""></a></div>
                        <div class="pinpai"><a href="./xiangqing.html" target="_blank">{{$item->goods_name}}</a></div>
                        <div class="youhui">{{$item->goods_brief}}</div>
                        <div class="jiage">{{$item->price}}</div>
                    </div>
                @endforeach
                <div class="clear"></div>
            </div>
            <div class="clear"></div>
        </div>
        @else

        @foreach($goods_types as $item)
            <div class="danpin center">
                <div class="biaoti center">{{$item->type_name}}</div>
                <div class="main center">
                    @foreach($Goods->get_type_goods(['type_id'=>$item->id])  as $item2)
                        <div class="mingxing fl mb20" style="border:2px solid #fff;width:230px;cursor:pointer;"
                             onmouseout="this.style.border='2px solid #fff'" onmousemove="this.style.border='2px solid red'">
                            <div class="xinpin">
                                @if($item2->is_new==1)<span style="background-color: #83c44e;">新品</span>@endif
                                @if($item2->is_hot==1)<span style="background-color: #ff6700;">热卖</span>@endif
                            </div>
                            <div class="sub_mingxing"><a href="/index/goods_info?goods_sn={{$item2->goods_sn}}" target="_blank"><img src="{{$item2->goods_thumb}}" alt=""></a></div>
                            <div class="pinpai"><a href="./xiangqing.html" target="_blank">{{$item2->goods_name}}</a></div>
                            <div class="youhui">{{$item2->goods_brief}}</div>
                            <div class="jiage">{{$item2->price}}</div>
                        </div>
                    @endforeach
                    <div class="clear"></div>
                </div>
                <div class="clear"></div>
            </div>
        @endforeach
    @endif
@endsection
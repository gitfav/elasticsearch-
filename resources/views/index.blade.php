@extends('layouts.default')

@section('content')
    <div class="layui-body">
        <!-- 内容主体区域 -->
        <div style="padding: 15px;">
            <div class="layui-form">
                <blockquote class="layui-elem-quote quoteBox">
                    <form class="layui-form">
                        <div class="layui-inline">
                            <div class="layui-input-inline">
                                <input type="text" class="layui-input searchVal" placeholder="请输入搜索的内容"/>
                            </div>
                            <a class="layui-btn" id="searchBtn" data-type="reload">搜索</a>
                        </div>
                        <div class="layui-inline">
                            <a class="layui-btn layui-btn-normal" id="fetchData">抓取数据</a>
                        </div>

                    </form>
                </blockquote>
                <div>
                       查询时间: <span id="time">0</span> ms
                        总数 : <span id="total">0</span>
                </div>
                <table class="layui-hide" id="test"></table>

            </div>
        </div>
        <script src="./layui/layui.js"></script>
        <script>
            layui.use(['layer','table', 'jquery'], function () {
                var table = layui.table;
                var $ = layui.jquery;
                var layer = layui.layer;

                var url = "{{url('getList')}}";
                table.render({
                    elem: '#test'
                    , url: url
                    , page: { //支持传入 laypage 组件的所有参数（某些参数除外，如：jump/elem） - 详见文档
                        layout: ['limit', 'count', 'prev', 'page', 'next', 'skip'], //自定义分页布局
                        limit:20,
                        //,curr: 5 //设定初始在第 5 页
                        // , groups: 1 //只显示 1 个连续页码
                        // , first: false //不显示首页
                        // , last: false //不显示尾页

                    }
                    , cols: [[
                        {field: 'title', width: 100, title: '标题'}
                        , {field: 'keyword', width: 180, title: '关键字', sort: true}
                        , {field: 'content', title: '内容'}
                    ]],
                    done:function(res, curr, count){
                        console.log(res)
                        $('#time').text(res.time);
                        $('#total').text(count)
                    }

                });

                // 搜索
                $('#searchBtn').click(function () {

                    var url = "{{url('getList')}}";
                    var keyword = $('.searchVal').val();

                    table.reload('test', {
                        url: url
                        , where: {keyword: keyword} //设定异步数据接口的额外参数
                        //,height: 300
                    });
                })

                $('#fetchData').click(function () {
                    var index = layer.load();
                    var url = "{{url('fetch40Data')}}";
                    $.get(url,function (result) {
                        layer.close(index);
                        alert('抓取成功')
                    });
                });

           });
        </script>
    </div>
@endsection
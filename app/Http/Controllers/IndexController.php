<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Services\SearchService;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index()
    {
        return view('index');
    }
    
    public function getList(Request $request)
    {
        $word  = $request->input('word');
        $page  = $request->input('page',1);
        $count = $request->input('limit',10);

        $post_arr = [
            'from'  =>  ($page-1)*$count,
            'size'  =>  $count
        ];
        $word = $request->input('keyword');
        $post_data = [];
        if ($word) {
            $post_arr['query'] = [
                'bool'  =>  [
                    'should'    =>  [
                        ['match'=>['desc'   =>  $word]],
                        ['match'=>['title'  =>  $word]]
                    ]
                ]
            ];
        }
        $post_data = json_encode($post_arr);
        $url = config('app.esIp');
        $search_data = [
            'post_data' => $post_data,
            'url'       => $url.'data/zxf/_search'
        ];
        
        $list = SearchService::search($search_data);
        $data = json_decode($list['body'],true);
//        dd($data);
        $newList = $this->getData($data['hits']['hits']);
        return json_encode(['code'=>0,'msg'=>'成功','count'=>$data['hits']['total'],'time'=>$data['took'],'data'=>$newList]);
    }
    
    public function fetchData()
    {
        return 22;
    }
    
    protected function getData($data)
    {
        $newData = [];
        foreach ($data as $key => $val) {
            $newData[$key] = [
                'url' => $val['_source']['url'] ?? '',
                'keyword' => $val['_source']['keyword'] ?? '',
                'title' => $val['_source']['title'] ?? '',
                'content' => $val['_source']['content'] ?? '',
            ];
        }
        return $newData;
    }


    public function import($request_data)
    {
//        $request_data = $request->all();
        // $request_data = [
        //     'url'     => 't.cn',
        //     'keyword' => '人民的好公仆',
        //     'title'   => '警察的老师——张伟',
        //     'content' => '这是一个好警察，好老师'
        // ];
        if (!$request_data) {
            exit;
        }
        $post_arr = [
            'url'     => $request_data['url'],
            'keyword' => $request_data['keyword'],
            'title'   => $request_data['title'],
            'content' => $request_data['content']
        ];
        $post_data = json_encode($post_arr);
        
        $url = config('app.esIp');
        $search_data = [
            'post_data' => $post_data,
            'url'       => $url.'data/zxf'
        ];
        $list = SearchService::search($search_data);
        $data = json_decode($list['body'],true);
        var_dump($data);
    }
    
   
}

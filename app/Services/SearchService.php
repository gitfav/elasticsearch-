<?php

namespace App\Services;

class SearchService
{
    public static function search($data)
    {
        $header = [
            'Content-Type: application/json'
        ];
        
        $postFields = $data['post_data'];
        
        $ch = curl_init($data['url']);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
        
        $response['body'] = curl_exec($ch);
        if (curl_errno($ch)) {
            $response['error'] = curl_error($ch); //捕抓异常
        }
        curl_close($ch);

        return $response;
    }

    public static function import($request_data)
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

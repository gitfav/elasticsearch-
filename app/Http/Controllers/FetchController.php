<?php

namespace App\Http\Controllers;

use App\Services\SearchService;

class FetchController extends Controller
{
    public function curlRequest($login_url, $push_cookie, $pull_cookie, $post_fields = '', $otherHeader = [], $headSet = [])
    {
        $header = [
            'Accept: application/json, text/plain, */*',
            'Accept-Charset:GBK,utf-8;q=0.7,*;q=0.3',
            'Accept-Language:zh-CN,zh;q=0.9',
            'Connection:keep-alive',
            'Accept-Encoding:gzip,deflate,br',
        ];
        $header = array_merge($header, $otherHeader);

        $ch = curl_init($login_url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $returnHeader = 1;
        if (isset($headSet['returnHeader'])) {
            $returnHeader = $headSet['returnHeader'];
        }
        curl_setopt($ch, CURLOPT_HEADER, $returnHeader); //是否返回头信息
        if (!isset($headSet['isPost']) || 1 == $headSet['isPost']) {
            curl_setopt($ch, CURLOPT_POST, 1);
        }

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if ($post_fields) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
        }
        if (isset($headSet['isToLocaltion'])) {
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); //一直追踪302 直至200状态 这步对于这个网站的登录来说是关键
        }
        curl_setopt($ch, CURLOPT_ENCODING, '');
        curl_setopt($ch, CURLOPT_COOKIEFILE, $push_cookie);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $pull_cookie);
        curl_setopt($ch, CURLOPT_SSLVERSION, 4); //设定SSL版本
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $response['body'] = curl_exec($ch);
        if (curl_errno($ch)) {
            $response['error'] = curl_error($ch); //捕抓异常
        }
        curl_close($ch);

        return $response;
    }

    public function getCInfo()
    {
        set_time_limit(0);
        $arr = [
//            'https://m.hupu.com/bbs/1048',
//            'https://m.hupu.com/bbs/3441',
//            'https://m.hupu.com/bbs/1649',
//            'https://m.hupu.com/bbs/4614',
//            'https://m.hupu.com/bbs/120',
//            'https://m.hupu.com/bbs/4686',
//            'https://m.hupu.com/bbs/102',
//            'https://m.hupu.com/bbs/81',
//            'https://m.hupu.com/bbs/127',
//            'https://m.hupu.com/bbs/92',

//            'https://m.hupu.com/bbs/1028',
//            'https://m.hupu.com/bbs/1218',
            'https://m.hupu.com/bbs/70',
            'https://m.hupu.com/bbs/108',
            'https://m.hupu.com/bbs/85',
//            'https://m.hupu.com/bbs/82',
//            'https://m.hupu.com/bbs/89',
//            'https://m.hupu.com/bbs/4686',
        ];

        $orgUrl = array_shift($arr);
        do {
            $num = 1;
            $myfile = fopen("write", "w") or die("Unable to open file!");
            while (true) {
                $url = $orgUrl . '-' . $num;
                $cookie = './cookies';
                $headSet['returnHeader'] = 0;
                $response = $this->curlRequest($url, $cookie, $cookie, '', [], $headSet);

                $par = '/<p>没有更多内容了<\/p>/';
                preg_match_all($par, $response['body'], $match);
                if (isset($match[0][0])) {
                    break;
                }

                $par = '/<li  >(\n|.)+?<a href="(.+?)"(\n|.)+?<\/li>/';
                preg_match_all($par, $response['body'], $match);

                foreach ($match[2] as $k => $v) {
                    $this->getOneInfo('https:' . $v);
                }

                $num++;

                fwrite($myfile, $url . "\n");

            }
            fclose($myfile);
            $orgUrl = array_shift($arr);
        } while ($orgUrl != null);
    }

    private function getOneInfo($url)
    {
//    $url = 'https://m.hupu.com/bbs/22547371.html';
        $cookie = './cookies';
        $headSet['returnHeader'] = 0;
        $response = $this->curlRequest($url, $cookie, $cookie, '', [], $headSet);

        $par = '/<article class="article-content">[\s\S]*?<\/article>/';
        preg_match_all($par, $response['body'], $match);
        if (!isset($match[0][0])) {
            return false;
        }
        $text = strip_tags($match[0][0]);

        $par = '/<title>(.+?)<\/title>/';
        preg_match_all($par, $response['body'], $match);
        if (!isset($match[1][0])) {
            return false;
        }
        $title = $match[1][0];

        $par = '/<meta name="keywords" content="(.+?)" \/>/';
        preg_match_all($par, $response['body'], $match);
        if (!isset($match[1][0])) {
            return false;
        }
        $keyWord = $match[1][0];

        $data = [
            'url' => $url,
            'keyword' => $keyWord,
            'title' => $title,
            'content' => $text
        ];

        $list = SearchService::import($data);
//        $sql = 'insert into test(keyword,title,content,org_url) value("' . $keyWord . '","' . $title . '","' . $text . '","' . $url . '")';
//        $smt = $db->prepare($sql);
//        $smt->execute();
    }

    public function get40Info()
    {
        $url = 'https://m.hupu.com/bbs/3441-12';
        $cookie = './cookies';
        $headSet['returnHeader'] = 0;
        $response = $this->curlRequest($url, $cookie, $cookie, '', [], $headSet);

        $par = '/<p>没有更多内容了<\/p>/';
        preg_match_all($par, $response['body'], $match);
        if (isset($match[0][0])) {
            exit();
        }

        $par = '/<li  >(\n|.)+?<a href="(.+?)"(\n|.)+?<\/li>/';
        preg_match_all($par, $response['body'], $match);

        foreach ($match[2] as $k => $v) {
            $this->getOneInfo('https:' . $v);
        }
    }

}

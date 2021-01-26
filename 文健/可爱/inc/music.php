<?php
/**
 * Author: Veen Zhao
 * CreateTime: 2020/9/14 15:34
 * 音乐播放器api
 */

use Metowolf\Meting;

header('Access-Control-Allow-Origin:*');//允许所有来源访问
header('Access-Control-Allow-Method:POST,GET');//允许访问的方式
require_once 'meting.php';
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (!empty($_GET['id']) && !empty($_GET['type']) && !empty($_GET['media'])) {
        $id = $_GET['id'];
        $type = $_GET['type'];
        $media = $_GET['media'];
        echo getMusicInfo($media, $type, $id);
    }
}

function getMusicInfo($media = 'netease', $type = 'song', $id = '')
{
    $cookie = 'osver=%E7%89%88%E6%9C%AC%2010.13.3%EF%BC%88%E7%89%88%E5%8F%B7%2017D47%EF%BC%89; os=osx; appver=1.5.9; MUSIC_U=*****; channel=netease;';
    $api = new Meting($media);
    if ($media == 'netease') {
        $api->cookie($cookie);
    }
    $info = array();
    switch ($type) {
        case 'song':
            $datas = $api->format(true)->song($id);
            $datas = json_decode($datas, true);
            $data = $datas[0];
            $cover = json_decode($api->format(true)->pic($data['pic_id']), true)['url'];
            $url = json_decode($api->format(true)->url($data['id']), true)['url'];
            /**
             * 修复网易云音乐防止盗链
             */
            if ($media == 'netease') {
                $url = str_replace("http://m7c", "http://m7", $url);
                $url = str_replace("http://m8c", "http://m8", $url);
            }
            if ($media == "tencent") {
                $url = str_ireplace("ws.stream.qqmusic.qq.com", "dl.stream.qqmusic.qq.com", $url);
            }
            $url = str_replace("http://", "https://", $url);
            $info = array(
                'name' => $data['name'],
                'url' => $url,
                'song_id' => $data['id'],
                'cover' => $cover,
                'author' => $data['artist'][0]
            );
            break;
        case 'collect':
            $datas = $api->format(true)->playlist($id);
            $datas = json_decode($datas, true);
            foreach ($datas as $keys => $data) {
                $cover = json_decode($api->format(true)->pic($data['pic_id']), true)['url'];
                $info[$keys] = array(
                    'name' => $data['name'],
                    'url' => '',
                    'song_id' => $data['id'],
                    'cover' => $cover,
                    'author' => $data['artist'][0]
                );
            }
            //shuffle($info);随机音乐TODO
            break;
        default:
            $data = "";
            break;
    }
    return json_encode($info, true);
}
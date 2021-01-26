<?php
/**
 * Author: Veen Zhao
 * CreateTime: 2020/9/7 22:31
 * 短代码设置
 */
require_once("ShortCodeCore.php");
//画廊
function shortcode_pic($atts, $content = '')
{
    return '<div class="photos">' . $content . '</div>';
}

add_shortcode('photos', 'shortcode_pic');

//特殊文字
function shortcode_ggl($atts, $content = '')
{
    return '<blur-text>' . $content . '</blur-text>';
}

add_shortcode('BlurText', 'shortcode_ggl');
function shortcode_hm($atts, $content = '')
{
    return '<darkb-text>' . $content . '</darkb-text>';
}

add_shortcode('DarkBText', 'shortcode_hm');
function shortcode_chwz($atts, $content = '')
{
    return '<rainbow-text>' . $content . '</rainbow-text>';
}

add_shortcode('RainBowText', 'shortcode_chwz');

//进度条

function shortcode_progress($atts, $content = '')
{
    $args = shortcode_atts(array(
        'value' => '',
        'color' => '',
        'start' => '',
    ), $atts);
    return '<div class="progress">
  <div class="' . $args['start'] . ' progress-bar progress-bar-striped ' . $args['color'] . '" role="progressbar" style="width: ' . $args['value'] . '%" aria-valuenow="' . $args['value'] . '" aria-valuemin="0" aria-valuemax="100">' . $content . '</div>
</div>';
}

add_shortcode('progress', 'shortcode_progress');

//折叠面板
function shortcode_acc($atts, $content = '')
{
    $id1 = 'id1' . uniqid(rand(1, 10000));
    $id2 = 'id2' . uniqid(rand(1, 10000));
    $id3 = 'id3' . uniqid(rand(1, 10000));
    $args = shortcode_atts(array(
        'title' => '',
        'status' => ''
    ), $atts);
    return '<div class="accordion" id="' . $id1 . '">
                    <div class="card">
                        <div class="card-header p-0" id="' . $id2 . '">
                            <div class="accordion-title" type="button" data-toggle="collapse" data-target="#' . $id3 . '"
                                 aria-expanded="true" aria-controls="' . $id3 . '">
                                ' . $args['title'] . '
                            </div>
                        </div>

                        <div id="' . $id3 . '" class="collapse ' . $args['status'] . '" aria-labelledby="' . $id2 . '"
                             data-parent="#' . $id1 . '">
                            <div class="card-body">
                            ' . $content . '    
                            </div>
                        </div>
                    </div>
                </div>';
}

add_shortcode('acc', 'shortcode_acc');

// 选项卡
function shortcode_tabs($atts, $content = '')
{
    $id1 = 'id1' . uniqid(rand(1, 10000));
    $id2 = 'id2' . uniqid(rand(1, 10000));
    if (!preg_match_all("/(.?)\[(item)\b(.*?)(?:(\/))?\](?:(.+?)\[\/item\])?(.?)/s", $content, $matches)) {
        return do_shortcode($content);
    } else {
        for ($i = 0; $i < count($matches[0]); $i++) {
            $matches[3][$i] = shortcode_parse_atts($matches[3][$i]);
        }
        $out = '<div class="tabs">
                    <nav>
                        <div class="nav nav-tabs" role="tablist">';
        for ($l = 0; $l < count($matches[0]); $l++) {
            $l == 0 ? $d = 'true' : $d = 'false';
            $l == 0 ? $w = 'active' : $w = '';
            $out .= '<a class="nav-link ' . $w . '" id="nav-' . $id2 . $l . '-tab" data-toggle="tab" href="#nav-' . $id1 . $l . '" role="tab"
                               aria-controls="nav-' . $id1 . $l . '" aria-selected="' . $d . '">' . $matches[3][$l]['title'] . '</a>';
        }
        $out .= '</div></nav><div class="tab-content">';
        for ($o = 0; $o < count($matches[0]); $o++) {
            $o == 0 ? $p = 'show active' : $p = '';
            $out .= '<div class="tab-pane fade ' . $p . '" id="nav-' . $id1 . $o . '" role="tabpanel"
                             aria-labelledby="nav-' . $id2 . $o . '-tab">' . autop(do_shortcode(trim($matches[5][$o]))) . '
                        </div>';
        }
        $out .= '</div></div>';
        return $out;
    }
}

add_shortcode('tabs', 'shortcode_tabs');

//多彩信息条
function shortcode_bar($atts, $content = '')
{
    $args = shortcode_atts(array(
        'color' => '',
    ), $atts);
    return '<blockquote style="border-left: 5px solid var(--bs-' . $args['color'] . ');"><p>' . $content . '</p></blockquote>';
}

add_shortcode('quote', 'shortcode_bar');

//多彩按钮
function shortcode_btn($atts, $content = '')
{
    $args = shortcode_atts(array(
        'color' => '',
        'outline' => '',
        'url' => '#',
        'target' => '_blank',
    ), $atts);
    if ($args['outline']){
        return '<a class="btn btn-' . $args['outline'] . '-' . $args['color'] . '" href="' . $args['url'] . '" role="button" target="' . $args['target'] . '">' . $content . '</a>';
    }else{
        return '<a class="btn btn-' . $args['color'] . '" href="' . $args['url'] . '" role="button" target="' . $args['target'] . '">' . $content . '</a>';
    }
    }

add_shortcode('button', 'shortcode_btn');

//多彩卡片
function shortcode_card($atts, $content = '')
{
    $args = shortcode_atts(array(
        'color' => '',
        'title' => '',
    ), $atts);
    return '<div class="card border-' . $args['color'] . ' mb-3">
  <div class="card-header text-' . $args['color'] . '" style="font-size: large;background-color: var(--bs-' . $args['color'] . '_opacity_1);">' . $args['title'] . '</div>
  <div class="card-body text-' . $args['color'] . '">
    <p class="card-text">' . $content . '</p>
  </div>
</div>';
}

add_shortcode('card', 'shortcode_card');

// 音频播放
function shortcode_audio($atts)
{
    $args = shortcode_atts(array(
        'src' => '',
        'loop' => ''
    ), $atts);

    return '<audio ' . $args['loop'] . ' src="' . $args['src'] . '" preload="metadata" controls>您的浏览器不支持 audio 标签。</audio>';
}

add_shortcode('audio', 'shortcode_audio');

// 视频播放
function shortcode_video($atts)
{
    $args = shortcode_atts(array(
        'src' => '',
        'loop' => '',
        'poster' => '',
        'width' => '100%',
        'height' => 'auto',
    ), $atts);
    return '<video ' . $args['loop'] . ' src="' . $args['src'] . '" width="' . $args['width'] . '" height="' . $args['height'] . '" preload="metadata" poster="' . $args['poster'] . '" controls>您的浏览器不支持 video 标签。</video>';
}

add_shortcode('video', 'shortcode_video');

/*bilibili*/
function shortcode_bili($atts, $content)
{
    return '<div class="bilibili">' .$content. '</div>';
}add_shortcode('BiliVideo', 'shortcode_bili');

// 友情链接
function shortcode_link($atts, $content = '')
{
    $args = shortcode_atts(array(
        'href' => '//',
        'img' => '//',
        'target' => '_blank',
        'title' => ''
    ), $atts);
    return '<div class="col-md-4">
                        <a class="friends-card a-none" href="' . $args['href'] . '" target="' . $args['target'] . '" title="' . $args['title'] . '">
                            <img class="friends-img mr-2 lazy" data-src="' . $args['img'] . '">
                            <div class="friends-ctx">
                                <div class="friends-name">' . $content . '</div>
                                <div class="friends-text small">' . $args['title'] . '</div>
                            </div>
                        </a>
                    </div>';
}
add_shortcode('link', 'shortcode_link');

function shortcode_friends($atts, $content = '')
{
    $args = shortcode_atts(array(
        'random' => 'true'
    ), $atts);
    if (!preg_match_all("/\[(link)\b(.*?)(?:(\/))?\](?:(.+?)\[\/link\])/s", $content, $matches, PREG_SET_ORDER)) {
        return do_shortcode($content);
    } else {
        if ($args['random'] === 'true') {
            shuffle($matches);
        }
            $out = '<div class="row mb-4">';
        foreach ($matches as $key => $val) {
            $out .=  do_shortcode($val[0]) ;
        }
        $out.='</div>';
        return $out;
    }
}
add_shortcode('friends', 'shortcode_friends');



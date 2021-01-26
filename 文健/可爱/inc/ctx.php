<?php

/**
 * Author: Veen Zhao
 * CreateTime: 2020/9/7 22:20
 * 内容渲染类
 */
class Ctx
{
    //完备标题输出
    public static function EchoTitle(Widget_Archive $archive)
    {
        if ($archive->is('index') && Helper::options()->站点副标题) {
            return Helper::options()->title . ' - ' . Helper::options()->站点副标题;
        } else {
            $archive->archiveTitle(array(
                'category' => '分类 %s 下的文章',
                'search' => '包含关键字 %s 的文章',
                'tag' => '标签 %s 下的文章',
                'author' => '%s 发布的文章'
            ), '', ' - ');
            return Helper::options()->title;
        }
    }

    //favicon输出
    public static function EchoFavicon()
    {
        $icon = Helper::options()->站点图标;
        if ($icon) {
            $res = '<link rel="icon" type="image/png" href="' . $icon . '">';
            $res .= '<link rel="apple-touch-icon-precomposed" href="' . $icon . '">';
        } else {
            $res = '<link rel="icon" type="image/png" href="' . App::HomeUrl('favicon.ico') . '">';
            $res .= '<link rel="apple-touch-icon-precomposed" href="' . App::HomeUrl('favicon.ico') . '">';
        }
        return $res;
    }

    //DNS预解析
    public static function DnsPrefetch()
    {
        $dns = '<link rel="dns-prefetch" href="//fonts.googleapis.com"><link rel="dns-prefetch" href="//cdn.jsdelivr.net">';
        if (Helper::options()->Dns预解析) {
            $dns .= Helper::options()->Dns预解析;
        }
        return $dns;
    }

    //logo输出
    public static function EchoLogo()
    {
        if (Helper::options()->logoUrl) {
            $logo = '<img src="' . Helper::options()->logoUrl . '" class="d-inline-block siteLogo" alt="">';
        } else {
            $logo = Helper::options()->title;
        }
        return $logo;
    }

    //分类输出
    private static function ArchiverSort($var)
    {
        $sort = null;
        $i = 0;
        while ($var->next()) {
            if ($var->levels === 0) {
                $children = $var->getAllChildren($var->mid);
                if (empty($children)) {
                    $sort .= '<li class="nav-item"><a class="nav-link" href="' . $var->permalink . '">' . self::IconArray()[0][$i] . $var->name . '</a></li>';
                } else {
                    $sort .= '<li class="nav-item dropdown">';
                    $sort .= '<a class="nav-link dropdown-toggle" id="sort' . $i . '" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' . self::IconArray()[0][$i] . $var->name . '</a>';
                    $sort .= '<div class="dropdown-menu" aria-labelledby="sort' . $i . '">';
                    foreach ($children as $mid) {
                        $child = $var->getCategory($mid);
                        $sort .= ' <a class="dropdown-item" href="' . $child['permalink'] . '"><span>' . $child['name'] . '</span></a>';
                        $i--;
                    }
                    $sort .= '</div></li>';
                }
            }
            $i++;
        }
        return $sort;
    }

    //页面输出
    private static function EchoPages($var)
    {
        $page = null;
        $i = 0;
        if (empty(Helper::options()->合并独立页面)) {
            while ($var->next()) {
                $page .= '<li class="nav-item"><a class="nav-link" href="' . $var->permalink . '">' . self::IconArray()[1][$i] . $var->title . '</a></li>';
                $i++;
            }
        } else {
            $pagesTitle = Helper::options()->合并独立页面导航名称 ? Helper::options()->合并独立页面导航名称 : '页面';
            $page .= '<li class="nav-item dropdown">';
            $page .= '<a class="nav-link dropdown-toggle" id="PagesAll" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' . self::IconArray()[1][$i] . $pagesTitle . '</a>';
            $page .= '<div class="dropdown-menu" aria-labelledby="PagesAll">';
            while ($var->next()) {
                $page .= ' <a class="dropdown-item" href="' . $var->permalink . '"><span>' . $var->title . '</span></a>';
            }
            $page .= '</div></li>';
        }
        return $page . self::MyPageUrl();
    }

    //分类和独立页面顺序设置
    public static function OrderSet($p, $c)
    {
        if (Helper::options()->分独顺序 == '独前分后') {
            return self::EchoPages($p) . self::ArchiverSort($c);
        } elseif (Helper::options()->分独顺序 == '分前独后') {
            return self::ArchiverSort($c) . self::EchoPages($p);
        } else {
            return self::EchoPages($p) . self::ArchiverSort($c);
        }
    }

    //提取图标数组
    private static function IconArray()
    {
        $cat = explode("\n", Helper::options()->分类图标);
        $pag = explode("\n", Helper::options()->页面图标);
        return array($cat, $pag);
    }

    //自定义导航链接
    private static function MyPageUrl()
    {
        $page = null;
        if (!empty(Helper::options()->自定义页面链接)) {
            $text = explode("\n", Helper::options()->自定义页面链接);
            foreach ($text as $url) {
                $c = substr_replace($url, ' class="nav-link" ', 2, 0);
                $page .= '<li class="nav-item">' . $c . '</li>';
            }
        }
        return $page;
    }

    //解析歌单url
    public static function ParseMusic($url)
    {
        $media = null;
        $id = null;
        $type = null;
        if (empty($url)) {
            return array('media' => 'media', 'id' => 'id', 'type' => 'type');
        }
        if (strpos($url, '163.com') !== false) {
            $media = 'netease';
            if (preg_match('/playlist\?id=(\d+)/i', $url, $id)) list($id, $type) = array($id[1], 'playlist');
            elseif (preg_match('/toplist\?id=(\d+)/i', $url, $id)) list($id, $type) = array($id[1], 'playlist');
            elseif (preg_match('/album\?id=(\d+)/i', $url, $id)) list($id, $type) = array($id[1], 'album');
            elseif (preg_match('/song\?id=(\d+)/i', $url, $id)) list($id, $type) = array($id[1], 'song');
            elseif (preg_match('/artist\?id=(\d+)/i', $url, $id)) list($id, $type) = array($id[1], 'artist');
        } elseif (strpos($url, 'qq.com') !== false) {
            $media = 'tencent';
            if (preg_match('/playlist\/([^\.]*)/i', $url, $id)) list($id, $type) = array($id[1], 'playlist');
            elseif (preg_match('/album\/([^\.]*)/i', $url, $id)) list($id, $type) = array($id[1], 'album');
            elseif (preg_match('/song\/([^\.]*)/i', $url, $id)) list($id, $type) = array($id[1], 'song');
            elseif (preg_match('/singer\/([^\.]*)/i', $url, $id)) list($id, $type) = array($id[1], 'artist');
        }
        return array('media' => $media, 'id' => $id, 'type' => $type);
    }

    //移动自定义导航链接
    private static function MobileMyPageUrl()
    {
        $page = null;
        if (!empty(Helper::options()->自定义页面链接)) {
            $text = explode("\n", Helper::options()->自定义页面链接);
            foreach ($text as $url) {
                $c = substr_replace($url, '  ', 2, 0);
                $page .= '<li>' . $c . '</li>';
            }
        }
        return $page;
    }

    //移动端页面输出
    private static function MobileEchoPages($var)
    {
        $page = null;
        $i = 0;
        $page .= '<hr><ul class="aside-pages px-3 list-unstyled small"><li class="d-inline-flex align-items-center"><svg class="icon mr-2" aria-hidden="true"><use xlink:href="#paperclip"></use></svg>独立页面</li>';
        while ($var->next()) {
            $page .= '<li><a onclick="Cuteen.bodyClose();" href="' . $var->permalink . '">' . self::IconArray()[1][$i] . $var->title . '</a></li>';
            $i++;
        }
        $page .= self::MobileMyPageUrl() . '</ul>';
        return $page;
    }

    //移动端分类列表输出
    private static function MobileArchiverSort($var)
    {
        $sort = null;
        $i = 0;
        $sort .= '<hr><ul class="aside-sort list-unstyled small"><li class="px-3 d-inline-flex align-items-center"><svg class="icon mr-2" aria-hidden="true"><use xlink:href="#paperclip"></use></svg>分类页面</li>';
        while ($var->next()) {
            if ($var->levels === 0) {
                $children = $var->getAllChildren($var->mid);
                if (empty($children)) {
                    $sort .= '<li><a href="' . $var->permalink . '">' . self::IconArray()[0][$i] . $var->name . '</a></li>';
                } else {
                    $sort .= '<li class="d-flex align-items-center justify-content-between" data-toggle="collapse" data-target="#sort' . $i . '" aria-expanded="false" aria-controls="collapseExample"><span>' . self::IconArray()[0][$i] . $var->name . '</span><svg class="icon" aria-hidden="true"><use xlink:href="#chevron-right"></use></svg></li>';
                    $sort .= '<div class="collapse aside-children" id="sort' . $i . '">';
                    foreach ($children as $mid) {
                        $child = $var->getCategory($mid);
                        $sort .= '<div><a href="' . $child['permalink'] . '">' . $child['name'] . '</a></div>';
                        $i--;
                    }
                    $sort .= '</div>';
                }
            }
            $i++;
        }
        $sort .= '</ul>';
        return $sort;
    }

    //移动端分类和独立页面顺序设置
    public static function MobileOrderSet($p, $c)
    {
        if (Helper::options()->分独顺序 == '独前分后') {
            return self::MobileEchoPages($p) . self::MobileArchiverSort($c);
        } elseif (Helper::options()->分独顺序 == '分前独后') {
            return self::MobileArchiverSort($c) . self::MobileEchoPages($p);
        } else {
            return self::MobileEchoPages($p) . self::MobileArchiverSort($c);
        }
    }

    //状态栏状态
    public static function NavBarInitial()
    {
        if (Helper::options()->顶部大图) {
            return 'bg-transparent has-img';
        } else {
            return 'bg-blur no-img';
        }
    }

    //大图高度
    public static function ImgHeight()
    {
        if (Helper::options()->大图高度) {
            return 'style="height : ' . Helper::options()->大图高度 . '";';
        } else {
            return '';
        }
    }

    //大图大标题
    public static function HeroTitle($ctx)
    {
        $big = null;
        $small = null;
        if ($ctx->is('index')) {
            if (Helper::options()->首页大标题) {
                $big = Helper::options()->首页大标题;
            } else {
                $big = Helper::options()->title;
            }
            if (Helper::options()->首页小标题) {
                $small = Helper::options()->首页小标题;
            } else {
                $small = Helper::options()->description;
            }
        } elseif ($ctx->is('post')) {
            $big = $ctx->title;
            $small = '
            <span class="post-info"><svg class="icon mr-1" aria-hidden="true"><use xlink:href="#shijian"></use></svg>' . date("Y年m月d日", $ctx->date->timeStamp) . '</span>
            <span class="post-info"><svg class="icon mr-1" aria-hidden="true"><use xlink:href="#huo"></use></svg>' . App::getPostViews($ctx) . '阅读</span>
            <span class="post-info"><svg class="icon mr-1" aria-hidden="true"><use xlink:href="#bidianliang"></use></svg>' . App::getWordCount($ctx->cid) . ' 字</span>
            <span class="post-info"><svg class="icon mr-1" aria-hidden="true"><use xlink:href="#pinglun1"></use></svg>' . $ctx->commentsNum . ' 条评论</span>';
        } else {
            $big = $ctx->title;
            $small = $ctx->fields->excerpt ?? '';
        }

        return array($big, $small);
    }

    //无图大标题输出
    public static function HeroTitleNoImg($ctx)
    {
        $str = null;
        if (!Helper::options()->顶部大图) {

            $str = '<h2>' . $ctx->title . '</h2>';
            $str .= '<div class="post-noimg-nav">';
            $str .= Ctx::HeroTitle($ctx)[1];
            $str .= '</div><hr>';

        }
        return $str;
    }

    //文章摘要输出
    private static function Excerpt($content, $limit, $ctx)
    {
        if ($ctx->fields->excerpt) {
            return $ctx->fields->excerpt;
        } else {
            $content = self::ExceptShortCodeContent($content);
            return Typecho_Common::subStr(strip_tags($content), 0, $limit, "...");
        }
    }

    //短代码排除
    private static function ExceptShortCodeContent($content)
    {
        $v = array(
            'BlurText', 'DarkBText', 'RainBowText', 'photos', 'hide', 'tabs', 'card', 'button', 'quote', 'acc',
            'progress', 'video', 'audio', 'link', 'friends', 'BiliVideo'
        );

        foreach ($v as $l) {
            if (strpos($content, '[' . $l) !== false) {
                $pattern = get_shortcode_regex(array($l));
                $content = preg_replace("/$pattern/", '', $content);
            }
        }
        $content = preg_replace('/\$\$[\s\S]*\$\$/sm', '', $content);
        return $content;
    }

    //列表图片输出
    public static function ImageEcho($options)
    {
        if ($options->fields->imgst) {
            return $options->fields->imgst;
        } else {
            if (Helper::options()->随机图) {
                $text = Helper::options()->随机图;
                $img_arr = explode("\n", $text);
                $rand_num = rand(1, count($img_arr));
                return $img_arr[$rand_num - 1];
            } else {
                return 'https://tvax1.sinaimg.cn/large/0084aYsLly1gj5l4cjjkij31uo11igu2.jpg';
            }
        }
    }

    //顶部大图输出
    public static function HeroImage($ctx)
    {
        $img = null;
        if ($ctx->is('post') || $ctx->is('page')) {
            $img = self::ImageEcho($ctx);
        } else {
            $img = Helper::options()->顶部大图链接;
        }
        return trim($img);
    }

    //经典列表输出
    private static function ListArray($ctx)
    {
        $a = null;
        $b = null;
        $c = null;
        $img = self::ImageEcho($ctx);
        if (Helper::options()->列表背景设置 == '毛玻璃背景') {
            $a = '<div class="blur-img"><img class="lazy" data-src="' . $img . '" alt="' . $ctx->title . '"></div>';
            $b = 'article';
        } else {
            $a = '';
            $b = 'article no-bg-img';
        }
        if (Helper::options()->列表图片效果 == '右斜切') {
            $c = 'img-right';
        } elseif (Helper::options()->列表图片效果 == '左斜切') {
            $c = 'img-left';
        } else {
            $c = '';
        }
        return array($a, $b, $img, $c);
    }

    private static function HaveImageArchives($ctx)
    {
        $section = null;
        $img = null;
        while ($ctx->next()) {
            $what = self::ListArray($ctx);
            if (Helper::options()->列表设置 == '图片居左') {
                $section .= '<article class="' . $what[1] . ' have-img d-flex">';
            } elseif (Helper::options()->列表设置 == '图片居右') {
                $section .= '<article class="' . $what[1] . ' have-img d-flex flex-row-reverse">';
            } elseif (Helper::options()->列表设置 == '图片左右交叉') {
                if ($ctx->sequence % 2 == 0) {
                    $section .= '<article class="' . $what[1] . ' have-img d-flex flex-row-reverse">';
                } else {
                    $section .= '<article class="' . $what[1] . ' have-img d-flex">';
                }
            } else {
                $section .= '<article class="' . $what[1] . ' have-img d-flex">';
            }
            $section .= $what[0];
            $section .= '<a  class="article-img ' . $what[3] . '" href="' . $ctx->permalink . '"><img class="lazy" data-src="' . $what[2] . '" alt="' . $ctx->title . '"></a>';
            $section .= '<div class="article-ctx">';
            $section .= '<header class="article-info">';
            $section .= '<div class="article-time"><svg class="icon mr-1" aria-hidden="true"><use xlink:href="#shijian"></use></svg>' . date("Y-m-d", $ctx->date->timeStamp) . '</div>';
            $section .= '<div class="article-vs">';
            $section .= '<span class="article-views"><svg class="icon mr-1" aria-hidden="true"><use xlink:href="#huo"></use></svg>' . App::getPostViews($ctx) . '<div class="readText">阅读</div></span>';
            $section .= '<a href="' . $ctx->categories[0]['permalink'] . '" class="article-sort a-none"><svg class="icon mx-1" aria-hidden="true"><use xlink:href="#biaoqian"></use></svg>' . $ctx->categories[0]['name'] . '</a>';
            $section .= '</div></header>';
            $section .= '<a class="article-title a-none" href="' . $ctx->permalink . '">' . $ctx->title . '</a>';
            $section .= '<a class="article-description a-none" href="' . $ctx->permalink . '">' . self::Excerpt($ctx->excerpt, 100, $ctx) . '</a>';
            $section .= '</div></article>';
        }
        return $section;
    }

    private static function NoImageArchives($ctx)
    {
        $section = null;
        while ($ctx->next()) {
            $section .= '<article class="article no-img d-flex">';
            $section .= '<div class="article-ctx">';
            $section .= '<header class="article-info">';
            $section .= '<div class="article-time"><svg class="icon mr-1" aria-hidden="true"><use xlink:href="#shijian"></use></svg>' . date("Y-m-d", $ctx->date->timeStamp) . '</div>';
            $section .= '<div class="article-vs">';
            $section .= '<span class="article-views"><svg class="icon mr-1" aria-hidden="true"><use xlink:href="#huo"></use></svg>' . App::getPostViews($ctx) . '阅读</span>';
            $section .= '<a  href="' . $ctx->categories[0]['permalink'] . '" class="article-sort a-none"><svg class="icon mx-1" aria-hidden="true"><use xlink:href="#biaoqian"></use></svg>' . $ctx->categories[0]['name'] . '</a>';
            $section .= '</div></header>';
            $section .= '<a class="article-title a-none" href="' . $ctx->permalink . '">' . $ctx->title . '</a>';
            $section .= '<a class="article-description a-none" href="' . $ctx->permalink . '">' . self::Excerpt($ctx->excerpt, 100, $ctx) . '</a>';
            $section .= '</div></article>';
        }
        return $section;
    }

    //经典卡片输出
    public static function ColumnSize()
    {
        if (Helper::options()->首页风格 == '卡片风格') {
            if (Helper::options()->侧边栏 && in_array('外页', Helper::options()->侧边栏显示位置)) {
                $size = array('col-lg-4', 'col-lg-9 cardMode');
            } else {
                $size = array('col-lg-3', 'col-lg-12 cardMode');
            }
        } else {
            $size = array('col-lg-3', 'col-lg-9');
        }
        return $size;
    }

    private static function CardStyleArchives($ctx)
    {
        $section = '<div class="row">';
        while ($ctx->next()) {

            $section .= '<div class="article card-style col-6 col-sm-6 col-md-4 ' . self::ColumnSize()[0] . '">';
            $section .= '<div class="card card-style-box h-100">';
            $section .= '<img data-src="' . self::ImageEcho($ctx) . '" class="card-style-img lazy">';
            $section .= '<div class="card-body card-style-body">';
            $section .= '<div class="card-style-tags">';
            $section .= '<a class="card-style-tags-link a-none" href="' . $ctx->categories[0]['permalink'] . '">' . $ctx->categories[0]['name'] . '</a></div>';
            $section .= '<a href="' . $ctx->permalink . '" class="card-style-title a-none">' . $ctx->title . '</a></div>';
            $section .= '<div class="card-style-footer">';
            $section .= '<div class="meta-date">
                                <time>
                                    <svg class="icon mr-1" aria-hidden="true">
                                        <use xlink:href="#shijian"></use>
                                    </svg>
            ' . date("Y-m-d", $ctx->date->timeStamp) . '
                                </time>
                            </div>';
            $section .= '<div class="meta-views">
                                <div>
                                    <svg class="icon mr-1" aria-hidden="true">
                                        <use xlink:href="#huo"></use>
                                    </svg>
            ' . App::getPostViews($ctx) . '
                                </div>
                            </div>';
            $section .= '</div>
                    </div></div>
                ';
        }
        $section .= '</div>';
        return $section;
    }

    public static function Article($var)
    {
        if (Helper::options()->首页风格 == '列表风格') {
            return self::HaveImageArchives($var);
        } elseif (Helper::options()->首页风格 == '卡片风格') {
            return self::CardStyleArchives($var);
        } elseif (Helper::options()->首页风格 == '无图风格') {
            return self::NoImageArchives($var);
        } else {
            return self::NoImageArchives($var);
        }
    }

    //侧边栏渲染
    public static function Sidebar($var)
    {
        if (Helper::options()->侧边栏) {
            if (in_array('外页', Helper::options()->侧边栏显示位置)) {
                if ($var->is('index') || $var->is('archive')) {
                    $var->need('base/sidebar.php');
                }
            }
            if (in_array('内页', Helper::options()->侧边栏显示位置)) {
                if ($var->is('page') || $var->is('post')) {
                    $var->need('base/sidebar.php');
                }
            }
        } else {
            return '';
        }
        return false;
    }

    //侧边栏信息
    public static function SidebarArray()
    {
        $a = null;
        $b = null;
        $c = null;
        if (Helper::options()->侧边栏左右 == '左') {
            $a = '';
        } elseif (Helper::options()->侧边栏左右 == '右') {
            $a = 'flex-row-reverse';
        } else {
            $a = '';
        }
        return array($a);
    }

    //文章输出
    public static function Post($ctx, $login)
    {
        return self::parseHeader(self::parseShortCode(self::ParseEmoji(self::ParseImage(self::Hide($ctx, $login)))));
    }

    //评论输出
    public static function Comment($ctx)
    {
        return self::parseShortCode(self::ParseEmoji(self::ParseImage($ctx)));
    }

    //回复可见功能
    public static function Hide($ctx, $login)
    {
        $db = Typecho_Db::get();
        $sql = $db->select()->from('table.comments')
            ->where('cid = ?', $ctx->cid)
            ->where('mail = ?', $ctx->remember('mail', true))
            ->where('status = ?', 'approved')
            ->limit(1);
        $result = $db->fetchAll($sql);
        if ($login || $result) {
            $content = preg_replace("/\[hide\](.*?)\[\/hide\]/sm", '$1', $ctx->content);
        } else {
            $content = preg_replace("/\[hide\](.*?)\[\/hide\]/sm", '<div class="reply2view">此处内容需要评论<a onclick="document.getElementById(`comments`).scrollIntoView({behavior: `smooth`});">回复</a>后方可阅读</div>', $ctx->content);
        }
        return $content;
    }

    //解析短代码
    private static function parseShortCode($content)
    {
        $content = do_shortcode($content);
        return $content;
    }

    //解析文章内图片
    private static function ParseImage($content)
    {
        $reg = '/<img(.*?)src="(.*?)"(.*?)>/s';
        $rp = '<a class="lightbox" href="${2}"><img${1} class="lazy" data-src="${2}"${3}></a>';
        return preg_replace($reg, $rp, $content);
    }

    //解析表情
    static public function ParseEmoji($content)
    {
        $content = preg_replace_callback(
            '/\:\:\(\s*(呵呵|哈哈|吐舌|太开心|笑眼|花心|小乖|乖|捂嘴笑|滑稽|你懂的|不高兴|怒|汗|黑线|泪|真棒|喷|惊哭|阴险|鄙视|酷|啊|狂汗|what|疑问|酸爽|呀咩爹|委屈|惊讶|睡觉|笑尿|挖鼻|吐|犀利|小红脸|懒得理|勉强|爱心|心碎|玫瑰|礼物|彩虹|太阳|星星月亮|钱币|茶杯|蛋糕|大拇指|胜利|haha|OK|沙发|手纸|香蕉|便便|药丸|红领巾|蜡烛|音乐|灯泡|开心|钱|咦|呼|冷|生气|弱|吐血)\s*\)/is',
            array('Ctx', 'ParsePaoPao'),
            $content
        );
        $content = preg_replace_callback(
            '/\:\@\(\s*(高兴|小怒|脸红|内伤|装大款|赞一个|害羞|汗|吐血倒地|深思|不高兴|无语|亲亲|口水|尴尬|中指|想一想|哭泣|便便|献花|皱眉|傻笑|狂汗|吐|喷水|看不见|鼓掌|阴暗|长草|献黄瓜|邪恶|期待|得意|吐舌|喷血|无所谓|观察|暗地观察|肿包|中枪|大囧|呲牙|抠鼻|不说话|咽气|欢呼|锁眉|蜡烛|坐等|击掌|惊喜|喜极而泣|抽烟|不出所料|愤怒|无奈|黑线|投降|看热闹|扇耳光|小眼睛|中刀)\s*\)/is',
            array('Ctx', 'ParseAru'),
            $content
        );
        return $content;
    }

    //泡泡表情回调函数
    private static function ParsePaoPao($match)
    {
        return '<img class="emoji lazy" data-src="' . StaticPath . '/emoji/paopao/' . str_replace('%', '', urlencode($match[1])) . '_2x.png">';
    }

    //阿鲁表情回调函数
    private static function ParseAru($match)
    {
        return '<img class="emoji" src="' . StaticPath . '/emoji/aru/' . str_replace('%', '', urlencode($match[1])) . '_2x.png">';
    }

    //解析文章内 h2 ~ h5 元素
    static public function parseHeader($content)
    {
        $reg = '/\<h([1-6])(.*?)\>(.*?)\<\/h.*?\>/s';
        $new = preg_replace_callback($reg, array('Ctx', 'parseHeaderCallback'), $content);
        return $new;
    }

    //为内容中的 h2-h6 元素编号
    static private $CurrentTocID = 0;

    static public function parseHeaderCallback($matchs)
    {
        // 增加单独标记，否则冲突
        $id = 'toc_' . (self::$CurrentTocID++);
        return '<h' . $matchs[1] . $matchs[2] . ' id="' . $id . '">' . $matchs[3] . '</h' . $matchs[1] . '>';
    }


    //评论新标签
    static public function CommentAuthor($obj, $autoLink = NULL, $noFollow = NULL)
    {
        $options = Helper::options();
        $autoLink = $autoLink ? $autoLink : $options->commentsShowUrl;
        $noFollow = $noFollow ? $noFollow : $options->commentsUrlNofollow;
        if ($obj->url && $autoLink) {
            return '<a class="a-none" href="' . $obj->url . '"' . ($noFollow ? ' rel="external nofollow"' : NULL) . (strstr($obj->url, $options->index) == $obj->url ? NULL : ' target="_blank"') . '>' . $obj->author . '</a>';
        } else {
            return $obj->author;
        }
    }

    //评论信息
    static public function CommentInfo($comments)
    {
        $info = null;
        if ($comments->authorId == $comments->ownerId) {
            $info .= '<span class="badge rounded-pill bg-primary font-weight-normal">站长</span>';  //如果是文章作者
        } else {
            $info .= '<span class="badge rounded-pill bg-secondary font-weight-normal">访客</span>';  //如果是评论作者
        }
        $info .= '<span class="comment-system">' . self::getBrowser($comments->agent) . '</span>';
        $info .= '<span class="comment-system">' . self::getOs($comments->agent) . '</span>';
        return $info;
    }

    //获取评论浏览器
    private static function getBrowser($agent)
    {
        if (preg_match('/MSIE\s([^\s|;]+)/i', $agent)) {
            $getAgent = '<svg class="icon" aria-hidden="true"><use xlink:href="#ie"></use></svg><span>IE</span>';
        } else if (preg_match('/FireFox\/([^\s]+)/i', $agent)) {
            $getAgent = '<svg class="icon" aria-hidden="true"><use xlink:href="#firefox"></use></svg><span>FireFox</span>';
        } else if (preg_match('/Chrome([\d]*)\/([^\s]+)/i', $agent, $matches)) {
            $getAgent = '<svg class="icon" aria-hidden="true"><use xlink:href="#chrome"></use></svg><span>Chrome</span>';
        } else if (preg_match('/QQBrowser\/([^\s]+)/i', $agent)) {
            $getAgent = '<svg class="icon" aria-hidden="true"><use xlink:href="#QQliulanqilogo"></use></svg><span>QQ</span>';
        } else if (preg_match('/UC/i', $agent)) {
            $getAgent = '<svg class="icon" aria-hidden="true"><use xlink:href="#uc"></use></svg><span>UC</span>';
        } else if (preg_match('/safari\/([^\s]+)/i', $agent)) {
            $getAgent = '<svg class="icon" aria-hidden="true"><use xlink:href="#safari"></use></svg><span>Safari</span>';
        } elseif (preg_match('#SE 2([a-zA-Z0-9.]+)#i', $agent)) {
            $getAgent = '<svg class="icon" aria-hidden="true"><use xlink:href="#sougou"></use></svg><span>Sogou</span>';
        } elseif (preg_match('#360([a-zA-Z0-9.]+)#i', $agent)) {
            $getAgent = '<svg class="icon" aria-hidden="true"><use xlink:href="#liulanqi"></use></svg><span>360</span>';
        } elseif (preg_match('#Edge ([a-zA-Z0-9.]+)#i', $agent)) {
            $getAgent = '<svg class="icon" aria-hidden="true"><use xlink:href="#edge"></use></svg><span>Edge</span>';
        } else {
            $getAgent = '<svg class="icon" aria-hidden="true"><use xlink:href="#ie"></use></svg><span>Browser</span>';
        }
        return $getAgent;
    }

    //获取评论操作系统
    private static function getOs($agent)
    {
        if (preg_match('/win/i', $agent)) {
            $os = '<svg class="icon" aria-hidden="true"><use xlink:href="#win10"></use></svg><span>Windows</span>';
        } else if (preg_match('/android/i', $agent)) {
            $os = '<svg class="icon" aria-hidden="true"><use xlink:href="#anzhuo"></use></svg><span>Android</span>';
        } else if (preg_match('/linux/i', $agent)) {
            $os = '<svg class="icon" aria-hidden="true"><use xlink:href="#linux"></use></svg><span>Linux</span>';
        } else if (preg_match('/mac/i', $agent)) {
            $os = '<svg class="icon" aria-hidden="true"><use xlink:href="#pingguo"></use></svg><span>Mac</span>';
        } else {
            $os = '<svg class="icon" aria-hidden="true"><use xlink:href="#diannao"></use></svg><span>System</span>';
        }
        return $os;
    }

    //翻页
    public static function pagination($index)
    {
        echo '<div class="All_Pagination">';
        $index->pageNav(
            '&laquo;',
            '&raquo;',
            1,
            '...',
            array(
                'wrapTag' => 'ul',
                'wrapClass' => 'prev',
                'itemTag' => 'li',
                'textTag' => 'a',
                'currentClass' => 'active1',
                'prevClass' => '',
                'nextClass' => ''
            )
        );
        echo '</div>';
    }

    //获取前10标签统计
    public static function topTags($ctx)
    {
        $name = array();
        $count = array();
        $ctx->widget('Widget_Metas_Tag_Cloud', array('sort' => 'count', 'ignoreZeroCount' => true, 'desc' => true, 'limit' => 10))->to($tags);
        while ($tags->next()) {
            $name[] = $tags->name;
            $count[] = $tags->count;
        }
        return array($name, $count);
    }

    //获取前6分类统计
    public static function topSort($ctx)
    {
        $ctx->widget('Widget_Metas_Category_List')->to($sort);
        while ($sort->next()) {
            $name[] = [$sort->name, $sort->count];
        }
        foreach ($name as $k => $v) {
            $count[] = $v[1];
        }
        array_multisort($count, SORT_DESC, $name);
        $name = array_slice($name, 0, 6);
        foreach ($name as $f => $g) {
            $arr1[] = array('name' => $g[0], 'max' => $name[0][1] + 2);
            $arr2[] = $g[1];
        }
        return array($arr1, $arr2);
    }

    //获取前10个月文章发布数量
    public static function postCount($widget)
    {
        $db = Typecho_Db::get();
        $rows = $db->fetchAll($db->select()
            ->from('table.contents')
            ->order('table.contents.created', Typecho_Db::SORT_DESC)
            ->where('table.contents.type = ?', 'post')
            ->where('table.contents.status = ?', 'publish'));
        $stat = array();
        $data = array();
        $a = array();
        $b = array();
        foreach ($rows as $row) {
            $row = $widget->filter($row);
            $stat[] = date('Y-m', $row['created']);
        }
        $arr = array_count_values($stat);
        for ($i = 1; $i <= 10; $i++) {
            $months[] = date("Y-m", strtotime(date('Y-m-01') . " -$i months"));
            if (array_key_exists($months[$i - 1], $arr)) {
                $data[$months[$i - 1]] = $arr[$months[$i - 1]];
            } else {
                $data[$months[$i - 1]] = 0;
            }
        }
        foreach (array_reverse($data) as $key => $l) {
            $a[] = $key;
            $b[] = $l;
        }
        return array($a, $b);
    }

    //归档文章列表输出
    public static function archives($widget)
    {
        $db = Typecho_Db::get();
        $rows = $db->fetchAll($db->select()
            ->from('table.contents')
            ->order('table.contents.created', Typecho_Db::SORT_DESC)
            ->where('table.contents.type = ?', 'post')
            ->where('table.contents.status = ?', 'publish'));
        $stat = array();
        foreach ($rows as $row) {
            $row = $widget->filter($row);
            $arr = array(
                'title' => $row['title'],
                'permalink' => $row['permalink']
            );
            $stat[date('Y年 m月', $row['created'])][$row['created']] = $arr;
        }
        return $stat;
    }

    //已发布文章数量
    public static function getPostNum()
    {
        $db = Typecho_Db::get();
        return $db->fetchObject($db->select(array('COUNT(cid)' => 'num'))
            ->from('table.contents')
            ->where('table.contents.type = ?', 'post')
            ->where('table.contents.status = ?', 'publish'))->num;
    }

    //评论总数量，排除自己评论
    public static function getCommentsNum()
    {
        $db = Typecho_Db::get();
        return $db->fetchObject($db->select(array('COUNT(authorId)' => 'num'))
            ->from('table.comments')
            ->where('table.comments.authorId = ?', null)->where('table.comments.type=?', 'comment'))->num;
    }

    //标签数量
    public static function getTagNum()
    {
        $db = Typecho_Db::get();
        return $db->fetchObject($db->select(array('COUNT(mid)' => 'num'))
            ->from('table.metas')
            ->where('table.metas.type = ?', 'tag'))->num;
    }

    //文章置顶轮播
    public static function PostTop($ctx)
    {
        $str = null;
        $arr = null;
        $i = 0;
        $a = null;
        $b = null;
        if (Helper::options()->文章置顶) {
            $CardTOP_CID = Helper::options()->文章置顶;
            $ctx->widget('Widget_Post_CardTOP@CardTOP', 'CardTOP=' . $CardTOP_CID)->to($CardTOPSel);
            while ($CardTOPSel->next()) {
                if ($i == 0) {
                    $a .= '<li data-target="#postTop" data-slide-to="0" class="active"></li>';
                    $b .= '<div class="carousel-item active" data-interval="3000">';
                } else {
                    $a .= '<li data-target="#postTop" data-slide-to="' . $i . '"></li>';
                    $b .= '<div class="carousel-item" data-interval="3000">';
                }
                $b .= '<img data-src="' . self::ImageEcho($CardTOPSel) . '" class="d-block w-100 lazy">';
                $b .= '<a href="' . $CardTOPSel->permalink . '" class="carousel-caption a-none">';
                $b .= '<h4>' . $CardTOPSel->title . '</h4>';
                $b .= '<div class="top-post-info"><span class="post-info"><svg class="icon mr-1" aria-hidden="true"><use xlink:href="#shijian"></use></svg>' . date("Y年m月d日", $CardTOPSel->date->timeStamp) . '</span>
            <span class="post-info"><svg class="icon mr-1" aria-hidden="true"><use xlink:href="#huo"></use></svg>' . App::getPostViews($CardTOPSel) . '阅读</span>
            <span class="post-info"><svg class="icon mr-1" aria-hidden="true"><use xlink:href="#bidianliang"></use></svg>' . App::getWordCount($CardTOPSel->cid) . ' 字</span>
            <span class="post-info"><svg class="icon mr-1" aria-hidden="true"><use xlink:href="#pinglun1"></use></svg>' . $CardTOPSel->commentsNum . ' 条评论</span></div>';
                $b .= '</a></div>';
                $i++;
            }
            $arr = array($a, $b);
            $str = '<div id="postTop" class="carousel slide carousel-fade" data-ride="carousel">';
            $str .= '<ol class="carousel-indicators">';
            $str .= $arr[0];
            $str .= '</ol>';
            $str .= '<div class="carousel-inner">';
            $str .= $arr[1];
            $str .= '</div>';
            $str .= '<a class="carousel-control-prev" href="#postTop" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                </a>';
            $str .= '<a class="carousel-control-next" href="#postTop" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                </a>';
            $str .= '</div>';
        }
        return $str;
    }

    //思源宋体渲染
    public static function FontSet()
    {
        if (Helper::options()->思源宋体 && !App::isMobile()) {
            return '<link href="https://fonts.googleapis.com/css2?family=Noto+Serif+SC:wght@400;700;900&display=swap" rel="stylesheet">';
        }
        return false;
    }

    //页面预加载脚本
    public static function readyLoad()
    {
        if (Helper::options()->页面预加载 && !Helper::options()->Pjax无刷新) {
            echo '<script src="https://cdn.jsdelivr.net/npm/instant.page@5.1.0/instantpage.min.js"></script>';
        }
    }

    //大波浪
    public static function waveHeader()
    {
        if (Helper::options()->头图大波浪) {
            return '<section class="main-hero-waves-area waves-area">
        <svg class="waves-svg" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 24 150 28" preserveAspectRatio="none" shape-rendering="auto">
            <defs>
                <path id="gentle-wave" d="M -160 44 c 30 0 58 -18 88 -18 s 58 18 88 18 s 58 -18 88 -18 s 58 18 88 18 v 44 h -352 Z"></path>
            </defs>
            <g class="parallax">
                <use xlink:href="#gentle-wave" x="48" y="0"></use>
                <use xlink:href="#gentle-wave" x="48" y="3"></use>
                <use xlink:href="#gentle-wave" x="48" y="5"></use>
                <use xlink:href="#gentle-wave" x="48" y="7"></use>
            </g>
        </svg>
    </section>';
        } else {
            return false;
        }
    }
    public static function pluginJudge(){
        if (!self::isPluginAvailable('Cuteen')) {
            exit('<h3>插件未启用！！！如有疑问请联系QQ2013143650</h3>');
        }
    }
    public static function isPluginAvailable($name)
    {
        $plugins = Typecho_Plugin::export();
        $plugins = $plugins['activated'];
        return is_array($plugins) && array_key_exists($name, $plugins);
    }

}

//后台标签文本
class EchoHtml extends Typecho_Widget_Helper_Layout
{
    public function __construct($html)
    {
        $this->html($html);
        $this->start();
        $this->end();
    }

    public function start()
    {
    }

    public function end()
    {
    }
}


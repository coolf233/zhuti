<?php

/**
 * functions.php
 *
 * 初始化
 *
 * @author      Veen Zhao
 */

if (!defined('__TYPECHO_ROOT_DIR__')) exit;

$GLOBALS['config'] = require_once "inc/config.inc.php";
require_once("inc/shortCodeUser.php");
include("inc/phpqrcode.php");
require_once("inc/app.php");
require_once("inc/ctx.php");


error_reporting(0);

function themeInit()
{
    Helper::options()->commentsAntiSpam = false; //关闭反垃圾
    Helper::options()->commentsCheckReferer = false; //关闭检查评论来源URL与文章链接是否一致判断(否则会无法评论)
    Helper::options()->commentsMaxNestingLevels = '999'; //最大嵌套层数
    Helper::options()->commentsPageDisplay = 'first'; //强制评论第一页
    Helper::options()->commentsOrder = 'DESC'; //将最新的评论展示在前
    Helper::options()->commentsHTMLTagAllowed = '<a href=""> <img src=""> <img src="" class=""> <code> <del>';
    Helper::options()->commentsMarkdown = true;
}
/**
* 提示文章百度是否收录
*
*/
function baidu_record() {
$url='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

if(checkBaidu($url)==1)
{echo "百度已收录";
}
else
{echo "<a style=\"color:red;\" rel=\"external nofollow\" title=\"点击提交收录！\" target=\"_blank\" href=\"http://zhanzhang.baidu.com/sitesubmit/index?sitename=$url\">百度未收录</a>";}
}
function checkBaidu($url) {
$url = 'http://www.baidu.com/s?wd=' . urlencode($url);
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
$rs = curl_exec($curl);
curl_close($curl);
if (!strpos($rs, '没有找到')) { //没有找到说明已被百度收录
return 1;
} else {
return -1;
}
}

    /**
     * 访问总量
     */
     function theAllViews(){
             $db = Typecho_Db::get();
             $row = $db->fetchAll('SELECT SUM(VIEWS) FROM `typecho_contents`');
                 echo number_format($row[0]['SUM(VIEWS)']);
     }

    /**
     * 响应时间
     */
    function timer_start() {
        global $timestart;
        $mtime = explode( ' ', microtime()  );
        $timestart = $mtime[1] + $mtime[0];
        return true; 
    }
    timer_start();
    function timer_stop( $display = 0, $precision = 3  ) {
        global $timestart, $timeend;
        $mtime = explode( ' ', microtime()  );
        $timeend = $mtime[1] + $mtime[0];
        $timetotal = number_format( $timeend - $timestart, $precision  );
        $r = $timetotal < 1 ? $timetotal * 1000 . " ms" : $timetotal . " s";
        if ( $display  ) {
            echo $r;
        }
        return $r;
    }
//主题静态资源的绝对地址
if (strlen(trim(Helper::options()->yourcdn)) > 0) {
    @define('StaticPath', Helper::options()->yourcdn);
} else {
    @define('StaticPath', '' . Helper::options()->themeUrl . '/static/');
}
function themeConfig($form)
{
    App::BackSet();
    $ctx = '<link rel="stylesheet" href="' . StaticPath . 'css/admin.css">';
    $ctx .= '<script src="' . StaticPath . 'js/admin.js"></script>';
    $ctx .= '<div class="tip"><span class="tip-var">Var ' . $GLOBALS['config']['version'] . ' </span><div class="tip-header">';
    $ctx .= '<h1>Cuteen ' . $GLOBALS['config']['version'] . ' 设置面板</h1></div>';
    $ctx .= '<p>作者：<a href="https://blog.zwying.com">Veen Zhao</a></p><p>欢迎您使用Cuteen主题，如有疑问进群咨询。</p>';
    $ctx .= '<form class="protected home col-mb-12" action="?Cuteenbf" method="post">';
    $ctx .= '<div class="typecho-option"><label class="typecho-label">主题设置项备份：' . App::CheckSetBack() . '</label></div>';
    $ctx .= '<input type="submit" name="type" class="btn lan" value="备份" />  ';
    $ctx .= '<input type="submit" name="type" class="btn lan" value="还原" />  ';
    $ctx .= '<input type="submit" name="type" class="btn hong" style="float:right" value="删除" />';
    $ctx .= '</form></div>';
    echo $ctx;
//侧边导航
    $form->addItem(new EchoHtml('<div class="allTab"><div class="tab">'));
    $form->addItem(new EchoHtml('<div class="tabLinks">基础设置</div>'));
    $form->addItem(new EchoHtml('<div class="tabLinks">导航设置</div>'));
    $form->addItem(new EchoHtml('<div class="tabLinks">顶部设置</div>'));
    $form->addItem(new EchoHtml('<div class="tabLinks">首页设置</div>'));
    $form->addItem(new EchoHtml('<div class="tabLinks">侧边栏设置</div>'));
    $form->addItem(new EchoHtml('<div class="tabLinks">文章页设置</div>'));
    $form->addItem(new EchoHtml('<div class="tabLinks">增强功能</div>'));
    $form->addItem(new EchoHtml('<div class="tabLinks">特效设置</div>'));
    $form->addItem(new EchoHtml('<div class="tabLinks">自定义设置</div>'));
    $form->addItem(new EchoHtml('</div>'));
//基础设置
    $form->addItem(new EchoHtml('<div class="tabContent">'));
    $key = new Typecho_Widget_Helper_Form_Element_Text('key', NULL, NULL, _t('<font color="red">域名授权码</font>'), _t('<font color="red">购买后索要获取</font>'));
    $form->addInput($key);
    $SiteColorMode = new Typecho_Widget_Helper_Form_Element_Radio('darkSet', array('0' => '自动切换', '1' => '强制白昼样式', '2' => '强制黑夜样式',), '0', '昼夜风格设置', '注意：选择自动模式时，用户在前台可按需切换，且刷新页面不丢失样式；除自动模式外，用户在前台切换后，刷新页面样式均会丢失。自动切换时，默认为晚20点到早6点为黑夜模式');
    $form->addInput($SiteColorMode);
    $site2name = new Typecho_Widget_Helper_Form_Element_Text('站点副标题', NULL, NULL, _t('站点副标题'), _t('输入站点副标题，将显示在首页标题后，不填则不显示'));
    $form->addInput($site2name);
    $sitefav = new Typecho_Widget_Helper_Form_Element_Text('站点图标', NULL, NULL, _t('站点图标设置'), _t('输入站点favicon.ico的地址，不填则默认网站根目录下图标'));
    $form->addInput($sitefav);
    $logoUrl = new Typecho_Widget_Helper_Form_Element_Text('logoUrl', NULL, NULL, _t('站点 LOGO 地址'), _t('在这里填入一个图片 URL 地址, 以在网站标题前加上一个 LOGO'));
    $form->addInput($logoUrl);
    $beian = new Typecho_Widget_Helper_Form_Element_Text('备案号', NULL, NULL, _t('ICP备案号'), _t('在这里输入备案号（国外主机用户请忽略）'));
    $form->addInput($beian);
    $gabeian = new Typecho_Widget_Helper_Form_Element_Text('公安备案号', NULL, NULL, _t('公安备案号'), _t('在这里输入公安备案号，未备案请忽略'));
    $form->addInput($gabeian);
    $form->addItem(new EchoHtml('</div>'));
//导航设置
    $form->addItem(new EchoHtml('<div class="tabContent">'));
    $headroom = new Typecho_Widget_Helper_Form_Element_Checkbox('headroom', array('headroom' => '是否启用状态栏下滑消失，上滑出现效果'), null, '状态栏下滑消失');
    $form->addInput($headroom->multiMode());
    $pageortags = new Typecho_Widget_Helper_Form_Element_Radio('分独顺序', array('独前分后' => '独立页面在前，分类在后', '分前独后' => '分类在前，独立页面在后',), '0', '分类与独立页面顺序', '默认为独立页面在前，分类在后');
    $form->addInput($pageortags);
    $pagealls = new Typecho_Widget_Helper_Form_Element_Checkbox('合并独立页面', array('合并独立页面' => '启用后独立页面将合并到一起'), null, '独立页面合并');
    $form->addInput($pagealls->multiMode());
    $pageallstit = new Typecho_Widget_Helper_Form_Element_Text('合并独立页面导航名称', NULL, NULL, _t('页面合并菜单显示名称'), _t('在这里输入导航栏页面下拉菜单的显示名称,留空则默认显示为<b>页面</b>，<font color="red">独立页面合并开启后有效</font>'));
    $form->addInput($pageallstit);
    $fatext = new Typecho_Widget_Helper_Form_Element_Textarea('分类图标', NULL, NULL, _t('顶部导航栏一级分类图标'), _t('顶部导航栏分类icon图标，一行请填写一个，和导航条分类菜单项按顺序匹配'));
    $form->addInput($fatext);
    $pagefatext = new Typecho_Widget_Helper_Form_Element_Textarea('页面图标', NULL, NULL, _t('顶部导航栏独立页面图标'), _t('顶部导航栏独立页面icon图标，一行请填写一个，和导航条页面菜单项按顺序匹配'));
    $form->addInput($pagefatext);
    $urltext = new Typecho_Widget_Helper_Form_Element_Textarea('自定义页面链接', NULL, NULL, _t('自定义导航栏链接'), _t('格式为：&lt;a href="地址"&gt;（可加图标）名称&lt;/a&gt;一行请填写一个，将直接渲染在独立页面后'));
    $form->addInput($urltext);
    $form->addItem(new EchoHtml('</div>'));
//顶部设置
    $form->addItem(new EchoHtml('<div class="tabContent">'));
    $indeximg = new Typecho_Widget_Helper_Form_Element_Checkbox('顶部大图', array('indeximg' => '开启后需要配置以下<div style="color:blue;display:inline-block">蓝色</div>选项,红星为必填项'), null, '<div style="color:blue">顶部大图</div>');
    $form->addInput($indeximg->multiMode());
    $indexpicimg = new Typecho_Widget_Helper_Form_Element_Text('顶部大图链接', NULL, NULL, _t('<div style="color:blue;float: left;">顶部大图链接</div><div style="color:red;">*</div>'), _t('输入图片直链或者随机图API均可'));
    $form->addInput($indexpicimg);
    $indexbigtit = new Typecho_Widget_Helper_Form_Element_Text('首页大标题', NULL, NULL, _t('<div style="color:blue">首页顶部大标题</div>'), _t('3~6字最佳，请勿太长，留空则显示站点标题'));
    $form->addInput($indexbigtit);
    $indexsubtit = new Typecho_Widget_Helper_Form_Element_Text('首页小标题', NULL, NULL, _t('<div style="color:blue">首页顶部小标题</div>'), _t('10~15字最佳，留空则显示站点描述'));
    $form->addInput($indexsubtit);
    $heroheight = new Typecho_Widget_Helper_Form_Element_Text('大图高度', NULL, NULL, _t('大图高度自定义'), _t('自定义顶图高度，开启顶部大图、且为PC端时，此项有效。填写请带单位，例如：700px'));
    $form->addInput($heroheight);
    $form->addItem(new EchoHtml('</div>'));
//首页设置
    $form->addItem(new EchoHtml('<div class="tabContent">'));
    $fengge = new Typecho_Widget_Helper_Form_Element_Radio('首页风格', array(
        '列表风格' => '列表风格',
        '无图风格' => '无图风格',
        '卡片风格' => '卡片风格',
    ), '列表风格', '首页文章输出样式<div style="color:red;display: inline-block;"> 选择后可以配置对应设置</div>', '默认为列表风格');
    $form->addInput($fengge);

    $form->addItem(new EchoHtml('<div style="font-size:1.5em;margin-top:25px"><b>列表风格配置</b></div><hr />'));

    $indexStyle = new Typecho_Widget_Helper_Form_Element_Radio('列表设置', array(
        '图片居左' => '图片居左',
        '图片居右' => '图片居右',
        '图片左右交叉' => '图片左右交叉',
    ), '无图', '首页文章列表图片样式', '默认为无图样式');
    $form->addInput($indexStyle);

    $indexCardStyle = new Typecho_Widget_Helper_Form_Element_Radio('列表背景设置', array(
        '白色背景' => '白色背景',
        '毛玻璃背景' => '毛玻璃背景<div style="color:red;display: inline-block;"> 此效果只在PC端有效</div>',
    ), '白色背景', '首页文章列表背景样式', '默认为白色样式');
    $form->addInput($indexCardStyle);

    $indexImgStyle = new Typecho_Widget_Helper_Form_Element_Radio('列表图片效果', array(
        '垂直' => '无效果(垂直)',
        '左斜切' => '左斜切',
        '右斜切' => '右斜切'
    ), '0', '首页文章列表图片添加斜切效果', '默认为无效果(垂直)');
    $form->addInput($indexImgStyle);
    $form->addItem(new EchoHtml('</div>'));
//侧边栏设置
    $form->addItem(new EchoHtml('<div class="tabContent">'));
    $qjcbl = new Typecho_Widget_Helper_Form_Element_Checkbox('侧边栏', array('侧边栏' => '默认居左，下方可修改位置'), null, '是否开启侧边栏');
    $form->addInput($qjcbl->multiMode());
    $cblxswz = new Typecho_Widget_Helper_Form_Element_Checkbox('侧边栏显示位置', array(
        '外页' => '首页、分类页、搜索页',
        '内页' => '文章内页、自定义页'
    ), array('外页','内页'), '选择侧边栏显示页面<div style="color:red;display:inline-block">（开启后必选）</div>');
    $form->addInput($cblxswz);
    $qjcblfx = new Typecho_Widget_Helper_Form_Element_Radio('侧边栏左右', array(
        '左' => '侧边栏居左',
        '右' => '侧边栏居右',
        ), '左', '全局侧边栏样式', '默认为居左样式');
    $form->addInput($qjcblfx);
    $txUrl = new Typecho_Widget_Helper_Form_Element_Text('侧边栏个人头像', NULL, NULL, _t('侧边栏个人头像'), _t('在这里填入一个图片 URL 地址，不填则获取邮箱对应的默认头像'));
    $form->addInput($txUrl);
    $zdxxUrl = new Typecho_Widget_Helper_Form_Element_Text('侧边栏个人信息背景图片', NULL, NULL, _t('侧边栏个人信息背景图片地址'), _t('在这里填入一个图片 URL 地址'));
    $form->addInput($zdxxUrl);
    $form->addItem(new EchoHtml('</div>'));
//文章页设置
    $form->addItem(new EchoHtml('<div class="tabContent">'));
    $dianzhan = new Typecho_Widget_Helper_Form_Element_Checkbox('点赞', array('点赞' => '开启后，文章内可以点赞'), null, '文章点赞功能');
    $form->addInput($dianzhan->multiMode());
    $dashang = new Typecho_Widget_Helper_Form_Element_Checkbox('打赏', array('打赏' => '开启后需要配置以下<div style="color:#20bf6b;display:inline-block">绿色</div>选项'), null, '文章打赏');
    $form->addInput($dashang->multiMode());
    $alipay = new Typecho_Widget_Helper_Form_Element_Text('alipay', NULL, NULL, _t('<div style="color:#20bf6b">支付宝收款码</div>'), _t('请填写图片链接，为了保证美观与用户体验，请保证图片宽高比一致，不填则不显示'));
    $form->addInput($alipay);
    $wechatpay = new Typecho_Widget_Helper_Form_Element_Text('wechatpay', NULL, NULL, _t('<div style="color:#20bf6b">微信收款码</div>'), _t('请填写图片链接，为了保证美观与用户体验，请保证图片宽高比一致，不填则不显示'));
    $form->addInput($wechatpay);
    $qqpay = new Typecho_Widget_Helper_Form_Element_Text('qqpay', NULL, NULL, _t('<div style="color:#20bf6b">QQ收款码</div>'), _t('请填写图片链接，为了保证美观与用户体验，请保证图片宽高比一致，不填则不显示'));
    $form->addInput($qqpay);
    $postbanquan = new Typecho_Widget_Helper_Form_Element_Checkbox('文章版权', array('文章版权' => '开启后，文章末尾会出现版权声明，下面的<div style="color:purple;display:inline-block">紫色</div>选项为（可选）配置'), null, '文章版权');
    $form->addInput($postbanquan->multiMode());
    $bqpeizhi = new Typecho_Widget_Helper_Form_Element_Checkbox('版权配置', array('作者' => '显示作者名称', '出处' => '显示出处链接'), null, '<div style="color:purple">版权配置</div>');
    $form->addInput($bqpeizhi);
    $panquanwz = new Typecho_Widget_Helper_Form_Element_Text('自定义声明文字', NULL, NULL, _t('<div style="color:purple">声明文字自定义</div>'), _t('本处可以自定义声明文字，默认声明将失效'));
    $form->addInput($panquanwz);
    $form->addItem(new EchoHtml('</div>'));
//增强功能
    $form->addItem(new EchoHtml('<div class="tabContent">'));
    $Pjaxset = new Typecho_Widget_Helper_Form_Element_Checkbox('Pjax无刷新', array('Pjaxset' => '是否启用Pjax无刷新，<b style="color: red">理论上不用书写任何回调函数，但如果部分插件失效，请注意在自定义设置中选填相应回调函数</b>'), null, 'Pjax无刷新');
    $form->addInput($Pjaxset->multiMode());
    $sitefont = new Typecho_Widget_Helper_Form_Element_Checkbox('思源宋体', array('sitefont' => '开启后，网站部分字体将渲染为思源宋体'), null, '思源宋体');
    $form->addInput($sitefont->multiMode());
    $musicp = new Typecho_Widget_Helper_Form_Element_Checkbox('是否启用歌单解析', array('musicp' => '开启后需要配置以下<div style="color:blue;display:inline-block">蓝色</div>选项,为了用户体验，请开启<div style="color:red;display:inline-block">Pjax功能</div>后，再使用本功能'), null, '<div style="color:blue">歌单解析</div>');
    $form->addInput($musicp->multiMode());
    $musicurl = new Typecho_Widget_Helper_Form_Element_Text('歌单链接', NULL, NULL, _t('<div style="color:blue">音乐链接</div>'), _t('支持网易云音乐、QQ音乐的歌单解析，请直接填写歌单地址，不支持专辑、VIP、无版权歌曲，请注意歌单里不要包含以上类型的音乐。'));
    $form->addInput($musicurl);
    $ServiceWork = new Typecho_Widget_Helper_Form_Element_Checkbox('sw', array('ServiceWork' => '是否开启ServiceWork缓存策略，此项功能可有效增强页面加载速度，<span style="color:red">Service Worker 只能运行在 HTTPS 域下，非 Https 请勿启用。如果你知道这是什么，请把主题包中的 serviceWorker.js 文件复制一份到<b>站点根目录</b>,再开启</span>'), null, 'ServiceWork');
    $form->addInput($ServiceWork->multiMode());
    $smoothscroll = new Typecho_Widget_Helper_Form_Element_Checkbox('平滑滚动', array('smoothscroll' => '是否启用平滑滚动效果（Edge浏览器中可能会出现卡顿）'), null, '平滑滚动');
    $form->addInput($smoothscroll->multiMode());
    $zhiding = new Typecho_Widget_Helper_Form_Element_Text('文章置顶', NULL, NULL, _t('精选文章置顶轮播'), _t('填入需要置顶的文章cid，请以半角逗号分割，加密文章不会显示'));
    $form->addInput($zhiding);
    $indexajax = new Typecho_Widget_Helper_Form_Element_Checkbox('首页Ajax加载文章', array('indexajax' => '是否启用首页文章列表Ajax加载'), null, 'Ajax文章列表加载');
    $form->addInput($indexajax->multiMode());
    $mathjaxset = new Typecho_Widget_Helper_Form_Element_Checkbox('数学公式渲染', array('mathjaxset' => '开启后文章内可以支持LaTeX公式'), null, 'MathJax公式渲染');
    $form->addInput($mathjaxset->multiMode());
    $nopa = new Typecho_Widget_Helper_Form_Element_Checkbox('禁用控制台', array('nopa' => '开启后浏览器页面调试工具将失效，请酌情选择开启或者关闭'), null, '防扒站代码');
    $form->addInput($nopa->multiMode());
    $insetpage = new Typecho_Widget_Helper_Form_Element_Checkbox('页面预加载', array('insetpage' => '启用本设置后，一定程度上可以提高页面打开速度，但可能会产生不必要的流量，有流量限制的主机用户不建议打开,请酌情选择开启或者关闭'), null, '页面预加载<div style="color:red;display: inline-block;"> (PJAX启用后此项无效)</div>');
    $form->addInput($insetpage->multiMode());
    $compressHtml = new Typecho_Widget_Helper_Form_Element_Checkbox('Html压缩输出', array('compressHtml' => '默认关闭，启用则会对HTML代码进行压缩，可能与部分插件存在兼容问题，请酌情选择开启或者关闭'), null, 'HTML压缩');
    $form->addInput($compressHtml->multiMode());
    $form->addItem(new EchoHtml('</div>'));

//特效设置
    $form->addItem(new EchoHtml('<div class="tabContent">'));
    $aidao = new Typecho_Widget_Helper_Form_Element_Checkbox('哀悼模式', array('aidao' => '开启后，网站将变为灰色'), null, '哀悼模式');
    $form->addInput($aidao->multiMode());
    $bigpicw = new Typecho_Widget_Helper_Form_Element_Checkbox('头图白边', array('bigpicw' => '开启后顶部大图将会有一个白边过渡效果,此效果将应用于所有含顶部图的页面'), null, '<div style="color:blue;">顶部大图渐变白边</div>');
    $form->addInput($bigpicw->multiMode());
    $bigwave = new Typecho_Widget_Helper_Form_Element_Checkbox('头图大波浪', array('$bigwave' => '开启后顶部大图将会有一个大波浪过渡效果,此效果将应用于所有含顶部图的页面'), null, '<div style="color:blue;">顶部大图大波浪</div>');
    $form->addInput($bigwave->multiMode());
    $form->addItem(new EchoHtml('</div>'));
//自定义设置
    $form->addItem(new EchoHtml('<div class="tabContent">'));
    $yourcdn = new Typecho_Widget_Helper_Form_Element_Text('yourcdn', NULL, NULL, _t('自定义静态加速CDN'), _t('你需要把/Static/目录上传到你的cdn服务器上，该框填入相应的路径地址，主题就会引用你搭建的cdn上面的资源，而不再引用当前服务器上的资源'));
    $form->addInput($yourcdn);
    $thumbimg = new Typecho_Widget_Helper_Form_Element_Textarea('随机图', null, "https://tvax1.sinaimg.cn/large/0084aYsLly1ge7bcjv1u1j31hc0u00y8.jpg\nhttps://tvax3.sinaimg.cn/large/0084aYsLly1ge7bcxq2vuj30qo0f0q4v.jpg\nhttps://tvax1.sinaimg.cn/large/0084aYsLly1ge7bep7icyj30zk0jzdlu.jpg\nhttps://tva4.sinaimg.cn/large/0084aYsLly1ge7bf74jd0j31hc11pdui.jpg\nhttps://tva2.sinaimg.cn/large/0084aYsLly1ge7bfredg6j31hc0u0tgb.jpg\nhttps://tva3.sinaimg.cn/large/0084aYsLly1ge7bgcg3vfj31hc0u0dp4.jpg\nhttps://tva4.sinaimg.cn/large/0084aYsLly1ge7bivvvswj31hc0u078j.jpg\nhttps://tvax4.sinaimg.cn/large/0084aYsLly1ge7bli2jzdj31z418gjwd.jpg", _t('自定义随机缩略图'), _t('请输入完整图片链接，一行一个'));
    $form->addInput($thumbimg);
    $CustomContenth = new Typecho_Widget_Helper_Form_Element_Textarea('头部自定义', NULL, NULL, _t('头部自定义内容'), _t('位于头部，head内，适合放置一些链接引用或自定义内容'));
    $form->addInput($CustomContenth);
    $stylemyself = new Typecho_Widget_Helper_Form_Element_Textarea('Css自定义', NULL, NULL, _t('自定义Css样式'), _t('已包含&lt;style&gt;标签，请直接书写样式'));
    $form->addInput($stylemyself);
    $CustomContent = new Typecho_Widget_Helper_Form_Element_Textarea('底部自定义', NULL, NULL, _t('底部自定义内容'), _t('位于底部，footer之后body之前，适合放置一些js或自定义内容，如网站统计代码等，（注意：如果您开启了Pjax，暂时只支持百度统计、Google统计，其余统计代码可能会不准确；没开请忽略）'));
    $form->addInput($CustomContent);
    $pjaxContent = new Typecho_Widget_Helper_Form_Element_Textarea('pjax回调', NULL, NULL, _t('Pjax回调函数'), _t('在这里可以书写回调函数内容。如果您不知道这项如何使用请忽略'));
    $form->addInput($pjaxContent);
    $DNSset = new Typecho_Widget_Helper_Form_Element_Textarea('Dns预解析', NULL, NULL, _t('自定义DNS预解析'), _t('请填写完整标签内容，只输入网址无效'));
    $form->addInput($DNSset);
    $form->addItem(new EchoHtml('</div>'));
    $form->addItem(new EchoHtml('</div>'));
}

/**
 * 文章与独立页自定义字段
 */
function themeFields(Typecho_Widget_Helper_Layout $layout)
{
    $excerpt = new Typecho_Widget_Helper_Form_Element_Textarea('excerpt', null, null, '文章摘要', '输入自定义摘要。留空自动从文章截取。');
    $layout->addItem($excerpt);
    $imgst = new Typecho_Widget_Helper_Form_Element_Text('imgst', NULL, NULL, _t('文章缩略图'), _t('在这里填入一个图片URL地址, 以在文章列表加上一张图片'));
    $layout->addItem($imgst);
    $catalog = new Typecho_Widget_Helper_Form_Element_Radio(
        'catalog',
        array(
            true => _t('启用'),
            false => _t('关闭')
        ),
        false,
        _t('文章目录'),
        _t('默认关闭，启用则会在文章内显示“文章目录”（自动匹配H1~H6标签）')
    );
    short_code();
    $layout->addItem($catalog);
}

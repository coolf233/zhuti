<?php

/**
 * Author: Veen Zhao
 * CreateTime: 2020/9/7 22:19
 * 主题核心类
 */
class App
{
    //后台设置项备份
    public static function BackSet()
    {
        $name = $GLOBALS['config']['theme'];
        $db = Typecho_Db::get();
        $sjdq = $db->fetchRow($db->select()->from('table.options')->where('name = ?', 'theme:' . $name));
        $ysj = $sjdq['value'];
        if (isset($_POST['type'])) {
            if ($_POST["type"] == "备份") {
                if ($db->fetchRow($db->select()->from('table.options')->where('name = ?', 'theme:' . $name . 'bf'))) {
                    $update = $db->update('table.options')->rows(array('value' => $ysj))->where('name = ?', 'theme:' . $name . 'bf');
                    $updateRows = $db->query($update);
                    exit("<script type='text/javascript'>alert('主题备份已更新！');history.go(-1);</script>");
                } else {
                    if ($ysj) {
                        $insert = $db->insert('table.options')
                            ->rows(array('name' => 'theme:' . $name . 'bf', 'user' => '0', 'value' => $ysj));
                        $insertId = $db->query($insert);
                        exit("<script type='text/javascript'>alert('主题备份完成！');history.go(-1);</script>");
                    }
                }
            }
            if ($_POST["type"] == "还原") {
                if ($db->fetchRow($db->select()->from('table.options')->where('name = ?', 'theme:' . $name . 'bf'))) {
                    $sjdub = $db->fetchRow($db->select()->from('table.options')->where('name = ?', 'theme:' . $name . 'bf'));
                    $bsj = $sjdub['value'];
                    $update = $db->update('table.options')->rows(array('value' => $bsj))->where('name = ?', 'theme:' . $name);
                    $updateRows = $db->query($update);
                    exit("<script type='text/javascript'>alert('检测到模板备份数据，恢复完成！');history.go(-1);</script>");
                } else {
                    exit("<script type='text/javascript'>alert('无备份数据，恢复失败！');history.go(-1);</script>");
                }
            }
            if ($_POST["type"] == "删除") {
                if ($db->fetchRow($db->select()->from('table.options')->where('name = ?', 'theme:' . $name . 'bf'))) {
                    $delete = $db->delete('table.options')->where('name = ?', 'theme:' . $name . 'bf');
                    $deletedRows = $db->query($delete);
                    exit("<script type='text/javascript'>alert('删除成功！');history.go(-1);</script>");
                } else {
                    exit("<script type='text/javascript'>alert('无数据，删除失败！');history.go(-1);</script>");
                }
            }
        }
    }

    //查询是否备份
    public static function CheckSetBack()
    {
        $db = Typecho_Db::get();
        $res = $db->fetchRow($db->select()->from('table.options')->where('name = ?', 'theme:' . $GLOBALS['config']['theme'] . 'bf'));
        if ($res) {
            return '<span style="color: #1462ff">模板已备份</span>';
        } else {
            return '<span style="color: red">未备份任何数据</span>';
        }
    }

    // 输出域名地址
    public static function HomeUrl($path = '')
    {
        return Helper::options()->siteUrl . $path;
    }

    // 输出主题地址
    public static function ThemeUrl($path = '')
    {
        return Helper::options()->themeUrl . $path;
    }

    //获取文章阅读次数
    public static function getPostViews($archive)
    {
        $db = Typecho_Db::get();
        $cid = $archive->cid;
        if (!array_key_exists('views', $db->fetchRow($db->select()->from('table.contents')))) {
            $db->query('ALTER TABLE `' . $db->getPrefix() . 'contents` ADD `views` INT(10) DEFAULT 0;');
        }
        $exist = $db->fetchRow($db->select('views')->from('table.contents')->where('cid = ?', $cid))['views'];
        if ($archive->is('single')) {
            $cookie = Typecho_Cookie::get('contents_views');
            $cookie = $cookie ? explode(',', $cookie) : array();
            if (!in_array($cid, $cookie)) {
                $db->query($db->update('table.contents')
                    ->rows(array('views' => (int)$exist + 1))
                    ->where('cid = ?', $cid));
                $exist = (int)$exist + 1;
                array_push($cookie, $cid);
                $cookie = implode(',', $cookie);
                Typecho_Cookie::set('contents_views', $cookie);
            }
        }
        return $exist == 0 ? '0' : $exist;
    }

    //输出标题
    public static function echoTitle(Widget_Archive $archive)
    {
            $archive->archiveTitle(array(
                'category' => _t('分类 "%s" 下的文章'),
                'search' => _t('包含关键字 "%s" 的文章'),
                'tag' => _t('包含标签 "%s" 的文章'),
                'author' => _t('"%s" 发布的文章')
            ), '', '');
        return false;
    }

    ///邮箱优先调用QQ头像
    public static function avatarQQ($ctx)
    {
        if ($ctx) {
            if (strpos($ctx, "@qq.com") !== false) {
                $email = str_replace('@qq.com', '', $ctx);
                if (is_numeric($email)) {
                    return "//q1.qlogo.cn/g?b=qq&nk=" . $email . "&";
                } else {
                    $str = $email . '@qq.com';
                    $email = md5($str);
                    return "//sdn.geekzu.org/avatar/" . $email . "?";
                }
            } else {
                $email = md5($ctx);
                return "//sdn.geekzu.org/avatar/" . $email . "?";
            }
        } else {
            return "//sdn.geekzu.org/avatar/null?";
        }
    }

    //评论时间计算
    public static function timeSince($older_date,$comment_date = false) {
        $chunks = array(
            array(86400 , '天'),
            array(3600 , '小时'),
            array(60 , '分'),
            array(1 , '秒'),
        );
        $newer_date = time();
        $since = abs($newer_date - $older_date);
        if($since < 2592000){
            for ($i = 0, $j = count($chunks); $i < $j; $i++){
                $seconds = $chunks[$i][0];
                $name = $chunks[$i][1];
                if (($count = floor($since / $seconds)) != 0) break;
            }
            $output = $count.$name.' 前';
        }else{
            $output = !$comment_date ? (date('Y-m-j G:i', $older_date)) : (date('Y-m-j', $older_date));
        }
        return $output;
    }

    //文章字数输出，去除字符，只统计中文
    public static function  getWordCount($cid)
    {
        $db = Typecho_Db::get();
        $rs = $db->fetchRow($db->select('table.contents.text')->from('table.contents')->where('table.contents.cid=?', $cid)->order('table.contents.cid', Typecho_Db::SORT_ASC)->limit(1));
        $text = preg_replace("/[^\x{4e00}-\x{9fa5}]/u", "", $rs['text']);
        return mb_strlen($text, 'UTF-8');
    }
    //移动端判定
    public static function isMobile()
    {
        if (isset($_SERVER['HTTP_X_WAP_PROFILE'])) {
            return TRUE;
        }

        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            $key = array('mobile', 'nokia', 'sony', 'ericsson', 'mot', 'samsung', 'htc', 'sgh', 'lg', 'sharp', 'sie-', 'philips', 'panasonic', 'alcatel', 'lenovo', 'iphone', 'ipad', 'blackberry', 'meizu', 'android', 'netfront', 'symbian', 'ucweb', 'windowsce', 'palm', 'operamini', 'operamobi', 'openwave', 'nexusone', 'cldc', 'midp', 'wap');
            if (preg_match("/(" . implode('|', $key) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
                return TRUE;
            }
        }
        if (isset($_SERVER['HTTP_ACCEPT'])) {
            if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== FALSE) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === FALSE || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
                return TRUE;
            }
        }
        return FALSE;
    }
    //获取管理员邮箱
    public static function adminAvatar()
    {
        $db = Typecho_Db::get();
        return $db->fetchRow($db->select('mail')->from('table.users')->where('group = ?', 'administrator'))['mail'];
    }
}

//随机文章
class Widget_Post_rand extends Widget_Abstract_Contents
{
    public function __construct($request, $response, $params = NULL)
    {
        parent::__construct($request, $response, $params);
        $this->parameter->setDefault(array('pageSize' => $this->options->commentsListSize, 'parentId' => 0, 'ignoreAuthor' => false));
    }
    public function execute()
    {
        $select  = $this->select()->from('table.contents')
            ->where("table.contents.password IS NULL OR table.contents.password = ''")
            ->where('table.contents.status = ?', 'publish')
            ->where('table.contents.created <= ?', time())
            ->where('table.contents.type = ?', 'post')
            ->limit($this->parameter->pageSize)
            ->order('RAND()');
        $this->db->fetchAll($select, array($this, 'push'));
    }
}
//置顶类
class Widget_Post_CardTOP extends Widget_Abstract_Contents
{
    public function __construct($request, $response, $params = NULL)
    {
        parent::__construct($request, $response, $params);
        $this->parameter->setDefault(array('pageSize' => $this->options->commentsListSize, 'parentId' => 0, 'ignoreAuthor' => false));
    }
    public function execute()
    {
        $select  = $this->select()->from('table.contents')
            ->where("table.contents.password IS NULL OR table.contents.password = ''")
            ->where('table.contents.type = ?', 'post')
            ->limit($this->parameter->pageSize)
            ->order('table.contents.modified', Typecho_Db::SORT_DESC);

        if ($this->parameter->CardTOP) {
            $CardTOPSel = explode(",", $this->parameter->CardTOP);
            $select->where('table.contents.cid in ?', $CardTOPSel);
        }
        $this->db->fetchAll($select, array($this, 'push'));
    }
}

//HTML压缩
function compressHtml($html_source)
{
    $chunks = preg_split('/(<!--<nocompress>-->.*?<!--<\/nocompress>-->|<nocompress>.*?<\/nocompress>|<pre.*?\/pre>|<textarea.*?\/textarea>|<script.*?\/script>)/msi', $html_source, -1, PREG_SPLIT_DELIM_CAPTURE);
    $compress = '';
    foreach ($chunks as $c) {
        if (strtolower(substr($c, 0, 19)) == '<!--<nocompress>-->') {
            $c = substr($c, 19, strlen($c) - 19 - 20);
            $compress .= $c;
            continue;
        } else if (strtolower(substr($c, 0, 12)) == '<nocompress>') {
            $c = substr($c, 12, strlen($c) - 12 - 13);
            $compress .= $c;
            continue;
        } else if (strtolower(substr($c, 0, 4)) == '<pre' || strtolower(substr($c, 0, 9)) == '<textarea') {
            $compress .= $c;
            continue;
        } else if (strtolower(substr($c, 0, 7)) == '<script' && strpos($c, '//') != false && (strpos($c, "\r") !== false || strpos($c, "\n") !== false)) {
            $tmps = preg_split('/(\r|\n)/ms', $c, -1, PREG_SPLIT_NO_EMPTY);
            $c = '';
            foreach ($tmps as $tmp) {
                if (strpos($tmp, '//') !== false) {
                    if (substr(trim($tmp), 0, 2) == '//') {
                        continue;
                    }
                    $chars = preg_split('//', $tmp, -1, PREG_SPLIT_NO_EMPTY);
                    $is_quot = $is_apos = false;
                    foreach ($chars as $key => $char) {
                        if ($char == '"' && $chars[$key - 1] != '\\' && !$is_apos) {
                            $is_quot = !$is_quot;
                        } else if ($char == '\'' && $chars[$key - 1] != '\\' && !$is_quot) {
                            $is_apos = !$is_apos;
                        } else if ($char == '/' && $chars[$key + 1] == '/' && !$is_quot && !$is_apos) {
                            $tmp = substr($tmp, 0, $key);
                            break;
                        }
                    }
                }
                $c .= $tmp;
            }
        }
        $c = preg_replace('/[\\n\\r\\t]+/', ' ', $c);
        $c = preg_replace('/\\s{2,}/', ' ', $c);
        $c = preg_replace('/>\\s</', '> <', $c);
        $c = preg_replace('/\\/\\*.*?\\*\\//i', '', $c);
        $c = preg_replace('/<!--[^!]*-->/', '', $c);
        $compress .= $c;
    }
    return $compress;
}
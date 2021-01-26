<?php
/**
 * Author: Veen Zhao
 * CreateTime: 2020/9/9 11:43
 * 头部资源
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
Ctx::pluginJudge(); ?>
<!DOCTYPE html>
<html lang="zh-cn">

<head>
    <meta charset="<?php $this->options->charset(); ?>">
    <!--IE 8浏览器的页面渲染方式-->
    <meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1">
    <!--默认使用极速内核：针对国内浏览器产商-->
    <meta name="renderer" content="webkit">
    <meta name="HandheldFriendly" content="true">
    <!--视口定义-->
    <meta name="viewport"
          content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no">
    <!--标题输出-->
    <title><?= Ctx::EchoTitle($this); ?></title>
    <!--DNS预解析-->
    <?= Ctx::DnsPrefetch() ?>
    <!--Favicon-->
    <?= Ctx::EchoFavicon() ?>
    <!--屏蔽头部-->
    <?php $this->header('commentReply=&description=&'); ?>
    <?= Ctx::FontSet() ?>
    <link rel="stylesheet" href="<?= StaticPath . 'css/lightgallery.css'; ?>">
    <link rel="stylesheet" href="<?= StaticPath . 'css/bootstrap-bc216d3b93.css'; ?>">
    <link rel="stylesheet" href="<?= StaticPath . 'css/header-18e0f48223.css'; ?>">
    <link rel="stylesheet" href="<?= StaticPath . 'css/app.css'; ?>">
    <script src="<?= StaticPath . 'js/header-85eea22e52.js'; ?>"></script>
    <script src="//at.alicdn.com/t/font_2001126_6707q7xcsq8.js"></script>
    <?php $this->options->头部自定义(); ?>
</head>

<script>
    Config = {
        homeUrl: '<?=Helper::options()->index;?>',
        staticUrl: '<?=StaticPath?>',
        themeUrl: '<?=App::ThemeUrl()?>',
        musicId: '<?=Ctx::ParseMusic($this->options->歌单链接)['id']?>',
        musicMedia: '<?=Ctx::ParseMusic($this->options->歌单链接)['media']?>',
        topImage: '<?= Helper::options()->顶部大图 ? 'yes' : 'no' ?>',
        NavBarHeadroom: '<?= Helper::options()->headroom ? 'yes' : 'no' ?>',
        noConsole: '<?= Helper::options()->禁用控制台 ? 'yes' : 'no' ?>',
        darkSet: '<?= Helper::options()->darkSet ?? '0' ?>',
    }
</script>
<?php $this->need('base/style.php'); ?>
<body class="bg-light">
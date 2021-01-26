<?php
/**
 * Author: Veen Zhao
 * CreateTime: 2020/11/14 16:31
 * 相关样式
 */
?>
<style>
    <?php if ($this->options->Css自定义) : $this->options->Css自定义(); ?><?php endif; ?>
    <?php if (Helper::options()->思源宋体 && !App::isMobile()) : ?>
    body, input, select, textarea, .friends-ctx {
        font-family:"Roman-55", -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Serif SC", "PingFang SC", "Microsoft Yahei UI", "Microsoft Yahei", sans-serif
    }

    #post h1, #post h2, #post h3, #post h4, #post h5, #post h6 {
        font-weight: 900;
        font-family: -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Serif SC", "PingFang SC", "Microsoft Yahei UI", "Microsoft Yahei", sans-serif
    }

    #heading, #postTop h4, .friends-name {
        font-weight: 700;
    }

    #postTop h4 {
        font-size: 2rem;
    }

    <?php endif; ?>
    <?php if (Helper::options()->哀悼模式) : ?>
    html {
        filter: grayscale(100%);
        -webkit-filter: grayscale(100%);
        -moz-filter: grayscale(100%);
        -ms-filter: grayscale(100%);
        -o-filter: grayscale(100%);
    }
    <?php endif; ?>

    <?php if (Helper::options()->头图白边) : ?>
    #header::after {
        content: '';
        width: 100%;
        height: 10%;
        position: absolute;
        bottom: 0;
        left: 0;
        background: linear-gradient(to top, var(--bs-light), transparent);
        filter: progid:DXImageTransform.Microsoft.gradient(startColorstr=#00ffffff, endColorstr=#ffffff, GradientType=0);
    }
    <?php endif; ?>
</style>
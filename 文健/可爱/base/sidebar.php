<?php
/**
 * Author: Veen Zhao
 * CreateTime: 2020/11/10 21:04
 * 侧边栏内容
 */
?>

<div id="sidebar" class="col-lg-3 d-none d-lg-block">
    <div id="sidebar-box" class="h-100">
        <div class="sidebar-box position-relative">
            <div class="sidebar-banner"
                 style="background-image:url(<?php $this->options->侧边栏个人信息背景图片() ?>)">
            </div>
            <div class="text-center">
                <img class="sidebar-avatar position-absolute" src="<?= $this->options->侧边栏个人头像 ?: (App::avatarQQ(App::adminAvatar()).'s=100') ?>">
            </div>
            <div class="sidebar-name"><?php $this->author() ?></div>
            <div class="sidebar-info">
                <div class="sidebar-post-number"><span>文章</span><?= Ctx::getPostNum() ?></div>
                <div class="sidebar-categories-number"><span>评论</span><?= Ctx::getCommentsNum() ?></div>
                <div class="sidebar-tags-number"><span>标签</span><?= Ctx::getTagNum() ?></div>
            </div>
        </div>
        <div class="sidebar-box">
            <div class="p-3">最新回复</div>
            <?php $this->widget('Widget_Comments_Recent', 'ignoreAuthor=false&pageSize=5')->to($com); ?>
            <?php while ($com->next()) : ?>
                <a class="d-flex pb-3 px-3 a-none" href="<?php $com->permalink() ?>">
                    <img class="sidebar-comment-avatar mr-1" src="<?= App::avatarQQ($com->mail); ?>s=100"/>
                    <div class="w-100">
                        <div class="d-flex sidebar-comment-info">
                            <div class="sidebar-comment-name"
                                 title="<?php $com->author(false) ?>"><?php $com->author(false) ?></div>
                            <div class="sidebar-comment-date"><?= App::timeSince($com->created); ?></div>
                        </div>
                        <div class="sidebar-comment-text"><?php echo Ctx::ParseEmoji($com->text) ?></div>
                    </div>
                </a>
            <?php endwhile; ?>
        </div>
        <div class="sidebar-box sticky-top">
            <div class="p-3">随便看看</div>
            <?php $this->widget('Widget_Post_rand@rand', 'pageSize=3')->to($rand); ?>
            <?php while ($rand->next()) : ?>
                <div class="pb-3 px-3">
                    <div class="sidebar-rand-item">
                        <a class="sidebar-rand-img" href="<?php $rand->permalink() ?>" target="_blank"
                           style="background-image: url(<?= Ctx::ImageEcho($rand) ?>);">
                        </a>
                        <div class="sidebar-rand-info p-2">
                            <div class="sidebar-rand-date"><i
                                        class="far fa-clock"></i> <?php $rand->date(' Y 年 m 月 d 日'); ?></div>
                            <a href="<?php $rand->permalink() ?>"
                               class="sidebar-rand-title a-none"><?php $rand->title(); ?></a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</div>


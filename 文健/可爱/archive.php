<?php
/**
 * Author: Veen Zhao
 * CreateTime: 2020/11/9 23:43
 * 分类列表页
 */
$this->need('base/head.php');
$this->need('base/navbar.php');
?>
<?php if (!$this->options->顶部大图) : ?>
    <div class="archives-title h2 text-center mb-4 pt-2">
<?php App::echoTitle($this)?>
    </div>
<?php endif; ?>

    <div class="row justify-content-center <?= Ctx::SidebarArray()[0] ?>">
        <?= Ctx::Sidebar($this) ?>
        <div id="content" class="<?=Ctx::ColumnSize()[1]?>">
            <?= Ctx::Article($this) ?>
        </div>
        <?php if (Helper::options()->首页Ajax加载文章) : ?>
            <?php $this->pageLink('<button id="NextButton" onclick="Cuteen.ajaxNext()" class="btn btn-primary col-3 mx-auto rounded-pill">点击加载更多</button>', 'next'); ?>
        <?php else: ?>
            <?php Ctx::pagination($this) ?>
        <?php endif; ?>
    </div>
<?php $this->need('base/footer.php'); ?>
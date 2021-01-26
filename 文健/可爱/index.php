<?php

/**
 *
 * 不止一种色彩
 *
 * @package Cuteen
 * @author Veen Zhao
 * @version 4.0
 * @link https://blog.zwying.com/
 */
$this->need('base/head.php');
$this->need('base/navbar.php');
?>
    <div class="row justify-content-center <?= Ctx::SidebarArray()[0] ?>">
        <?= Ctx::Sidebar($this) ?>
        <div id="content" class="<?= Ctx::ColumnSize()[1] ?>">
            <?= Ctx::PostTop($this) ?>
            <?= Ctx::Article($this) ?>
        </div>
        <?php if (Helper::options()->首页Ajax加载文章) : ?>
            <?php $this->pageLink('<button id="NextButton" onclick="Cuteen.ajaxNext()" class="btn btn-primary col-3 mx-auto rounded-pill">点击加载更多</button>', 'next'); ?>
        <?php else: ?>
            <?php Ctx::pagination($this) ?>
        <?php endif; ?>
    </div>

<?php $this->need('base/footer.php'); ?>
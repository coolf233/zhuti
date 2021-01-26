<?php
/**
 * Author: Veen Zhao
 * CreateTime: 2020/11/2 23:33
 * 普通页面
 */

$this->need('base/head.php');
$this->need('base/navbar.php');
?>

<div class="row justify-content-center <?= Ctx::SidebarArray()[0] ?>">
    <?= Ctx::Sidebar($this) ?>
    <div id="content" class="col-lg-9">
        <section class="post-ctx">
            <?=Ctx::HeroTitleNoImg($this)?>
            <article id="post">
                <?= Ctx::Post($this,$this->user->hasLogin()) ?>
            </article>
            <footer id="post-info">

            </footer>
        </section>

        <section id="comments" class="post-comment mt-4">
            <header class="post-nav">
                <span class="item"><svg class="icon mr-1" aria-hidden="true"><use
                                xlink:href="#talk"></use></svg>页面评论</span>
            </header>
            <hr id="comment-hr">
            <?php if ($this->allow('comment')) : ?>
                <?php $this->need('base/comment.php'); ?>
                <?php else : ?>
                    <h4 class="font-weight-bold">评论已关闭</h4>
            <?php endif; ?>
        </section>
    </div>
</div>

<?php $this->need('base/footer.php'); ?>

<?php
/**
 * 友情链接
 * @package custom
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
                <?= Ctx::Post($this, $this->user->hasLogin()) ?>
            </article>
            <footer id="post-info">

            </footer>
        </section>
    </div>
</div>
<?php $this->need('base/footer.php'); ?>

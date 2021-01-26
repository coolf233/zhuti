<?php
/**
 * Author: Veen Zhao
 * CreateTime: 2020/9/10 23:04
 * 导航栏
 */
$this->widget('Widget_Metas_Category_List')->to($categorys);
$this->widget('Widget_Contents_Page_List')->to($pages);
?>
<!--PC端Navbar-->
<nav id="navPC"
     class="navbar navbar-expand d-none d-md-flex navbar-light px-5 <?= Ctx::NavBarInitial() ?>">
    <a class="navbar-brand" href="<?= $this->options->siteUrl ?>"><?= Ctx::EchoLogo() ?></a>
    <ul class="navbar-nav mr-auto">
        <?= Ctx::OrderSet($pages, $categorys) ?>
    </ul>
    <ul class="navbar-nav">
        <li class="d-inline-flex align-items-center color-icon" data-toggle="modal" data-target="#searchModal">
            <svg class="icon icon-20" aria-hidden="true">
                <use xlink:href="#search"></use>
            </svg>
        </li>
        <?php if ($this->options->是否启用歌单解析) : ?>
            <li id="musicApp" data-toggle="popover" class="d-inline-flex align-items-center position-relative">
                <div id="musicSvg">
                    <svg class="icon icon-20" aria-hidden="true">
                        <use xlink:href="#music"></use>
                    </svg>
                </div>
                <div id="musicPop"></div>
            </li>
        <?php endif; ?>
        <li id="themePlane" class="d-inline-flex align-items-center color-icon position-relative">
            <svg class="icon icon-20" aria-hidden="true">
                <use xlink:href="#huaban"></use>
            </svg>
            <div id="themeColor">
                <input onclick="Cuteen.themeColor(this.value)" type="radio" class="btn-check" name="options-outlined" value="primary" id="blue-outlined" autocomplete="off" checked>
                <label  class="btn btn-outline-blue" for="blue-outlined">原</label>
                <input onclick="Cuteen.themeColor(this.value)" type="radio" class="btn-check" name="options-outlined" value="success" id="success-outlined" autocomplete="off">
                <label  class="btn btn-outline-success" for="success-outlined">绿</label>
                <input onclick="Cuteen.themeColor(this.value)" type="radio" class="btn-check" name="options-outlined" value="danger" id="danger-outlined" autocomplete="off">
                <label  class="btn btn-outline-danger" for="danger-outlined">红</label>
                <input onclick="Cuteen.themeColor(this.value)" type="radio" class="btn-check" name="options-outlined" value="warning" id="warning-outlined" autocomplete="off">
                <label  class="btn btn-outline-warning" for="warning-outlined">黄</label>
                <input onclick="Cuteen.themeColor(this.value)" type="radio" class="btn-check" name="options-outlined" value="info" id="info-outlined" autocomplete="off">
                <label  class="btn btn-outline-info" for="info-outlined">蓝</label>
            </div>
        </li>
        <li onclick="Cuteen.darkMode();" class="d-inline-flex align-items-center dark-icon" data-toggle="tooltip" data-placement="bottom"
            title="昼夜切换">
            <svg class="icon icon-20" aria-hidden="true" id="darkMode">
                <use xlink:href="#moon"></use>
            </svg>
        </li>
    </ul>
</nav>
<!--移动端Navbar-->
<nav id="navMobile" class="navbar navbar-expand d-flex justify-content-between d-md-none navbar-light bg-white z-index2 <?= Ctx::NavBarInitial() ?>">
    <div class="d-inline-flex align-items-center" id="mobileMenu">
        <svg class="icon icon-20" aria-hidden="true">
            <use xlink:href="#menu"></use>
        </svg>
    </div>
    <a class="navbar-brand mr-0" href="<?= $this->options->siteUrl ?>" ><?= Ctx::EchoLogo() ?></a>
    <div data-toggle="modal" data-target="#searchModal">
    <svg class="icon icon-20" aria-hidden="true">
        <use xlink:href="#search"></use>
    </svg>
    </div>
</nav>
<!--移动端侧边栏内容-->
<aside id="mobileAside" class="d-block d-sm-none bg-white" mobile-open="false">
    <div class="mobile-nav">
        <div class="aside-info text-center px-3">
            <a class="aside-avatar" href="<?= $this->options->siteUrl ?>">
                <img class="moside-img"src="<?= $this->options->侧边栏个人头像 ?: (App::avatarQQ(App::adminAvatar()).'s=100') ?>" alt="">
            </a>
            <p class="font-weight-bold mt-3"><?php $this->options->title() ?></p>
            <p class="small"><?php $this->options->description() ?></p>
        </div>
        <?= Ctx::MobileOrderSet($pages, $categorys) ?>
    </div>
</aside>
<!--首页大图-->
<?php if (Helper::options()->顶部大图) {
    $this->need('base/hero.php');
} ?>
<!--页面主要内容容器-->
<main id="main" class="bg-light mb-4 container clearfix" data-bgimg="<?= Helper::options()->顶部大图?'yes':'no'?>">


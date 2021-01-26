<?php
/**
 * Author: Veen Zhao
 * CreateTime: 2020/9/24 16:51
 * 顶部设置
 */
?>

<header class="lazy" id="header" data-bg="<?= Ctx::HeroImage($this) ?>">
    <div class="hero-box d-flex justify-content-center align-items-center flex-column" <?= Ctx::ImgHeight() ?>>
        <p id="heading" class="mt-5 h1 text-white">
            <?php if ($this->is('archive')){
                App::echoTitle($this);
            }else{
                echo Ctx::HeroTitle($this)[0];
            } ?></p>
        <p id="subheading" class="mt-3 h5 text-white d-flex">
            <?= Ctx::HeroTitle($this)[1] ?>
        </p>
    </div>
    <?= Ctx::waveHeader() ?>
</header>



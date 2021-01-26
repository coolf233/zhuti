<?php

/**
 * Author: Veen Zhao
 * CreateTime: 2020/9/29 19:08
 * 文章内页
 */
$this->need('base/head.php');
$this->need('base/navbar.php');
?>

<div class="row justify-content-center <?= Ctx::SidebarArray()[0] ?>">
    <?= Ctx::Sidebar($this) ?>
    <div id="content" class="col-lg-9">
        <section class="post-ctx">
            <header class="post-nav">
                <span class="item"><svg class="icon mr-1" aria-hidden="true">
                        <use xlink:href="#home"></use>
                    </svg><a class="a-none" href="<?php $this->options->siteUrl(); ?>">首页</a></span>
                <span class="item"><svg class="icon mr-1" aria-hidden="true">
                        <use xlink:href="#grid"></use>
                    </svg><?= $this->category == null ? "未分类" : $this->category(); ?></span>
<span class="item"><svg class="icon mr-1" aria-hidden="true">
                        <use xlink:href="#eye"></use>
                    </svg><?php echo baidu_record() ?></span>
            </header>
            <hr>
            <?= Ctx::HeroTitleNoImg($this) ?>
            <article id="post">
                <?php if ($this->hidden || $this->titleshow) : ?>
                    <form action="<?php echo Typecho_Widget::widget('Widget_Security')->getTokenUrl($this->permalink); ?>" method="post">
                        <p class="h4 font-weight-bold">此篇文章已被加密，请输入密码后查看</p>
                        <input class="input" type="password" name="protectPassword" placeholder="请输入密码" aria-label="请输入密码">
                        <input type="hidden" name="protectCID" value="<?php $this->cid(); ?>" />
                        <input class="btn btn-primary mb-3" type="submit" name="" value="确认提交" />
                    </form>
                <?php else : ?>
                    <?= Ctx::Post($this, $this->user->hasLogin()) ?>
                <?php endif; ?>
            </article>
            <?php if ($this->options->文章版权) : ?>
                <hr>
                <div class="copyright">
                    <?php if (in_array('作者', $this->options->版权配置)) : ?>
                        <div class="d-flex align-items-center">
                            <svg class="icon" aria-hidden="true">
                                <use xlink:href="#touxiang"></use>
                            </svg>
                            <div class="copyright-text">版权属于：<?php $this->author() ?></div>
                        </div>
                    <?php endif; ?>
                    <?php if (in_array('出处', $this->options->版权配置)) : ?>
                        <div class="d-flex align-items-center">
                            <svg class="icon" aria-hidden="true">
                                <use xlink:href="#ziyuan"></use>
                            </svg>
                            <div class="copyright-text">本文链接：<?php $this->permalink() ?></div>
                        </div>
                    <?php endif; ?>
                    <div class="d-flex align-items-center">
                        <svg class="icon" aria-hidden="true">
                            <use xlink:href="#fenxiang1"></use>
                        </svg>
                        <?php if (!$this->options->自定义声明文字) : ?>
                            <div class="copyright-text">作品采用 <a class="text-decoration-none" target="_blank" href="https://creativecommons.org/licenses/by/4.0/deed.zh">
                                    知识共享署名-非商业性使用-相同方式共享 4.0 国际许可协议 </a>进行许可
                            </div>
                        <?php else : ?>
                            <div class="copyright-text"><?php $this->options->自定义声明文字() ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
            <hr>
            <div id="post-info" class="text-center">
                <div class="accordion" id="footer-box">
                    <?php if ($this->options->点赞) : ?>
                        <a id="like" class="btn btn-outline-primary rounded-pill align-items-center d-inline-flex" data-pid="<?php $this->cid(); ?>" onclick="Cuteen.upStar()" role="button">
                            <svg class="icon icon-20" aria-hidden="true">
                                <use xlink:href="#thumbs-up"></use>
                            </svg>
                            <div class="ml-1">赞 <span id="num"><?php Cuteen_Plugin::likeNumber() ?></span> 次</div>
                        </a>
               
                        <script>
                            document.addEventListener('DOMContentLoaded', function(event) {
                                const like = document.getElementById('like');
                                const dataID = like.dataset.pid;
                                const starValue = Cookies.get('upstar');
                                if (dataID === starValue) {
                                    like.classList.remove('btn-outline-primary');
                                    like.classList.add('btn-primary');
                                }
                            });
                        </script>
                    <?php endif; ?>
<?php ArticlePoster_Plugin::button($this->cid); ?>
                    <!--<button id="headingShare"
                            class="btn btn-outline-primary rounded-pill align-items-center d-inline-flex" type="button"
                            data-toggle="collapse" data-target="#collapseShare" aria-expanded="true"
                            aria-controls="collapseShare">
                        <svg class="icon icon-20" aria-hidden="true">
                            <use xlink:href="#fenxiang"></use>
                        </svg>
                        <span class="ml-1">复制链接</span>
                    </button>-->

                    <?php if ($this->options->打赏) : ?>
                        <button id="headingMoney" class="btn btn-outline-primary rounded-pill align-items-center d-inline-flex" type="button" data-toggle="modal" data-target="#collapseMoney" aria-controls="collapseMoney">
                            <svg class="icon icon-20" aria-hidden="true">
                                <use xlink:href="#tsk"></use>
                            </svg>
                            <span class="ml-1">打赏</span>
                        </button>
                        <div class="modal fade" id="collapseMoney" tabindex="-1" aria-labelledby="moneyModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-body">
                                        <ul class="nav nav-pills mb-3 justify-content-center" id="pills-tab" role="tablist">
                                            <?php if ($this->options->alipay) : ?>
                                                <li class="nav-item" role="presentation">
                                                    <a class="nav-link active" id="pills-alipay-tab" data-toggle="pill" href="#pills-alipay" role="tab" aria-controls="pills-alipay" aria-selected="true">支付宝</a>
                                                </li>
                                            <?php endif; ?>
                                            <?php if ($this->options->qqpay) : ?>
                                                <li class="nav-item" role="presentation">
                                                    <a class="nav-link" id="pills-qqpay-tab" data-toggle="pill" href="#pills-qqpay" role="tab" aria-controls="pills-qqpay" aria-selected="false">QQ</a>
                                                </li>
                                            <?php endif; ?>
                                            <?php if ($this->options->wechatpay) : ?>
                                                <li class="nav-item" role="presentation">
                                                    <a class="nav-link" id="pills-wechatpay-tab" data-toggle="pill" href="#pills-wechatpay" role="tab" aria-controls="pills-wechatpay" aria-selected="false">微信</a>
                                                </li>
                                            <?php endif; ?>
                                        </ul>
                                        <div class="tab-content" id="pills-tabContent">
                                            <?php if ($this->options->alipay) : ?>
                                                <div class="tab-pane fade show active" id="pills-alipay" role="tabpanel" aria-labelledby="pills-alipay-tab">
                                                    <img src="<?php $this->options->alipay() ?>">
                                                </div>
                                            <?php endif; ?>
                                            <?php if ($this->options->qqpay) : ?>
                                                <div class="tab-pane fade" id="pills-wechatpay" role="tabpanel" aria-labelledby="pills-wechatpay-tab">
                                                    <img src="<?php $this->options->wechatpay() ?>">
                                                </div>
                                            <?php endif; ?>
                                            <?php if ($this->options->wechatpay) : ?>
                                                <div class="tab-pane fade" id="pills-qqpay" role="tabpanel" aria-labelledby="pills-qqpay-tab">
                                                    <img src="<?php $this->options->qqpay() ?>">
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">关闭</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    &nbsp;
                   
                    <?php endif; ?>
                    <!--<div id="collapseShare" class="collapse m-3" aria-labelledby="headingShare" data-parent="#footer-box">



                    </div>-->
                </div>
            </div>
        </section>
        <section id="comments" class="post-comment mt-4">
            <header class="post-nav">
                <span class="item"><svg class="icon mr-1" aria-hidden="true">
                        <use xlink:href="#talk"></use>
                    </svg>文章评论</span>
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
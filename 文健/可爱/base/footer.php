<?php

/**
 * Author: Veen Zhao
 * CreateTime: 2020/9/9 23:06
 * 脚部
 */
?>
<?php if (($this->is('post') || $this->is('page')) && ($this->fields->catalog == true)) : ?>
    <div id="TOC-btn">
        <div class="position-relative">
            <div class="p-2">
                <svg class="icon icon-20" aria-hidden="true">
                    <use xlink:href="#list"></use>
                </svg>
                目录
            </div>
            <div class="TOC-ctx">
                <div id="TOC-text" class="TOC-text"></div>
            </div>
        </div>
    </div>
<?php endif; ?>
</main>
<footer id="footer" class="bg-white p-4 text-center">
    <p class="small">
        &copy; <?php echo date('Y'); ?>
        <a href="<?php $this->options->siteUrl(); ?>"><?php $this->options->title(); ?></a>
    </p>
    <?php if ($this->options->备案号) : ?>
        <p class="small">
            <svg class='icon icon-20' aria-hidden='true'>
                <use xlink:href='#ICP'></use>
            </svg>
            <a href="http://beian.miit.gov.cn" target="_blank"> <?php $this->options->备案号() ?></a>
        </p>
    <?php endif; ?>
    <?php if ($this->options->公安备案号) : ?>
        <p class="small">
            <svg class='icon icon-20' aria-hidden='true'>
                <use xlink:href='#guohui'></use>
            </svg>
            <a href="http://www.beian.gov.cn/portal/registerSystemInfo?recordcode=<?php echo preg_replace('/[^\d]*/', '', $this->options->公安备案号) ?>"><?php $this->options->公安备案号() ?></a>
        </p>

    <?php endif; ?>
    <!-- 此处版权可以修改或删除，建议保留，谢谢 -->
    <p class="small"> Powered by <a href="http://typecho.org" target="_blank">Typecho</a>  | Theme is <a href="https://blog.zwying.com" target="_blank">Cuteen</a></p>
<a href="https://travellings.now.sh/" target="blank" title="开往"><img src="https://cdn.jsdelivr.net/gh/volfclub/travellings/assets/logo.svg" alt="开往-友链接力" width="100"></a>
<p class="busuzicss" style="font-size:14px;"><span id="busuanzi_container_site_pv" style="margin-right:3px"><i class="fa fa-users" aria-hidden="true" style="margin-right:5px"></i><span id="busuanzi_value_site_uv">访问<?php echo theAllViews();?></span>人次</span> | <span id="busuanzi_container_site_pv" style="margin-left:3px"><i class="fa fa-spinner" aria-hidden="true" style="margin-right:5px"></i><span id="busuanzi_value_site_pv">耗时<?php echo timer_stop();?></span></span></p>
 <script>
    function secondToDate(second) {
        if (!second) {
            return 0;
        }
        var time = new Array(0, 0, 0, 0, 0);
        if (second >= 365 * 24 * 3600) {
            time[0] = parseInt(second / (365 * 24 * 3600));
            second %= 365 * 24 * 3600;
        }
        if (second >= 24 * 3600) {
            time[1] = parseInt(second / (24 * 3600));
            second %= 24 * 3600;
        }
        if (second >= 3600) {
            time[2] = parseInt(second / 3600);
            second %= 3600;
        }
        if (second >= 60) {
            time[3] = parseInt(second / 60);
            second %= 60;
        }
        if (second > 0) {
            time[4] = second;
        }
        return time;
    }
</script>
<script type="text/javascript" language="javascript">
    function setTime() {
        // 博客创建时间秒数，时间格式中，月比较特殊，是从0开始的，所以想要显示5月，得写4才行，如下
        var create_time = Math.round(new Date(Date.UTC(2018, 12, 31, 0, 0, 0))
                .getTime() / 1000);
        // 当前时间秒数,增加时区的差异
        var timestamp = Math.round((new Date().getTime() + 8 * 60 * 60 * 1000) / 1000);
        currentTime = secondToDate((timestamp - create_time));
        currentTimeHtml = currentTime[0] + '年' + currentTime[1] + '天'
                + currentTime[2] + '时' + currentTime[3] + '分' + currentTime[4]
                + '秒';
        document.getElementById("htmer_time").innerHTML = currentTimeHtml;
    }
    setInterval(setTime, 1000);
</script>
   本站已度过<span id="htmer_time" style="color: blue;"></span>
</footer>
<div id="mask" onclick="Cuteen.bodyClose()" data-mask="close"></div>

<!-- 搜索框 -->
<div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <label class="modal-title h5" id="searchModalLabel" for="searchNameAccept">搜点什么……</label>
            </div>
            <div class="modal-body">
                <input autocomplete="off" onkeydown="Cuteen.enterSearch(this)" id="searchNameAccept" class="form-control" name="s" type="text" placeholder="请输入搜索关键词……" required />
            </div>
            <div class="modal-footer">
                <button id="closeSearch" type="button" class="btn btn-secondary" data-dismiss="modal">关闭</button>
                <button type="button" class="btn btn-primary" onclick="Cuteen.startSearch(document.getElementById('searchNameAccept'))">点我搜索
                </button>
            </div>
        </div>
    </div>
</div>

<div class="mobile-right-btn">
    <?php if ($this->options->是否启用歌单解析) : ?>
        <!--    移动端播放器-->
        <div id="mobileMusic" class="right-btn-icon position-relative d-block d-sm-none">
            <svg class="icon icon-20" aria-hidden="true">
                <use xlink:href="#music"></use>
            </svg>
            <div id="musicMobileBox"></div>
        </div>
    <?php endif; ?>
    <!--移动端昼夜切换-->
    <div onclick="Cuteen.darkMode()" class="right-btn-icon d-block d-sm-none">
        <svg class="icon icon-20" aria-hidden="true" id="mobileDarkMode">
            <use xlink:href="#moon"></use>
        </svg>
    </div>
    <!--    返回顶部-->
    <div class="right-btn-icon" onclick="Cuteen.backTop()">
        <svg class="icon icon-20" aria-hidden="true" id="darkMode">
            <use xlink:href="#arrow-up"></use>
        </svg>
    </div>
</div>
<script src="<?= StaticPath . 'js/bundle-f4b7476a35.js'; ?>"></script>
<?php if ($this->options->数学公式渲染) : ?>
    <script no-pjax async id="MathJax-script" src="https://cdn.jsdelivr.net/npm/mathjax@3.1.2/es5/tex-mml-chtml.js"></script>
    <script no-pjax>
        MathJax = {
            options: {
                renderActions: {
                    addMenu: [0, "", ""]
                }
            },
            tex: {
                inlineMath: [
                    ["$", "$"],
                    ["\\(", "\\)"]
                ]
            },
            svg: {
                fontCache: "global"
            }
        }
    </script>
<?php endif; ?>
<script type="text/javascript">
    lightGallery(document.getElementById("comment-list"), {
        selector: '.lightbox'
    });
    lightGallery(document.getElementById("post"), {
        selector: '.lightbox'
    });
</script>

<?php if ($this->options->Pjax无刷新) : ?>
    <script src="https://cdn.jsdelivr.net/npm/pjax-api@3.33.0/dist/pjax-api.min.js"></script>
    <script no-pjax>
        var _require = require('pjax-api'),
            Pjax = _require.Pjax;

        new Pjax({
            areas: ['#header,#main'],
            link: ['a:not(.next)'],
            update: {
                ignore: '[no-pjax]'
            }
        });
        window.addEventListener('pjax:fetch', function() {
            NProgress.set(0.6);
        });
        document.addEventListener('pjax:ready', function() {
            PjaxLoad();
            <?php if ($this->options->数学公式渲染) : ?>
                MathJax.typeset();
            <?php endif; ?>
            <?php if ($this->options->pjaxContent) : $this->options->pjax回调(); ?><?php endif; ?>
            NProgress.done();
        });
    </script>
<?php endif; ?>

<script src="<?= StaticPath . 'js/app.js'; ?>"></script>
<?php if ($this->options->是否启用歌单解析) : ?>
    <script src="<?= StaticPath . 'js/skPlayer.js'; ?>"></script>
<?php endif; ?>
<?php if ($this->options->平滑滚动) : ?>
    <script src="<?= StaticPath . 'js/SmoothScroll.min.js'; ?>"></script>
<?php endif; ?>
<script>
    if ('serviceWorker' in navigator) {
        <?php if ($this->options->sw) : ?>
            navigator.serviceWorker.register('/serviceWorker.js')
                .then(function(reg) {}).catch(function(error) {
                    console.log('cache failed with ' + error); // registration failed
                });
            navigator.serviceWorker.addEventListener('controllerchange', function(ev) {
                try {
                    Toastify({
                        text: "检测到本地缓存需要更新",
                        backgroundColor: "var(--bs-info)",
                    }).showToast();
                } catch (e) {
                    console.log("controllerchange");
                }
            });
        <?php else : ?>
            navigator.serviceWorker.getRegistrations()
                .then(function(registrations) {
                    for (let registration of registrations) {
                        registration.unregister();
                        // 清除缓存
                        window.caches && caches.keys && caches.keys().then(function(keys) {
                            keys.forEach(function(key) {
                                caches.delete(key);
                            });
                        });
                        console.log("unregister success")
                    }
                });
        <?php endif; ?>
    }
</script>
<?php $this->footer(); ?>
<?php if ($this->options->底部自定义) : $this->options->底部自定义(); ?><?php endif; ?>

</body>

</html>
<?php if ($this->options->Html压缩输出) : $html_source = ob_get_contents();
    ob_clean();
    print compressHtml($html_source);
    ob_end_flush();
endif; ?>
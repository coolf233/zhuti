<?php
/**
 * 哔哔页面
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
                <?= Ctx::Post($this,$this->user->hasLogin()) ?>
                <main id="app">
                  <article class="message lan"><div class="message_body">共计发送 {{count}} 条说说</div></article>
 
                    <div class="timenode" v-for="item in contents" v-cloak>
                      <div class="meta">
                        <p><time v-bind:datetime="item.attributes.time">{{item.attributes.time}}</time></p>
                      </div>
                      <div class="body">
                        <p v-html='item.attributes.content'></p>
                      </div>
                    </div>
 
        <div class="load-ctn">
        <a class="btn btn-success" role="button" v-on:click="loadMore" v-if="contents" v-cloak>再翻翻</a>
        <p class="tip" v-else>别急，加载呢</p>
        </div>
                  </main>
            </article>
            <footer id="post-info">
 
            </footer>
        </section>
        <style>
    @font-face {
      font-family: "iconfont";
      src: url('//at.alicdn.com/t/font_1755564_z4mhxbw13mq.eot?t=1586882866168');
      /* IE9 */
      src: url('//at.alicdn.com/t/font_1755564_z4mhxbw13mq.eot?t=1586882866168#iefix') format('embedded-opentype'),
        /* IE6-IE8 */
        url('data:application/x-font-woff2;charset=utf-8;base64,d09GMgABAAAAAALcAAsAAAAAB2gAAAKPAAEAAAAAAAAAAAAAAAAAAAAAAAAAAAAAHEIGVgCCfgqCUII8ATYCJAMMCwgABCAFhG0HQBuEBsiuMRkynL4YFCHtLKs4b+k7/80U8DwHIB6+xtr7u3vniDZI6tmnkyDS8NC5iFgJlE7pTPcO8e5b+803aYQy8YtH1JPKyZzY3ttb1EonioVGSBAiRJp4JV9cFppCxLpe2z8sUs37O4QEDFRM0lFeAw4XN6bQPG06zTG8DRwHFOBeGEVWIjE+mP8wduVFPCbQNK4ktN8/OgFJhbEqEFemLkEyFVcUVqoL1ZKDRbyIqafb9AA8+9+PX41IklQyY+PBSZ8KnR+9iBx0OxZcp8PxvGDHyFgACnFY6j8QCfMLIppKPmoCrGtW8OG/y0eufLX/8AiJqDKa22AGWpMzUth6p6aARySQQRUUL4AnSBmuyvIwPdbz21OX5ybO5duOJifaLl6cvkrd1HR7kWEy6yikff29PvgO17V/Tf/9f/oN3PDqz22a719IYlZJHQetpLY08MV6fwFDrF0B6F4XyB3/FyEEnZnU2XGtVwHvpxtA1d6pftKB4AcoWo4U1QQbiiy35Xow27whbwK2NNFlhAc99zeOtM4S6ikFQ1IzhqxuglAoC6ho2UBV3Raa5g0cbxnCRURpYM4pQOh3h6TXG7J+j4RCeULFqFdU9UcETYfRdGHLVJjpHk6SIBXlTTQV5mmWS6SD1jHSVx2JJ1U+a4p4YKawvrSSLQ2QR3yMLcGa3hBCQ40zF/vBc+Q4DH3OLFJExRDCb64un5S9qaIwF9L2cEQiEBWSbUImBePR3PFCOvf5MUS3yiHhNR0VySmEC5jTo7qKWgvCgN5r1XEtrwTW6BoEQYM0HONC/cBLHDPPIL98noUohAqjR7uvWVV1HlVbfWV+o/t8J6DJOJ4jRY6idmVKnmVS1q8tK8zfBAAA') format('woff2'),
        url('//at.alicdn.com/t/font_1755564_z4mhxbw13mq.woff?t=1586882866168') format('woff'),
        url('//at.alicdn.com/t/font_1755564_z4mhxbw13mq.ttf?t=1586882866168') format('truetype'),
        /* chrome, firefox, opera, Safari, Android, iOS 4.2+ */
        url('//at.alicdn.com/t/font_1755564_z4mhxbw13mq.svg?t=1586882866168#iconfont') format('svg');
      /* iOS 4.1- */
    }
  #app img{display: block;}
    .iconfont {
      font-family: "iconfont" !important;
      font-size: 16px;
      font-style: normal;
      -webkit-font-smoothing: antialiased;
      -moz-osx-font-smoothing: grayscale;
    }
 
    .icon-lianjie:before {
      content: "\e6a3";
    }
 
    .icon-lianjie-copy:before {
      content: "\e6a4";
    }
 
/* bb样式 */
div.timenode {
    position: relative;
  }
  div.timenode:before,
  div.timenode:after {
    content: '';
    z-index: 1;
    position: absolute;
    background: rgba(68,215,182,0.5);
    width: 2px;
    left: 7px;
  }
  div.timenode:before {
    top: 0px;
    height: 6px;
  }
  div.timenode:after {
    top: 26px;
    height: calc(100% - 26px);
  }
  div.timenode:last-child:after {
    height: calc(100% - 26px - 16px);
    border-bottom-left-radius: 2px;
    border-bottom-right-radius: 2px;
  }
  div.timenode .meta,
  div.timenode .body {
    max-width: calc(100% - 24px);
  }
  div.timenode .meta {
    position: relative;
    color: var(--color-meta);
    font-size: 0.875rem;
    line-height: 32px;
    height: 32px;
  }
  div.timenode .meta:before,
  div.timenode .meta:after {
    content: '';
    position: absolute;
    top: 8px;
    z-index: 2;
  }
  div.timenode .meta:before {
    background: rgba(68,215,182,0.5);
    width: 16px;
    height: 16px;
    border-radius: 8px;
  }
  div.timenode .meta:after {
    background: #44d7b6;
    margin-left: 2px;
    margin-top: 2px;
    width: 12px;
    height: 12px;
    border-radius: 6px;
    transform: scale(0.5);
    transition: all 0.28s ease;
    -moz-transition: all 0.28s ease;
    -webkit-transition: all 0.28s ease;
    -o-transition: all 0.28s ease;
  }
  div.timenode .meta p {
    font-weight: bold;
    margin: 0 0 0 24px;
  }
  div.timenode .body {
    margin: 4px 0 16px 24px;
    padding: 16px;
    border-radius: 8px;
    background: var(--color-block);
    display: inline-block;
  }
  div.timenode .body:empty {
    display: none;
  }
  div.timenode .body >*:first-child {
    margin-top: 0.25em;
  }
  div.timenode .body >*:last-child {
    margin-bottom: 0.25em;
  }
  div.timenode .body .highlight {
    border: 1px solid #e4e4e4;
  }
  div.timenode:hover .meta {
    color: var(--color-text);
  }
  div.timenode:hover .meta:before {
    background: rgba(255,87,34,0.5);
  }
  div.timenode:hover .meta:after {
    background: #ff5722;
    transform: scale(1);
  }
  div.timenode .body {
    margin: 0 0 0 24px;
    padding: 16px;
    border-radius: 8px;
    background: #f6f6f6;
    display: inline-block;
}
 
/*bb暗黑样式*/
.dark-mode .timenode meta p{
    color: #fff;
}
.dark-mode .timenode p,.dark-mode .timenode li{
    color: #555;
}
.dark-mode .timenode blockquote p{
    color:#cfcfcf
}
.dark-mode .timenode p a{
    color: rgb(36, 122, 62);
}
.dark-mode .timenode .body {
    background: #c4c4c4;
}
  </style>
<script src="https://cdn.bootcss.com/vue/2.6.11/vue.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/leancloud-storage@4.5.3/dist/av-min.js"></script>
<script src="<?= StaticPath . 'js/bb.js'; ?>"></script>
        <section id="comments" class="post-comment mt-4">
            <header class="post-nav">
                <span class="item"><svg class="icon mr-1" aria-hidden="true"><use
                                xlink:href="#talk"></use></svg>页面评论</span>
            </header>
            <hr id="comment-hr">
            <?php $this->need('base/comment.php'); ?>
        </section>
    </div>
</div>
 
<?php $this->need('base/footer.php'); ?>
 
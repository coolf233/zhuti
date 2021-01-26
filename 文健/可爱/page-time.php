<?php
/**
 * 说说页面
 * @package custom
 */

$this->need('base/head.php');
$this->need('base/navbar.php');
function threadedComments($comments, $options)
{ ?>
    <li id="<?php $comments->theId(); ?>">
        <time><?= App::timeSince($comments->created); ?></time>
        <div class="timeline-text"><?= Ctx::Comment($comments->content) ?></div>
    </li>
<?php }

$this->comments()->to($comments); ?>
    <div class="row justify-content-center <?= Ctx::SidebarArray()[0] ?>">
        <?= Ctx::Sidebar($this) ?>
        <div id="content" class="col-lg-9">
            <section class="post-ctx">
                <?=Ctx::HeroTitleNoImg($this)?>
                <div class="row">
                    <div class="col-md-12">
                        <ul class="timeline" id="comment-list">
                            <?php $comments->listComments(); ?>
                        </ul>
                    </div>
                    <?php Ctx::pagination($comments) ?>
                    <?php if ($this->user->hasLogin()) : ?>
                        <div class="comment-input-warp" id="<?php $this->respondId(); ?>">
                            <form id="comment-form" method="post" role="form"
                                  data-action="<?php $this->commentUrl() ?>">
                                <div class="row">
                                    <div class="p-2 px-3 font-weight-bold"><?php $this->user->screenName(); ?>，发一条说说吧~
                                    </div>
                                </div>
                                <div class="comment-edit">
                                    <textarea id="comment-textarea" class="comment-textarea comment-input" name="text"
                                              required></textarea>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <svg id="comment-emoji" class="icon icon-32" aria-hidden="true">
                                        <use xlink:href="#guaiqiao"></use>
                                    </svg>
                                    <button type="submit" class="btn btn-sm btn-primary"
                                            onclick="Cuteen.ajaxComment();">提交
                                    </button>
                                </div>
                                <div id="OwO" class="OwO"></div>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
            </section>
        </div>
    </div>
    <script src="<?= StaticPath . 'js/OwO.js'; ?>"></script>
    <script type="text/javascript">
        (function () {
            window.TypechoComment = {
                dom: function (id) {
                    return document.getElementById(id)
                },
                pom: function (id) {
                    return document.getElementsByClassName(id)[0]
                },
                iom: function (id, dis) {
                    var alist = document.getElementsByClassName(id);
                    if (alist) {
                        for (var idx = 0; idx < alist.length; idx++) {
                            var mya = alist[idx];
                            mya.style.display = dis
                        }
                    }
                },
                create: function (tag, attr) {
                    var el = document.createElement(tag);
                    for (var key in attr) {
                        el.setAttribute(key, attr[key])
                    }
                    return el
                },
                reply: function (cid, coid) {
                    var comment = this.dom(cid),
                        response = this.dom("<?php echo $this->respondId(); ?>"),
                        input = this.dom("comment-parent"),
                        form = "form" === response.tagName ? response : response.getElementsByTagName("form")[0],
                        textarea = response.getElementsByTagName("textarea")[0];
                    if (null == input) {
                        input = this.create("input", {"type": "hidden", "name": "parent", "id": "comment-parent"});
                        form.appendChild(input)
                    }
                    input.setAttribute("value", coid);
                    if (null == this.dom("comment-form-place-holder")) {
                        var holder = this.create("div", {"id": "comment-form-place-holder"});
                        response.parentNode.appendChild(holder, response)
                    }
                    comment.children[1].appendChild(response);
                    this.iom("comment-reply", "");
                    this.pom("cp-" + cid).style.display = "none";
                    this.iom("cancel-comment-reply", "none");
                    this.pom("cl-" + cid).style.display = "";
                    if (null != textarea && "text" === textarea.name) {
                        textarea.focus()
                    }
                    return false
                },
                cancelReply: function () {
                    var response = this.dom("<?php echo $this->respondId(); ?>"),
                        holder = this.dom("comment-form-place-holder"),
                        input = this.dom("comment-parent");
                    if (null != input) {
                        input.parentNode.removeChild(input)
                    }
                    if (null == holder) {
                        return true
                    }
                    this.iom("comment-reply", "");
                    this.iom("cancel-comment-reply", "none");
                    //this.dom('comment-hr').insertAdjacentElement('afterend', response);
                    this.dom('comment-list').insertAdjacentElement('afterend', response);
                    return false
                }
            }
        })();
    </script>
<?php $this->need('base/footer.php'); ?>
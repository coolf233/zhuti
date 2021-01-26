<?php
/**
 * Author: Veen Zhao
 * CreateTime: 2020/10/8 22:55
 * 评论区
 */
function threadedComments($comments, $options)
{
    $commentLevelClass = $comments->levels > 0 ? 'comment-child' : 'comment-parent';  //评论层数大于0为子级，否则是父级
    ?>
    <div id="<?php $comments->theId(); ?>" class="comment-card <?= $commentLevelClass ?>">
        <img class="comment-avatar" src="<?= App::avatarQQ($comments->mail); ?>s=100">
        <div class="comment-info">
            <div class="comment-head">
                <span class="comment-nick"><?= Ctx::CommentAuthor($comments) ?></span>
                <?= Ctx::CommentInfo($comments) ?>
            </div>
            <div class="comment-meta">
                <span class="comment-time"><?= App::timeSince($comments->created); ?></span>
                <span class="comment-reply cp-<?php $comments->theId(); ?> comment-reply-link"><?php $comments->reply('回复<svg class="icon ml-1" aria-hidden="true" id="darkMode"><use xlink:href="#huifu"></use></svg>'); ?></span>
                <span id="cancel-comment-reply"
                      class="cancel-comment-reply cl-<?php $comments->theId(); ?> comment-reply-link"
                      style="display:none"><?php $comments->cancelReply('取消<svg class="icon ml-1" aria-hidden="true" id="darkMode"><use xlink:href="#x-circle"></use></svg>'); ?></span>

            </div>
            <div class="comment-content" data-expand="查看更多..."><p><?= Ctx::Comment($comments->content) ?></p>
            </div>
        </div>
        <?php if ($comments->children) : ?>
            <div class="comment-quote">
                <?php $comments->threadedComments($options); ?>
            </div>
        <?php endif ?>
    </div>

<?php }

$this->comments()->to($comments); ?>
<div id="comment-list">
    <?php $comments->listComments(); ?>
    <?php Ctx::pagination($comments)  ?>
</div>
<div class="comment-input-warp" id="<?php $this->respondId(); ?>">
    <form id="comment-form" method="post" role="form" data-action="<?php $this->commentUrl() ?>">
        <div class="row">
            <?php if ($this->user->hasLogin()) : ?>
                <div class="p-2 px-3 font-weight-bold">使用 <?php $this->user->screenName(); ?> 发表评论：</div>
            <?php else : ?>
            <input id="author" name="author" placeholder="昵称" class="comment-input col-sm-4" type="text"
                   value="<?php $this->remember('author'); ?>" required>
            <input id="mail" name="mail" placeholder="邮箱" class="comment-input col-sm-4" type="email"
                   value="<?php $this->remember('mail'); ?>" <?php if ($this->options->commentsRequireMail) : ?> required<?php endif; ?>>
            <input id="url" name="url" placeholder="网址(https://)" class="comment-input col-sm-4" type="text"
                   value="<?php $this->remember('url'); ?>" <?php if ($this->options->commentsRequireURL) : ?> required<?php endif; ?>>
            <?php endif; ?>
        </div>
        <div class="comment-edit">
            <textarea id="comment-textarea" class="comment-textarea comment-input" name="text" placeholder="来都来了，说一句吧~"
                      required></textarea>
        </div>
        <div class="d-flex justify-content-between align-items-center">
            <svg id="comment-emoji" class="icon icon-32" aria-hidden="true">
                <use xlink:href="#guaiqiao"></use>
            </svg>
            <button type="submit" class="btn btn-sm btn-primary" onclick="Cuteen.ajaxComment();">提交</button>
        </div>
        <div id="OwO" class="OwO"></div>
    </form>
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
                this.dom('comment-list').insertAdjacentElement('afterend', response);
                return false
            }
        }
    })();
</script>
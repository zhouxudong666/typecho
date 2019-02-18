<?php
$GLOBALS['z']  = $this->options->CDNURL;
//$GLOBALS['timechoice'] = $this->options->langis;
$GLOBALS['imgdelay'] = $this->options->themeUrl.'/img/white.gif';
$GLOBALS['isimgdelay'] = !empty($this->options->indexsetup) && in_array('lazyloadimg', $this->options->indexsetup);
function threadedComments($comments, $options) {
    $commentClass = '';
    if ($comments->authorId) {
        if ($comments->authorId == $comments->ownerId) {
            $commentClass .= ' comment-by-author';  //如果是文章作者的评论添加 .comment-by-author 样式
        } else {
            $commentClass .= ' comment-by-user';  //如果是评论作者的添加 .comment-by-user 样式
        }
    }
    $commentLevelClass = $comments->_levels > 0 ? ' comment-child' : ' comment-parent';  //评论层数大于0为子级，否则是父级
    $depth = $comments->levels +1; //添加的一句
    if ($comments->url) {
        $author = '<a href="' . $comments->url . '"target="_blank"' . ' rel="external nofollow">' . $comments->author . '</a>';
    } else {
        $author = $comments->author;
    }
?>

<!--自定义评论代码结构-->
<!--<a name="<?php //$comments->theId(); ?>" class="target">
</a>-->
    <li data-no-instant id="<?php $comments->theId(); ?>" class="comment-body<?php
if ($depth > 1 && $depth < 3) {
    echo ' comment-child ';
    $comments->levelsAlt('comment-level-odd', ' comment-level-even');
}
else if( $depth > 2){
    echo ' comment-child2';
    $comments->levelsAlt(' comment-level-odd', ' comment-level-even');
}
else {
    echo ' comment-parent';
}
$comments->alt(' comment-odd', ' comment-even');
echo $commentClass;
?>">
      <div id="div-<?php $comments->theId(); ?>" class="comment-body">
        <?php
        //头像CDN by Rich http://forum.typecho.org/viewtopic.php?f=5&t=6165&p=29961&hilit=gravatar#p29961
            $host = $GLOBALS['z'];//自定义头像CDN服务器
            $url = '/avatar/';//自定义头像目录,一般保持默认即可
            $size = '80';//自定义头像大小
            $rating = Helper::options()->commentsAvatarRating;
            $hash = md5(strtolower($comments->mail));
            $avatar = $host . $url . $hash . '?s=' . $size . '&r=' . $rating . '&d=';
        ?>
        <a class="pull-left thumb-sm">
        <?php if ($GLOBALS['isimgdelay']): ?>
          <img data-original="<?php echo $avatar ?>" src="<?php echo $GLOBALS['imgdelay'] ?>" class="avatar-40 photo img-circle" style="height:40px!important; width: 40px!important;">
        <?php else: ?>
          <img src="<?php echo $avatar ?>" class="avatar-40 photo img-circle" style="height:40px!important; width: 40px!important;">
        <?php endif; ?>
          </a>
        <div class="m-b m-l-xxl">
          <div class="comment-meta">
            <span class="comment-author vcard">
              <b class="fn"><?php echo $author; ?></b>
              </span>
            <div class="comment-metadata">
              <time class="text-muted text-xs block m-t-xs" pubdate="pubdate" datetime="<?php $comments->date(); ?>"><?php $comments->date(_mt("Y 年 m 月 d 日 h 时 i 分 A")); ?>
              </time>
              </div>
          </div>
          <!--回复内容-->
          <div class="comment-content m-t-sm">
            <span class="comment-author-at"><b><?php get_comment_at($comments->coid)?></b></span><?php $comments->content(); ?>
          </div>
          <!--回复按钮-->
          <div class="reply m-t-sm">
            <?php $comments->reply(); ?>
        </div>
      </div>

      </div>
      <!-- 单条评论者信息及内容 -->
      <?php if ($comments->children) { ?> <!-- 是否嵌套评论判断开始 -->
      <div class="children list-unstyled m-l-xxl">
        <?php $comments->threadedComments($options); ?> <!-- 嵌套评论所有内容-->
      </div>
      <?php } ?> <!-- 是否嵌套评论判断结束 -->
    </li><!--匹配`自定义评论的代码结构`下面的li标签-->
<?php } ?>


<div id="comments">
   <?php $this->comments()->to($comments); ?>
   <?php if ($comments->have()): ?>
    <h4 class="comments-title m-t-lg m-b"><?php $this->commentsNum(_t(' 暂无评论'), _t(' 1 条评论'), _t(' %d 条评论')); ?></h4>
    <?php $comments->listComments(); ?>
    <nav class="text-center m-t-lg m-b-lg" role="navigation">
        <?php $comments->pageNav('&lt;', '&gt;'); ?>
    </nav>
<script type="text/javascript">
//给分页按钮增加样式
$(".page-navigator").addClass("pagination pagination-md");
$("#comments .page-navigator").addClass("pagination-sm");
$(".page-navigator .current").addClass("active");
$("#comments .comment-list").addClass("list-unstyled m-b-none");
</script>
    <?php endif; ?>
    <?php //endif; ?>
    <!--如果允许评论，会出现评论框和个人信息的填写-->
    <?php if($this->allow('comment')): ?>
    <div id="<?php $this->respondId(); ?>" class="respond comment-respond">

    <h4 id="reply-title" class="comment-reply-title m-t-lg m-b"><?php _me("发表评论") ?>
        <small data-no-instant class="cancel-comment-reply">
          <?php $comments->cancelReply(); ?>
        </small>
    </h4>
    <form id="comment_form" method="post" action="<?php $this->commentUrl() ?>"  class="comment-form" role="form">
      <div class="comment-form-comment form-group">
        <label for="comment"><?php _me("评论") ?>
          <span class="required text-danger">*</span></label>
        <textarea id="comment" class="textarea form-control OwO-textarea" name="text" rows="5" required><?php $this->remember('text'); ?></textarea>
        <div class="OwO"></div>
      </div>
      <!--判断是否登录-->
    <?php if($this->user->hasLogin()): ?>
    <p><?php _me("欢迎") ?>&nbsp;<a data-no-intant target="blank" href="<?php $this->options->profileUrl(); ?>"><?php $this->user->screenName(); ?></a>&nbsp;<?php _me("归来") ?>！&nbsp;<a data-no-instant href="<?php $this->options->logoutUrl(); ?>" title="Logout"><?php _me("退出") ?>&raquo;</a></p>
    <?php else: ?>
        <?php if($this->remember('author',true) != "" && $this->remember('mail',true) != "") : ?>
        <p><?php _me("欢迎") ?>&nbsp;<a style="color: #333!important;" onClick='showhidediv("author_info")' data-toggle="tooltip" title="点击修改信息"><?php $this->remember('author'); ?></a>&nbsp;<?php _me("归来") ?>！</p>
        <div id="author_info" style="display:none;">
        <?php else : ?>
        <div id="author_info" class="row row-sm">
        <?php endif; ?>
        <div class="comment-form-author form-group col-sm-6 col-md-4">
          <label for="author"><?php _me("名称") ?>
            <span class="required text-danger">*</span></label>
          <input id="author" class="form-control" name="author" type="text" value="<?php $this->remember('author'); ?>" maxlength="245" placeholder="<?php _me("姓名或昵称") ?>" required>
          </div>

        <div class="comment-form-email form-group col-sm-6 col-md-4">
          <label for="email"><?php _me("邮箱") ?>
            <span class="required text-danger">*</span>
            </label>
          <input type="email" name="mail" id="mail" class="form-control" required placeholder="邮箱 (必填,将保密)" value="<?php $this->remember('mail'); ?>" />
          </div>

        <div class="comment-form-url form-group col-sm-12 col-md-4">
          <label for="url"><?php _me("邮箱") ?></label>
          <input id="url" class="form-control" name="url" type="url" value="<?php $this->remember('url'); ?>" maxlength="200" placeholder="<?php _me("网站或博客") ?>"></div>
      </div>
      <?php endif; ?>
      <!--提交按钮-->
      <div class="form-group">
        <button type="submit" name="submit" id="submit" class="submit btn btn-success padder-lg">
          <span class="text"><?php _me("发表评论") ?></span>
          <span class="text-active"><?php _me("提交中") ?>...</span>
        </button>
        <i class="icon-spin iconfont icon-spinner hide" id="spin"></i>
        <input type="hidden" name="comment_post_ID" value="448" id="comment_post_ID">
        <input type="hidden" name="comment_parent" id="comment_parent" value="0">
        </div>
    </form>
    </div>
    <?php else: ?>
    <h4><?php _me("此处评论已关闭") ?></h4>
    <?php endif; ?>
</div>

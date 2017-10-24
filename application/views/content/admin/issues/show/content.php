<nav id='nav'>
  <a href="<?php echo base_url ('admin', 'issues', $obj->tab->id);?>"><?php echo $obj->tab->name;?></a>
  <span><?php echo $obj->title;?></span>
</nav>

<?php if ($b = Session::getData ('_fi', true)) { ?>
        <span class='_w b'><?php echo $b;?></span>
<?php }
      if ($r = Session::getData ('_fd', true)) { ?>
        <span class='_w r'><?php echo $r;?></span>
<?php } if ($obj->user_id == User::current ()->id) { ?>
        <div class='editer'>
          <a href="<?php echo base_url ('admin', 'issue', $obj->id);?>" class='icon-17 delete' data-method='delete' data-alert='確定要刪除這個 issue？'>刪除</a>
          <a href="<?php echo base_url ('admin', 'issue', $obj->id, 'edit');?>" class='icon-18'>編輯</a>
          <a href="<?php echo base_url ('admin', 'issue', $obj->id, 'status');?>" class='icon-<?php echo $obj->status == Issue::STATUS_2 ? 22 : 10;?>'>標注<?php echo Issue::$statusNames[$obj->status];?></a>
        </div>
<?php }?>

<article class='article<?php echo $obj->status == Issue::STATUS_3 ? '  finish' : '';?>'>
  
  <header>    
    <time data-time='<?php echo $obj->created_at->format ('Y.m.d');?>' datetime='<?php echo $obj->created_at->format ('Y-m-d H:i:s');?>'><?php echo $obj->created_at->format ('Y-m-d H:i:s');?></time>
    <h1><?php echo $obj->title;?></h1>
  </header>
  
  <section>
    <h2>內容</h2>
    <?php echo $obj->content ? $obj->content : '<p class="e"></p>';?>
  </section>

  <section>
    <h2>解決方式</h2>
    <?php echo $obj->fix ? nl2p ($obj->fix) : '<p class="e"></p>';?>
  </section>

<?php
  foreach ($obj->advices as $advice) {
    if ($advice->content) { ?>
      <section>
        <h2><?php echo Advice::$typeNames[$advice->type];?> 考量 / 建議</h2>
        <?php echo nl2p ($advice->content);?>
      </section>
<?php
    }
  } ?>
  <?php
  if ($obj->knowns) { ?>
    <section>
      <h2>可能造成的問題</h2>
      <ul>
  <?php foreach ($obj->knowns as $known) { ?>
          <li><?php echo $known->content;?></li>
  <?php }?>
      </ul>
    </section>
  <?php
  } ?>
  <?php
  if ($obj->sources) { ?>
    <section>
      <h2>數據相關輔助資料</h2>
      <ul>
  <?php foreach ($obj->sources as $source) { ?>
          <li><?php echo $source->content;?></li>
  <?php }?>
      </ul>
    </section>
  <?php
  } ?>

  <div class='info'>
    <span> </span>
    <span class='icon-8'>瀏覽次數 <?php echo number_format ($obj->pv);?> 次</span>
  </div>

</article>

<form class='cm' action='<?php echo base_url ('admin', 'comments');?>' method='post' enctype='multipart/form-data'>
  <input type='hidden' name='issue_id' value='<?php echo $obj->id;?>' />

  <figure class='_ic'>
    <img src='<?php echo User::current ()->avatar ();?>'>
  </figure>

  <div>
    <textarea name='content' placeholder='請輸入點什麼吧？' class='autosize' maxlength='255' pattern='.{1,255}' required title='請輸入留言內容說明!'></textarea>
    <div>
      <button type='submit'>留言</button>
      <!-- <a class='icon-16'></a> -->
    </div>
  </div>
</form>

<div class='cm<?php echo count ($contents = Comment::find ('all', array ('order' => 'id DESC', 'include' => array ('user', 'likes', 'unlikes'), 'conditions' => array ('issue_id = ?', $obj->id)))) ? '' : ' e';?>'>
<?php foreach ($contents as $comment) { ?>
  <div>
    <a class='_ic' href='<?php echo base_url ('admin', 'users', $comment->user->id);?>'>
      <img src='<?php echo $comment->user->avatar ();?>'>
    </a>
    <span<?php echo $comment->status == Comment::STATUS_1 ? ' class="deleted"' : '';?>><b><?php echo $comment->user->name;?></b>
<?php if ($comment->status != Comment::STATUS_1) { ?>
        <?php echo $comment->content;?>
        <i class='icon-15 unlike'><?php echo ($cnt = count ($unlikes = column_array ($comment->unlikes, 'user_id'))) ? $cnt > 10 ? '10+' : $cnt : '';?></i>
        <i class='icon-14 like'><?php echo ($cnt = count ($likes = column_array ($comment->likes, 'user_id'))) ? $cnt > 10 ? '10+' : $cnt : '';?></i>
<?php } ?>
    </span>
<?php if ($comment->status != Comment::STATUS_1) { ?>
        <div>
          <time datetime='<?php echo $comment->created_at->format ('Y-m-d H:i:s');?>'><?php echo $comment->created_at->format ('Y-m-d H:i:s');?></time>
          <a data-url='<?php echo base_url ('admin', 'comments', 'like', $obj->id, $comment->id, CommentLike::STATUS_3);?>'class='icon-14 like<?php echo in_array (User::current ()->id, $likes) ? ' a' : '';?>'></a>
          <a data-url='<?php echo base_url ('admin', 'comments', 'like', $obj->id, $comment->id, CommentLike::STATUS_2);?>'class='icon-15 unlike<?php echo in_array (User::current ()->id, $unlikes) ? ' a' : '';?>'></a>
    <?php if ($comment->user_id == User::current ()->id ) { ?>
            <a class='delete' data-url="<?php echo base_url ('admin', 'comments', $comment->id);?>">刪除</a>
    <?php } ?>
        </div>
<?php } ?>
  </div>
<?php }?>
</div>
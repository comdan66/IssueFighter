<?php if ($b = Session::getData ('_fi', true)) { ?>
    <span class='_w b'><?php echo $b;?></span>
<?php }
      if ($r = Session::getData ('_fd', true)) { ?>
    <span class='_w r'><?php echo $r;?></span>
<?php } ?>

<div id='user'>
  <div class='l'>
    <figure class='_ic'>
      <img src='<?php echo User::current ()->avatar ();?>'>
    </figure>
    <h1><?php echo User::current ()->name;?></h1>
    <div>
      <b>參與度</b>
      <span><?php echo number_format (User::current ()->score (), 1);?> 分</span>
    </div>
    <a href="<?php echo base_url ('logout');?>">登出</a>
  </div>

  <div class='r'>
    <div>
      <b>issue 篇數</b>
      <span><?php echo number_format (count (User::current ()->issues));?> 篇</span>
    </div>
    
    <div>
      <b>留 言 次 數</b>
      <span><?php echo number_format (count (User::current ()->comments));?> 次</span>
    </div>
    
    <div>
      <b>登 入 次 數</b>
      <span><?php echo number_format (User::current ()->cnt_login);?> 次</span>
    </div>

    <div>
      <b>上 次 登 入</b>
      <time datetime='<?php echo User::current ()->logined_at->format ('Y-m-d H:i:s');?>'><?php echo User::current ()->logined_at->format ('Y-m-d H:i:s');?></time>
    </div>
    
    <div>
      <b>註 冊 日 期</b>
      <time datetime='<?php echo User::current ()->created_at->format ('Y-m-d H:i:s');?>'><?php echo User::current ()->created_at->format ('Y-m-d H:i:s');?></time>
    </div>
    
    <div>
      <b>擁 有 權 限</b>
      <div<?php echo User::current ()->role_names () ? '' : ' class="e"';?>>
  <?php foreach (User::current ()->role_names () as $role_name) { ?>
          <span><?php echo $role_name;?></span>
  <?php } ?>
      </div>
    </div>
    
  </div>
</div>
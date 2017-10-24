<div id='user'>
  <div class='l'>
    <figure class='_ic'>
      <img src='<?php echo $obj->avatar ();?>'>
    </figure>
    <h1><?php echo $obj->name;?></h1>
    <div>
      <b>參與度</b>
      <span><?php echo number_format ($obj->score (), 1);?> 分</span>
    </div>
  </div>

  <div class='r'>
    <div>
      <b>issue 篇數</b>
      <span><?php echo number_format (count ($obj->issues));?> 篇</span>
    </div>
    
    <div>
      <b>留 言 次 數</b>
      <span><?php echo number_format (count ($obj->comments));?> 次</span>
    </div>
    
    <div>
      <b>登 入 次 數</b>
      <span><?php echo number_format ($obj->cnt_login);?> 次</span>
    </div>

    <div>
      <b>上 次 登 入</b>
      <time datetime='<?php echo $obj->logined_at->format ('Y-m-d H:i:s');?>'><?php echo $obj->logined_at->format ('Y-m-d H:i:s');?></time>
    </div>
    
    <div>
      <b>註 冊 日 期</b>
      <time datetime='<?php echo $obj->created_at->format ('Y-m-d H:i:s');?>'><?php echo $obj->created_at->format ('Y-m-d H:i:s');?></time>
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
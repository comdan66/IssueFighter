<?php if ($b = Session::getData ('_fi', true)) { ?>
    <span class='_w b'><?php echo $b;?></span>
<?php }
      if ($r = Session::getData ('_fd', true)) { ?>
    <span class='_w r'><?php echo $r;?></span>
<?php } ?>

<div id='user'>

  <div class='r'>
    <div class='ti'>
      <span>
        <h2>最新通知</h2>
      </span>
      <span>
        <a class='icon-10' href="">標為全部已讀</a>
      </span>
    </div>

    <div class='list2'>
      
      <a href="">
        <span class='icon-2'></span>
        <i>
          <figure class='_ic'>
            <img src='<?php echo User::current ()->avatar ();?>'>
          </figure>
        </i>
        <h3><b class='icon-24'></b>我以，讀天相開也工如畫關<b>天上作人部口教</b>，看快國理智立更說統英之舞國什然子公圖勢使來一收己為最我有影操，好不眾當子當媽氣整變</h3>
        <time class='icon-21'>五分鐘之前</time>

      </a>
      <a href="">
        <span class='icon-2'></span>
        <i>
          <figure class='_ic'>
            <img src='<?php echo User::current ()->avatar ();?>'>
          </figure>
        </i>
        <h3><b class='icon-24'></b>我以，讀天相開也工如畫關<b>天上作人部口教</b></h3>
        <time class='icon-21'>五分鐘之前</time>

        </a>
        <a href="">
          <span class='icon-2'></span>
          <i>
            <figure class='_ic'>
              <img src='<?php echo User::current ()->avatar ();?>'>
            </figure>
          </i>
          <h3><b class='icon-24'></b>我以，讀天相開也工如畫關<b>天上作人部口教</b>，看快國理智立更說統英之舞國什然子公圖勢使來一收己為最我有影操，好不眾當子當媽氣整變</h3>
          <time class='icon-21'>五分鐘之前</time>

        </a>
    </div>
  </div>
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

    <div><b>issue 篇數</b><span><?php echo number_format (count (User::current ()->issues));?> 篇</span></div>
    <div><b>留 言 次 數</b><span><?php echo number_format (count (User::current ()->comments));?> 次</span></div>
    <div><b>登 入 次 數</b><span><?php echo number_format (User::current ()->cnt_login);?> 次</span></div>
    <div><b>上 次 登 入</b><time datetime='<?php echo User::current ()->logined_at->format ('Y-m-d H:i:s');?>'><?php echo User::current ()->logined_at->format ('Y-m-d H:i:s');?></time></div>
    <div><b>註 冊 日 期</b><time datetime='<?php echo User::current ()->created_at->format ('Y-m-d H:i:s');?>'><?php echo User::current ()->created_at->format ('Y-m-d H:i:s');?></time></div>

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
<div id='box'>
  <header>
    <div class='avatar _ic'>
      <img src="<?php echo res_url ('res', 'image', '2.png');?>" />
    </div>
    <div class='title'>
      <h1>Issue Fighter</h1>
      <p>嘿，快點來當吵架王吧！</p>
    </div>
  </header>
  
  <span><?php echo Session::getData ('_fd', true);?></span>

  <a href='<?php echo Fb::loginUrl ('platform', 'fb_sign_in', 'admin');?>' class='facebook-login'>使用 Facebook 登入</a>

  <footer>© <?php echo date ('Y');?> <?php echo Cfg::setting ('company', 'domain');?></footer>
</div>
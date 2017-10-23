<!DOCTYPE html>
<html lang="zh">
  <head>
    <?php echo isset ($meta_list) ? $meta_list : ''; ?>

    <title><?php echo isset ($title) ? $title : ''; ?></title>

<?php echo isset ($css_list) ? $css_list : ''; ?>
<?php echo isset ($js_list) ? $js_list : ''; ?>

  </head>
  <body lang="zh-tw">
    <?php echo isset ($hidden_list) ? $hidden_list : ''; ?>

    <header id='header'>
      <h1>Issue Fighter</h1>
      <p>我是巷弄吵架王</p>
    </header>

    <div id='menu'>
      <input type='checkbox' id='menu_ckb' class='_ckbh' >
      <label class='icon-13' for='menu_ckb'></label>

      <div class='wrap'>
        <a href="index.html" class='a'>選項 1</a>
        <a href="content.html">選項 2</a>
        <a href="create.html">選項 3</a>
      </div>
    </div>

    <main id='main'>
    <?php echo isset ($content) ? $content : ''; ?>
    </main>

    <footer id='footer'>
      <div>版權所有 ©2017 kerker.tw, All Rights Reserved.</div>
      <div>本網站內會員言論僅代表個人觀點，不代表本站同意其說法，本站不承擔由該言論引起的法律責任。</div>
    </footer>

    <div id='loading' class='_box'>請稍候..</div><label class='_bc'></label>

  </body>
</html>
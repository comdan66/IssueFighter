<!DOCTYPE html>
<html lang="zh">
  <head>
    <?php echo isset ($meta_list) ? $meta_list : ''; ?>

    <title><?php echo isset ($title) ? $title : ''; ?></title>

<?php echo isset ($css_list) ? $css_list : ''; ?>
<?php echo isset ($js_list) ? $js_list : ''; ?>

  </head>
  <body lang="zh-tw">
<?php echo isset ($hidden_list) ? $hidden_list : '';
      if (isset ($searches)) { ?>
        <input type='checkbox' id='conditions_ckb' class='_ckbh'<?php echo $isSearch = array_filter (column_array ($searches, 'value'), function ($t) { return $t !== null; }) ? ' checked' : '';?> />
<?php } ?>

    <header id='header'>
      <h1>Issue Fighter</h1>
      <p>我是吵架王</p>
    </header>

    <div id='menu'>
      <input type='checkbox' id='menu_ckb' class='_ckbh' >
      <label class='icon-13' for='menu_ckb'></label>

      <div class='wrap'>
          <a href="<?php echo $url = base_url ('admin');?>"<?php echo isset ($nurl) && $nurl == $url ? ' class="a"' : '';?>>個人頁面</a>
  <?php foreach (Tab::find ('all', array ('order' => 'sort DESC', 'include' => array ('issues'))) as $tab) { ?>
          <a href="<?php echo $url = base_url ('admin', 'issues', $tab->id);?>"<?php echo isset ($nurl) && $nurl == $url ? ' class="a"' : '';?> data-cnt='<?php echo count ($tab->issues);?>'><?php echo $tab->name;?></a>
  <?php } ?>
      </div>
    </div>

    <main id='main'>
    <?php echo isset ($content) ? $content : ''; ?>
    </main>

    <footer id='footer'>
      <div>版權所有 ©2017 ioa.tw, All Rights Reserved.</div>
      <div>本網站內會員言論僅代表個人觀點，不代表本站同意其說法，本站不承擔由該言論引起的法律責任。</div>
    </footer>

    <div id='ntf'></div>
    <div id='loading' class='_box'>請稍候..</div><label class='_bc'></label>

  </body>
</html>
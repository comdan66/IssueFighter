<nav id='nav'>
  <a href="<?php echo base_url ('admin', 'issues', $t->id);?>"><?php echo $t->name;?></a>
  <span>新增文章</span>
</nav>

<form class='create' action='<?php echo base_url ('admin', 'issue', $t->id);?>' method='post' enctype='multipart/form-data'>
<?php if ($r = Session::getData ('_fd')) { ?>
        <span class='_w r'><?php echo $r;?></span>
<?php } ?>

  <div class='row'>
    <b class='need min' data-tip='此 issue 是否已經完成？'>狀態</b>
    <label class='switch'>
      <input type='checkbox' name='status'<?php echo (isset ($posts['status']) ? $posts['status'] : Issue::STATUS_1) == Issue::STATUS_3 ? ' checked' : '';?> value='<?php echo Issue::STATUS_3;?>' />
      <span></span>
    </label>
  </div>

  <label>
    <b class='need' data-tip='給這 issue 一個標題吧！'>標題</b>
    <input type='text' name='title' value='<?php echo isset ($posts['title']) ? $posts['title'] : '';?>' placeholder='請輸入 issue 標題..' maxlength='200' pattern='.{1,200}' required title='請輸入標題!' autofocus />
  </label>
  
  <label>
    <b class='need' data-tip='說明一下 issue 內容吧！'>內容</b>
    <textarea name='content' class='cke' placeholder='請輸入 issue 內容說明..' maxlength='1000' pattern='.{1,1000}' required title='請輸入 issue 內容說明!'><?php echo isset ($posts['content']) ? $posts['content'] : '';?></textarea>
  </label>

  <label>
    <b class='need' data-tip='此 issue 的分類'>類別</b>
    <select name='tab_id'>
<?php foreach (Tab::find ('all', array ('order' => 'sort DESC')) as $tab) { ?>
        <option value='<?php echo $tab->id;?>'<?php echo (isset ($posts['tab_id']) ? $posts['tab_id'] : $t->id) == $tab->id ? ' selected' : '';?>><?php echo $tab->name;?></option>
<?php } ?>
    </select>
  </label>

  <label>
    <b>解決方式</b>
    <textarea name='fix' class='autosize' placeholder='請輸入 issue 解決方式..'><?php echo isset ($posts['fix']) ? $posts['fix'] : '';?></textarea>
  </label>
  
<?php
  foreach (Advice::$typeNames as $key => $typeName) { ?>
    <label>
      <b><?php echo $typeName;?> 考量 / 建議</b>
      <textarea name='advices[<?php echo $key;?>]' class='autosize' placeholder='請輸入 <?php echo $typeName;?> 考量 / 建議..'><?php echo isset ($posts['advices[' . $key . ']']) ? $posts['advices[' . $key . ']'] : '';?></textarea>
    </label>
<?php
  } ?>
  
  <div class='row muti' data-vals='<?php echo json_encode ($knowns);?>' data-cnt='<?php echo count ($row_knowns);?>' data-attrs='<?php echo json_encode ($row_knowns);?>'>
    <b class='need'>可能造成的問題</b>
    <span><a></a></span>
  </div>

  <div class='row muti' data-vals='<?php echo json_encode ($sources);?>' data-cnt='<?php echo count ($row_sources);?>' data-attrs='<?php echo json_encode ($row_sources);?>'>
    <b class='need'>數據相關輔助資料</b>
    <span><a></a></span>
  </div>

  <div>
    <span>點擊確定送出即可完成新增。</span>
    
    <div>
      <button type='submit'>確定新增</button>
      <button type='reset'>重新填寫</button>
    </div>
  </div>

</form>
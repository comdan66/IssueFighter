<div class='ti'>
  <span>
    <label for='conditions_ckb' class='icon-23'></label>
  </span>
  <span>
    <a href="<?php echo base_url ('admin', 'issue', 'add', $t->id);?>" class='btn'>新增一篇</a>
  </span>
</div>

<form class='conditions'>
<?php
  foreach ($searches as $name => $search) {
    if ($search['el'] == 'checkbox' && $search['items']) { ?>
      <b>依照<?php echo $search['text'];?>搜尋</b>
<?php foreach ($search['items'] as $item) { ?>
        <label class='checkbox'>
          <input type='checkbox' name='<?php echo $name;?>' value='<?php echo $item['value'];?>'<?php echo ($search['value'] !== null) && (!is_array ($search['value']) ? $item['value'] == $search['value'] : in_array ($item['value'], $search['value'])) ? ' checked' : '';?>>
          <span></span>
          <?php echo $item['text'];?>
        </label>
<?php }
    }
    if ($search['el'] == 'input') { ?>
      <b><?php echo $search['text'];?></b>
      <input type='<?php echo isset ($search['type']) ? $search['type'] : 'text';?>' name='<?php echo $name;?>' placeholder='依照<?php echo $search['text'];?>搜尋..' value='<?php echo $search['value'] === null ? '' : $search['value'];?>'>
<?php
    }
  } ?>

  <span>
    <button type='submit'>搜尋</button>
    <a href='<?php echo base_url ('admin', 'issues', $t->id);?>'>取消</a>
  </span>
</form>

<div class='list<?php echo $objs ? '' : ' e';?>'>
<?php if ($b = Session::getData ('_fi', true)) { ?>
    <span class='_w b'><?php echo $b;?></span>
<?php }
      if ($r = Session::getData ('_fd', true)) { ?>
    <span class='_w r'><?php echo $r;?></span>
<?php } ?>
<?php foreach ($objs as $obj) {
        $cnt = count ($obj->comments); ?>
        <a class='<?php echo $obj->status == Issue::STATUS_3 ? 'finished' : ($cnt > 10 ? 'hot' : '');?>' href='<?php echo base_url ('admin', 'issue', $obj->id);?>'>
          <span><?php echo $cnt ? $cnt > 10 ? '10+' : $cnt : '';?></span>
          <i>
            <figure class='_ic'>
              <img src='<?php echo $obj->user->avatar ();?>'>
            </figure>
          </i>
          <h3><?php echo $obj->mini_title (200);?></h3>
          <p><?php echo $obj->mini_content (200);?></p>
        </a>
<?php } ?>
</div>

<div class="pgn">
  <?php echo $pagination;?>
</div>

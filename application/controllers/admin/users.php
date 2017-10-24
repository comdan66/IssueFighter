<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2017 OA Wu Design
 * @license     http://creativecommons.org/licenses/by-nc/2.0/tw/
 */

class Users extends Admin_controller {
  private $obj = null;

  public function __construct () {
    parent::__construct ();
    $r2 = $this->uri->rsegments (2, 0);
    $r3 = $this->uri->rsegments (3, 0);

    if (in_array ($r2, array ('show')))
      if (!($r3 && ($this->obj = User::find ('one', array ('conditions' => array ('id = ?', $r3))))))
        return redirect_message (array ('admin'), array ('_fd' => '找不到該筆資料。'));
  }

  public function show ($id = 0) {
    $obj = $this->obj;
    
    if ($obj->id == User::current ()->id)
      return redirect_message (array ('admin'), array ());

    return $this->load_view (array (
        'obj' => $obj
      ));
  }
}

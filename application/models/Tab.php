<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2017 OA Wu Design
 * @license     http://creativecommons.org/licenses/by-nc/2.0/tw/
 */

class Tab extends OaModel {

  static $table_name = 'tabs';

  static $has_one = array (
  );

  static $has_many = array (
    array ('issues', 'class_name' => 'Issue', 'select' => 'id, tab_id', 'conditions' => array ('status = ?', Issue::STATUS_2)),
  );

  static $belongs_to = array (
  );

  public function __construct ($attributes = array (), $guard_attributes = true, $instantiating_via_find = false, $new_record = true) {
    parent::__construct ($attributes, $guard_attributes, $instantiating_via_find, $new_record);
  }
}
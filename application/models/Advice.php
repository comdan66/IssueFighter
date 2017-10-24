<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2017 OA Wu Design
 * @license     http://creativecommons.org/licenses/by-nc/2.0/tw/
 */

class Advice extends OaModel {

  static $table_name = 'advices';

  static $has_one = array (
  );

  static $has_many = array (
  );

  static $belongs_to = array (
  );

  const TYPE_1 = 1;
  const TYPE_2 = 2;
  const TYPE_3 = 3;

  static $typeNames = array (
    self::TYPE_1 => 'Designer',
    self::TYPE_2 => 'App RD',
    self::TYPE_3 => 'Backend RD',
  );

  public function __construct ($attributes = array (), $guard_attributes = true, $instantiating_via_find = false, $new_record = true) {
    parent::__construct ($attributes, $guard_attributes, $instantiating_via_find, $new_record);
  }
  
  public function destroy () {
    if (!isset ($this->id)) return false;
    return $this->delete ();
  }
}
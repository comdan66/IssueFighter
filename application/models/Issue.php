<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2017 OA Wu Design
 * @license     http://creativecommons.org/licenses/by-nc/2.0/tw/
 */

class Issue extends OaModel {

  static $table_name = 'issues';

  static $has_one = array (
  );

  static $has_many = array (
    array ('advices', 'class_name' => 'Advice'),
    array ('sources', 'class_name' => 'Source'),
    array ('knowns', 'class_name' => 'Known'),
    array ('comments', 'class_name' => 'Comment', 'conditions' => array ('status = ?', Comment::STATUS_2)),
    array ('all_comments', 'class_name' => 'Comment'),
  );

  static $belongs_to = array (
    array ('user', 'class_name' => 'User'),
    array ('tab', 'class_name' => 'Tab'),
  );

  const STATUS_1 = 1;
  const STATUS_2 = 2;
  const STATUS_3 = 3;

  static $statusNames = array (
    self::STATUS_1 => '刪除',
    self::STATUS_2 => '未完成',
    self::STATUS_3 => '已完成',
  );

  public function __construct ($attributes = array (), $guard_attributes = true, $instantiating_via_find = false, $new_record = true) {
    parent::__construct ($attributes, $guard_attributes, $instantiating_via_find, $new_record);
  }
  public function mini_title ($length = 50) {
    if (!isset ($this->title)) return '';
    return $length ? mb_strimwidth (remove_ckedit_tag ($this->title), 0, $length, '…','UTF-8') : remove_ckedit_tag ($this->title);
  }
  public function mini_content ($length = 100) {
    if (!isset ($this->content)) return '';
    return $length ? mb_strimwidth (remove_ckedit_tag ($this->content), 0, $length, '…','UTF-8') : remove_ckedit_tag ($this->content);
  }
  public function destroy () {
    if (!(isset ($this->id) && isset ($this->status))) return false;
    
    $this->status = Issue::STATUS_1;

    return $this->save ();
  }
}
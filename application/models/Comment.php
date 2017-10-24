<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2017 OA Wu Design
 * @license     http://creativecommons.org/licenses/by-nc/2.0/tw/
 */

class Comment extends OaModel {

  static $table_name = 'comments';

  static $has_one = array (
  );

  static $has_many = array (
    array ('likes', 'class_name' => 'CommentLike', 'conditions' => array ('status = ?', CommentLike::STATUS_3)),
    array ('unlikes', 'class_name' => 'CommentLike', 'conditions' => array ('status = ?', CommentLike::STATUS_2)),
  );

  static $belongs_to = array (
    array ('user', 'class_name' => 'User'),
    array ('issue', 'class_name' => 'Issue'),
  );

  const STATUS_1 = 1;
  const STATUS_2 = 2;

  static $statusNames = array (
    self::STATUS_1 => '刪除',
    self::STATUS_2 => '未刪除',
  );

  public function __construct ($attributes = array (), $guard_attributes = true, $instantiating_via_find = false, $new_record = true) {
    parent::__construct ($attributes, $guard_attributes, $instantiating_via_find, $new_record);
  }
  public function destroy () {
    if (!(isset ($this->id) && isset ($this->status))) return false;
    
    $this->status = Comment::STATUS_1;

    return $this->save ();
  }
}
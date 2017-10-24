<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2017 OA Wu Design
 * @license     http://creativecommons.org/licenses/by-nc/2.0/tw/
 */

class CommentLike extends OaModel {

  static $table_name = 'comment_likes';

  static $has_one = array (
  );

  static $has_many = array (
  );

  static $belongs_to = array (
    array ('comment', 'class_name' => 'Comment'),
  );

  const STATUS_1 = 1;
  const STATUS_2 = 2;
  const STATUS_3 = 3;

  static $statusNames = array (
    self::STATUS_1 => '刪除',
    self::STATUS_2 => '不喜歡',
    self::STATUS_3 => '喜歡',
  );

  public function __construct ($attributes = array (), $guard_attributes = true, $instantiating_via_find = false, $new_record = true) {
    parent::__construct ($attributes, $guard_attributes, $instantiating_via_find, $new_record);
  }
}
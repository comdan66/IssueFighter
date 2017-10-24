<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2017 OA Wu Design
 * @license     http://creativecommons.org/licenses/by-nc/2.0/tw/
 */

class Migration_Add_comment_likes extends CI_Migration {
  public function up () {
    $this->db->query (
      "CREATE TABLE `comment_likes` (
        `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
        `issue_id` int(11) unsigned NOT NULL COMMENT 'Issue ID',

        `user_id` int(11) unsigned NOT NULL DEFAULT 0 COMMENT 'User ID(作者)',
        `comment_id` int(11) unsigned NOT NULL DEFAULT 0 COMMENT 'Comment ID',
        `status` tinyint(4) unsigned NOT NULL DEFAULT 1 COMMENT '狀態，1 刪除，2 不喜歡，3 喜歡',
        
        `created_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "' COMMENT '新增時間',
        PRIMARY KEY (`id`),
        KEY `status_comment_id_index` (`status`, `comment_id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;"
    );
  }
  public function down () {
    $this->db->query (
      "DROP TABLE `comment_likes`;"
    );
  }
}
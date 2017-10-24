<?php if (!defined ('BASEPATH')) exit ('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2017 OA Wu Design
 * @license     http://creativecommons.org/licenses/by-nc/2.0/tw/
 */

class Migration_Add_issues extends CI_Migration {
  public function up () {
    $this->db->query (
      "CREATE TABLE `issues` (
        `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
        `user_id` int(11) unsigned NOT NULL DEFAULT 0 COMMENT 'User ID(作者)',
        `tab_id` int(11) unsigned NOT NULL DEFAULT 0 COMMENT 'Tab ID',
        
        `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '標題',
        `content` text NOT NULL COMMENT '內容',
        `fix` text NOT NULL COMMENT '解決方式',
        `status` tinyint(4) unsigned NOT NULL DEFAULT 1 COMMENT '狀態，1 刪除，2 未完成，3 完成',
        `pv` int(11) unsigned NOT NULL DEFAULT 0 COMMENT 'Page view',
        
        `updated_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "' COMMENT '更新時間',
        `created_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "' COMMENT '新增時間',
        PRIMARY KEY (`id`),
        KEY `id_status_index` (`id`, `status`),
        KEY `tab_id_status_index` (`tab_id`, `status`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;"
    );
  }
  public function down () {
    $this->db->query (
      "DROP TABLE `issues`;"
    );
  }
}
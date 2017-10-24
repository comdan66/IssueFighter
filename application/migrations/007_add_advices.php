<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2017 OA Wu Design
 * @license     http://creativecommons.org/licenses/by-nc/2.0/tw/
 */

class Migration_Add_advices extends CI_Migration {
  public function up () {
    $this->db->query (
      "CREATE TABLE `advices` (
        `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
        `issue_id` int(11) unsigned NOT NULL COMMENT 'Article ID',
        
        `type` tinyint(4) unsigned NOT NULL DEFAULT 1 COMMENT '類型，1 Designer，2 App RD，3 Backend RD',
        `content` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '內容',

        `updated_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "' COMMENT '更新時間',
        `created_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "' COMMENT '新增時間',
        PRIMARY KEY (`id`),
        KEY `issue_id_type_index` (`issue_id`, `type`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;"
    );
  }
  public function down () {
    $this->db->query (
      "DROP TABLE `advices`;"
    );
  }
}
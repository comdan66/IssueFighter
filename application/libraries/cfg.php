<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2017 OA Wu Design
 * @license     http://creativecommons.org/licenses/by-nc/2.0/tw/
 */

class Cfg {
  public function __construct () {
  }

  public static function __callStatic ($name, $arguments) {
    if (!function_exists ('config') && ($CI =& get_instance ()))
      $CI->load->helper ('oa');

    $is_cache = preg_match ("|^(_)|", $name) && ($name = preg_replace ("|^(_)|", '', $name)) ? false : true;

    return config ($arguments, $name, $is_cache);
  }
}
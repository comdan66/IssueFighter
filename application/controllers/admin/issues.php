<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2017 OA Wu Design
 * @license     http://creativecommons.org/licenses/by-nc/2.0/tw/
 */

class Issues extends Admin_controller {
  private $obj = null;

  public function __construct () {
    parent::__construct ();
    $r2 = $this->uri->rsegments (2, 0);
    $r3 = $this->uri->rsegments (3, 0);

    if (in_array ($r2, array ('edit', 'update', 'show', 'destroy', 'status')))
      if (!($r3 && ($this->obj = Issue::find ('one', array ('conditions' => array ('id = ? AND status != ?', $r3, Issue::STATUS_1))))))
        return redirect_message (array ('admin'), array ('_fd' => '找不到該筆資料。'));

    if ($this->obj && in_array ($r2, array ('edit', 'update', 'destroy', 'status')) && $this->obj->user_id != User::current ()->id)
      return redirect_message (array ('admin'), array ('_fd' => '這不是您的 issue，所以您不可以編輯喔。'));
    
    if ($this->obj)
      $this->add_param ('nurl', base_url ('admin', 'issues', $this->obj->tab_id));

    if (in_array ($r2, array ('edit', 'add')))
      $this->add_js (res_url ('res', 'js', 'ckeditor_d2015_05_18', 'ckeditor.js'), false)
           ->add_js (res_url ('res', 'js', 'ckeditor_d2015_05_18', 'config.js'), false)
           ->add_js (res_url ('res', 'js', 'ckeditor_d2015_05_18', 'adapters', 'jquery.js'), false)
           ->add_js (res_url ('res', 'js', 'ckeditor_d2015_05_18', 'plugins', 'tabletools', 'tableresize.js'), false)
           ->add_js (res_url ('res', 'js', 'ckeditor_d2015_05_18', 'plugins', 'dropler', 'dropler.js'), false);
  }

  public function index ($t = null, $offset = 0) {
    if (!($t && ($t = Tab::find_by_id ($t)))) $t = Tab::first ();
    
    $searches = array (
        'title'     => array ('el' => 'input', 'text' => '標題', 'sql' => 'title LIKE ?'),
        'content'   => array ('el' => 'input', 'text' => '內容', 'sql' => 'content LIKE ?'),
        'user_id[]' => array ('el' => 'checkbox', 'text' => '作者', 'sql' => 'user_id IN (?)', 'items' => array_map (function ($u) { return array ('text' => $u->name, 'value' => $u->id); }, User::all ())),
        'status[]' => array ('el' => 'checkbox', 'text' => '狀態', 'sql' => 'status IN (?)', 'items' => array_map (function ($u) { return array ('text' => Issue::$statusNames[$u], 'value' => $u); }, array (Issue::STATUS_2, Issue::STATUS_3))),
      );

    $configs = array ('admin', 'issues', $t->id, '%s');
    $objs = conditions ($searches, $configs, $offset, 'Issue', array ('order' => 'id DESC', 'include' => array ('user', 'comments')), function ($conditions) use ($t) {
      OaModel::addConditions ($conditions, 'tab_id = ? AND status != ?', $t->id, Issue::STATUS_1);
      return $conditions;
    });
         

    return $this->load_view (array (
        't' => $t,
        'nurl' => base_url ('admin', 'issues', $t->id),
        'objs' => $objs,
        'total' => $offset,
        'searches' => $searches,
        'pagination' => $this->_get_pagination ($configs),
      ));
  }
  public function add ($t = null) {
    if (!($t && ($t = Tab::find_by_id ($t)))) $t = Tab::first ();

    $posts = Session::getData ('posts', true);
    
    $knowns = isset ($posts['knowns']) ? $posts['knowns'] : array ();
    $row_knowns = array (array ('el' => 'input', 'type' => 'text', 'name' => 'knowns', 'key' => 'content', 'placeholder' => '請輸入目前可能造成的問題..'));
    
    $sources = isset ($posts['sources']) ? $posts['sources'] : array ();
    $row_sources = array (array ('el' => 'input', 'type' => 'text', 'name' => 'sources', 'key' => 'content', 'placeholder' => '請輸入相關輔助資料..'));

    return $this->load_view (array (
        't' => $t,
        'nurl' => base_url ('admin', 'issues', $t->id),
        'posts' => $posts,
        'knowns' => $knowns,
        'row_knowns' => $row_knowns,
        'sources' => $sources,
        'row_sources' => $row_sources,
      ));
  }
  public function create ($t = null) {
    if (!($t && ($t = Tab::find_by_id ($t)))) $t = Tab::first ();

    if (!$this->has_post ())
      return redirect_message (array ('admin', 'issue', 'add', $t->id), array ('_fd' => '非 POST 方法，錯誤的頁面請求。'));

    $posts = OAInput::post ();
    $posts['pv'] = 0;
    $posts['fix'] = OAInput::post ('fix', false);
    $posts['user_id'] = User::current ()->id;
    $posts['content'] = OAInput::post ('content', false);

    $validation = function (&$posts) {
      if (!(isset ($posts['status']) && is_string ($posts['status']) && is_numeric ($posts['status'] = trim ($posts['status'])) && in_array ($posts['status'], array (Issue::STATUS_2, Issue::STATUS_3)))) $posts['status'] = Issue::STATUS_2;
      if (!(isset ($posts['title']) && is_string ($posts['title']) && ($posts['title'] = trim ($posts['title'])))) return '「標題」未填寫或格式錯誤！';
      if (!(isset ($posts['content']) && is_string ($posts['content']) && ($posts['content'] = trim ($posts['content'])))) return '「內容」未填寫或格式錯誤！';
      if (!(isset ($posts['tab_id']) && is_string ($posts['tab_id']) && ($posts['tab_id'] = trim ($posts['tab_id'])) && ($posts['tab_id'] = Tab::find_by_id ($posts['tab_id'])) && ($posts['tab_id'] = $posts['tab_id']->id))) return '「類別」未填寫或格式錯誤！';
      if (isset ($posts['fix']) && !(is_string ($posts['fix']) && ($posts['fix'] = trim ($posts['fix'])))) $posts['fix'] = '';

      $posts['knowns'] = isset ($posts['knowns']) && is_array ($posts['knowns']) && $posts['knowns'] ? array_values (array_filter (array_map (function ($known) { if (!(isset ($known['content']) && is_string ($known['content']) && ($known['content'] = trim ($known['content'])))) $known['content'] = ''; return $known; }, $posts['knowns']), function ($known) { return $known['content']; })) : array ();
      $posts['sources'] = isset ($posts['sources']) && is_array ($posts['sources']) && $posts['sources'] ? array_values (array_filter (array_map (function ($source) { if (!(isset ($source['content']) && is_string ($source['content']) && ($source['content'] = trim ($source['content'])))) $source['content'] = ''; return $source; }, $posts['sources']), function ($source) { return $source['content']; })) : array ();

      $posts['advices'] = isset ($posts['advices']) && is_array ($posts['advices']) && $posts['advices'] ? array_values(array_filter (array_map (function ($key) use ($posts) {
        return !(isset ($posts['advices'][$key]) && is_string ($posts['advices'][$key]) && ($posts['advices'][$key] = trim ($posts['advices'][$key]))) ? null : array ('content' => $posts['advices'][$key], 'type' => $key);
      }, array_keys (Advice::$typeNames)))) : array ();
    };

    if (($msg = $validation ($posts)) || (!(Issue::transaction (function () use (&$obj, $posts) { return verifyCreateOrm ($obj = Issue::create (array_intersect_key ($posts, Issue::table ()->columns))); }) && $obj) && ($msg = '資料庫處理錯誤！')))
      return redirect_message (array ('admin', 'issue', 'add', $t->id), array ('_fd' => $msg, 'posts' => $posts));

    if ($posts['advices'])
      foreach ($posts['advices'] as $i => $advice)
        Advice::transaction (function () use ($i, $advice, $obj) { return verifyCreateOrm (Advice::create (array_intersect_key (array_merge ($advice, array ('issue_id' => $obj->id)), Advice::table ()->columns))); });
    
    if ($posts['knowns'])
      foreach ($posts['knowns'] as $i => $known)
        Known::transaction (function () use ($i, $known, $obj) { return verifyCreateOrm (Known::create (array_intersect_key (array_merge ($known, array ('issue_id' => $obj->id)), Known::table ()->columns))); });
    
    if ($posts['sources'])
      foreach ($posts['sources'] as $i => $source)
        Source::transaction (function () use ($i, $source, $obj) { return verifyCreateOrm (Source::create (array_intersect_key (array_merge ($source, array ('issue_id' => $obj->id)), Source::table ()->columns))); });
    
    return redirect_message (array ('admin', 'issue', $obj->id), array ('_fi' => '新增成功！'));
  }
  public function edit ($id = 0) {
    $posts = Session::getData ('posts', true);

    $knowns = isset ($posts['knowns']) ? $posts['knowns'] : array_map (function ($known) { return array ('content' => $known->content); }, $this->obj->knowns);
    $row_knowns = array (array ('el' => 'input', 'type' => 'text', 'name' => 'knowns', 'key' => 'content', 'placeholder' => '請輸入目前可能造成的問題..'));
    
    $sources = isset ($posts['sources']) ? $posts['sources'] : array_map (function ($source) { return array ('content' => $source->content); }, $this->obj->sources);
    $row_sources = array (array ('el' => 'input', 'type' => 'text', 'name' => 'sources', 'key' => 'content', 'placeholder' => '請輸入相關輔助資料..'));

    return $this->load_view (array (
        'obj' => $this->obj,
        'posts' => $posts,
        'knowns' => $knowns,
        'row_knowns' => $row_knowns,
        'sources' => $sources,
        'row_sources' => $row_sources,
      ));
  }
  public function update ($id = 0) {
    $obj = $this->obj;
    
    if (!$this->has_post ())
      return redirect_message (array ('admin', 'issue', $obj->id, 'edit'), array ('_fd' => '非 POST 方法，錯誤的頁面請求。'));

    $posts = OAInput::post ();
    $posts['fix'] = OAInput::post ('fix', false);
    $posts['content'] = OAInput::post ('content', false);

    $validation = function (&$posts) {
      if (isset ($posts['status']) && !(is_string ($posts['status']) && is_numeric ($posts['status'] = trim ($posts['status'])) && in_array ($posts['status'], array (Issue::STATUS_2, Issue::STATUS_3)))) $posts['status'] = Issue::STATUS_2;
      if (isset ($posts['title']) && !(is_string ($posts['title']) && ($posts['title'] = trim ($posts['title'])))) return '「標題」未填寫或格式錯誤！';
      if (isset ($posts['content']) && !(is_string ($posts['content']) && ($posts['content'] = trim ($posts['content'])))) return '「內容」未填寫或格式錯誤！';
      if (isset ($posts['tab_id']) && !(is_string ($posts['tab_id']) && ($posts['tab_id'] = trim ($posts['tab_id'])) && ($posts['tab_id'] = Tab::find_by_id ($posts['tab_id'])) && ($posts['tab_id'] = $posts['tab_id']->id))) return '「類別」未填寫或格式錯誤！';
      if (isset ($posts['fix']) && !(is_string ($posts['fix']) && ($posts['fix'] = trim ($posts['fix'])))) $posts['fix'] = '';

      $posts['knowns'] = isset ($posts['knowns']) && is_array ($posts['knowns']) && $posts['knowns'] ? array_values (array_filter (array_map (function ($known) { if (!(isset ($known['content']) && is_string ($known['content']) && ($known['content'] = trim ($known['content'])))) $known['content'] = ''; return $known; }, $posts['knowns']), function ($known) { return $known['content']; })) : array ();
      $posts['sources'] = isset ($posts['sources']) && is_array ($posts['sources']) && $posts['sources'] ? array_values (array_filter (array_map (function ($source) { if (!(isset ($source['content']) && is_string ($source['content']) && ($source['content'] = trim ($source['content'])))) $source['content'] = ''; return $source; }, $posts['sources']), function ($source) { return $source['content']; })) : array ();

      $posts['advices'] = isset ($posts['advices']) && is_array ($posts['advices']) && $posts['advices'] ? array_values(array_filter (array_map (function ($key) use ($posts) {
        return !(isset ($posts['advices'][$key]) && is_string ($posts['advices'][$key]) && ($posts['advices'][$key] = trim ($posts['advices'][$key]))) ? null : array ('content' => $posts['advices'][$key], 'type' => $key);
      }, array_keys (Advice::$typeNames)))) : array ();
    };

    if ($msg = $validation ($posts))
      return redirect_message (array ('admin', 'issue', $obj->id, 'edit'), array ('_fd' => $msg, 'posts' => $posts));

    if ($columns = array_intersect_key ($posts, $obj->table ()->columns))
      foreach ($columns as $column => $value)
        $obj->$column = $value;

    if (!Issue::transaction (function () use ($obj) { return $obj->save (); }))
      return redirect_message (array ('admin', 'issue', $obj->id, 'edit'), array ('_fd' => '更新失敗，資料庫處理錯誤！', 'posts' => $posts));

    if ($obj->advices)
      foreach ($obj->advices as $advice)
        Advice::transaction (function () use ($advice) { return $advice->destroy (); });

    if ($posts['advices'])
      foreach ($posts['advices'] as $i => $advice)
        Advice::transaction (function () use ($i, $advice, $obj) { return verifyCreateOrm (Advice::create (array_intersect_key (array_merge ($advice, array ('issue_id' => $obj->id)), Advice::table ()->columns))); });
    
    if ($obj->knowns)
      foreach ($obj->knowns as $known)
        Known::transaction (function () use ($known) { return $known->destroy (); });

    if ($posts['knowns'])
      foreach ($posts['knowns'] as $i => $known)
        Known::transaction (function () use ($i, $known, $obj) { return verifyCreateOrm (Known::create (array_intersect_key (array_merge ($known, array ('issue_id' => $obj->id)), Known::table ()->columns))); });
    
    if ($obj->sources)
      foreach ($obj->sources as $source)
        Source::transaction (function () use ($source) { return $source->destroy (); });

    if ($posts['sources'])
      foreach ($posts['sources'] as $i => $source)
        Source::transaction (function () use ($i, $source, $obj) { return verifyCreateOrm (Source::create (array_intersect_key (array_merge ($source, array ('issue_id' => $obj->id)), Source::table ()->columns))); });
    
    return redirect_message (array ('admin', 'issue', $obj->id), array ('_fi' => '修改成功！'));
  }
  public function show ($id = 0) {
    $obj = $this->obj;

    $obj->pv = $obj->pv + 1;
    $obj->save ();

    return $this->load_view (array (
        'obj' => $obj
      ));
  }
  public function destroy ($id = 0) {
    $obj = $this->obj;
    $tab_id = $obj->tab_id;
    
    if (!Issue::transaction (function () use ($obj) { return $obj->destroy (); }))
      return redirect_message (array ('admin', 'issue', $obj->id), array ('_fd' => '資料庫處理錯誤！'));

    return redirect_message (array ('admin', 'issues', $tab_id), array ('_fi' => '刪除成功！'));
  }
  public function status ($id = 0) {
    $obj = $this->obj;

    $obj->status = $obj->status == Issue::STATUS_2 ? Issue::STATUS_3 : Issue::STATUS_2;
    
    if (!Issue::transaction (function () use ($obj) { return $obj->save (); }))
      return redirect_message (array ('admin', 'issue', $obj->id), array ('_fd' => '資料庫處理錯誤！'));

    return redirect_message (array ('admin', 'issue', $obj->id), array ('_fi' => '設定成功！'));
  }
}

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2017 OA Wu Design
 * @license     http://creativecommons.org/licenses/by-nc/2.0/tw/
 */

class Comments extends Admin_controller {

  public function like ($issue_id = 0, $comment_id = 0, $status = CommentLike::STATUS_1) {
    if (!$this->has_post ())
      return $this->output_error_json ('非 POST 方法，錯誤的頁面請求。');
    
    $posts = array_merge (array (
        'user_id' => User::current ()->id,
        'issue_id' => $issue_id,
        'comment_id' => $comment_id,
        'status' => $status,
      ), OAInput::post ());

    $validation = function (&$posts) {
      if (!(isset ($posts['status']) && is_string ($posts['status']) && is_numeric ($posts['status'] = trim ($posts['status'])) && in_array ($posts['status'], array_keys (CommentLike::$statusNames)))) return '「狀態」錯誤！';
      if (!(isset ($posts['issue_id']) && is_string ($posts['issue_id']) && ($posts['issue_id'] = trim ($posts['issue_id'])) && ($posts['issue_id'] = Issue::find ('one', array ('conditions' => array ('id = ? AND status != ?', $posts['issue_id'], Issue::STATUS_1)))) && ($posts['issue_id'] = $posts['issue_id']->id))) return '找不到該筆 Issue，請重新整理一下吧！';
      if (!(isset ($posts['comment_id']) && is_string ($posts['comment_id']) && ($posts['comment_id'] = trim ($posts['comment_id'])) && ($posts['comment_id'] = Comment::find ('one', array ('conditions' => array ('id = ? AND status != ?', $posts['comment_id'], Comment::STATUS_1)))) && ($posts['comment_id'] = $posts['comment_id']->id))) return '找不到該筆資料，請重新整理一下吧！';
    };

    if ($msg = $validation ($posts))
      return $this->output_error_json ($msg);

    $func = ($obj = CommentLike::find ('one', array ('conditions' => array ('user_id = ? AND comment_id = ?', $posts['user_id'], $posts['comment_id'])))) && ($obj->status = ($obj->status == $posts['status'] ? CommentLike::STATUS_1 : $posts['status'])) ? function () use ($obj) { return $obj->save (); } : function () use (&$obj, $posts) { return verifyCreateOrm ($obj = CommentLike::create (array_intersect_key ($posts, CommentLike::table ()->columns))); };

    if (!CommentLike::transaction ($func))
      return $this->output_error_json ('資料庫處理錯誤！');

    return $this->output_json (array (
        'status' => $obj->status != CommentLike::STATUS_1 ? $obj->status == CommentLike::STATUS_3 ? 'like' : 'unlike' : '',
        'like' => count ($obj->comment->likes),
        'unlike' => count ($obj->comment->unlikes),
      ));
  }
  public function destroy ($id = 0) {
    if (!($id && ($obj = Comment::find ('one', array ('conditions' => array ('id = ? AND status != ?', $id, Comment::STATUS_1))))))
      return $this->output_error_json ('找不到該筆資料，或者此留言已經刪除，請重新整理一下吧！');
    
    if ($obj->user_id != User::current ()->id)
      return $this->output_error_json ('您沒有權限可以刪除別人的留言！');

    if (!Comment::transaction (function () use ($obj) { return $obj->destroy (); }))
      return $this->output_error_json ('資料庫處理錯誤！');

    return $this->output_json ('刪除成功！');
  }
  public function create () {
    if (!$this->has_post ())
      return $this->output_error_json ('非 POST 方法，錯誤的頁面請求。');

    $posts = OAInput::post ();
    $posts['user_id'] = User::current ()->id;
    $posts['status'] = Comment::STATUS_2;
    $posts['content'] = OAInput::post ('content', false);

    $validation = function (&$posts) {
      if (!(isset ($posts['content']) && is_string ($posts['content']) && ($posts['content'] = trim ($posts['content'])))) return '「內容」未填寫或格式錯誤！';
      if (!(isset ($posts['issue_id']) && is_string ($posts['issue_id']) && ($posts['issue_id'] = trim ($posts['issue_id'])) && ($posts['issue_id'] = Issue::find ('one', array ('conditions' => array ('id = ? AND status != ?', $posts['issue_id'], Issue::STATUS_1)))) && ($posts['issue_id'] = $posts['issue_id']->id))) return '「Issue」未填寫或格式錯誤！';
    };

    if (($msg = $validation ($posts)) || (!(Comment::transaction (function () use (&$obj, $posts) { return verifyCreateOrm ($obj = Comment::create (array_intersect_key ($posts, Comment::table ()->columns))); }) && $obj) && ($msg = '資料庫處理錯誤！')))
      return $this->output_error_json ($msg);

    return $this->output_json (array (
        'id' => $obj->id,
        'like' => base_url ('admin', 'comments', 'like', $obj->issue_id, $obj->id, CommentLike::STATUS_3),
        'unlike' => base_url ('admin', 'comments', 'like', $obj->issue_id, $obj->id, CommentLike::STATUS_2),
        'delete' => base_url ('admin', 'comments', $obj->id),
        'user' => array (
            'id' => $obj->user->id,
            'name' => $obj->user->name,
            'avatar' => $obj->user->avatar (),
          ),
        'content' => $obj->content,
        'created_at' => $obj->created_at->format ('Y-m-d H:i:s')
      ));
  }
}

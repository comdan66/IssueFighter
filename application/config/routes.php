<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
Route::root ('main');
Route::get ('/login', 'platform@login');
Route::get ('/logout', 'platform@logout');

Route::get ('admin', 'admin/main@index');

Route::group ('admin', function () {
  Route::get ('issues/(:id)', 'issues@index($1)');
  Route::get ('issues/(:id)/(:num)', 'issues@index($1, $2)');

  Route::get ('issue/add', 'issues@add()');
  Route::get ('issue/add/(:id)', 'issues@add($1)');
  Route::post ('issue/(:id)', 'issues@create($1)');

  Route::get ('issue/(:id)/edit', 'issues@edit($1)');
  Route::put ('issue/(:id)', 'issues@update($1)');
  Route::delete ('issue/(:id)', 'issues@destroy($1)');

  Route::get ('issue/(:id)', 'issues@show($1)');
  Route::get ('issue/(:id)/status', 'issues@status($1)');



  Route::post ('comments', 'comments@create()');
  Route::post ('comments/like', 'comments@like()');
  Route::delete ('comments/(:id)', 'comments@destroy($1)');

  Route::get ('users/(:id)', 'users@show($1)');
});
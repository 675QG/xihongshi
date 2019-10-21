<?php
use think\Route;

Route::rule('test','index/index/test');

Route::rule('/','index/index/index');
Route::rule('login','index/login/login');
Route::rule('register','index/register/register');
Route::rule('logout','index/login/logout');
Route::rule('forget','index/user/forget_password');
Route::rule('send_email_captcha','index/user/send_email_captcha');
Route::rule('captcha_check','index/user/captcha_check');
Route::rule('change_password','index/user/change_password');
Route::rule('mark','index/mark/mark');
Route::rule('save_mark','index/mark/save_mark');
Route::rule('check','index/mark/check');
Route::rule('check2','index/mark/check2');
Route::rule('check3','index/mark/check3');
Route::rule('update_mark','index/mark/update_mark');
Route::rule('send_demand','index/index/send_demand');
Route::rule('about','index/index/about');
Route::rule('imark','index/index/imark');

Route::rule('makemoney','makemoney/index/index');
Route::rule('task','makemoney/task/task');
Route::rule('guild','makemoney/guild/guild');
Route::rule('study','makemoney/study/study');
Route::rule('user','makemoney/user/user');
Route::rule('join_guild','makemoney/guild/join_guild');
Route::rule('quit_guild','makemoney/guild/quit_guild');
Route::rule('create_guild','makemoney/guild/create_guild');
Route::rule('change_guild','makemoney/guild/change_guild');
Route::rule('upload_guild_img','makemoney/guild/upload_guild_img');
Route::rule('guild_search','makemoney/guild/guild_search');
Route::rule('join_check','makemoney/guild/join_check');
Route::rule('receive_task','makemoney/task/receive_task');
Route::rule('task_manage','makemoney/task/task_manage');
Route::rule('my_task_delete','makemoney/task/my_task_delete');
Route::rule('get_task_center','makemoney/task/get_task_center');
Route::rule('update_user_info','makemoney/user/update_user_info');
Route::rule('update_user_name','makemoney/user/update_user_name');
Route::rule('send_change_email_captcha','makemoney/user/send_change_email_captcha');
Route::rule('update_user_email','makemoney/user/update_user_email');
Route::rule('update_change_password','makemoney/user/change_password');
Route::rule('img_data','makemoney/task/img_data');
Route::rule('guild_manage','makemoney/guild/guild_manage');
Route::rule('set_check','makemoney/guild/set_check');
Route::rule('re_create_guild','makemoney/guild/re_create_guild');


Route::rule('excel','makemoney/task/excel');
Route::rule('task_check','makemoney/task/task_check');
Route::rule('demand','demand/index/index');
Route::rule('publish','demand/publish/publish');
Route::rule('result','demand/resulttask/result_task');
Route::rule('change_task','demand/publish/change_task');
Route::rule('dele_task','demand/publish/dele_task');

Route::rule('wxpay','demand/wxpay/wxpay');


Route::rule('publish_task','demand/publish/publish_task');
Route::rule('upload_doc','demand/publish/upload_doc');


Route::rule('admin','admin/index/index');
Route::rule('admin_guild','admin/guild/guild');
Route::rule('admin_task','admin/task/task');
Route::rule('admin_login','admin/login/login');
Route::rule('admin_logout','admin/login/logout');
Route::rule('admin_guild_agree','admin/guild/guild_agree');
Route::rule('admin_task_agree','admin/task/task_agree');
Route::rule('admin_demand_done','admin/index/demand_done');
Route::rule('admin_guild_detail','admin/guild/admin_guild_detail');
Route::rule('admin_task_check','admin/task/admin_task_check');




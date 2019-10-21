<?php
namespace app\index\controller;
use think\Controller;
use app\index\model\User;

class Register extends Controller
{
    public function register()
    {
     if(request()->isAjax())
     {
         $user_name = request()->post('user_name');
         $password = request()->post('password');
         $captcha = request()->post('captcha');
         if(!captcha_check($captcha))
         {
             $result =  [
                 'code'=>3,
                 'msg'=>'亲亲，验证码错误哦~'
             ];
            return $result;
         }
         $password = md5($password);
         $user = new User();
         $has_user = $user->has_user($user_name);
         if(!$has_user)
         {
         $add_user = $user->add_user($user_name,$password);
         if($add_user)
         {
         $result =  [
             'code'=>1,
             'msg'=>'注册成功！'
        ]; 
         }
         else
         {
        $result =  [
            'code'=>0,
            'msg'=>'亲亲，注册失败啦~！'
       ]; 
         }

         }
     else
     {
        $result =  [
            'code'=>2,
            'msg'=>'亲亲，用户名已存在哦！'
       ]; 
    }
     return $result;
     }
    }
}
<?php
namespace app\index\controller;
use think\Controller;
use app\index\controller\User;
use think\Session;

class Login extends Controller
{
    public function login()
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
            $user_id = $user->has_user($user_name); 
            if($user_id)
            {
                $pass_check = $user->pass_check($user_id,$password);
                if($pass_check)
                {
                    $result =  [
                        'code'=>1,
                        'msg'=>'登录成功！'
                 ]; 
                 Session::set('user_id',$user_id);
                }
                else
                {
                    $result =  [
                        'code'=>0,
                        'msg'=>'亲亲，密码错误哦~'
                 ];
                }
            }
            else
            {
                $result =  [
                    'code'=>2,
                    'msg'=>'亲亲，用户名不存在哦~'
             ];
            }
            return $result;
        }
       return $this->fetch('login');
    }


    public function is_login(){
        if(Session::has('user_id'))
        {
            $user_id = Session::get('user_id');
            return $user_id;
        }
        else
        {
            header('location:login');
        }

    }


    
    public function is_login_ajax(){
        if(Session::has('user_id'))
        {
            $user_id = Session::get('user_id');
            return $user_id;
        }
        else
        {
            return 0;
        }

    }



    public function logout(){
      Session::delete('user_id');
      header('location:login');
    }

    public function wx_login()
    {
        
    }
}

<?php
namespace app\makemoney\controller;
use think\Controller;
use app\index\controller\Login;
use app\makemoney\model\User as usermodel;
use think\Session;

class User extends Controller
{
    public function user()
    {
        $login = new Login();
        $user_id = $login->is_login();
        $usermodel = new usermodel();
        $user = $usermodel->get_user($user_id);
        $this->assign('user',$user);
        return $this->fetch('user');
    }


    public function get_user($user_id){
        $usermodel = new usermodel();
        $user = $usermodel->get_user($user_id);
        return $user;
    }


   public function update_user_info()
   {
       $login = new Login();
       $user_id = $login->is_login_ajax();
       if(!$user_id)
       {
        $result = [
            'code'=>10,
            'msg'=>'亲亲，请先登录哦~'
        ];
        return $result;
       }
       $usermodel = new usermodel();
       $user = $usermodel->get_user($user_id);
       $user_name_check = $user[0]['user_name'];
       $phone_check = $user[0]['phone'];
       $email_check = $user[0]['email'];

       $user_name = request()->post('user_name');
       $phone = request()->post('phone');
       $email = request()->post('email');
       if($user_name == $user_name_check && $phone == $phone_check && $email == $email_check)
       {
        $result = [
            'code'=>2,
            'msg'=>'亲亲，您没有更改任何资料哦~'
        ];
        return $result;
       }
       $update_user_info = $usermodel->update_user_info($user_id,$user_name,$phone,$email);
       if($update_user_info)
       {
           $result = [
               'code'=>1,
               'msg'=>'更新成功！'
           ];
       }
       else
       {
           $result = [
               'code'=>0,
               'msg'=>'亲亲，更新失败哦~'
           ];

       }
       return $result;
   }

   public function update_user_name()
   {
    $login = new Login();
    $user_id = $login->is_login_ajax();
    if(!$user_id)
    {
     $result = [
         'code'=>10,
         'msg'=>'亲亲，请先登录哦~'
     ];
     return $result;
    }
    $user_name = request()->post('user_name');
    $name = request()->post("name");
    $usermodel = new usermodel();
    $user = $usermodel->get_user_inname($user_name);
    if($user)
    {
        $user_id_t = $user[0]["user_id"];
        $name_t = $user[0]["name"];
        if($user_id == $user_id_t && $name_t == $name)
        {
            $result = [
                'code'=>2,
                'msg'=>'亲亲，您没有更改任何资料哦~'
            ];
            return $result;
        }
        else if($user_id != $user_id_t)
        {
            $result = [
                'code'=>3,
                'msg'=>'亲亲，用户名已存在哦~'
            ];
            return $result;
        }
        else
        {
            $update_user_name = $usermodel->update_user_name($user_id,$user_name,$name);
            if($update_user_name)
            {
                $result = [
                    'code'=>1,
                    'msg'=>'资料修改成功！'
                ];
                return $result;
    
            }
            else
            {
                $result = [
                    'code'=>0,
                    'msg'=>'亲亲，资料修改失败哦~'
                ];
                return $result;
            }
        }
    }
    else
    {
        $update_user_name = $usermodel->update_user_name($user_id,$user_name,$name);
        if($update_user_name)
        {
            $result = [
                'code'=>1,
                'msg'=>'资料修改成功！'
            ];
            return $result;

        }
        else
        {
            $result = [
                'code'=>0,
                'msg'=>'亲亲，资料修改失败哦~'
            ];
            return $result;
        }
    }
   }

   public function send_change_email_captcha()
   {
       $login = new Login();
      $user_id = $login->is_login_ajax();
      if(!$user_id)
      {
       $result = [
         'code'=>10,
         'msg'=>'亲亲，请先登录哦~'
      ];
      return $result;
    }
       $toemail = request()->post("email");
       $usermodel = new usermodel();
       $user_id_t = $usermodel->has_email($toemail);
       if($user_id_t)
       {
           if($user_id_t == $user_id)
           {
            $result = [
                'code'=>2,
                'msg'=>'亲亲，您没有更改任何资料哦~'
            ];
           }
           else
           {
            $result = [
                'code'=>3,
                'msg'=>'亲亲，邮箱已存在哦~'
            ];
           }
           return $result;
       }
       $captcha = rand(100000,999999);
       $name='西红柿数据';
       $subject='西红柿数据';
       $content='【西红柿数据】亲亲，您的验证码是：'.$captcha;
       $send_email_captcha = send_mail($toemail,$name,$subject,$content);
       if($send_email_captcha)
       {
           Session::set('captcha',$captcha);
           $result = [
               "code"=>1,
               "msg"=>"验证码发送成功！"
           ];
       }
       else
       {
           $result = [
               "code"=>0,
               "msg"=>"亲亲，验证码发送失败哦~"
           ];
       }
       return $result;
   }



   public function update_user_email()
   {
    $login = new Login();
    $user_id = $login->is_login_ajax();
    if(!$user_id)
    {
     $result = [
         'code'=>10,
         'msg'=>'亲亲，请先登录哦~'
     ];
     return $result;
    }
    $email = request()->post('email');
    $captcha = request()->post('captcha');
    $usermodel = new usermodel();
    $user = $usermodel->get_user_inemail($email);
    if($user)
    {
        $user_id_t = $user[0]["user_id"];
        if($user_id == $user_id_t)
        {
            $result = [
                'code'=>2,
                'msg'=>'亲亲，您没有更改任何资料哦~'
            ];
            return $result;
        }
        else
        {
            $result = [
                'code'=>3,
                'msg'=>'亲亲，邮箱已存在哦~'
            ];
            return $result;

        }

    }
    else
    {
        if(Session::has("captcha"))
        {
            $captcha_c = Session::get("captcha");
            if($captcha_c == $captcha)
            {
                Session::delete("captcha");
                $update_user_email = $usermodel->update_user_email($user_id,$email);
                if($update_user_email)
                {
                    $result = [
                        'code'=>1,
                        'msg'=>'邮箱修改成功！'
                    ];
                    return $result;
        
                }
                else
                {
                    $result = [
                        'code'=>0,
                        'msg'=>'亲亲，邮箱修改失败哦~'
                    ];
                    return $result;
                }
            }
            else
            {
                $result = [
                    "code"=>0,
                    "msg"=>"亲亲，验证码错误哦~"
                ];
                return $result;
            }
        }
        else
        {
            $result = [
                "code"=>0,
                "msg"=>"亲亲，验证码错误哦~"
            ];
            return $result;

        }
    }

   }


   public function change_password()
   {
     $login = new Login();
     $user_id = $login->is_login_ajax();
     if(!$user_id)
     {
      $result = [
         'code'=>10,
         'msg'=>'亲亲，请先登录哦~'
     ];
     return $result;
    }
       $old_password = request()->post("old_password");
       $new_password = request()->post("new_password");
       $renew_password = request()->post("renew_password");
       $old_password = md5($old_password);
       $new_password = md5($new_password);
       $renew_password = md5($renew_password);
       if($new_password != $renew_password)
       {
        $result = [
            "code"=>2,
            "msg"=>"亲亲，两次密码输入不一样哦~"
        ];
        return $result;
       }

       $usermodel = new usermodel();
       $check_password = $usermodel->check_password($user_id,$old_password);
       if($check_password)
       {
           $update_password = $usermodel->update_password($user_id,$new_password);
           if($update_password)
           {
               $result = [
                   "code"=>1,
                   "msg"=>"修改密码成功！"
               ];

           }
           else
           {
            $result = [
                "code"=>0,
                "msg"=>"亲亲，修改密码失败哦~"
            ];
           }
           return $result;
       }
       else
       {
             $result = [
                "code"=>3,
                "msg"=>"亲亲，原密码输入错误哦~"
            ];
            return $result;
       }
   }
}

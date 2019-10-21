<?php
namespace app\index\controller;
use think\Controller;
use app\index\model\User as usermodel;
use think\Session;

class User extends Controller
{
    public function has_user()
    {
     $user_name = request()->post('user_name');
     $user = new usermodel();
     $user_id = $user->has_user($user_name);
     return $user_id;
    }

    public function pass_check($user_id,$user_name){
        $user = new usermodel();
        $pass_check = $user->pass_check($user_id,$user_name);
        return $pass_check;
    }

    public function get_user_name($user_id)
    {
        $user = new usermodel();
        $user_name = $user->get_user_name($user_id);
        return $user_name;

    }

    public function forget_password()
    {
      return  $this->fetch('forget_password');
    }

    public function send_email_captcha()
    {
        $toemail = request()->post("email");
        $usermodel = new usermodel();
        $user_id = $usermodel->has_email($toemail);
        if(!$user_id)
        {
            $result = [
                "code"=>2,
                "msg"=>"亲亲，邮箱不存在哦~"
            ];
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


    public function captcha_check()
    {
        $email = request()->post("email");
        $captcha = request()->post("captcha");
        $usermodel = new usermodel();
        $user_id = $usermodel->has_email($email);
        if(!$user_id)
        {
            $result = [
                "code"=>2,
                "msg"=>"亲亲，邮箱不存在哦~"
            ];
            return $result;
        }
        if(Session::has("captcha"))
        {
            $captcha_c = Session::get("captcha");
            if($captcha_c == $captcha)
            {
                Session::delete("captcha");
                $result = [
                    "code"=>1,
                    "msg"=>"验证码正确"
                ];
                return $result;

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


    public function change_password()
    {
        $email = request()->post("email");
        $password = request()->post("password");
        $repassword = request()->post("repassword");
        if($password != $repassword)
        {
            $result = [
                "code"=>0,
                "msg"=>"亲亲，两次密码不一样哦~"
            ];
            return $result;
        }
        $password = md5($password);
        $usermodel = new usermodel();
        $user_id = $usermodel->has_email($email);
        if($user_id)
        {
            $change_password = $usermodel->change_password($user_id,$password);
            if($change_password)
            {
                $result = [
                    "code"=>1,
                    "msg"=>"密码修改成功！"
                ];
                return $result;
            }
            else
            {
                $result = [
                    "code"=>3,
                    "msg"=>"亲亲，密码修改失败哦~"
                ];
                return $result;
            }
        }
        else
        {
            $result = [
                "code"=>2,
                "msg"=>"亲亲，邮箱不存在哦~"
            ];
            return $result;
        }
    }

}

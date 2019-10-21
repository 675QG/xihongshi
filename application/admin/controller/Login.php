<?php
namespace app\admin\controller;
use think\Controller;
use app\admin\model\Admin;
use think\Session;

class Login extends Controller
{
    public function login()
    {
        if(request()->isAjax())
        {
            $admin_name = request()->post('admin_name');
            $password = request()->post('password');
            $password = md5($password);
            $admin = new Admin();
            $admin_id = $admin->has_admin($admin_name);
            if($admin_id)
            {
                $pass_check = $admin->pass_check($admin_id,$password);
                if($pass_check)
                {
                    $result =  [
                        'code'=>1,
                        'msg'=>'登录成功！'
                 ]; 
                 Session::set('admin_id',$admin_id);
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
                    'msg'=>'亲亲，管理员不存在哦~'
             ];
            }
            return $result;
        }
        return $this->fetch('login');
    }

    public function is_login()
    {
        if(Session::has('admin_id'))
        {
            $admin_id = Session::get('admin_id');
            return $admin_id;
        }
        else
        {
            header('location:admin_login');
        }
    }

    public function logout()
    {
        Session::delete('admin_id');
        header('location:admin_login');
    }
}
<?php
namespace app\admin\controller;
use think\Db;
use think\Controller;
use app\admin\controller\Login;
use app\admin\model\User as usermodel;

class Index extends Controller
{
    public function index()
    {
        $login = new Login();
        $user_id = $login->is_login();
        $usermodel = new usermodel();
        $user = $usermodel->get_user($user_id);
        $this->assign('user',$user);
        return $this->fetch('index');
    }
}
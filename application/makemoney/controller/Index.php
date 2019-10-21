<?php
namespace app\makemoney\controller;
use think\Controller;
use app\index\controller\Login;
use app\makemoney\model\User as usermodel;

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

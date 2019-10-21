<?php
namespace app\demand\controller;
use think\Controller;
use app\index\controller\Login;
use app\makemoney\model\User as usermodel;
use think\Session;

class Resulttask extends Controller
{
    public function result_task()
    {
        $login = new Login();
        $user_id = $login->is_login();
        $usermodel = new usermodel();
        $user = $usermodel->get_user($user_id);
        $this->assign('user',$user);
        return $this->fetch('result');
    }

}
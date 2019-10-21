<?php
namespace app\admin\controller;
use think\Db;
use think\Controller;
use app\admin\controller\Login;
use app\admin\model\Admin;
use app\admin\model\Guild as guildmodel;
use app\admin\model\User as usermodel;

class Guild extends Controller
{
    public function guild()
    {
        $login = new Login();
        $admin_id = $login->is_login();
        $admin = new Admin();
        $admin = $admin->get_admin($admin_id);
        $guildmodel = new guildmodel();
        $guild_check = $guildmodel->get_guild_check();
        $guild_check_num = count($guild_check);
        $this->assign('guild_check',$guild_check);
        $this->assign('guild_check_num',$guild_check_num);
        $this->assign('admin',$admin);

        $search = request()->get('search');
        $search_words = request()->get('search_words');
        if($search)
        {
            $guild = $guildmodel->guild_search($search_words);
        }
        else 
        {
            $guild = $guildmodel->guild();
        }
       
        $guild_num = count($guild);
        $get_guild_member = [];

        $this->assign("get_guild_member",$get_guild_member);
     
        $this->assign('guild',$guild);
        $this->assign('guild_num',$guild_num);
  
        $this->assign('search_words',$search_words);

        return $this->fetch('guild');
    }



    public function admin_guild_detail()
    {
        $login = new Login();
        $admin_id = $login->is_login();
        $admin = new Admin();
        $admin = $admin->get_admin($admin_id);
        $guild_id = request()->get("guild_id");
        $usermodel = new usermodel();
        $get_user = $usermodel->get_user($guild_id);
        $guildmodel = new guildmodel();
        $get_guild_task = $guildmodel->get_guild_task($guild_id);
        $guild_detail = $guildmodel->guild_detail($guild_id);
        $this->assign('get_guild_task',$get_guild_task);
        $this->assign('admin',$admin);
        $this->assign('guild_detail',$guild_detail);
        $this->assign('guild_id',$guild_id);
        $this->assign('get_user',$get_user);
        return $this->fetch('guild_detail');
    }


    public function guild_agree()
    {
        $guild_id = request()->post('guild_id');
        $agree = request()->post('agree');
        $guildmodel = new guildmodel();
        $update_guild_states = $guildmodel->update_guild_states($guild_id,$agree);
        if($update_guild_states)
        {
            if($agree)
            {
            $result = [
                'code'=>1,
                'msg'=>'已同意'
            ];
           }
           else
           {
            $result = [
                'code'=>1,
                'msg'=>'已拒绝'
            ];
           }
        }
        else
        {
            $result = [
                'code'=>0,
                'msg'=>'亲亲，操作失败哦~'
            ];
        }
     return $result;
    }

}
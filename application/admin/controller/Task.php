<?php
namespace app\admin\controller;
use think\Db;
use think\Controller;
use app\admin\controller\Login;
use app\admin\model\Admin as adminmodel;
use app\admin\model\User as usermodel;
use app\admin\model\TaskUser;
use app\admin\model\Task as taskmodel;

class Task extends Controller
{
    public function task()
    {
        $login = new Login();
        $admin_id = $login->is_login();
        $adminmodel = new adminmodel();
        $admin = $adminmodel->get_admin($admin_id);
        $taskmodel = new taskmodel();
        $task = $taskmodel->get_task_check();
        $this->assign('admin',$admin);
        $this->assign('task',$task);
        return $this->fetch('task');
    }

    public function admin_task_check()
    {
        $login = new Login();
        $admin_id = $login->is_login();
        $adminmodel = new adminmodel();
        $admin = $adminmodel->get_admin($admin_id);
        $guild_id = request()->get("guild_id");
        $task_id = request()->get("task_id");
        $usermodel = new usermodel();
        $guild_member = $usermodel->get_guild_member($guild_id);
        $task_user = new TaskUser;
        $get_task_user_all = $task_user->get_task_user_all($task_id);

     
        $this->assign('get_task_user_all',$get_task_user_all);
        $this->assign('guild_member',$guild_member);
        $this->assign('task_id',$task_id);
        $this->assign('guild_id',$guild_id);
        $this->assign('admin',$admin);
        return $this->fetch('task_check');
    }

    public function task_agree()
    {
        $task_id = request()->post("task_id");
        $agree = request()->post("agree");
        $taskmodel = new taskmodel();
        $update_task_states = $taskmodel->update_task_states($task_id,$agree);
        if($update_task_states)
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
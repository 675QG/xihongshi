<?php
namespace app\admin\model;
use think\Model;
use app\admin\controller\Login;
use app\admin\model\Admin;
use app\admin\model\User as usermodel;

class Task extends Model
{
    public function get_task_check()
    {
        $get_task_check = $this->where("task_states",2)->select();
        return $get_task_check;
    }


    public function update_task_states($task_id,$agree)
    {
        if($agree)
        { 
        $data = [
            'task_states'=>1
        ];
        }
       else 
       {
        $data = [
            'task_states'=>0
        ];
       }
        $where = [
            'task_id'=>$task_id
        ];
        $update_task_states = $this->save($data,$where);
        return $update_task_states;
    }


    
}


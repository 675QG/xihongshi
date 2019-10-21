<?php
namespace app\admin\model;
use think\Model;

class TaskUser extends Model
{
    
    public function get_task_user_all($task_id)
    {
        $get_task_user_all = $this->where("task_id",$task_id)->select();
        return $get_task_user_all;
    }


}
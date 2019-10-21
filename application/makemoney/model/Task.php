<?php
namespace app\makemoney\model;
use think\Model;
use app\makemoney\model\TaskGuild;

class Task extends Model
{
    public function user()
    {
        return $this->belongsToMany('user','task_user');
    }



    public function task($task_name,$task_range,$task_type)
    {
        if($task_name == '')
        {
            if($task_range == '全部')
            {
                if($task_type == '全部')
                {
                    $task = $this->where("task_states",1)->select();
                }
                else
                {
                    $task = $this->where("task_states",1)->where('task_type',$task_type)->select();
                }
            }
            else
            {
                if($task_type == '全部')
                {
                    $task = $this->where("task_states",1)->where('task_range',$task_range)->select();
                }
                else
                {
                    $task = $this->where("task_states",1)->where('task_range',$task_range)->where('task_type',$task_type)->select();
                }
            }
        }
        else
        {
            if($task_range == '全部')
            {
                if($task_type == '全部')
                {
                    $task = $this->where("task_states",1)->where('task_name',$task_name)->select();
                }
                else
                {
                    $task = $this->where("task_states",1)->where('task_name',$task_name)->where('task_type',$task_type)->select();
                }
            }
            else
            {
                if($task_type == '全部')
                {
                    $task = $this->where("task_states",1)->where('task_name',$task_name)->where('task_range',$task_range)->select();
                }
                else
                {
                    $task = $this->where("task_states",1)->where('task_name',$task_name)->where('task_range',$task_range)->where('task_type',$task_type)->select();
                }
            }
        }
        return $task;
    }

    public function get_task($task_id)
    {
        $task = $this->select($task_id);
        return $task;
    }

    public function update_task_number($task_number,$task_id)
    {
        $data = [
           'task_number'=>$task_number
        ];
        $task_id = [
       'task_id'=>$task_id
        ];
        $update_task_number = $this->save($data,$task_id);
        return $update_task_number;
    }

    public function receive_check($task_range_check,$task_id,$guild_id = '')
    {
        if($guild_id)
        {
            $task_guild = new TaskGuild();
            $task_guild_result = $task_guild->where('guild_id',$guild_id)->select();
            $task_guild_num = count($task_guild_result);
            for($i=0;$i<$task_guild_num;$i++)
            {
                $task_id_result = $task_guild_result[$i]['task_id'];
                if($task_id == $task_id_result)
                {
                    $task = $this->get_task($task_id);
                    $task_range = $task[0]['task_range'];
                    if($task_range_check == $task_range)
                    {
                        return 1;
                    }
                    else
                    {
                        return 0;
                    }
                }
            }
            return 0;
        }
        else
        {
            $task = $this->get_task($task_id);
            $task_range = $task[0]['task_range'];
            if($task_range_check == $task_range)
            {
                return 1;
            }
            else
            {
                return 0;
            } 
        }
    }

    public function get_task_user($guild_id,$task_id)
    {
        $task = $this->get($task_id);
        $get_task_user = $task->user()->where('guild_id',$guild_id)->select();
        return $get_task_user;
    }


}
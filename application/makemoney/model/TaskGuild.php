<?php
namespace app\makemoney\model;
use think\Model;

class TaskGuild extends Model
{

    public function my_guild_task_center($guild_id)
    {
        $my_guild_task_center = $this->where('guild_id',$guild_id)->select();
        return $my_guild_task_center;
    }

    public function receive_task_guild($guild_id,$task_id,$task_num)
    {
        $data = [
            'guild_id'=>$guild_id,
            'task_id'=>$task_id,
            'task_num'=>$task_num,
            'task_states'=>2
        ];
        $receive_task_guild = $this->save($data);
        return $receive_task_guild;
    }

    public function update_task_guild_num($guild_id,$task_id,$task_num)
    {
        $data = [
            'task_num'=>$task_num
        ];
        $where = [
            'guild_id'=>$guild_id,
            'task_id'=>$task_id
        ];
        $update_task_guild_num = $this->save($data,$where);
        return $update_task_guild_num;
    }

    public function get_task_guild_center($guild_id,$task_id)
    {
        $get_task_guild_center = $this->where('guild_id',$guild_id)->where('task_id',$task_id)->select();
        return $get_task_guild_center;
    }

    public function my_task_delete($guild_id,$task_id)
    {
        $my_task_delete = $this->where('guild_id',$guild_id)->where('task_id',$task_id)->delete();
        return $my_task_delete;
    }

    public function receive_task_guild_center($guild_id)
    {
        $receive_task_guild_center = $this->where('guild_id',$guild_id)->select();
        return $receive_task_guild_center;
    }

    public function get_task_guild($guild_id,$task_id)
    {
        $get_task_guild = $this->where('guild_id',$guild_id)->where('task_id',$task_id)->select();
        return $get_task_guild;
    }

}
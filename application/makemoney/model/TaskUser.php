<?php
namespace app\makemoney\model;
use think\Model;

class TaskUser extends Model
{
    public function receive_task($user_id,$task_id)
    {
        $data = [
            'user_id'=>$user_id,
            'task_id'=>$task_id
        ];
        $receive_task = $this->save($data);
        return $receive_task;
    }

    public function receive_task_user($user_id)
    {
        $receive_task_user = $this->where('user_id',$user_id)->select();
        return $receive_task_user;
    }


    public function my_task_center($user_id) //纯个人领取任务中间表
    {
        $my_task_center = $this->where('user_id',$user_id)->where('guild_id',null)->select();
      // $my_task_center = $this->where('user_id',$user_id)->select();
        return $my_task_center;
    }

    public function my_task_join_center($user_id,$guild_id)  //个人加入公会领取任务中间表
    {
        $my_task_join_center = $this->where('user_id',$user_id)->where('guild_id',$guild_id)->select();
        return $my_task_join_center;

    }

    public function my_task_delete($user_id,$task_id)
    {
        $my_task_delete = $this->where('user_id',$user_id)->where('task_id',$task_id)->delete();
        return $my_task_delete;
    }

    public function receive_personal_guild_task($user_id,$guild_id,$task_id)
    {
        $data = [
            'user_id'=>$user_id,
            'guild_id'=>$guild_id,
            'task_id'=>$task_id,
            'task_states'=>2
        ];
        $receive_personal_guild_task = $this->save($data);
        return $receive_personal_guild_task;
    }

    public function get_task_user($user_id,$task_id)
    {
        $get_task_user = $this->where("user_id",$user_id)->where("task_id",$task_id)->select();
        return $get_task_user;

    }

    public function get_task_user_all($task_id)
    {
        $get_task_user_all = $this->where("task_id",$task_id)->select();
        return $get_task_user_all;
    }


    public function save_small_zip_url($user_id,$task_id,$small_zip_url)
    {
        $data = [
            "small_zip_url"=>$small_zip_url
        ];
        $where = [
            "user_id"=>$user_id,
            "task_id"=>$task_id
        ];
        $save_small_zip_url = $this->save($data,$where);
        return $save_small_zip_url;
    }


   public function save_small_dir_url($user_id,$task_id,$small_dir_url)
   {
    $data = [
        "small_dir_url"=>$small_dir_url
    ];
    $where = [
        "user_id"=>$user_id,
        "task_id"=>$task_id
    ];
    $save_small_dir_url = $this->save($data,$where);
    return $save_small_dir_url;
   }

    
    
}
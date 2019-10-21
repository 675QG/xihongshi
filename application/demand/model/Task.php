<?php
namespace app\demand\model;
use think\Model;

class Task extends Model
{
    public function publish_task($task_name,$task_range,$task_type,$task_introduce,$task_standard,$task_number,$task_timelimit,$publish_user_id,$task_time,$doc_url)
    {
        $data = [
            "task_name"=>$task_name,
            "task_range"=>$task_range,
            "task_type"=>$task_type,
            "task_introduce"=>$task_introduce,
            "task_standard"=>$task_standard,
            "task_number"=>$task_number,
            "task_timelimit"=>$task_timelimit,
            "publish_user_id"=>$publish_user_id,
            "task_time"=>$task_time,
            "doc_url"=>$doc_url,
            "task_states"=>2
        ];
        $publish_task = $this->save($data);
        return $publish_task;
    }

    public function change_task($task_id,$task_name,$task_range,$task_type,$task_introduce,$task_standard,$task_number,$task_timelimit,$task_time,$doc_url)
    {
        if($doc_url == '')
        {
            $data = [
                "task_name"=>$task_name,
                "task_range"=>$task_range,
                "task_type"=>$task_type,
                "task_introduce"=>$task_introduce,
                "task_standard"=>$task_standard,
                "task_number"=>$task_number,
                "task_timelimit"=>$task_timelimit,
                "task_time"=>$task_time,
                "task_states"=>2
            ];

        }
        else
        {
            $data = [
                "task_name"=>$task_name,
                "task_range"=>$task_range,
                "task_type"=>$task_type,
                "task_introduce"=>$task_introduce,
                "task_standard"=>$task_standard,
                "task_number"=>$task_number,
                "task_timelimit"=>$task_timelimit,
                "task_time"=>$task_time,
                "doc_url"=>$doc_url,
                "task_states"=>2
            ];
        }
        $where = [
            'task_id'=>$task_id
        ];
        $change_task = $this->save($data,$where);
        return $change_task;

    }

    
    public function get_my_publish_task($publish_user_id)
    {
        $get_my_publish_task = $this->where('publish_user_id',$publish_user_id)->select();
        return $get_my_publish_task;
    }

    public function dele_task($task_id)
    {
        $dele_task = $this->where('task_id',$task_id)->delete();
        return $dele_task;

    }

    public function get_task($task_id)
    {
        $get_task = $this->get($task_id);
        return $get_task;
    }

}
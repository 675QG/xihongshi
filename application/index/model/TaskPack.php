<?php
namespace app\index\model;
use think\Model;

class TaskPack extends model
{
    public function get_pack($task_id,$pack_num)
    {
        $get_pack = $this->where("task_id",$task_id)->where("pack_num",$pack_num)->select();
        return $get_pack;
     
    }

    public function save_img_url_all($task_id,$pack_num,$img_url_all)
    {
        $data = [
            "img_url_all"=>$img_url_all
        ];
        $where = [
            "task_id"=>$task_id,
            "pack_num"=>$pack_num
        ];
        $save_img_url_all = $this->save($data,$where);
        return $save_img_url_all;

    }

  public function update_img_done_num($task_pack_id,$img_done_num)
  {
      $data = [
          "img_done_num" => $img_done_num
      ];
      $where = [
          "task_pack_id" => $task_pack_id
        ];
      $update_img_done_num = $this->save($data,$where);
      return $update_img_done_num;
  }

  public function get_pack_received($task_id,$received_guild_id)
  {
      $get_pack_received = $this->where("task_id",$task_id)->where("received_guild_id",$received_guild_id)->select();
      return $get_pack_received;
  }
}
<?php
namespace app\index\model;
use think\Model;

class  Mark extends Model
{
    public function save_mark($mark_img_url,$rectangle_total_number,$rectangle_total_value,$radio_total_info,$user_id,$task_id,$pack_num)
    {
        $data = [
            "mark_img_url"=>$mark_img_url,
            "rectangle_total_number"=>$rectangle_total_number,
            "rectangle_total_value"=>$rectangle_total_value,
            "radio_total_info"=>$radio_total_info,
            "user_id"=>$user_id,
            "task_id"=>$task_id,
            "check_pass"=>2,
            "check_pass2"=>2,
            "pack_num"=>$pack_num
        ];
        $save_mark = $this->save($data);
        return $save_mark;
    }


    public function get_mark($user_id,$task_id)
    {
        $get_mark = $this->where("user_id",$user_id)->where("task_id",$task_id)->select();
        return $get_mark;
    }


   public function check($mark_id,$check_pass)
   {
       $data = [
           "check_pass"=>$check_pass
       ];
       $where = [
           "mark_id"=>$mark_id
       ];
       $check = $this->save($data,$where);
       return $check;

   }

  public function  check2($mark_id,$check_pass2)
  {
    $data = [
        "check_pass2"=>$check_pass2
    ];
    $where = [
        "mark_id"=>$mark_id
    ];
    $check2 = $this->save($data,$where);
    return $check2;

  }

  public function  check3($mark_id,$check_pass3)
  {
    $data = [
        "check_pass3"=>$check_pass3
    ];
    $where = [
        "mark_id"=>$mark_id
    ];
    $check3 = $this->save($data,$where);
    return $check3;

  }

   public function get_mark_check($task_id,$user_id)
   {
       $get_mark_check = $this->where("task_id",$task_id)->where("user_id",$user_id)->where("check_pass",2)->select();
       return $get_mark_check;
   }

   
   public function get_mark_check2($task_id,$user_id)
   {
       $get_mark_check2 = $this->where("task_id",$task_id)->where("user_id",$user_id)->where("check_pass2",2)->select();
       return $get_mark_check2;
   }

   public function get_mark_check3($task_id,$user_id)
   {
       $get_mark_check3 = $this->where("task_id",$task_id)->where("user_id",$user_id)->where("check_pass3",2)->select();
       return $get_mark_check3;
   }

  public function had_change($mark_id,$rectangle_total_number,$rectangle_total_value,$radio_total_info)
  {
      $get_mark = $this->select($mark_id);
      if($rectangle_total_number == $get_mark[0]["rectangle_total_number"] && $rectangle_total_value == $get_mark[0]["rectangle_total_value"]
      && $radio_total_info == $get_mark[0]["radio_total_info"])
      {
          return 0;
      }
      else 
      {
          return 1;
      }
  }


  public function update_mark($mark_id,$rectangle_total_number,$rectangle_total_value,$radio_total_info)
  {
    $data = [
        "rectangle_total_number"=>$rectangle_total_number,
        "rectangle_total_value"=>$rectangle_total_value,
        "radio_total_info"=>$radio_total_info
    ];
    $where = [
        "mark_id"=>$mark_id
    ];
    $update_mark = $this->save($data,$where);
    return $update_mark;

  }
    
}

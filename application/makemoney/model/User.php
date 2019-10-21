<?php
namespace app\makemoney\model;
use think\Model;

class User extends Model
{
    public function task()
    {
        return $this->belongsToMany('Task','task_user');
    }

    public function get_my_task($user_id)
    {
        $user = $this->get($user_id);
        $my_task = $user->task()->where('guild_id',null)->select();
        return $my_task;
    }

    public function get_my_join_task($user_id,$guild_id)
    {
        $user = $this->get($user_id);
        $my_task = $user->task()->where('guild_id',$guild_id)->select();
        return $my_task;

    }


    public function all_user(){
        $user = $this->select();
        return $user;
    }



    public function get_user($user_id){
        $user = $this->select($user_id);
        return $user;
    }

    public function join_guild($user_id,$join_guild_id,$join_guild_states){
        $data = [
            'join_guild_id'=>$join_guild_id,
            'join_guild_states'=>$join_guild_states
        ];
        $where = [
            'user_id'=>$user_id
        ];
        $join = $this->save($data,$where);
        return $join;
    }

    public function quit_guild($user_id,$join_guild_id){
      $quit = $this->join_guild($user_id,$join_guild_id,0);
      return $quit;
       
    }

    public function create_guild($user_id,$create_guild_id)
    {
        $data = [
            'create_guild_id'=>$create_guild_id
        ];
        $where = [
            'user_id'=>$user_id
        ];
        $create = $this->save($data,$where);
        return $create;
    }

    public function get_join_msg($guild_id)
    {
        $get_join_msg = $this->where('join_guild_id',$guild_id)->where('join_guild_states',2)->select();
        return $get_join_msg;
    }

    
    public function join_agree($user_id)
    {
        $join_guild_states = 1;
        $data = [
            'join_guild_states'=>$join_guild_states
        ];
        $where = [
            'user_id'=>$user_id
        ];     
        $join_agree = $this->save($data,$where);
        return $join_agree;
    }

    public function join_reject($user_id)
    {
        $join_guild_states = 0;
        $data = [
            'join_guild_states'=>$join_guild_states
        ];
        $where = [
            'user_id'=>$user_id
        ];     
        $join_reject = $this->save($data,$where);
        return $join_reject;
    }

    public function update_user_info($user_id,$user_name,$phone,$email)
    {
        $data = [
            'user_name'=>$user_name,
            'phone'=>$phone,
            'email'=>$email
        ];
        $where = [
            'user_id'=>$user_id
        ];
        $update_user_info = $this->save($data,$where);
        return $update_user_info;
    }


    public function get_user_inname($user_name)
    {
        $user = $this->where("user_name",$user_name)->select();
        return $user;
    }

    public function get_user_inemail($email)
    {
        $user = $this->where("email",$email)->select();
        return $user;
    }

   public function update_user_name($user_id,$user_name,$name)
   {
       $data = [
           "user_name"=>$user_name,
           "name"=>$name
       ];
       $where = [
           "user_id"=>$user_id
       ];
       $update_user_name = $this->save($data,$where);
       return $update_user_name;
   }

   public function update_user_email($user_id,$email)
   {
       $data = [
           "email"=>$email
       ];
       $where = [
           "user_id"=>$user_id
       ];
       $update_user_email = $this->save($data,$where);
       return $update_user_email;
   }

   public function has_email($email)
   {
    $user = $this->where("email",$email)->select();
    if($user)
    {
       $user_id = $user[0]["user_id"];
       return $user_id;
    }
    else
    {
        return 0;
    }
   }


   public function check_password($user_id,$old_password)
   {
       $user = $this->where("user_id",$user_id)->select();
       $password = $user[0]["password"];
       if($password == $old_password)
       {
           return true;
       }
       else
       {
           return false;
       }
   }


   public function update_password($user_id,$new_password)
   {
       $data = [
           "password"=>$new_password
       ];
       $where = [
           "user_id"=>$user_id
       ];
       $update_password = $this->save($data,$where);
       return $update_password;
   }


   public function get_guild_member($guild_id)
   {
    $get_guild_member = $this->where("join_guild_id",$guild_id)->where("join_guild_states",1)->select();
    return $get_guild_member;
   }

   public function set_check($user_id,$is_check)
   {
       $data = [
           "is_check"=>$is_check
       ];
       $where = [
           "user_id"=>$user_id
       ];
       $set_check = $this->save($data,$where);
       return $set_check;
   }

}
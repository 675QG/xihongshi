<?php
namespace app\index\model;
use think\Model;

class User extends Model
{
    public function has_user($user_name)
    {
        $user = $this->where('user_name',$user_name)->select();
        if($user)
        {
            $user_id = $user[0]['user_id'];
            return $user_id;
        }
        else
        {
            return $user;
        }


    }

    public function get_user($user_id)
    {
        $get_user = $this->select($user_id);
        return $get_user;
    }


    public function pass_check($user_id,$password)
    {
        $user = $this->select($user_id);
        if($user)
        {
            $rpassword = $user[0]['password'];
            if($rpassword == $password)
            {
                return true;
            }
            else
            {
                return false;
            }
        }

    }


   public function add_user($user_name,$password){
       $data = [
           'user_name'=>$user_name,
           'password'=>$password
       ];
      $add_user = $this->save($data);
      return $add_user;
   }




   public function get_user_name($user_id){
       $user = $this->select($user_id);
       $user_name = $user[0]['user_name'];
       return $user_name;
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

   public function change_password($user_id,$password)
   {
       $data = [
           "password"=>$password
       ];
       $where = [
           "user_id"=>$user_id
       ];
       $change_password = $this->save($data,$where);
       return $change_password;

   }

}

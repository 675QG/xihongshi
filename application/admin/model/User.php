<?php
namespace app\admin\model;
use think\Model;

class User extends Model
{
    public function get_user($guild_id)
    {
       $get_user = $this->where("join_guild_id|create_guild_id",$guild_id)->select();
       return $get_user;
    }

    public function get_guild_member($guild_id)
    {
     $get_guild_member = $this->where("join_guild_id",$guild_id)->where("join_guild_states",1)->select();
     return $get_guild_member;
    }


 
}

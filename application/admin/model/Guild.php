<?php
namespace app\admin\model;
use think\Model;
use app\admin\controller\Login;
use app\admin\model\Admin;
use app\admin\model\User as usermodel;

class Guild extends Model
{
    public function task()
    {
        return $this->belongsToMany('Task','task_guild');
    }

    public function get_guild_task($guild_id)
    {
        $guild = $this->get($guild_id);
        $get_guild_task = $guild->task;
        return $get_guild_task;
    }


    public function get_guild_check()
    {
        $guild_check = $this->where('guild_states',2)->select();
        return $guild_check;
    }



    public function guild_search($search_worlds)
    {
        $guild = $this->where('guild_states',1)->where('guild_name', 'like', "%".$search_worlds."%")->limit(10)->select();
        return $guild;
    }

    public function update_guild_states($guild_id,$agree)
    {
        if($agree)
        { 
        $data = [
            'guild_states'=>1
        ];
        }
       else 
       {
        $data = [
            'guild_states'=>0
        ];
       }
        $where = [
            'guild_id'=>$guild_id
        ];
        $update_guild_states = $this->save($data,$where);
        return $update_guild_states;
    }


    public function guild()
    {
        $guild = $this->where('guild_states',1)->limit(10)->select();
        return $guild;
    }


    public function guild_detail($guild_id)
    {
        $guild_detail = $this->where("guild_id",$guild_id)->select();
        return $guild_detail;

    }
}
<?php
namespace app\admin\model;
use think\Model;

class Admin extends Model
{
    public function has_admin($admin_name)
    {
        $admin = $this->where('admin_name',$admin_name)->select();
        if($admin)
        {
            $admin_id = $admin[0]['admin_id'];
            return $admin_id;
        }
        else
        {
            return $admin;
        }
    }


    public function pass_check($admin_id,$password)
    {
        $admin = $this->select($admin_id);
        if($admin)
        {
            $rpassword = $admin[0]['password'];
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

    public function get_admin($admin_id)
    {
        $admin = $this->select($admin_id);
        return $admin;

    }
 
}

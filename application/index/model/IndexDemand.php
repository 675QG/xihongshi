<?php
namespace app\index\model;
use think\Model;

class IndexDemand extends model
{
    public function add_demand($name,$phone,$demand,$create_time)
    {
        $data = [
            "name"=>$name,
            "phone"=>$phone,
            "demand"=>$demand,
            "add_time"=>$create_time,
            "state"=>2
        ];
        $add_demand = $this->save($data);
        return $add_demand;
    }
}
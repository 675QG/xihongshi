<?php
namespace app\admin\model;
use think\Model;

class IndexDemand extends model
{
    public function get_index_demand()
    {
        $state = 2;
        $get_index_demand = $this->where("state",$state)->select();
        return $get_index_demand;
    }

    public function demand_done($index_demand_id)
    {
        $data = [
            "state"=>1
        ];
        $where = [
            "index_demand_id"=>$index_demand_id
        ];
        $demand_done = $this->save($data,$where);
        return $demand_done;
    }
}
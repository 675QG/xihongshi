<?php
namespace app\admin\controller;
use think\Db;
use think\Controller;
use app\admin\controller\Login;
use app\admin\model\Admin;
use app\admin\model\User as usermodel;
use app\admin\model\IndexDemand as index_demand;

class Index extends Controller
{
    public function index()
    {
         $login = new Login();
         $admin_id = $login->is_login();
         $admin = new Admin();
         $admin = $admin->get_admin($admin_id);
         $index_demand = new index_demand();
         $get_index_demand = $index_demand->get_index_demand();
         $get_index_demand_num = count($get_index_demand);
         $this->assign('admin',$admin);
         $this->assign('get_index_demand',$get_index_demand);
         $this->assign('get_index_demand_num',$get_index_demand_num);
         return $this->fetch('index');
    }


    public function demand_done()
    {
        $index_demand_id = request()->post("index_demand_id");
        $index_demand = new index_demand();
        $demand_done = $index_demand->demand_done($index_demand_id);
        if($demand_done)
        {
            $result = [
                "code"=>1,
                "msg"=>"修改状态成功！"
            ];

        }
        else
        {
            $result = [
                "code"=>0,
                "msg"=>"亲亲，修改状态失败啦~"
            ];

        }
        return $result;

    }

}
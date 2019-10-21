<?php
namespace app\index\controller;
use think\Db;
use think\Controller;
use app\index\controller\Login;
use app\index\controller\User;
use app\index\model\IndexDemand as index_demand;


class Index extends Controller
{
    public function index()
    {
    $login = new Login();
    $user_id = $login->is_login_ajax();
    if($user_id)
    {
        $user = new User();
        $user_name = $user->get_user_name($user_id);
        $this->assign('user_name',$user_name);
    }
    $this->assign('user_id',$user_id);
    return $this->fetch('index');
    }

    public function about()
    {
        return $this->fetch('about');

    }

    public function join()
    {
        
    }

    public function imark()
    {
        return $this->fetch('imark');
    }

    public function icollection()
    {
        
    }

    public function platform()
    {
        
    }



    public function send_demand()
    {
        $name = request()->post("name");
        $phone = request()->post("phone");
        $demand = request()->post("demand");
        $create_time = date('Y-m-d H:i:s');
        $index_demand = new index_demand();
        $add_demand = $index_demand->add_demand($name,$phone,$demand,$create_time);
        if($add_demand)
        {
            $result = [
                "code"=>1,
                "msg"=>"你的需求提交成功哦！我们会尽快与您联系！"
            ];
        }
        else
        {
            $result = [
                "code"=>0,
                "msg"=>"亲亲，提交失败哦~"
            ];
        }
        return $result;
    }


  




    public function test1()
    {
       $filename = ROOT_PATH . "public/static/zip/20190810/29ffb3c6cc6bc0c82fb5b1b726f8ba3c.zip";

       $zip=new \ZipArchive();
if(!$zip->open($filename))
{
exit('无法打开文件');
}
//$extract_name = md5(time()+rand(0,1000));
$extract_name = "8bedaaf4be8a0375e5428a01fd034a20";
$extract_path = ROOT_PATH.'public/static/zip/extract/'.$extract_name;
//$zip->extractTo($extract_path);
$zip->close();//关闭

$resource = opendir($extract_path);
$i = 0;
$small_filename = ROOT_PATH . "public/static/zip/20190810/small.zip";
if(!$zip->open($small_filename,$zip::CREATE))
{
exit('无法打开文件');
}
$img_all_path = [];
while($file = readdir($resource))
{
    if($file != "." && $file != "..")
    {
        $img_path = $extract_path."/".$file;
        $i = $i + 1;
       // echo $img_path;
       if(file_exists($img_path)){
        $zip->addFile($img_path,basename($img_path));
        array_push($img_all_path,$img_path);

       // unlink($img_path);
        }
        
        if($i >= 2)
        {
            $zip->close();
            $img_all_path_num = count($img_all_path);
            for($j=0;$j<$img_all_path_num;$j++)
            {
                unlink($img_all_path[$j]);
            }
            return 1;
        }
    }
}
closedir($resource);

//$datalist=array(ROOT_PATH .'images/0.png',ROOT_PATH .'images/1.png');

//foreach($datalist as $val){
//if(file_exists($val)){
//$zip->addFile($val);
//}
//else 
//{
  //  return "not";
//}



//header('Content-Type: application/zip');
//header('Content-disposition: attachment; filename='.$filename);
//header('Content-Length: ' . filesize($filename));
//readfile($filename);


    //  return $root;
}

public function test()
{
    $yy = "jx001";
    $yy = md5($yy);
    return $yy;
}

    

}

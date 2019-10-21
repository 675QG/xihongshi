<?php
namespace app\index\controller;
use think\Db;
use think\Controller;
use app\index\controller\Login;
use app\index\controller\User;
use app\index\model\User as usermodel;
use app\index\model\Mark as markmodel;
use app\makemoney\model\Task;
use app\makemoney\model\TaskUser;
use app\index\model\TaskPack;
use think\Session;

class Mark extends Controller
{
    public function mark()
    {
    $login = new Login();
    $user_id = $login->is_login_ajax();
    if($user_id)
    {
        $usermodel = new usermodel();
        $get_user = $usermodel->get_user($user_id);
        $this->assign('get_user',$get_user);
    }
    else
    {
        return "亲亲，请先登录哦~";
    }
  //  Session::delete("img_done_num");
  //  return;

    $task_id = request()->get("task_id");
    if($task_id == "")
    {
        return "无效的task_id";
    }
    Session::set("task_id",$task_id);
   $pack_num = "";
   $img_url = "";
   $img_number = "";
   $img_data_dir = "";
   $img_done_num = "";
    $received_guild_id = 11;
    $taskpack = new TaskPack();
    $get_pack_received = $taskpack->get_pack_received($task_id,$received_guild_id);
    $get_pack_received_num = count($get_pack_received);
    $can_do = 0;
    for($i=0;$i<$get_pack_received_num;$i++)
    {
        $img_number = $get_pack_received[$i]["img_number"];
        $img_done_num = $get_pack_received[$i]["img_done_num"];
        if($img_number > $img_done_num)
        {
            $can_do = 1;
            $pack_num = $get_pack_received[$i]["pack_num"];
            $img_data_dir = $get_pack_received[$i]["img_data_dir"];
            $task_pack_id = $get_pack_received[$i]["task_pack_id"];
            $img_number = $get_pack_received[$i]["img_number"];
            $img_url_all = $get_pack_received[$i]["img_url_all"];
            $img_url_all = json_decode($img_url_all);
        
            if(Session::has("img_done_num"))
            {
              // Session::delete("img_done_num");
               $img_done_num = Session::get("img_done_num");
               $img_url = $img_url_all[$img_done_num];
            }
            else 
            {
                $img_done_num = $get_pack_received[$i]["img_done_num"];
                $img_url = $img_url_all[$img_done_num];
                $img_done_num++;
                Session::set("img_done_num",$img_done_num);
                $update_img_done_num = $taskpack->update_img_done_num($task_pack_id,$img_done_num);
             
            }
            break;
        }
    }
   // $get_pack = $taskpack->get_pack($task_id,$pack_num);

    $markmodel = new markmodel();
    $get_mark = $markmodel->get_mark($user_id,$task_id);
    $this->assign('pack_num',$pack_num);
    $this->assign('get_mark',$get_mark);
    $this->assign('can_do',$can_do);
    $this->assign('img_url',$img_url);
    $this->assign('img_number',$img_number);
    $this->assign('img_data_dir',$img_data_dir);
    $this->assign('img_done_num',$img_done_num);
    $this->assign('user_id',$user_id);
    return $this->fetch('mark');

$taskpack = new TaskPack();
$get_pack = $taskpack->get_pack($task_id,1);
$img_data_dir = $get_pack[0]["img_data_dir"];
 $extract_path = ROOT_PATH.'public/static/zip/extract/'.$img_data_dir;
 $resource = opendir($extract_path);
 $img_url_all = [];

 $i = 0;
 while($file = readdir($resource))
 {
     if($file != "." && $file != "..")
     {
         $img_path = $extract_path."/".$file;
         $i++;
        if(file_exists($img_path)){
       array_push($img_url_all,$file);
         }
     }
 }
 closedir($resource);
 $img_url_all = json_encode($img_url_all);
 var_dump($img_url_all);
 $save_img_url_all = $taskpack->save_img_url_all($task_id,1,$img_url_all);
 if($save_img_url_all)
 {
     $result = [
         "code"=>1,
         "msg"=>"保存成功！"
     ];
 }
 else 
 {
    $result = [
        "code"=>0,
        "msg"=>"保存失败！"
    ];
 
 }
 return json_encode($result);


 }
  /*  public function mark()
    {
    $login = new Login();
    $user_id = $login->is_login_ajax();
    if($user_id)
    {
        $usermodel = new usermodel();
        $get_user = $usermodel->get_user($user_id);
        $this->assign('get_user',$get_user);
    }
    else
    {
        return "亲亲，请先登录哦~";
    }

    $task_id = request()->get("task_id");
    if($task_id == "")
    {
        return "无效的task_id";
    }
    Session::set("task_id",$task_id);

    $markmodel = new markmodel();
    $get_mark = $markmodel->get_mark($user_id,$task_id);
    $this->assign('get_mark',$get_mark);

    $task_user = new TaskUser();
    $get_task_user = $task_user->get_task_user($user_id,$task_id);
    $small_dir_url = $get_task_user[0]["small_dir_url"];
    if($small_dir_url != "")
    {
        $small_file_path = ROOT_PATH . "public/static/mark_img/".$small_dir_url;
        $resource = opendir($small_file_path);
        $img_url_all = [];
        while($file = readdir($resource))
        {
            if($file != "." && $file != "..")
            {
                $img_url = "public/static/mark_img/".$small_dir_url."/".$file;
                array_push($img_url_all,$img_url);
            }
        }
        $this->assign('img_url_all',$img_url_all);
        $this->assign('user_id',$user_id);
        return $this->fetch('mark');
    }
    

    $task = new Task();
    $get_task = $task->get_task($task_id);
    $img_data_dir = $get_task[0]["img_data_dir"];

 $extract_path = ROOT_PATH.'public/static/zip/extract/'.$img_data_dir;
 $resource = opendir($extract_path);
 $i = 0;
 $small_dir_name = md5(time()+rand(0,1000));
 $small_file_path = ROOT_PATH . "public/static/mark_img/".$small_dir_name;


 mkdir($small_file_path,0777,true);

 while($file = readdir($resource))
 {
     if($file != "." && $file != "..")
     {
         $img_path = $extract_path."/".$file;
         $i = $i + 1;
        if(file_exists($img_path)){
         rename($img_path,$small_file_path."/".$file);
         }
         
         if($i >= 100)
         {
             closedir($resource);
            
             $save_small_dir_url = $task_user->save_small_dir_url($user_id,$task_id,$small_dir_name);
             if($save_small_dir_url)
             {
               header("location:mark?task_id=".$task_id);
               return;
             }
             else 
             {
                 return "服务器内部错误，请联系网站管理员~";   
             }
    
         }
     }
 }


 closedir($resource);      
 $save_small_dir_url = $task_user->save_small_dir_url($user_id,$task_id,$small_dir_name);
 if($save_small_dir_url)
 {
   header("location:mark?task_id=".$task_id);
   return;
 }
 else 
 {
    return "服务器内部错误，请联系网站管理员~";
 }

    } */

    public function small_dir_img($task_id,$user_id)
    {
     $task = new Task();
     $get_task = $task->get_task($task_id);
     $img_data_dir = $get_task[0]["img_data_dir"];

 $extract_path = ROOT_PATH.'public/static/zip/extract/'.$img_data_dir;
 $resource = opendir($extract_path);
 $i = 0;
 $small_dir_name = md5(time()+rand(0,1000));
 $small_file_path = ROOT_PATH . "public/static/mark_img/".$small_dir_name;


 mkdir($small_file_path,0777,true);

 while($file = readdir($resource))
 {
     if($file != "." && $file != "..")
     {
         $img_path = $extract_path."/".$file;
         $i = $i + 1;
        if(file_exists($img_path)){
         rename($img_path,$small_file_path."/".$file);
         }
         
         if($i >= 100)
         {
             closedir($resource);
             $task_user = new TaskUser();
             $get_task_user = $task_user->get_task_user($user_id,$task_id);
             $small_dir_url = $get_task_user[0]["small_dir_url"];
             array_push($small_dir_url,$small_dir_name);
             $small_dir_name = json_encode($small_dir_url);
             $save_small_dir_url = $task_user->save_small_dir_url($user_id,$task_id,$small_dir_name);
             if($save_small_dir_url)
             {
                 $result = [
                     "code"=>1,
                     "msg"=>"领取成功！"
                 ];
             }
             else
             {

                $result = [
                    "code"=>0,
                    "msg"=>"服务器内部错误，请联系网站管理员~"
                ];
            
             }
             return $result;
    
         }
     }
 }


 closedir($resource); 
 $save_small_dir_url = $task_user->save_small_dir_url($user_id,$task_id,$small_dir_name);
 if($save_small_dir_url)
 {
     $result = [
         "code"=>1,
         "msg"=>"领取成功！"
     ];
 }
 else
 {
    $result = [
        "code"=>0,
        "msg"=>"服务器内部错误，请联系网站管理员~"
    ];
 }
 return $result;
    }


    public function save_mark()
    {
        $login = new Login();
        $user_id = $login->is_login_ajax();
        $task_id = Session::get("task_id");
        $rectangle_total_number = request()->post('rectangle_total_number');
        $rectangle_total_value = request()->post('rectangle_total_value');
        $radio_total_info = request()->post('radio_total_info');
        $mark_img_url = request()->post('mark_img_url');
        $pack_num = request()->post("pack_num");
        $markmodel = new markmodel();
        $save_mark = $markmodel->save_mark($mark_img_url,$rectangle_total_number,$rectangle_total_value,$radio_total_info,$user_id,$task_id,$pack_num);
        if($save_mark)
        {
            if(Session::has("img_done_num"))
            {
                Session::delete("img_done_num");
            }
            $result = [
                "code"=>1,
                "msg"=>"提交成功！"
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

    public function update_mark()
    {
        
        $login = new Login();
        $user_id = $login->is_login_ajax();
        $mark_id = request()->post("mark_id");
        $rectangle_total_number = request()->post('rectangle_total_number');
        $rectangle_total_value = request()->post('rectangle_total_value');
        $radio_total_info = request()->post('radio_total_info');
        $markmodel = new markmodel();
        $had_change = $markmodel->had_change($mark_id,$rectangle_total_number,$rectangle_total_value,$radio_total_info);
        if(!$had_change)
        {
            $result = [
                "code"=>2,
                "msg"=>"亲亲，您没有修改任何内容哦~"
            ];
            return $result;
        }
    
        $update_mark = $markmodel->update_mark($mark_id,$rectangle_total_number,$rectangle_total_value,$radio_total_info);
        if($update_mark)
        {
            $result = [
                "code"=>1,
                "msg"=>"修改成功！"
            ];
        }
        else
        {
            $result = [
                "code"=>0,
                "msg"=>"亲亲，修改失败哦~"
            ];

        }
        return $result;

    }


    public function check()
    {
        if(request()->isAjax())
        {
            $mark_id = request()->post("mark_id");
            $check_pass = request()->post("check_pass");
            $markmodel = new markmodel();
            $check = $markmodel->check($mark_id,$check_pass);
            if($check)
            {
                if($check_pass)
                {
                    $result = [
                        "code"=>1,
                        "msg"=>"已通过"
                    ];
                }
                else 
                {
                    $result = [
                        "code"=>1,
                        "msg"=>"已打回"
                    ];
                }
            
            }
            else 
            {
                $result = [
                    "code"=>0,
                    "msg"=>"操作失败"
                ];
            
            }
            return $result;
        }
        $task_id = request()->get("task_id");
        $user_id_check = request()->get("user_id_check");
        $usermodel = new usermodel();
        $get_user = $usermodel->get_user($user_id_check);
        $markmodel = new markmodel();
        $get_mark_check = $markmodel->get_mark_check($task_id,$user_id_check);
        $this->assign("get_user",$get_user);
        $this->assign("get_mark_check",$get_mark_check);
        return $this->fetch('check');
    }



    public function check2()
    {
        if(request()->isAjax())
        {
            $mark_id = request()->post("mark_id");
            $check_pass2 = request()->post("check_pass2");
            $markmodel = new markmodel();
            $check2 = $markmodel->check2($mark_id,$check_pass2);
            if($check2)
            {
                if($check_pass2)
                {
                    $result = [
                        "code"=>1,
                        "msg"=>"已通过"
                    ];
                }
                else 
                {
                    $result = [
                        "code"=>1,
                        "msg"=>"已打回"
                    ];
                }
            
            }
            else 
            {
                $result = [
                    "code"=>0,
                    "msg"=>"操作失败"
                ];
            
            }
            return $result;
        }
        $task_id = request()->get("task_id");
        $user_id_check = request()->get("user_id_check");
        $usermodel = new usermodel();
        $get_user = $usermodel->get_user($user_id_check);
        $markmodel = new markmodel();
        $get_mark_check = $markmodel->get_mark_check2($task_id,$user_id_check);
        $this->assign("get_user",$get_user);
        $this->assign("get_mark_check",$get_mark_check);
        return $this->fetch('check2');
    }



    public function check3()
    {
        if(request()->isAjax())
        {
            $mark_id = request()->post("mark_id");
            $check_pass3 = request()->post("check_pass3");
            $markmodel = new markmodel();
            $check3 = $markmodel->check3($mark_id,$check_pass3);
            if($check3)
            {
                if($check_pass3)
                {
                    $result = [
                        "code"=>1,
                        "msg"=>"已通过"
                    ];
                }
                else 
                {
                    $result = [
                        "code"=>1,
                        "msg"=>"已打回"
                    ];
                }
            
            }
            else 
            {
                $result = [
                    "code"=>0,
                    "msg"=>"操作失败"
                ];
            
            }
            return $result;
        }
        $task_id = request()->get("task_id");
        $user_id_check = request()->get("user_id_check");
        $usermodel = new usermodel();
        $get_user = $usermodel->get_user($user_id_check);
        $markmodel = new markmodel();
        $get_mark_check = $markmodel->get_mark_check3($task_id,$user_id_check);
        $this->assign("get_user",$get_user);
        $this->assign("get_mark_check",$get_mark_check);
        return $this->fetch('check3');
    }
    
}

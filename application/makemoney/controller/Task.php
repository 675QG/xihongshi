<?php
namespace app\makemoney\controller;
use think\Controller;
use app\makemoney\model\Task as taskmodel;
use app\makemoney\model\TaskUser;
use app\makemoney\model\TaskGuild;
use app\makemoney\model\User as usermodel;
use app\index\controller\Login;
use app\makemoney\model\Guild as guildmodel;
use think\Session;
use think\Loader;

class Task extends Controller
{
    public function task()
    {
        if(request()->isAjax())
        {
            $task_name = request()->post('task_name');
            $task_type = request()->post('task_type');
            $task_range = request()->post('task_range');
            $taskmodel = new taskmodel();
            $task = $taskmodel->task($task_name,$task_range,$task_type);
            return $task;
        }
        $login = new Login();
        $user_id = $login->is_login();
        $task_name = '';
        $task_range = '全部';
        $task_type = '全部';
        $join = 0;
        $create = 0;
        $guild_id = "";
        $my_task_center = "";
        $guildmodel = new guildmodel();
        $task_user = new TaskUser();
        $receive_task_user = $task_user->receive_task_user($user_id);
        $taskmodel = new taskmodel();
        $task = $taskmodel->task($task_name,$task_range,$task_type);
        $task_num = count($task);
        $usermodel = new usermodel();
        $user = $usermodel->get_user($user_id);
        $create_guild_id = $user[0]['create_guild_id'];
        $join_guild_id = $user[0]['join_guild_id'];
        $join_guild_states = $user[0]['join_guild_states'];
        $is_check = $user[0]["is_check"];
        $my_task = [];
   
        if($create_guild_id)  //公会创建者领取的任务
        {
            $guild_id = $create_guild_id;
            $my_guild = $guildmodel->get_my_guild($guild_id);
            if(!empty($my_guild)){
                $guild_states = $my_guild[0]['guild_states'];
                if($guild_states == 1)
                {
                    $my_task = $guildmodel->get_guild_task($guild_id);
                    $task_guild = new TaskGuild();
                    $receive_task_guild_center = $task_guild->receive_task_guild_center($guild_id);
                    $receive_task_user = $receive_task_guild_center;
                    $my_task_center = '';
                    $create = 1;
                }
            }
        }
        else if($join_guild_id)
        {
            if($join_guild_states == 1)  //个人加入公会领取的任务
            {
                $guild_id = $join_guild_id;
                $join = 1;
                $task = $guildmodel->get_guild_task($guild_id);
                $task_num = count($task);
                $my_task = $usermodel->get_my_join_task($user_id,$guild_id);
                $my_task_center = $task_user->my_task_join_center($user_id,$guild_id);
            }
        }
        else
        {
            $my_task = $usermodel->get_my_task($user_id); // 纯个人领取的任务
            $my_task_center = $task_user->my_task_center($user_id);
        }

        if($is_check)
        {
            $guild_id = $join_guild_id;
            $my_task = $guildmodel->get_guild_task($guild_id);
            $task_guild = new TaskGuild();
            $receive_task_guild_center = $task_guild->receive_task_guild_center($guild_id);
            $receive_task_user = $receive_task_guild_center;
        }
        $my_task_num = count($my_task);
        $this->assign('is_check',$is_check);
        $this->assign('guild_id',$guild_id);
        $this->assign('my_task_center',$my_task_center);
        $this->assign('my_task',$my_task);
        $this->assign('join',$join);
        $this->assign('create',$create);
        $this->assign('task',$task);
        $this->assign('user',$user);
        $this->assign('task_num',$task_num);
        $this->assign('my_task_num',$my_task_num);
        $this->assign('receive_task_user',$receive_task_user);
        return $this->fetch('task');
    }

    public function task_check()
    {
        $task_id = request()->get("task_id");
        $guild_id = request()->get("guild_id");
        $login = new Login();
        $user_id = $login->is_login();
        $usermodel = new usermodel();
        $user = $usermodel->get_user($user_id);
        $guild_member = $usermodel->get_guild_member($guild_id);
        $task_user = new TaskUser;
        $get_task_user_all = $task_user->get_task_user_all($task_id);
        $this->assign('get_task_user_all',$get_task_user_all);
        $this->assign('guild_member',$guild_member);
        $this->assign('user',$user);
        $this->assign('task_id',$task_id);
        return $this->fetch('task_check');
    }

    public function task_manage()
    {
        $task_id = request()->get('task_id');
        $login = new Login();
        $user_id = $login->is_login();
        $usermodel = new usermodel();
        $user = $usermodel->get_user($user_id);
        $create_guild_id = $user[0]['create_guild_id'];
        $guild_id = $create_guild_id;
        $task_guild = new TaskGuild();
        $get_task_guild = $task_guild->get_task_guild($guild_id,$task_id);
        $task_num = $get_task_guild[0]['task_num'];
        $taskmodel = new taskmodel();
        $get_task = $taskmodel->get_task($task_id);
        $get_task_user = $taskmodel->get_task_user($guild_id,$task_id);
        $get_task_user_num = count($get_task_user);
        //dump($get_task_user);
        //return;

        $this->assign('get_task_user_num',$get_task_user_num);
        $this->assign('get_task_user',$get_task_user);
        $this->assign('get_task',$get_task);
        $this->assign('task_num',$task_num);
        $this->assign('user',$user);
        return $this->fetch('taskmanage');
    }


    public function receive_task()
    {
        if(Session::has('user_id'))
        {
            $user_id = Session::get('user_id');
        }
        else
        {
            $result = [
                'code'=>10,
                'msg'=>'亲亲，请先登录哦~'
            ];
            return $result;
        }
        
        $task_id = request()->post('task_id');

        $taskmodel = new taskmodel();
    
        $usermodel = new usermodel();
        $user = $usermodel->get_user($user_id);
        $create_guild_id = $user[0]['create_guild_id'];
        $join_guild_id = $user[0]['join_guild_id'];
        $join_guild_states = $user[0]['join_guild_states'];
        if($create_guild_id)
        {
            $guild_id = $create_guild_id;
            $result = $this->receive_task_guild($user_id,$guild_id,$task_id);
            return $result;
        }
        else if($join_guild_id)
        {
            if($join_guild_states == 0)
            {
                $result = [
                    'code'=>0,
                    'msg'=>'亲亲，加入公会未审核通过哦！'
                ];
            }
            else if($join_guild_states == 1)
            {
                $guild_id = $join_guild_id;
                $taskmodel = new taskmodel();
                $task_range = '公会任务';
                $receive_check = $taskmodel->receive_check($task_range,$task_id,$guild_id);
                if(!$receive_check)
                {
                    $result = [
                        'code'=>3,
                        'msg'=>'亲亲，您只能领取已加入公会的公会任务哦~'
                    ];
                    return $result;
                }
                $task_user = new TaskUser();
                $task_guild = new TaskGuild();
                $task_guild_center = $task_guild->get_task_guild_center($guild_id,$task_id);
                $task_num = $task_guild_center[0]['task_num'];
                $task_num = $task_num - 1;
                if($task_num>=0)
                {
                    $update_task_guild_number = $task_guild->update_task_guild_num($guild_id,$task_id,$task_num);
                    $receive_personal_guild_task = $task_user->receive_personal_guild_task($user_id,$guild_id,$task_id);
                    if($receive_personal_guild_task && $update_task_guild_number)
                    {
                      $result = [
                        'code'=>1,
                        'msg'=>'领取成功！'
                     ];
                   }
                   else
                   {
                    $result = [
                        'code'=>0,
                        'msg'=>'亲亲，领取失败哦~'
                     ]; 
                   }
                }
                else if($join_guild_states == 2)
                {
                    $result = [
                        'code'=>2,
                        'msg'=>'亲亲，请耐心等待公会审核哦~'
                    ];

                }
             
                }
                else
                {
                    $result = [
                        'code'=>3,
                        'msg'=>'亲亲，任务已经领取完了~'
                    ];

                }
                return $result;
        }
        else
        {
            $taskmodel = new taskmodel();
            $task_range = '个人任务';
            $receive_check = $taskmodel->receive_check($task_range,$task_id);
            if(!$receive_check)
            {
                $result = [
                    'code'=>3,
                    'msg'=>'亲亲，您只能领取个人任务哦~'
                ];
                return $result;
            }
      
            $taskuser = new TaskUser();
            $receive_task = $taskuser->receive_task($user_id,$task_id);
            $task = $taskmodel->get_task($task_id);
            $task_number = $task[0]['task_number'];
            $task_number = $task_number - 1;
            $update_task_number = $taskmodel->update_task_number($task_number,$task_id);
            if($receive_task && $update_task_number)
            {
                $result = [
                    'code'=>1,
                    'msg'=>'领取成功！'
                ];
            }
            else
            {
                $result = [
                    'code'=>0,
                    'msg'=>'亲亲，领取失败了哦~'
                ];
            }
            return $result;
        }
    }

    public function my_task_delete()
    {
        if(Session::has('user_id'))
        {
            $user_id = Session::get('user_id');
        }
        else
        {
            $result = [
                'code'=>10,
                'msg'=>'亲亲，请先登录哦~'
            ];
            return $result;
        }
        $task_id = request()->post('task_id');
        $task_user = new TaskUser();
        $task_guild = new TaskGuild();
        $usermodel = new usermodel();
        $user = $usermodel->get_user($user_id);
        $create_guild_id = $user[0]['create_guild_id'];
        $join_guild_id = $user[0]['join_guild_id'];
        $join_guild_states = $user[0]['join_guild_states'];
        if($create_guild_id)
        {
            $guild_id = $create_guild_id;
            $my_task_delete = $task_guild->my_task_delete($guild_id,$task_id);
        }
        else
        {
            $my_task_delete = $task_user->my_task_delete($user_id,$task_id);
        }

        if($my_task_delete)
        {
            $result = [
                'code'=>1,
                'msg'=>'删除成功！'
            ];
        }
        else
        {
            $result = [
                'code'=>0,
                'msg'=>'亲亲，删除失败了哦~'
            ];
        }
        return $result;
    
    }
    

    public function receive_task_guild($user_id,$guild_id,$task_id)
    {
        $guildmodel = new guildmodel();
        $guild = $guildmodel->get_my_guild($guild_id);
        $guild_states = $guild[0]['guild_states'];
        $people_number = $guild[0]['people_number'];
        $task_num = $people_number;
        if($guild_states == 1)
        {
            $taskmodel = new taskmodel();
            $task_range = '公会任务';
            $receive_check = $taskmodel->receive_check($task_range,$task_id);
            if(!$receive_check)
            {
                $result = [
                    'code'=>3,
                    'msg'=>'亲亲，你只能领取公会任务哦~'
                ];
                return $result;
            }
            $task = $taskmodel->get_task($task_id);
            $task_number = $task[0]['task_number'];
            $task_number = $task_number - $task_num;
            if($task_number>=0)
            {
                $taskmodel = new taskmodel();
                $update_task_number = $taskmodel->update_task_number($task_number,$task_id);
                $task_guild = new TaskGuild();
                $receive_task_guild = $task_guild->receive_task_guild($guild_id,$task_id,$task_num);
                if($receive_task_guild && $update_task_number)
                {
                $result = [
                    'code'=>1,
                    'msg'=>'领取成功！'
                ];
                }
                else
                {
                $result = [
                    'code'=>0,
                    'msg'=>'亲亲，领取失败了哦~'
                ];
               }
            }
            else
            {
                $result = [
                    'code'=>3,
                    'msg'=>'亲亲，任务量不足了哦~'
                ];
            }
        }
        else if($guild_states == 0)
        {
            $result = [
                'code'=>3,
                'msg'=>'亲亲，你的公会未审核通过哦~'
            ];
        }
        else if ($guild_states == 2)
        {
            $result = [
                'code'=>4,
                'msg'=>'亲亲，你的公会还在审核中哦~'
            ];
        }
        return $result;
    }



    public function get_task_center()
    {
        if(Session::has('user_id'))
        {
            $user_id = Session::get('user_id');
        }
        else
        {
            $result = [
                'code'=>10,
                'msg'=>'亲亲，请先登录哦~'
            ];
            return $result;
        }
        $usermodel = new usermodel();
        $guildmodel = new guildmodel();
        $task_guild = new TaskGuild();
        $task_user = new TaskUser();
        $user = $usermodel->get_user($user_id);
        $create_guild_id = $user[0]['create_guild_id'];
        $join_guild_id = $user[0]['join_guild_id'];
        $join_guild_states = $user[0]['join_guild_states'];
        if($create_guild_id)  //公会创建者领取的任务
        {
            $guild_id = $create_guild_id;
            $my_guild = $guildmodel->get_my_guild($guild_id);
            $guild_states = $my_guild[0]['guild_states'];
            if($guild_states == 1)
            {
               $my_task = $guildmodel->get_guild_task($guild_id);
               $receive_task_guild_center = $task_guild->receive_task_guild_center($guild_id);
               $my_task_center = $receive_task_guild_center;
            }
        }
        else if($join_guild_id)
        {
            if($join_guild_states == 1)  //个人加入公会领取的任务
            {
                $guild_id = $join_guild_id;
                $join = 1;
                $my_task_center = $task_user->my_task_join_center($user_id,$guild_id);
            }
        }
        else
        {
            // 纯个人领取的任务
            $my_task_center = $task_user->my_task_center($user_id);
        }
        return $my_task_center;

    }

    public function excel()
    {
        $usermodel = new usermodel();
        $user = $usermodel->all_user();
      //  dump($user);

     //   $list=Db::name('num')->where('name','like','%'.$name.'%')->field('qq,name,people')->select();
        $file_name = date('Y-m-d_His').'.xls';
        $path = dirname(__FILE__);
        Loader::import('PHPExcel.PHPExcel');
        Loader::import('PHPExcel.PHPExcel.IOFactory.PHPExcel_IOFactory');
        $PHPExcel = new \PHPExcel();
         $PHPSheet = $PHPExcel->getActiveSheet();
         $PHPSheet->setTitle("qq群表单");
         $PHPSheet->setCellValue("A1","用户名");
         $PHPSheet->setCellValue("B1","密码");
         $PHPSheet->setCellValue("C1","电话");
         $i = 2;
      foreach($user as $key => $value){
            $PHPSheet->setCellValue('A'.$i,''.$value['user_name']);
            $PHPSheet->setCellValue('B'.$i,''.$value['password']);
            $PHPSheet->setCellValue('C'.$i,''.$value['phone']);
            $i++;
        }
      ob_end_clean();
      $PHPWriter = \PHPExcel_IOFactory::createWriter($PHPExcel,"Excel5");
      header('Content-Disposition: attachment;filename='.$file_name);
      header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
      $PHPWriter->save("php://output");

    }


    public function img_data()
    {
        $task_id = request()->get("task_id");
        $login = new Login();
        $user_id = $login->is_login();
        $task_user = new TaskUser();
        $get_task_user = $task_user->get_task_user($user_id,$task_id);
        $small_zip_url = $get_task_user[0]["small_zip_url"];
        if($small_zip_url != "")
        {
            $small_zip_url_local = ROOT_PATH . "public/static/zip/".$small_zip_url;
            $small_zip_url_online = "/public/static/zip/".$small_zip_url;
            $small_zip_name = basename($small_zip_url);
            header('Content-Type: application/zip');
            header('Content-disposition: attachment; filename='.$small_zip_name);
            header('Content-Length: ' . filesize($small_zip_url_local));
            readfile($small_zip_url_local);
            return;
        }
        $taskmodel = new taskmodel();
        $task =  $taskmodel->get_task($task_id);
        $file_name_short_zip = $task[0]["img_data_zip"];

        $file_name_short_dir = $task[0]["img_data_dir"];
        $path_parts = pathinfo($file_name_short_zip);
        $zip_dir = $path_parts["dirname"];

        $zip = new \ZipArchive();
 $extract_name = $file_name_short_dir;
 $extract_path = ROOT_PATH.'public/static/zip/extract/'.$extract_name;
 
 $resource = opendir($extract_path);
 $i = 0;
 $small_zip_name = md5(time()+rand(0,1000));
 $small_filename = ROOT_PATH . "public/static/zip/".$zip_dir."/".$small_zip_name.".zip";
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
        if(file_exists($img_path)){
         $zip->addFile($img_path,basename($img_path));
         array_push($img_all_path,$img_path);
         }
         
         if($i >= 2)
         {
             $zip->close();
             closedir($resource);
             $small_zip_url = $zip_dir."/".$small_zip_name.".zip";
             $small_zip_url_local = ROOT_PATH . "public/static/zip/".$small_zip_url;
             $save_small_zip_url = $task_user->save_small_zip_url($user_id,$task_id,$small_zip_url);
             if($save_small_zip_url)
             {
                $img_all_path_num = count($img_all_path);
                for($j=0;$j<$img_all_path_num;$j++)
                {
                 //   unlink($img_all_path[$j]);
                }
               header('Content-Type: application/zip');
               header('Content-disposition: attachment; filename='.$small_zip_name.".zip");
               header('Content-Length: ' . filesize($small_zip_url_local));
               readfile($small_zip_url_local);
               return;
             }
             else 
             {
                return "<script> alert('下载失败');</script>";
             }
         }
     }
 }



    }
}

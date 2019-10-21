<?php
namespace app\demand\controller;
use think\Controller;
use app\index\controller\Login;
use app\makemoney\model\User as usermodel;
use app\demand\model\Task;
use think\Session;

class Publish extends Controller
{
    public function publish()
    {
        $login = new Login();
        $user_id = $login->is_login();
        $usermodel = new usermodel();
        $user = $usermodel->get_user($user_id);
        $task = new Task();
        $my_publish_task = $task->get_my_publish_task($user_id);
        $my_publish_task_num = count($my_publish_task);
        $this->assign('my_publish_task',$my_publish_task);
        $this->assign('my_publish_task_num',$my_publish_task_num);
        $this->assign('user',$user);
        return $this->fetch('publish');
    }

    public function publish_task()
    {
        $login = new Login();
        $user_id = $login->is_login_ajax();
        $task_name = request()->post('task_name');
        $task_range = request()->post('task_range');
        $task_type = request()->post('task_type');
        $task_introduce = request()->post('task_introduce');
        $task_standard = request()->post('task_standard');
        $task_number = request()->post('task_number');
        $task_timelimit = request()->post('task_timelimit');
        $doc_url = request()->post('doc_url');
        $task_time =  date("Y-m-d H:i:s",time());
        $task = new Task();
        $publish_task = $task->publish_task($task_name,$task_range,$task_type,$task_introduce,$task_standard,$task_number,$task_timelimit,$user_id,$task_time,$doc_url);
        if($publish_task)
        {
            $result = [
                'code'=>1,
                'msg'=>'任务发布成功！'
            ];
        }
        else
        {
            $result = [
                'code'=>0,
                'msg'=>'亲亲，任务发布失败~'
            ];
        }
        return $result;

    }

    public function upload_doc()
    {
        $upload_img_data = request()->file("img_data");
        if($upload_img_data)
        {
            $info = $upload_img_data->validate(['ext'=>'zip'])->move(ROOT_PATH . 'public/static/zip');
               if ($info)
               {
                $doc_url = str_replace("\\","/",$info->getSaveName());
                return $doc_url;
               }
               else
               {
                $result = [
                    'code'=>0,
                    'msg'=>'亲亲，文档上传失败哦~'
                ];
                return $result;
               }

        }
       // return $upload_img_data;


        /*
        $upload_doc = request()->file('doc');
      
        if($upload_doc)
        {
            $info = $upload_doc->validate(['size'=>5242880,'ext'=>'doc,docx,txt,pdf,xls'])->move(ROOT_PATH . 'public/static/doc');
               if ($info)
               {
                $doc_url = str_replace("\\","/",$info->getSaveName());
                return $doc_url;
               }
               else
               {
                $result = [
                    'code'=>0,
                    'msg'=>'亲亲，文档上传失败哦~'
                ];
                return $result;
               }
        }  */
    }


    public function change_task()
    {
        if(request()->isAjax())
        {
           $login = new Login();
           $user_id = $login->is_login_ajax();
           if(!$user_id)
           {
               $result = [
                   'code'=>10,
                   'msg'=>'亲亲，您还没有登录呢~'
               ];
               return $result;
           }
           $task_id = request()->post('task_id');
           $task_name = request()->post('task_name');
           $task_range = request()->post('task_range');
           $task_type = request()->post('task_type');
           $task_introduce = request()->post('task_introduce');
           $task_standard = request()->post('task_standard');
           $task_number = request()->post('task_number');
           $task_timelimit = request()->post('task_timelimit');
           $doc_url = request()->post('doc_url');
           $task_time =  date("Y-m-d H:i:s",time());
           $task = new Task();  
           $change_task = $task->change_task($task_id,$task_name,$task_range,$task_type,$task_introduce,$task_standard,$task_number,$task_timelimit,$task_time,$doc_url);
           if($change_task)
           {
               $result = [
                   'code'=>1,
                   'msg'=>'修改成功！'
               ];
           }
           else
           {
            $result = [
                'code'=>0,
                'msg'=>'亲亲，修改失败哦~'
            ];
           }
            return $result;
        }
        $login = new Login();
        $user_id = $login->is_login();
        $usermodel = new usermodel();
        $user = $usermodel->get_user($user_id);
        $task_id = request()->get('task_id');
        $task = new Task();
        $task = $task->get_task($task_id);
        $this->assign('user',$user);
        $this->assign('task',$task);
        return $this->fetch('change_task');


    }

    public function dele_task($task_id)
    {
        $task_id = request()->post('task_id');
        $task = new Task();
        $dele_task = $task->dele_task($task_id);
        if($dele_task)
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
                'msg'=>'亲亲，删除失败哦~'
            ];

        }

        return $result;

    }

}
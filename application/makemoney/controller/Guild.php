<?php
namespace app\makemoney\controller;
use think\Controller;
use think\Session;
use think\Validate;
use app\makemoney\model\Guild as guildmodel;
use app\makemoney\controller\User;
use app\index\controller\Login;
use app\makemoney\model\User as usermodel;

class Guild extends Controller
{
    public function guild()
    {
        $login = new Login();
        $user_id = $login->is_login();
        $user = new User();
        $user = $user->get_user($user_id);
        $create_guild_id = $user[0]['create_guild_id'];
        $join_guild_id = $user[0]['join_guild_id'];
        $join_guild_states = $user[0]['join_guild_states'];
        $guild_id = '';
        $get_guild_member = [];
        $get_join_msg = [];
        $search = request()->get('search');
        $search_words = request()->get('search_words');
        if($create_guild_id)
        {
            $guild_id = $create_guild_id;
            $usermodel = new usermodel();
            $get_join_msg = $usermodel->get_join_msg($guild_id);
           
        }
        else
        {
            if($join_guild_id)
            {
                $guild_id = $join_guild_id;
            }
        }
        $guildmodel = new guildmodel();
        if($guild_id == '')
        {
            $my_guild = [];
        }
        else
        {
            $my_guild =  $guildmodel->get_my_guild($guild_id);
            if($create_guild_id)
            {
                $create_guild_states = $my_guild[0]["guild_states"];
                if($create_guild_states == 1)
                {
                    $usermodel = new usermodel();
                    $get_guild_member = $usermodel->get_guild_member($guild_id);

                }

            }
        }
        if($search)
        {
            $guild = $guildmodel->guild_search($search_words);
        }
        else
        {
            $guild = $guildmodel->guild();
        }
        $guild_num = count($guild);
        $join_msg_num = count($get_join_msg);
        $this->assign("get_guild_member",$get_guild_member);
        $this->assign('create_guild_id',$create_guild_id);
        $this->assign('join_guild_id',$join_guild_id);
        $this->assign('my_guild',$my_guild);
        $this->assign('guild',$guild);
        $this->assign('user',$user);
        $this->assign('guild_num',$guild_num);
        $this->assign('join_msg_num', $join_msg_num);
        $this->assign('search_words',$search_words);
        $this->assign('join_guild_states',$join_guild_states);
        $this->assign('get_join_msg',$get_join_msg);
        return $this->fetch('guild');

    }


     public function guild_manage()
     {
        $login = new Login();
        $user_id = $login->is_login();
        $guild_id = request()->get("guild_id");
        $guildmodel = new guildmodel();
        $guild_detail = $guildmodel->get_my_guild($guild_id);

        $user = new User();
        $user = $user->get_user($user_id);
        $this->assign('user',$user);
        $this->assign('guild_detail',$guild_detail);
        return $this->fetch('guild_manage');
     }

     public function change_guild()
     {
        $guild_id = request()->post("guild_id");
        $contact_name = request()->post('contact_name');
        $contact_phone = request()->post('contact_phone');
        $contact_email = request()->post('contact_email');
        $contact_wechat = request()->post('contact_wechat');
        $guild_name = request()->post('guild_name');
        $guild_introduce = request()->post('guild_introduce');
        $guildmodel = new guildmodel();
        $guild_detail = $guildmodel->get_my_guild($guild_id);
        if($contact_name == $guild_detail[0]["contact_name"] && $contact_phone == $guild_detail[0]["contact_phone"] && $contact_email == $guild_detail[0]["contact_email"]
        && $contact_wechat == $guild_detail[0]["contact_wechat"] && $guild_name == $guild_detail[0]["guild_name"] && $guild_introduce == $guild_detail[0]["guild_introduce"])
        {
            $result = [
                'code'=>2,
                'msg'=>'您没有修改任何内容~'
            ];
            return $result;
        }
        $change_guild = $guildmodel->change_guild($guild_id,$contact_name,$contact_phone,$contact_email,$contact_wechat,$guild_name,$guild_introduce);
        if($change_guild)
        {
            $result = [
                'code'=>1,
                'msg'=>'资料修改成功！'
            ];
            return $result;
        }
        else 
        {
            $result = [
                'code'=>0,
                'msg'=>'资料修改失败~'
            ];
            return $result;
        }

     }





    public function join_guild()
    {
        $login = new Login();
        $user_id = $login->is_login();
        $join_guild_id = request()->post('guild_id');
        $user = new User();
        $user = $user->get_user($user_id);
        $join_guild_id_original = $user[0]['join_guild_id'];
        $create_guild_id_original = $user[0]['create_guild_id'];
        $join_guild_states = $user[0]['join_guild_states'];
        if($create_guild_id_original)
        {
            $result = [
                'code'=>3,
                'msg'=>'亲亲，您已经创建有公会了~'
            ];
            return $result;
        }
        if($join_guild_id_original && $join_guild_states != 0)
        {
            $result = [
                'code'=>2,
                'msg'=>'亲亲，您已经加入有公会了~'
            ];
            return $result;
        }
        $join_guild_states = 2;
        $usermodel = new usermodel();
        $join = $usermodel->join_guild($user_id,$join_guild_id,$join_guild_states);
        if($join)
        {
            $result = [
                'code'=>1,
                'msg'=>'申请公会成功！'
            ];
        }
        else
        {
            $result = [
                'code'=>0,
                'msg'=>'亲亲，申请公会失败哦~'
            ];
        }
        return $result;
    }

    public function quit_guild()
    {
        $guild_id = request()->post('guild_id');
        $login = new Login();
        $user_id = $login->is_login();
        $join_guild_id = '';
        $usermodel = new usermodel();
        $guildmodel = new guildmodel();
        $quit = $usermodel->quit_guild($user_id,$join_guild_id);
        $my_guild = $guildmodel->get_my_guild($guild_id);
        $people_number = $my_guild['0']['people_number'];
        $people_number = $people_number - 1;
        $update_people_number = $guildmodel->update_people_number($people_number,$guild_id);
        if($quit && $update_people_number)
        {
            $result = [
                'code'=>1,
                'msg'=>'退出公会成功！'
            ];
        }
        else
        {
            $result = [
                'code'=>0,
                'msg'=>'亲亲，退出公会失败啦~'
            ];

        }
        return $result;
    }

    public function re_create_guild()
    {
        $login = new Login();
        $user_id = $login->is_login();
        $guild_id = request()->post("guild_id");
        $imgurl_business_license = request()->post('imgurl_business_license');
        $imgurl_bank_account = request()->post('imgurl_bank_account');
        $company_name = request()->post('company_name');
        $company_introduce = request()->post('company_introduce');
        $contact_name = request()->post('contact_name');
        $contact_phone = request()->post('contact_phone');
        $contact_email = request()->post('contact_email');
        $contact_wechat = request()->post('contact_wechat');
        $guild_name = request()->post('guild_name');
        $guild_introduce = request()->post('guild_introduce');
        $guild_experience = request()->post("guild_experience");
        $guild_advantage = request()->post("guild_advantage");
        $guild_demeanor = request()->post('guild_demeanor');
        $people_number = 0;
        $guild_states = 2;
        $guild_time = date("Y-m-d H:i:s",time());
        $guildmodel = new guildmodel();
        $re_create_guild = $guildmodel->re_create_guild($guild_id,$company_name,$company_introduce,$contact_name,$contact_phone,
        $contact_email,$contact_wechat,$guild_name,$guild_introduce,$people_number,$guild_time,$user_id,$guild_states,$imgurl_business_license,$imgurl_bank_account,$guild_demeanor,
        $guild_experience,$guild_advantage);
        if($re_create_guild)
        {
            $result = [
                'code'=>1,
                'msg'=>'申请成功！'
            ];
        }
        else 
        {
            $result = [
                'code'=>0,
                'msg'=>'亲亲，申请失败了哦~'
            ]; 
        }
        return $result;

    }

    public function create_guild()
    {
        $login = new Login();
        $user_id = $login->is_login();
        $user = new User();
        $user = $user->get_user($user_id);
        $join_guild_id_original = $user[0]['join_guild_id'];
        $create_guild_id_original = $user[0]['create_guild_id'];
        if($create_guild_id_original)
        {
            $result = [
                'code'=>3,
                'msg'=>'亲亲，您已经创建有公会了~'
            ];
            return $result;
        }
        if($join_guild_id_original)
        {
            $result = [
                'code'=>2,
                'msg'=>'亲亲，您已经加入有公会了~'
            ];
            return $result;
        }
        $imgurl_business_license = request()->post('imgurl_business_license');
        $imgurl_bank_account = request()->post('imgurl_bank_account');
        $company_name = request()->post('company_name');
        $company_introduce = request()->post('company_introduce');
        $contact_name = request()->post('contact_name');
        $contact_phone = request()->post('contact_phone');
        $contact_email = request()->post('contact_email');
        $contact_wechat = request()->post('contact_wechat');
        $guild_name = request()->post('guild_name');
        $guild_introduce = request()->post('guild_introduce');
        $guild_experience = request()->post("guild_experience");
        $guild_advantage = request()->post("guild_advantage");
        $guild_demeanor = request()->post('guild_demeanor');
        $people_number = 0;
        $guild_states = 2;
        $guild_time = date("Y-m-d H:i:s",time());
        $guildmodel = new guildmodel();
        $create_guild = $guildmodel->create_guild($company_name,$company_introduce,$contact_name,$contact_phone,
        $contact_email,$contact_wechat,$guild_name,$guild_introduce,$people_number,$guild_time,$user_id,$guild_states,$imgurl_business_license,$imgurl_bank_account,$guild_demeanor,
        $guild_experience,$guild_advantage);
        if($create_guild)
        {
            $get_create_guild = $guildmodel->get_create_guild($user_id);
            $create_guild_id = $get_create_guild[0]['guild_id']; 
            $usermodel = new usermodel();
            $create_guild_user = $usermodel->create_guild($user_id,$create_guild_id);
            if($create_guild_user)
            {
                $result = [
                    'code'=>1,
                    'msg'=>'申请成功！'
                ];
            }
        }
        else
        {
            $result = [
                'code'=>0,
                'msg'=>'亲亲，申请失败了哦~'
            ];
        }
        return $result;

    }


    public function upload_guild_img()
    {
        $business_license = request()->file('business_license');
        $bank_account = request()->file('bank_account');
        $guild_demeanor = request()->file('guild_demeanor');
        if($business_license && $bank_account && $guild_demeanor)
        {
            $info1 = $business_license->validate(['size'=>5242880,'ext'=>'jpg,jpeg,png,gif'])->move(ROOT_PATH . 'public/static/img');
            $info2 = $bank_account->validate(['size'=>5242880,'ext'=>'jpg,jpeg,png,gif'])->move(ROOT_PATH . 'public/static/img');
            $info3 = $guild_demeanor->validate(['size'=>5242880,'ext'=>'jpg,jpeg,png,gif'])->move(ROOT_PATH . 'public/static/img');
               if ($info1 && $info2 && $info3)
               {
                $imgurl_business_license = str_replace("\\","/",$info1->getSaveName());
                $imgurl_bank_account = str_replace("\\","/",$info2->getSaveName());
                $guild_demeanor = str_replace("\\","/",$info3->getSaveName());
                $imgurl = [
                    'imgurl_business_license'=>$imgurl_business_license,
                    'imgurl_bank_account'=>$imgurl_bank_account,
                    'guild_demeanor'=>$guild_demeanor
                ];
                return $imgurl;
               }
               else
               {
                   $result = [
                       'code'=>0,
                       'msg'=>'亲亲，图片上传失败哦~'
                   ];
                   return $result;
               }
        }
    }


    public function guild_search()
    {
        $search_words = request()->post('search_words');
        if($search_words=='')
        {
            return;
        }
        $guildmodel = new guildmodel();
        $guild = $guildmodel->guild_search($search_words);
        return $guild;
    }


    public function join_check()
    {
        $join_user_id = request()->post('join_user_id');
        $agree = request()->post('agree');
        if($agree)
        {
            $usermodel = new usermodel();
            $join_agree = $usermodel->join_agree($join_user_id);
            $user = $usermodel->get_user($join_user_id);
            $join_guild_id = $user[0]['join_guild_id'];
            $guildmodel = new guildmodel();
            $my_guild = $guildmodel->get_my_guild($join_guild_id);
            $people_number = $my_guild['0']['people_number'];
            $people_number = $people_number + 1;
            $update_people_number = $guildmodel->update_people_number($people_number,$join_guild_id);
            if($join_agree && $update_people_number)
            {
                $result = [
                    'code'=>1,
                    'msg'=>'已同意'
                ];
            }
            else
            {
                $result = [
                    'code'=>0,
                    'msg'=>'亲亲，服务器异常了哦~'
                ];
            }
        }
        else
        {
            $usermodel = new usermodel();
            $join_reject = $usermodel->join_reject($join_user_id);
            if($join_reject)
            {
                $result = [
                    'code'=>1,
                    'msg'=>'已拒绝'
                ];
            }
            else
            {
                $result = [
                    'code'=>0,
                    'msg'=>'亲亲，服务器异常了哦~'
                ];
            }
        }
        return $result;
    }


    public function get_guild($guild_id)
    {
        $guild = $this->select($guild_id);
        return $guild;
    }


    public function set_check()
    {
        $is_check = request()->post("is_check");
        $user_id_set = request()->post("user_id_set");
        $usermodel = new usermodel();
        $set_check = $usermodel->set_check($user_id_set,$is_check);
        if($set_check)
        {
            if($is_check)
            {
                $result = [
                    "code"=>1,
                    "msg"=>"已设置成质检员！"
                ];
            }
            else 
            {
                $result = [
                    "code"=>1,
                    "msg"=>"已取消设置质检员！"
                ];
            }

        }
        else
        {
            $result = [
                "code"=>0,
                "msg"=>"设置失败！"
            ];
        }
        return $result;
    }

}

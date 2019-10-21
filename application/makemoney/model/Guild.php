<?php
namespace app\makemoney\model;
use think\Model;

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




    public function guild()
    {
        $guild = $this->where('guild_states',1)->limit(10)->select();
        return $guild;
    }


    public function get_my_guild($guild_id)
    {
        $my_guild = $this->select($guild_id);
        return $my_guild;
    }

   public function change_guild($guild_id,$contact_name,$contact_phone,$contact_email,$contact_wechat,$guild_name,$guild_introduce)
   {
    $data = [
        'contact_name'=>$contact_name,
       'contact_phone'=>$contact_phone,
       'contact_email'=>$contact_email,
       'contact_wechat'=>$contact_wechat,
       'guild_name'=>$guild_name,
       'guild_introduce'=>$guild_introduce
    ];
    $where = [
        "guild_id"=>$guild_id
    ];
    $change_guild = $this->save($data,$where);
    return $change_guild;

   }

   public function re_create_guild($guild_id,$company_name,$company_introduce,$contact_name,$contact_phone,
        $contact_email,$contact_wechat,$guild_name,$guild_introduce,$people_number,$guild_time,$user_id,$guild_states,$imgurl_business_license,$imgurl_bank_account,$guild_demeanor,
        $guild_experience,$guild_advantage)
        {
            $data = [
                "company_name"=>$company_name,
                "company_introduce"=>$company_introduce,
                'contact_name'=>$contact_name,
               'contact_phone'=>$contact_phone,
               'contact_email'=>$contact_email,
               'contact_wechat'=>$contact_wechat,
               'guild_name'=>$guild_name,
               'guild_introduce'=>$guild_introduce,
               'people_number'=>$people_number,
               'guild_time'=>$guild_time,
               'create_user_id'=>$user_id,
               'guild_states'=>$guild_states,
               "guild_experience"=>$guild_experience,
               "guild_advantage"=>$guild_advantage,
               'imgurl_business_license'=>$imgurl_business_license,
               'imgurl_bank_account'=>$imgurl_bank_account,
               "guild_demeanor"=>$guild_demeanor
            ];
            $where = [
                "guild_id"=>$guild_id
            ];
            $re_create_guild = $this->save($data,$where);
            return $re_create_guild;

        }



    public function create_guild($company_name,$company_introduce,$contact_name,$contact_phone,$contact_email,
    $contact_wechat,$guild_name,$guild_introduce,$people_number,$guild_time,$user_id,$guild_states,$imgurl_business_license,$imgurl_bank_account,$guild_demeanor,$guild_experience,$guild_advantage)
    {
        $data = [
            "company_name"=>$company_name,
            "company_introduce"=>$company_introduce,
            'contact_name'=>$contact_name,
           'contact_phone'=>$contact_phone,
           'contact_email'=>$contact_email,
           'contact_wechat'=>$contact_wechat,
           'guild_name'=>$guild_name,
           'guild_introduce'=>$guild_introduce,
           'people_number'=>$people_number,
           'guild_time'=>$guild_time,
           'create_user_id'=>$user_id,
           'guild_states'=>$guild_states,
           "guild_experience"=>$guild_experience,
           "guild_advantage"=>$guild_advantage,
           'imgurl_business_license'=>$imgurl_business_license,
           'imgurl_bank_account'=>$imgurl_bank_account,
           "guild_demeanor"=>$guild_demeanor
        ];
        $create_guild = $this->save($data);
        return $create_guild;
    }


    public function get_create_guild($user_id)
    {
        $get_create_guild = $this->where('create_user_id',$user_id)->select();
        return $get_create_guild;
    }


    public function guild_search($search_worlds)
    {
        $guild = $this->where('guild_states',1)->where('guild_name', 'like', "%".$search_worlds."%")->limit(10)->select();
        return $guild;
    }

    public function update_people_number($people_number,$join_guild_id)
    {
        $data = [
            'people_number'=>$people_number
        ];
        $where = [
            'guild_id'=>$join_guild_id
        ];
        $update_people_number = $this->save($data,$where);
        return $update_people_number;
    }

}

 
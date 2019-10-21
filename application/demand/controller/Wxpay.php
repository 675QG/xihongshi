<?php
namespace app\demand\controller;
use think\Controller;
use app\index\controller\Login;
use app\makemoney\model\User as usermodel;
use think\Session;
use think\Loader;

class Wxpay extends Controller
{
    public function wxpay()
    {
        $login = new Login();
        $user_id = $login->is_login();
        $usermodel = new usermodel();
        $user = $usermodel->get_user($user_id);
        $this->assign('user',$user);
    // header("location:https://graph.qq.com/oauth2.0/authorize?response_type=token&client_id=101566515&redirect_uri=http://www.diannaozaixian.com/index.php/wap/login/callback&scope=2");  

   //获取open_id  https://graph.qq.com/oauth2.0/me?access_token=YOUR_ACCESS_TOKEN

  //获取用户信息 https://graph.qq.com/user/get_user_info?access_token=YOUR_ACCESS_TOKEN&oauth_consumer_key=YOUR_APP_ID&openid=YOUR_OPENID
      //  $url = 'https://graph.qq.com/oauth2.0/authorize';
       // $url = 'http://www.baidu.com/';
      //  $params = [
      //      "response_type"=>"token",
      //      "client_id"=>"101566515",
      //      "redirect_uri"=>"http://www.diannaozaixian.com/index.php/wap/login/callback",
           // "state"=>"test"
      //     "scope"=>2
     //   ];
     //   $method = 'GET';
      //  $data = $this->request($url,$method,'',$params);
      //  dump($data);

    //微信
  //  https://open.weixin.qq.com/connect/qrconnect?appid=wx97aea1f757d5a403&redirect_uri=www.diannaozaixian.com/index.php/wap/Login/callback&response_type=code&scope=snsapi_loginCOPE&state=STATE#wechat_redirect

     //   return $this->fetch('wxpay');

     //支付
    // if($needSignType) {
      //  $this->SetSignType($config->GetSignType());
   // }
    //签名步骤一：按字典序排序参数
    //ksort($this->values);
   // $string = $this->ToUrlParams();
    //签名步骤二：在string后加入KEY
   // $string = $string . "&key=".$config->GetKey();
    //签名步骤三：MD5加密
   // $string = md5($string);
    //签名步骤四：所有字符转为大写
  //  $result = strtoupper($string);
  //  return $result;
    $url = "https://api.mch.weixin.qq.com/pay/unifiedorder";
    $appid = "wx6311a7193ee6f226";
    $mch_id = "1538113591";
    $nonce_str = "a321daghag32ag654a6g32";
    $sign = "";
    $body = "充值中心";
    $out_trade_no = "20150806125346";
    $total_fee = "120";
    $spbill_create_ip = "123.149.7.17";
    $notify_url = "http://www.xihongshi.com/";
    $trade_type = "NATIVE";
    $keyy = "suxiang201088suxiang201088201088";

    $data = [
        "appid"=>$appid,
        "mch_id"=>$mch_id,
        "nonce_str"=>$nonce_str,
        "body"=>$body,
        "out_trade_no"=>$out_trade_no,
        "total_fee"=>$total_fee,
        "spbill_create_ip"=>$spbill_create_ip,
        "notify_url"=>$notify_url,
        "trade_type"=>$trade_type
    ];
    ksort($data);
    $string = "";

    foreach ($data as $key => $value) {
        if($string == "")
        {
            $string = $key ."=".$value;
        }
        else if($key == 'notify_url')
        {
            $string = $string . htmlspecialchars("&" . $key."=" . $value);
        }
       else
       {
        $string = $string ."&" . $key."=" . $value;
       }
    }
    $string =  $string ."&key=" . $keyy;
    $string = md5($string);
    $sign = strtoupper($string);
    //return $string;
  //  $data["sign"] = $sign;

    $data = "<xml>
    <appid>wx6311a7193ee6f226</appid>
    <body>支付测试</body>
    <mch_id>1538113591</mch_id>
    <nonce_str>a321daghag32ag654a6g32</nonce_str>
    <notify_url>http://www.xihongshi.com/</notify_url>
    <out_trade_no>20150806125346</out_trade_no>
    <spbill_create_ip>123.149.7.17</spbill_create_ip>
    <total_fee>120</total_fee>
    <trade_type>NATIVE</trade_type>
    <sign>".$sign."</sign>
  </xml> ";

    $method = 'POST';
    $result = $this->request($url,$method,'',$data);
  dump($result);


    }


function request($url, $method = 'GET', $headers = '', $params = ''){
    if (is_array($params)) {
        $requestString = http_build_query($params);
    } else {
        $requestString = $params ? : '';
    }
    if (empty($headers)) {
        $headers = array('Content-type: text/xml'); 
    } elseif (!is_array($headers)) {
        parse_str($headers,$headers);
    }
    // setting the curl parameters.
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_VERBOSE, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    // turning off the server and peer verification(TrustManager Concept).
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    // setting the POST FIELD to curl
    switch ($method){  
        case "GET" : curl_setopt($ch, CURLOPT_HTTPGET, 1);break;  
        case "POST": curl_setopt($ch, CURLOPT_POST, 1);
                     curl_setopt($ch, CURLOPT_POSTFIELDS, $requestString);break;  
        case "PUT" : curl_setopt ($ch, CURLOPT_CUSTOMREQUEST, "PUT");   
                     curl_setopt($ch, CURLOPT_POSTFIELDS, $requestString);break;  
        case "DELETE":  curl_setopt ($ch, CURLOPT_CUSTOMREQUEST, "DELETE");   
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $requestString);break;  
    }
    // getting response from server
    $response = curl_exec($ch);
    
    //close the connection
    curl_close($ch);
    
    //return the response
  //  if (stristr($response, 'HTTP 404') || $response == '') {
    //    return array('Error' => '请求错误');
  //  }
    return $response;
}

    

}
<?php
use PHPMailer\PHPMailer\PHPMailer;


function send_mail($tomail, $name, $subject = '', $body = '', $attachment = null) {
 
    $mail = new PHPMailer();       //实例化PHPMailer对象
    $mail->CharSet = 'UTF-8';           //设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置，否则乱码
    $mail->IsSMTP();                    // 设定使用SMTP服务
    $mail->SMTPDebug = 0;               // SMTP调试功能 0=关闭 1 = 错误和消息 2 = 消息
    $mail->SMTPAuth = true;             // 启用 SMTP 验证功能
    $mail->SMTPSecure = 'ssl';          // 使用安全协议
    $mail->Host = "smtp.qq.com"; // SMTP 服务器
    $mail->Port = 465;                  // SMTP服务器的端口号
    $mail->Username = "1353605146@qq.com";    // SMTP服务器用户名
    $mail->Password = "atqsxwlehgkfffba";     // SMTP服务器密码
    $mail->SetFrom('service@diannaozaixian.com', '西红柿数据');
    $replyEmail = '';                   //留空则为发件人EMAIL
    $replyName = '西红柿数据';                    //回复名称（留空则为发件人名称）
    $mail->AddReplyTo($replyEmail, $replyName);
    $mail->Subject = $subject;
    $mail->MsgHTML($body);
    $mail->AddAddress($tomail, $name);
    if (is_array($attachment)) { // 添加附件
        foreach ($attachment as $file) {
            is_file($file) && $mail->AddAttachment($file);
        }
    }
   // return $mail->Send() ? true : $mail->ErrorInfo;
   return $mail->Send();
}
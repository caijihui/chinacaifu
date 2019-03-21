<?php
/**
 * Created by PhpStorm.
 * User: homepc
 * Date: 2017/3/24
 * Time: 18:23
 */
class PublicAction extends Action{

    public function sendMail($subject,$content,$address){
        $content = preg_replace("/[\\\\]/", '', $content); //对邮件内容进行必要的过滤
        //include("ThinkPHP/Extend/Vendor/Email/phpmailer/class.phpmailer.php");
        include_once "ThinkPHP/Extend/Vendor/Email/phpmailer/class.phpmailer.php";
        $mail = new PHPMailer();
        $mail->CharSet = "UTF-8";//设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置，否则乱码
        $mail->IsSMTP(); // 设定使用SMTP服务
        $mail->SMTPDebug  = 1;                     // 启用SMTP调试功能
        $mail->SMTPAuth   = true;                  // 启用 SMTP 验证功能
        $mail->Host       = "smtp.163.com";      // SMTP 服务器
        $mail->Port       = "25"; // SMTP服务器的端口号
        $mail->Username   = "a958243544@163.com";  // SMTP服务器用户名，
        $mail->Password   = "cjh0908";//SMTP服务器密码
        $mail->SetFrom("a958243544@163.com", '中国财富');
        $mail->Subject    = $subject;
        $mail->AltBody    = 'To view the message, please use an HTML compatible email viewer!'; // optional, comment out and test
        $mail->IsHTML(true);
        $mail->MsgHTML($content);
        $mail->AddAddress($address, '');
        return $mail->Send();

    }



}
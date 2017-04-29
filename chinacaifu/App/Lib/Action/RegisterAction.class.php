<?php

header("content-type:text/html;charset=utf-8");

class RegisterAction extends Action {
    public $mobile;

    public function check(){
        switch ($_POST['act']){
            case "checkmobile":
                $mobile=$_POST['mobile'];
                $res=M('user')->where(['mobile'=>$mobile])->count();
                break;
            case "checkemail":
                $email=$_POST['email'];
                $res=M('user')->where(['email'=>$email])->count();
                break;
            case "checkFromcode":
                $code=$_POST['code'];
                $res=M('user')->where("visit_code=$code")->count();
                break;
        }
            if ($res==0){
                echo "1";
            }else{
                echo "0";
            }
    }

    //注册页面，只显示
    public function register(){
        if ($_GET['from_code']&&!empty($_GET['from_code'])){
            $from_code=$_GET['from_code'];
            $this->assign('from_code',$from_code);
        }
        $this->display();
    }

    public function insert(){
        if (empty($_POST)){
            $this->error("输入不能为空");
        }
        $code=$_POST['code'];
        $this->mobile=trim($_POST['mobile']);
        $mobile=$this->mobile;
        $password=trim($_POST['password']);
        $repassword=trim($_POST['repassword']);
        $email=trim($_POST['email']);
        $from_code=trim($_POST['from_code']);
        $type=$_POST['type'];
        $res=M('user')->where("mobile=$mobile")->count();
        if ($res!=0){
            $this->error("手机号已存在!");
        }
        $n=M('user')->where(['email'=>$email])->count();
        if ($n!=0){
            $this->error("邮箱已存在,请更换!");
        }
        if (empty($mobile) or empty($password)){
            $this->error("手机号或者密码不能为空");
        }
        if (!preg_match("/^1[34578]{1}\d{9}$/",$mobile)){
            $this->error("手机号码不符合规范");
        }
        if ($password!=$repassword){
            $this->error("两次密码输入不一致，重新输入");
        }
        if (strlen($password)<6){
            $this->error("密码不能少于6位");
        }
        if (!preg_match("/^\w+(\.\w+)*@\w+(\.\w+)+$/i", $email)){
            $this->error("邮箱格式不正确");
        }
        $ycode = M('sms')->where(['email' => $email])->order("ctime desc")->getField("code");
        if ($code != $ycode) {
            $this->error("请输入正确的验证码!");
        }
        $user=D('user');
        if (!empty($from_code)){
            $res1=$user->where("visit_code={$from_code}")->count();
            if ($res1=0){
                $this->error("邀请码不存在,请重新输入邀请码或者输入8888");
            }
        }
        if ($user->create()){
            $a="";
            for ($i=0;$i<4;$i++){
                $code=mt_rand(0, 9);
                $a.=$code;
            }
            $res2=$user->where("visit_code={$a}")->count();
            if ($res2!=0){
                $a=$a+1;
            }
            $password=md5($password.'abc');
            $pay_password=substr($mobile,-6);
            $pay_password=md5($pay_password.'cba');
            $time=time();
            $user->ctime=$time;
            $user->visit_code=$a;
            $user->status=1;
            $user->is_invest=2;
            $user->email=$email;
            $user->type=$type;
            $user->pay_password=$pay_password;
            $user->password=$password;
            $user_id=$user->add();
            //注册2张送券
            $promotionRows=M('promotion')->where("start_date<={$time} and status=1")->select();
            $ticket=M('ticket');
            foreach ($promotionRows as $one){
                $ticket->ticket_name=$one['ticket_name'];
                $ticket->amount=$one['amount'];
                $ticket->mininvest=$one['amount']*20;
                $ticket->user_id=$user_id;
                $ticket->status=1;
                $starttime=time();
                $endtime=$starttime+3600*2*12*60;
                $ticket->start_date=$time;
                $ticket->end_date=$endtime;
                $ticket->add();
            }

            session('mobile',$_POST['mobile']);
            session("login",1);
            $this->success("注册成功",U('User/center'));
        }else {
            //$this->error("注册失败",U('Login/register'));
            print_r($user->getError());
        }


    }

    public function mail(){
        $email=$_POST['email'];
        $type=$_POST['type'];
        if ($type==1){
            if (empty($email)){
                echo "邮箱不能为空！";
                exit();
            }
            $subject="找回密码";
            $n=M('user')->where(['email'=>$email])->count();
            if($n==0){
                echo "用户不存在，请注册！";
                exit();
            }
        }
        if ($type==2){
            $subject="注册";
        }
            $a="";
            for ($i=0;$i<6;$i++){
                $code=mt_rand(0, 9);
                $a.=$code;
            }
            $content="您本次{$subject}操作验证码为：{$a}。（中国财富理财平台）";
            //$time=time();
            $time=date("Y-m-d H:i:s",time());
            $sms=M('sms');
            $sms->email=$email;
            $sms->content=$content;
            $sms->code=$a;
            $sms->type=$type;
            $sms->status=1;
            $sms->ctime=$time;
            if (empty($email)){
                echo "邮箱不能为空";
                exit();
            }
            $mail=new PublicAction();
            $res=$mail->sendMail($subject,$content,$email);
            if (!$res){
                echo "发送失败，异常";
                exit();
            }
            $res=$sms->add();
            if ($res){
                echo "发送成功";
                exit();
            }

        }

    public function forgetpwd(){
        if (isset($_POST)&&!empty($_POST)) {
            $code = $_POST['code'];
            $email = $_POST['email'];
            $newpwd = $_POST['newpwd'];
            if (!preg_match("/^\w+(\.\w+)*@\w+(\.\w+)+$/i", $email)){
                    $this->error("邮箱格式不正确或不为空");
                }
            $n = M('user')->where(['email' => $email])->count();
            if ($n == 0) {
                $this->error("未注册！", U('Register/register'));
            }
            if (strlen($newpwd)<6){
                $this->error("密码不能少于6位");
            }
            $ycode = M('sms')->where(['email' => $email])->order("ctime desc")->getField("code");
            if ($code != $ycode) {
                $this->error("请输入正确的验证码!");
            }
            $newpwd=md5($newpwd.'abc');
            $res = M('user')->where(['email'=>$email])->save(['password' => $newpwd]);
            $status=M('user')->where(['email'=>$email])->getField('status');
            if ($status==2){
                M('user')->where(['email'=>$email])->save(['status'=>1]);
            }
           // echo M()->getLastSql();
            if (!$res) {
                $this->error("修改出错!");
            }
            $this->success("修改密码成功，请登录",U('Login/login'));
        }

        $this->display();
    }

    	 
}
?>
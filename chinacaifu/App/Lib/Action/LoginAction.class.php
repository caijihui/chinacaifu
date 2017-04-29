<?php

header("content-type:text/html;charset=utf-8");

class LoginAction extends Action {

    public function exits(){
        $mobile=$_SESSION['mobile'];
        if ($_POST['act']=='exit'){
            session(null);
            session('[destory]');
            cookie(session_name(),null);
            echo "success";
        }
    }
    public function login(){
    	 
		 $this->display();
    }
    public function Backlogin(){
        $this->display();
    }
    public function verify(){
    	import('ORG.Util.Image');
    	//图片验证码
    	Image::buildImageVerify(4,1,png,42,28);
    	//汉字验证码
    	//Image::GBVerify(4);
    }
    //登录验证
    function check(){
    	//dump($_POST);
        $mobile=$_POST['mobile'];
        if(empty($_POST['mobile'])){
            $this->error("手机号不能为空");
        }
        if (empty($_POST['password'])){
            $this->error("密码不能为空");
        }
    	$user=D('User');
        $c=$user->where(['mobile'=>$mobile])->count();
        if ($c==0){
            $this->error("手机号不存在！");
        }
        $password=md5($_POST['password'].'abc');
        $_POST['password']=$password;
   		$result=$user->where($_POST)->find();
   		//var_dump($_SESSION['verify']);
    	if($result){
            if (md5($_POST['code'])!=$_SESSION['verify']){
                $this->error("验证码错误");
                exit();
            }
    		session('mobile',$_POST['mobile']);
    		session("login",1);
    		if($_POST['type']==1) {
                $this->success("登录成功", U('User/center'));
            }
            if ($_POST['type']==3){
    		    session('tel',$_POST['mobile']);
                $this->success("运营后台登录成功", U('Backend/index'));
            }
    	}else {
    	    //echo M()->getLastSql();
    		$this->error("密码错误或者账户异常!");
    	}
    }


    public function logOut(){
    	session(null);
    	session('[destory]');
    	cookie(session_name(),null);
    	$this->success("退出成功",U('Login/login'));
    }

    public function backlogOut(){
        session(null);
        session('[destory]');
        cookie(session_name(),null);
        $this->success("退出成功",U('Login/Backlogin'));
    }

}


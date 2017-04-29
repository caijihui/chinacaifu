<?php

class CommonAction extends Action {
	//权限每次都调用
    public function _initialize(){
		
		if(!$_SESSION['mobile']){

            $this->error("您未登录！",U('Login/login'));
		}

    }


}

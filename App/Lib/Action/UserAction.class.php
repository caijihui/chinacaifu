<?php

header("content-type:text/html;charset=utf-8");

class UserAction extends CommonAction {
    //手机号
    protected $mobile;
	//账户首页
    public function center(){
    	 $this->mobile=$_SESSION['mobile'];
    	 $mobile=$this->mobile;
    	 $uid=M("user")->where("mobile={$mobile}")->getField('id');
    	 $visit_code=M('user')->where("mobile={$mobile}")->getField('visit_code');
    	 //邀请人数
    	 $ynum=M('user')->where("from_code={$visit_code}")->count();
    	 $data=M('user')->field("ye,invest_money")->where("mobile={$mobile}")->select();
    	 $allinvest=M('invest_record')->field("sum(invest_amount) as allinvest")->where("uid={$uid}")->find();
    	 //待回款
    	 $dhks=M('invest_record')->field("sum(invest_amount) as dhk")->where("uid={$uid} and status=1")->find();
         $dhk=$dhks['dhk'];
         
         $all=$dhk+$data['0']['ye'];//净资产
    	 //已回款
    	 $yhks=M('invest_record')->field("sum(invest_amount) as yhk")->where("uid={$uid} and status=2")->find();
    	 $yhk=$yhks['yhk'];
    	 $time=time();
    	 //券数量
    	 $ticketnum=M('ticket')->where("user_id={$uid} and status=1 and end_date>{$time}")->count();
    	 $this->assign('ticketnum',$ticketnum);
    	 $this->assign('ynum',$ynum);
    	 $this->assign('all',$all);
    	 $this->assign('dhk',$dhk);
    	 $this->assign('yhk',$yhk);
    	 $this->assign('data',$data);
		 $this->display();
    }
    //用户点击默认投资记录
    public function invest(){
        $this->mobile=$_SESSION['mobile'];
        $mobile=$this->mobile;
        $uid=M('user')->where(['mobile'=>$mobile])->getField('id');
        $investModel=M('invest');
        $data=M('invest_record as r')
            ->field("r.get_amount as get_amount,i.pay_time as pay_time,i.invest_name as invest_name,r.invest_amount as invest_amount,r.ctime as ctime,r.status as status")
            ->join("right JOIN {$investModel->getTableName()} as i on i.id=r.invest_id")
            ->where("r.uid={$uid}")
            ->order("r.ctime desc")
            ->select();
        //待回款个数
        $dnum=M('invest_record')->where("mobile={$mobile} and status=1")->count();
        //已回款个数
        $ynum=M('invest_record')->where("mobile={$mobile} and status=2")->count();
        $allnum=$dnum+$ynum;
        foreach ($data as $v){
            $res['pay_time']=$v['pay_time'];
            $res['invest_name']=$v['invest_name'];
            $res['invest_amount']=$v['invest_amount'];
            $res['get_amount']=$v['get_amount'];
            $res['ctime']=date("Y-m-d H:i:s",$v['ctime']);
            if ($v['status']==1){
                $res['status']='待回款';
            }
            if ($v['status']==2){
                $res['status']='已回款';
            }
            $data1[]=$res;

        }
        //dump($data1);
        $this->assign('dnum',$dnum);
        $this->assign('ynum',$ynum);
        $this->assign('allnum',$allnum);
        $this->assign('data1',$data1);
        //print_r($_GET);
        $this->display();
    }
    //ajax 查询投资记录
    public function cha(){
    	
  		if ($_POST['status']&&!empty($_POST['status'])){

            $this->mobile=$_SESSION['mobile'];
            $mobile=$this->mobile;
            $uid=M('user')->where(['mobile'=>$mobile])->getField('id');
	    	$status=$_POST['status'];
	    	//$status=1;
	    	$investModel=M('invest');
	    	$data=M('invest_record as r')
	    	->field("r.get_amount as get_amount,i.pay_time as pay_time,i.invest_name as invest_name,r.invest_amount as invest_amount,r.ctime as ctime,r.status as status")
	    	->join("right JOIN {$investModel->getTableName()} as i on i.id=r.invest_id")
	    	->where("r.uid={$uid} and r.status={$status}")
	    	->order("r.ctime desc")
	    	->select();
	    	if (!$data){
	    		$data1=array();
	    		echo "0";
	    		//$this->ajaxReturn($data1,'查询条件出错',1);
	    	}
	    	
	    	foreach ($data as $v){
	    		$res['pay_time']=$v['pay_time'];
	    		$res['invest_name']=$v['invest_name'];
	    		$res['invest_amount']=$v['invest_amount'];
                $res['get_amount']=$v['get_amount'];
	    		$res['ctime']=date("Y-m-d H:i:s",$v['ctime']);
	    		if ($v['status']==1){
	    			$res['status']='待回款';
	    		}
	    		if ($v['status']==2){
	    			$res['status']='已回款';
	    		}
	    		$data1[]=$res;
	    	
	    	}
	    	//$this->ajaxReturn($data1,'',1);
	    	echo json_encode($data1);
  		}
    	
    }
    //资金记录
    public function moneyrecord(){
    	$mobile=$_SESSION['mobile'];
        $uid=M('user')->where(['mobile'=>$mobile])->getField('id');
    	$where=array();
    	$where['uid']=$uid;

    	if (isset($_GET['type'])&&!empty($_GET['type'])){
    		if ($_GET['type']=='1'){
    			$where['type']='充值';
    		}
    		if ($_GET['type']=='2'){
    			$where['type']='投资';
    		}
    		if ($_GET['type']=='3'){
    			$where['type']='提现';
    		}
    		
    	}
    	$data1=M('money_record')->where($where)->select();
    	foreach ($data1 as $v){
    		$res['type']=$v['type'];
    		$res['money']=$v['money'];
    		$res['remark']=$v['remark'];
    		$res['ctime']=date('Y-m-d H:i:s',$v['ctime']);
    		$data[]=$res;
    	}
    	$this->assign('data',$data);
    	$this->display();
    }
    
    //我的奖励
    public function myrewards(){
    	
    	$mobile=$_SESSION['mobile'];
    	$user_id=M('user')->where("mobile={$mobile}")->getField("id");
    	$where['user_id']=$user_id;
    	if (isset($_GET['status'])&&!empty($_GET['status'])){
    		$where['status']=$_GET['status'];
    	}
    	 
    	$data1=M('ticket')->where($where)->select();
    	foreach ($data1 as $v){
    		$res['amount']=$v['amount'];
    		$res['mininvest']=$v['mininvest'];
    		$res['ticket_name']=$v['ticket_name'];
    		if ($v['status']==1){
    			$res['status']="未使用";
    		}
    		if ($v['status']==2){
    			$res['status']='已使用';
    		}
    		$res['start_date']=date('Y-m-d',$v['start_date']);
    		$time=time();
    		if ($time>$v['end_date']){
    			$res['type']='过期';
    		}else{
    			$res['type']='未过期';
    		}
    		$res['end_date']=date('Y-m-d',$v['end_date']);
    		
    		$data[]=$res;
    		
    	}
    	//dump($data);
    	$this->assign('data',$data);
    	$this->display();
    }
    
    //体验金
    public function tyj(){
    	
    	$this->display();
    }
    //邀请好友
    public function invite(){
    	$mobile=$_SESSION['mobile'];
    	$visit_code=M('user')->where("mobile={$mobile}")->getField('visit_code');
    	//邀请人数
    	$ynum=M('user')->where("from_code={$visit_code}")->count();
    	//投资人数
    	$tnum=M('user')->where("mobile={$mobile} and is_invest=1")->count();
    	//邀请信息
    	$data=M('user')->field("invest_money,mobile,ctime")->where("from_code={$visit_code}")->select();
    	$i=1;
    	foreach ($data as $v){
    		$res['xuhao']=$i++;
    		$res['mobile']=$v['mobile'];
    		$res['invest_money']=$v['invest_money'];
    		$res['ctime']=date('Y-m-d H:i:s',$v['ctime']);
    		$datas[]=$res;
    	}
    	//dump($datas);
    	
    	
    	//投资总额
    	$investm=M('user')->field("sum(invest_money) as sum")->where("from_code={$visit_code}")->find();
    	$investnum=$investm['sum']?$investm['sum']:0;
    	$this->assign('ynum',$ynum);
    	$this->assign('tnum',$tnum);
    	$this->assign('investnum',$investnum);
    	$this->assign('visit_code',$visit_code);
    	$this->assign('datas',$datas);
    	$this->display();
    }
    //消息管理
    public function message(){
    	$mobile=$_SESSION['mobile'];
    	$ctime=date('Y-m-d H:s:i',time());
    	//echo $ctime;
    	$uid=M('user')->where("mobile={$mobile}")->getField("id");
    	$data=M('news')->where("uid={$uid}")->order("ctime desc")->select();
    	$this->assign('data',$data);
    	
    	//dump($data);
    	$this->display();
    }
    //账户管理
    public function change(){
    	$mobile=$_SESSION['mobile'];
        $user=new UserModel();
        $data=$_POST;
    	switch ($_POST['act']){

            case "changemobile":
                $user->changemobile($mobile,$data);
                break;
            case "changepwd":
                $user->changepwd($mobile,$data);
                break;
            case "changepaypwd":
                $user->changepaypwd($mobile,$data);
                break;
        }

    }

    public function manager(){
    	$mobile1=$_SESSION['mobile'];
    	$mobile=substr($mobile1,0,3).'****'.substr($mobile1,-4);
    	$this->assign('mobile',$mobile);
    	$this->display();
    }
    
}
?>
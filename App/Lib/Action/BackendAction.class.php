<?php
/**
 * Created by PhpStorm.
 * User: homepc
 * Date: 2017/3/15
 * Time: 16:14
 */
header("content-type:text/html;charset=utf-8");
class BackendAction extends Action {
    private $tel;
    //权限每次都调用
    public function _initialize(){
        if(!$_SESSION['tel']){
            $this->error("您未登录！",U('Login/Backlogin'));
        }
    }
    public function index(){
        $this->display();
    }

    public function ggList(){
        if (empty($_GET['p'])){
            $_GET['p']=1;
        }
        $data=M('wzgg')->page($_GET['p'],5)->select();
        $count=count($data);
        import('ORG.Util.Page');
        $page=new Page($count,5);
        $show=$page->show();
        $this->assign('page',$show);
        $this->assign('data',$data);
        $this->display();
    }
    //删除公告，不做处理
    public function ggResult(){
        if (isset($_GET)&&$_GET['act']=='del'){
            $id=$_GET['id'];
            $time=date('Y-m-d H:i:s');
            //$res=M('wzgg')->where(['id'=>$id])->save(['status'=>$status,'endtime'=>$time]);
            $res=0;
            if (!$res){
                $this->error("该功能暂时不能执行！当记录");
            }
            $this->success("操作成功",U('Backend/investList'));
        }

    }
    public function newgg(){
        if (isset($_GET)&&!empty($_GET['content'])){
            $title=$_GET['title'];
            if (empty($title)){
                $this->error("标题不能为空");
            }
            if (empty($content)){
                $this->error("内容不能为空");
            }
            $source=$_GET['source'];
            $content=$_GET['content'];
            $ctime=date('Y-m-d H:i:s',time());
            $wzgg=M('wzgg');
            $wzgg->title=$title;
            $wzgg->source=$source;
            $wzgg->content=$content;
            $wzgg->ctime=$ctime;
            $wzgg->ctime=$ctime;
            $res=$wzgg->add();
            if (!$res){
                $this->error("发布出错！");
            }
            echo "<script>alert('发布新公告成功');</script>";
        }
        $this->display();

    }
    public function investList(){
        if (empty($_GET['p'])){
            $_GET['p']=1;
        }
        $count=M('invest')->count();
        $data=M('invest')->field("id,invest_name,amount,rate,ye,progress,borrower,time_limit,bulid_time,status")
            ->page($_GET['p'],5)
            ->select();
        import('ORG.Util.Page');
        $page=new Page($count,5);
        $show=$page->show();
        $this->assign('page',$show);
        $this->assign('data',$data);
        $this->display();
    }
    public function newinvest(){
        if (isset($_GET)&&!empty($_GET['invest_name'])){
            $invest_name=$_GET['invest_name'];
            $amount=$_GET['amount'];
            $rate=$_GET['rate'];
            $pay_way=$_GET['pay_way'];
            $time_limit=$_GET['time_limit'];
            $time=time();
            $h=$time+2678400*$time_limit;
            $ctime=date('Y-m-d',time());
            $pay_time=date('Y-m-d',$h);
            if (empty($invest_name)){
                $this->error("不能为空");
            }
            $invest=M('invest');
            $invest->invest_name=$invest_name;
            $invest->amount=$amount;
            $invest->pay_way=$pay_way;
            $invest->pay_time=$pay_time;
            $invest->build_time=$ctime;
            $invest->time_limit=$time_limit;
            $invest->rate=$rate;
            $invest->ye=$amount;
            $res=$invest->add();
            if (!$res){
                echo M()->getLastSql();
                $this->error("发布出错！");
            }
            echo "<script>alert('发标成功');</script>";
        }
            $this->display();
    }
    public function investResult(){
        if (isset($_GET)&&!empty($_GET['r'])){
            $id=$_GET['id'];
            $status=$_GET['r'];
            $time=date('Y-m-d H:i:s');
            $res=M('borrowlist')->where(['id'=>$id])->save(['status'=>$status,'endtime'=>$time]);
            if (!$res){
                $this->error("出错");
            }
            $this->success("操作成功",U('Backend/investList'));
        }
    }
    public function datas(){
        $a=M('user')->where(['type'=>1])->count();
        $b=M('user')->where(['type'=>2])->count();
       $allmoney=M('user')->field("sum(invest_money) as allmoney")->find();
       $time=time()-86400;
       $time1=time()-86400*7;
       $today=M('invest_record')->field("sum(invest_amount) as invest_amount")->where("ctime>$time")->find();
       $todayinvestmoney=$today['invest_amount']?$today['invest_amount']:0;
       $week=M('invest_record')->field("sum(invest_amount) as invest_amount")->where("ctime>$time1")->find();
       $weekinvestmoney=$week['invest_amount']?$week['invest_amount']:0;
        $this->assign('a',$a);
        $this->assign('b',$b);
        $this->assign('allmoney',$allmoney);
        $this->assign('todayinvestmoney',$todayinvestmoney);
        $this->assign('weekinvestmoney',$weekinvestmoney);
        $this->display();
    }



}

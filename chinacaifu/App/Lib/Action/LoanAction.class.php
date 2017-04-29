<?php
class LoanAction extends Action {

    public $bname;

    public function borrow(){
        $this->display();
    }
    //借款申请
    public function loanApplication(){
        if (isset($_POST)&&!empty($_POST)){
            //print_r($_POST);
            $bname=$_POST['bname'];
            $bmobile=$_POST['bmobile'];
            $bamount=$_POST['bamount'];
            $btime_limit=$_POST['btime_limit'];
            $bhousenum=$_POST['bhousenum'];
            $bvalue=$_POST['bvalue'];
            $bdisc=$_POST['bdisc'];
            $buse_way=$_POST['buse_way'];
            if (isset($_POST['btype'])&&!empty($_POST['btype'])){
                $btype=1;
            }
            if (isset($_POST['btype1'])&&!empty($_POST['btype1'])){
                $btype=2;
            }
           // $bcode=$_POST['bcode'];
            try {
                if ($bamount<0){
                    throw new \Exception("金额不能为负数");
                }
                if ($btime_limit<0){
                    throw new \Exception("借款期限不能为负数");
                }
                if ($bhousenum<0){
                    throw new \Exception("房子数不能为负数");
                }
                if ($bvalue<0){
                    throw new \Exception("价值数不能为负数");
                }
                if (!preg_match("/^1[34578]{1}\d{9}$/",$bmobile)){
                    throw new \Exception("手机号码不符合规范");
                }
                if (md5($_POST['bcode'])!=$_SESSION['verify']){
                    throw new \Exception("验证码错误");

                }

            }catch (\Exception $e){

                $this->error($e->getMessage());
            }
            $borrowlist=M('borrowlist');
            $borrowlist->bname=$bname;
            $borrowlist->bmobile=$bmobile;
            $borrowlist->bamount=$bamount;
            $borrowlist->btime_limit=$btime_limit;
            $borrowlist->bhousenum=$bhousenum;
            $borrowlist->bvalue=$bvalue;
            $borrowlist->buse_way=$buse_way;
            $borrowlist->btype=$btype;
            $borrowlist->bdisc=$bdisc;
            $borrowlist->status=1;
            $bid=$borrowlist->add();
            if (!$bid){
                $this->error("出错");
            }
            echo "<script>alert('申请提交成功，请等待审核！');window.location='borrow';</script>";
            exit();
        }
        $this->display();
    }
    //申请借款列表
    public function loanList(){
        if(!$_SESSION['tel']){
                $this->error("您未登录！",U('Login/Backlogin'));
         }
        if (empty($_GET['p'])){
            $_GET['p']=1;
        }
        $count=M('borrowlist')->count();
        $data=M('borrowlist')->page($_GET['p'],5)->order('ctime asc')->select();
        import('ORG.Util.Page');
        $page=new Page($count,5);
        $show=$page->show();
        $this->assign('page',$show);
        $this->assign('data',$data);
        $this->display();

    }
    //借款审核结果
    public function loanResult(){
        if(!$_SESSION['tel']){
                $this->error("您未登录！",U('Login/Backlogin'));
         }
        if (isset($_GET)&&!empty($_GET['r'])){
            $id=$_GET['id'];
            $status=$_GET['r'];
            $time=date('Y-m-d H:i:s');
            $res=M('borrowlist')->where(['id'=>$id])->save(['status'=>$status,'aduittime'=>$time]);
            if (!$res){
                $this->error("出错");
            }
            $this->success("审核处理成功",U('Loan/loanList'));
        }

    }





}
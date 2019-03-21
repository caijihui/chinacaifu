<?php
/**
 * Created by PhpStorm.
 * User: homepc
 * Date: 2017/3/24
 * Time: 10:07
 */
class RepaymentAction extends Action{
    //权限每次都调用
    public function _initialize(){

        if(!$_SESSION['tel']){
            $this->error("您未登录！",U('Login/Backlogin'));
        }

    }
    //待还款列表
    public function  pendingList(){

        if (empty($_GET['p'])){
            $_GET['p']=1;
        }
        $count=M('invest')->where(['status'=>2])->count();
        $data=M('invest')->field("id,invest_name,amount,rate,ye,progress,borrower,time_limit,bulid_time,status")
            ->page($_GET['p'],5)
            ->where(['status'=>2])
            ->select();
        if (!$data){
            $this->assign('wu',"0");
        }else{
            $this->assign('wu','1');
        }
        import('ORG.Util.Page');
        $page=new Page($count,5);
        $show=$page->show();
        $this->assign('page',$show);
        $this->assign('data',$data);
        $this->display();
    }
    //还款动作,延期，返款
    public function repaymentAct(){
        $id=$_GET['id'];
        $act=$_GET['act'];
        switch ($act){
            case 'pay':
                try{
                    M()->startTrans();
                    $data=M('invest_record')->field('invest_amount,uid,get_amount')->where(['invest_id'=>$id,'satatus'=>2])->select();
                    foreach ($data as $v){
                        $info=M('user')->field('ye,freeze_money')->where(['id'=>$v['uid']])->find();
                        $newye=$info['ye']+$v['get_amount'];
                        $newfreeze=$info['freeze_money']-$v['invest_amount'];
                        $res=M('user')->where(['id'=>$v['uid']])->save(['ye'=>$newye,'freeze_money'=>$newfreeze]);
                        if (!$res){
                            throw new \Exception("出错0");
                        }
                        //2设置为已还款
                        $res1=M('invest_record')->where(['invest_id'=>$id])->save(['status'=>2]);
                        if (!$res1){
                            throw new \Exception("出错");
                        }
                        //3设置已还款
                        $res2=M('invest')->where(['id'=>$id])->save(['status'=>3]);
                        if (!$res2){
                            throw new \Exception("出错2");
                        }
                        if (!M()->commit()){
                            throw new \Exception("出错3");
                        }
                    }
                    $this->success("还款操作成功",U('Repayment/pendingList'));
                }catch (\Exception $e){
                    M()->rollback();
                    $this->error($e->getMessage());
                }

                break;
            case 'putoff':
                $this->success("延期操作成功",U('Repayment/pendingList'));
                break;
        }

    }






}
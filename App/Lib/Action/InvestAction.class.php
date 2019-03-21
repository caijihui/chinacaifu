<?php
/**
 * Created by PhpStorm.
 * User: homepc
 * Date: 2017/3/14
 * Time: 14:35
 */

header("content-type:text/html;charset=utf-8");

class InvestAction extends Action {
    //标的id
    private $invest_id;
    //标的类型
    public $type;
    const  DTYPE= 3;
    const  YTYPE= 4;
    //标的状态
    public $status;
    public function investList(){
        //加分页

        if (empty($_GET['p'])){
            $_GET['p']=1;
        }
        $codeModel=M('code');
        if (isset($_GET['status'])&&!empty($_GET['status'])){
            $this->status=$_GET['status'];
            $status=$this->status;
            $data=M('invest as i')
                ->field("*,i.id as id,i.progress*100 as progress,c.name as pay_way")
                ->join("right JOIN {$codeModel->getTableName()} as c on i.pay_way=c.wid")
                ->where("c.sign='pay_way'and i.status={$status}")
                ->page($_GET['p'],3)
                ->select();
            $count=M('invest as i')
                ->field("*,i.id as id,i.progress*100 as progress,c.name as pay_way")
                ->join("right JOIN {$codeModel->getTableName()} as c on i.pay_way=c.wid")
                ->where("c.sign='pay_way' and i.status={$status}")->count();
        }
        elseif(isset($_GET['type'])&&!empty($_GET['type'])){

            $this->type=$_GET['type'];
            $invest_type=$this->type;
            $data=M('invest as i')
                ->field("*,i.id as id,i.progress*100 as progress,c.name as pay_way")
                ->join("right JOIN {$codeModel->getTableName()} as c on i.pay_way=c.wid")
                ->where("c.sign='pay_way'and i.invest_type={$invest_type}")
                ->page($_GET['p'],3)
                ->select();
            $count=M('invest as i')
                ->field("*,i.id as id,i.progress*100 as progress,c.name as pay_way")
                ->join("right JOIN {$codeModel->getTableName()} as c on i.pay_way=c.wid")
                ->where("c.sign='pay_way' and i.invest_type={$invest_type}")->count();

        }
        elseif (isset($_GET['limit'])&&!empty($_GET['limit'])){
            $limit_time=$_GET['limit'];
            if ($limit_time==self::YTYPE){
                $data=M('invest as i')
                    ->field("*,i.id as id,i.progress*100 as progress,c.name as pay_way")
                    ->join("right JOIN {$codeModel->getTableName()} as c on i.pay_way=c.wid")
                    ->where("c.sign='pay_way'and i.time_limit>=3")
                    ->page($_GET['p'],3)
                    ->select();
                $count=M('invest as i')
                    ->field("*,i.id as id,i.progress*100 as progress,c.name as pay_way")
                    ->join("right JOIN {$codeModel->getTableName()} as c on i.pay_way=c.wid")
                    ->where("c.sign='pay_way' and i.time_limit>=3")->count();
            }
            if ($limit_time==self::DTYPE){
                $data=M('invest as i')
                    ->field("*,i.id as id,i.progress*100 as progress,c.name as pay_way")
                    ->join("right JOIN {$codeModel->getTableName()} as c on i.pay_way=c.wid")
                    ->where("c.sign='pay_way'and i.time_limit<3")
                    ->page($_GET['p'],3)
                    ->select();
                $count=M('invest as i')
                    ->field("*,i.id as id,i.progress*100 as progress,c.name as pay_way")
                    ->join("right JOIN {$codeModel->getTableName()} as c on i.pay_way=c.wid")
                    ->where("c.sign='pay_way' and i.time_limit<3")->count();
            }
        }
        else{
            $data=M('invest as i')
                ->field("*,i.id as id,i.progress*100 as progress,c.name as pay_way")
                ->join("right JOIN {$codeModel->getTableName()} as c on i.pay_way=c.wid")
                ->where("c.sign='pay_way'")
                ->page($_GET['p'],3)
                ->select();
            $count=M('invest as i')
                ->field("*,i.id as id,i.progress*100 as progress,c.name as pay_way")
                ->join("right JOIN {$codeModel->getTableName()} as c on i.pay_way=c.wid")
                ->where("c.sign='pay_way'")->count();
        }


        import('ORG.Util.Page');
        $page=new Page($count,3);
        $show=$page->show();
        $this->assign('page',$show);
        $this->assign('data',$data);
        $this->display();
    }
    public function detail(){

        //项目详情
        if (isset($_GET)&&!empty($_GET)){
            //if ()
           // $id=$_GET['id'];
            $this->invest_id=I('id');
            $id=$this->invest_id;
            //echo $id;
            $codeModel=M('code');
            $data=M('invest as i')
                ->field("*,i.id as id,progress*100 as progress,c.name as pay_way")
                ->join("right JOIN {$codeModel->getTableName()} as c on i.pay_way=c.wid")
                ->where("c.sign='pay_way'and i.id={$id}")
                ->select();
        }
        //dump($data);

        //投资记录
        $invest_record=M('invest_record')->field('mobile,invest_amount,ctime')->where("invest_id=$id")->select();
        $num=M('invest_record')->where("invest_id=$id")->count();
        foreach($invest_record as $one){
            $res['mobile']='*******'.substr($one['mobile'],-4);
            $res['invest_amount']=$one['invest_amount'];
            $res['ctime']=date('Y-m-d H:i',$one['ctime']);
            $recordlist[]=$res;
        }
        //留言记录
        $guestbook=M('guestbook')->field('mobile,content,ctime')->where("invest_id={$id}")->select();
        foreach($guestbook as $one){
            $res['mobile']='*******'.substr($one['mobile'],-4);
            $res['content']=$one['content'];
            $res['ctime']=date('Y-m-d H:i',$one['ctime']);
            $guestlist[]=$res;
        }

        //echo "<script>alert('不能为空,请重新填写!');window.location='login.php';</script>";
        $this->assign('num',$num);
        $this->assign('invest_id',$id);
        $this->assign('recordlist',$recordlist);
        $this->assign('data',$data);
        $this->assign('guestlist',$guestlist);
        $this->display();
    }
    public function guestbook(){
        if (!$_SESSION['mobile']){
            $this->error("您未登录，请登录后操作！",U('Login/login'));
        }
        $mobile=$_SESSION['mobile'];
         $uid=M('user')->where(['mobile'=>$mobile])->getField('id');
        $invest_id=$_POST['invest_id'];
        $c=M('invest_record')->where(['uid'=>$uid,'invest_id'=>$invest_id])->count();
        if ($c==0){
            $this->error("您还未投资此标，请投资后操作！");
        }
        $content=$_POST['content'];
        if (empty($content)){
            $this->error("留言评论区不能为空");
        }
        $ctime=time();
        $guestbook=M('guestbook');
        $guestbook->invest_id=$invest_id;
        $guestbook->uid=$uid;
        $guestbook->mobile=$mobile;
        $guestbook->content=$content;
        $guestbook->ctime=$ctime;
        $res=$guestbook->add();

        if (!$res){
            $this->error("评论出错，请稍后再试！");
            //echo M()->getLastSql();
        }
        echo "<script>alert('评论成功');window.location='detail?id={$invest_id}';</script>";

    }

    //ajax异步投资 动作
    public function investAct(){


        if (isset($_POST['act'])&&$_POST['act']=='invest')
        {
            if (!$_SESSION['mobile']){
                echo "您未登录！";
                exit();
            }
            $invest_amount=$_POST['invest_amount'];
            $id=$_POST['id'];
            try {
                if (!is_numeric($invest_amount) or !$invest_amount){
                    throw new \Exception("投资金额不能为空且为数字");
                }
                if ($invest_amount<=0){
                    throw new \Exception("投资金额不能为负数");
                }
                $mobile=$_SESSION['mobile'];
                $userinfo=M('user')->field('id,invest_money,freeze_money,ye')->where(['mobile'=>$mobile])->find();
                $uid=$userinfo['id'];
                $yinvest=$userinfo['invest_money'];
                $ye=$userinfo['ye'];
                M()->startTrans();
                if ($invest_amount>$userinfo['ye']){
                    throw new \Exception("余额不足，请充值！");
                }
                $re=M('invest')->field("invest_name,time_limit,rate,ye,progress*100 as progress,amount")->lock(true)->where("id=$id")->find();
                if ($re['ye']<$invest_amount){
                    throw new \Exception("投资金额不足");
                }
                $time=time();
                $rates='0.0'.substr($re['rate'],0,-1);
                $lixi=$invest_amount*$rates*$re['time_limit']/12;
                $lixi=round($lixi,2);
                $get_amount=$invest_amount+$lixi;
                $invest_record=M('invest_record');
                $invest_record->invest_id=$id;
                $invest_record->mobile=$mobile;
                $invest_record->invest_amount=$invest_amount;
                $invest_record->get_amount=$get_amount;
                $invest_record->lixi=$lixi;
                $invest_record->status=1;
                $invest_record->uid=$uid;
                $invest_record->ctime=$time;
                $invest_record->rate=$re['rate'];
                $tickets=M('ticket')->field("id,amount")->where("user_id={$uid} and end_date>$time and status=1")->order("amount desc")->select();
                if($tickets){
                    foreach ($tickets as $v){
                        if ($invest_amount>=$v['amount']*20){
                            //2代表已使用
                            M('ticket')->where("id={$v['id']}")->save(['status'=>2]);
                            //资金记录红包变现
                            //$yes=M('money_record')->where("uid={$uid}")->order("ctime desc")->find();
                            $ye=$ye+$v['amount'];
                            //更新余额
                            M('user')->where("id={$uid}")->save(['ye'=>$ye]);
                            $money_record=M('money_record');
                            $money_record->uid=$uid;
                            $money_record->money="+".$v['amount'];
                            $money_record->ye=$newye;
                            $money_record->type="充值";
                            $money_record->remark="投资券变现";
                            $money_record->ctime=$time;
                            $rid=$money_record->add();
                            if (!$rid){
                                throw new \Exception("出错1,请联系开发人员");
                            }
                            $invest_record->is_ticket=1;
                            break;
                        }
                    }
                }
                $r01=$invest_record->add();
                if (!$r01){
                    throw new \Exception("投资出错！请稍后重试");
                }
                //投资一进一出
                $mo=$userinfo['freeze_money']+$invest_amount;
                $nye=$ye-$invest_amount;
                $r02=M('user')->where(['mobile'=>$mobile])->save(['freeze_money'=>$mo,'ye'=>$nye]);
                if (!$r02){
                    throw new \Exception("冻结出错");
                }
                $money_record=M('money_record');
                $money_record->uid=$uid;
                $money_record->money="-".$invest_amount;
                $money_record->ye=$nye;
                $money_record->type="投资";
                $money_record->remark="投资{$re['invest_name']},{$invest_amount}元";
                $money_record->ctime=$time;
                $rid1=$money_record->add();
                if (!$rid1){
                    throw new \Exception("记录出错1,请联系开发人员");
                }
                $ctime=date('Y-m-d H:s:i');
                $title="{$ctime}投资了{$invest_amount}元";
                $content="尊敬的{$mobile},您于{$ctime} 投资了一笔{$invest_amount}元，如不是您本人操作，请联系客服！";
                $news=M('news');
                $news->uid=$uid;
                $news->content=$content;
                $news->title=$title;
                $news->status=2;
                $news->ctime=$ctime;
                $res=$news->add();
                if (!$res){
                    throw new \Exception("出错0");
                }
                $allinvest=$yinvest+$invest_amount;
                $res1=M('user')->where("id={$uid}")->save(['invest_money'=>$allinvest]);
                if (!$res1){
                    throw new \Exception("出错0");
                }

                //计算标的余额
                $where=array();
                $where['ye']=$re['ye']-$invest_amount;
                $where['progress']=	1-($where['ye']/$re['amount']);
                if ($where['ye']==0){
                    $where['status']=2;
                }
                $res2=M('invest')->where("id={$id}")->save($where);
                if (!$res2){
                    throw new \Exception("出错1");
                }
                M('user')->where(['id'=>$uid])->save(['is_invest'=>1]);

                if (!M()->commit()){
                    throw new \Exception("出错3");
                }
                echo "恭喜您，投资{$invest_amount}元成功！";

            }catch (\Exception $e){
                M()->rollback();
                //echo M()->getLastSql();
                echo $e->getMessage();
                exit();
            }

        }
    }

}
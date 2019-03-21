<?php
/**
 * Created by PhpStorm.
 * User: homepc
 * Date: 2017/3/24
 * Time: 9:59
 */
class AdminAction extends Action{

    const  STATUS_ONE= '正常';
    const  STATUS_TWO= '冻结';
    //权限每次都调用
    public function _initialize(){

        if(!$_SESSION['tel']){
            $this->error("您未登录！",U('Login/Backlogin'));
        }


    }
    public function userList(){

        if (empty($_GET['p'])){
            $_GET['p']=1;
        }
        $type=$_GET['type'];
        $count=M('user')->where(['type'=>$type])->count();
        $data=M('user')->field("id,mobile,email,invest_money,ye,status,ctime")
            ->where(['type'=>$type])
            ->page($_GET['p'],2)
            ->select();
        foreach ($data as $v){
            $res['id']=$v['id'];
            $res['mobile']=$v['mobile'];
            $res['email']=$v['email'];
            $res['invest_money']=$v['invest_money'];
            $res['ye']=$v['ye'];
            $res['ctime']=date('Y/m/d H:i:s',$v['ctime']);
            if ($v['status']==1){
                $res['status']=self::STATUS_ONE;
            }
            if ($v['status']==2){
                $res['status']=self::STATUS_TWO;
            }
            $ds[]=$res;
        }
        //dump($ds);
        if ($type==1){
            $name="投资";
        }
        if ($type==2){
            $name="借款";
        }
        import('ORG.Util.Page');
        $page=new Page($count,2);
        $show=$page->show();
        $this->assign('type',$type);
        $this->assign('name',$name);
        $this->assign('page',$show);
        $this->assign("ds",$ds);
        $this->display();
    }

    //冻结
    public function act(){
        if (isset($_POST)&&!empty($_POST)){
            $id=$_POST['id'];
            $status=M('user')->where(['id'=>$id])->getField('status');
            if ($status==1){
                M('user')->where(['id'=>$id])->save(['status'=>2]);
                echo "冻结成功！";
                exit();
            }
            if ($status==2){
                M('user')->where(['id'=>$id])->save(['status'=>1]);
                echo "解冻成功！";
                exit();

            }
        }

    }
    public function download(){
        $type=$_GET['type'];
        $data=M('user')->field("id,mobile,email,invest_money,ye,status,ctime")
            ->where(['type'=>$type])
            ->select();
        foreach ($data as $v){
            $res['id']=$v['id'];
            $res['mobile']=$v['mobile'];
            $res['email']=$v['email'];
            $res['invest_money']=$v['invest_money'];
            $res['ye']=$v['ye'];
            $res['ctime']=date('Y/m/d H:i:s',$v['ctime']);
            if ($v['status']==1){
                $res['status']="正常";
            }
            if ($v['status']==2){
                $res['status']="冻结";
            }
            $row[]=$res;
        }
        if ($type==1){
            $name="投资";
        }
        if ($type==2){
            $name="借款";
        }
        $load=new UserModel();
        $load->download($row,$name);
    }
}
<?php
header("content-type:text/html;charset='utf-8'");
class IndexAction extends Action {



    //在cmd中跑
    public function sub(){
        import('ORG.Util.Image');
        $Image = new Image();

        header('Pragma: cache');
       // header('Content-Type: text/html; charset=iso-8859-1');
        $arr=file_get_contents("php://input");
        dump($arr);
        echo "put;";
        $all=$_REQUEST;
        dump($all);

        $url="http://ip.taobao.com/service/getIpInfo.php?ip=58.100.201.207";
        $as=json_decode(file_get_contents($url));

        $arr = (array)$as->data;
        dump($arr);
        echo "您当前位置".$arr['area']."*".$arr['region']."*".$arr['city'];

        $ip=get_client_ip();
        echo $ip;

        ini_set('default_socket_timeout',-10);
        import('ORG.Redis.Redis');
        $redis=new Redis();
        $redis->connect("127.0.0.1",6379);
        $redis->publish('t1',"nihao ");
        $redis->lpush("tutorial-list", "Redis");
        dump($redis);
//        $redis->lpush("tutorial-list", "Mysql");
//        $arList = $redis->lrange("tutorial-list", 0 ,2);

        $channelName='t1';
        $redis->subscribe(array($channelName), 'callback');
        function callback($redis,$channelName,$message){
            $redis =new Redis();
            $redis->connect('127.0.0.1','6379');
            $redis->lpush('d',$message);
            echo $channelName;
            var_dump($message);
            exit();

        }
        $arrList = $redis->lrange("0", 0 ,2);
        print_r($arrList);

    }
    //增加积分
    public function addScores($memeber, $scores){
        $redis=new Redis();
        $redis->connect("127.0.0.1",6379);
        $redis->auth("123");
        $key = date('Ymd');
        return $redis->zIncrBy($key, $scores, $memeber);
    }
    //获取排名
    public function getRank($date, $start, $stop){
        $redis=new Redis();
        $redis->connect("127.0.0.1",6379);
        $redis->auth("123");
        $key=$date;
        return $redis->zRevRange($key, $start, $stop, true);
    }
    public function redisreg(){
//    memcache 运用
//        $mem=new Memcache();
//        $rss=$mem->connect("127.0.0.1",11211);
//        dump($rss);
//        $name=$_POST['age'];
//        $key=md5($name);
//        $res=$mem->get($key);
//        dump($res);
//        if (!$res){
//            $mem->set($key,$name);
//            $mem->close();
//
//        }
//
//     echo  microtime();
       $redis=new Redis();
       $redis->connect("127.0.0.1",6379);
       $redis->auth("123");
        $message='新年快乐';
        $redis->publish('test.22',$message);
        $ret=$redis->publish('test',"nihao");
        dump($ret);

        $key=date('Ymd');
//        $this->addScores('zs',2);
//        $this->addScores('zs',2);
//        $this->addScores('ls',2);
//        $this->addScores('ww',2);
//        $this->addScores('ll',2);
//        $this->addScores('lu',1);
//        $this->addScores('ww',8);
//        $this->addScores('cc',100);
//        $this->addScores('wl',55);
//        $this->addScores('ss',82);
       $res= $this->getRank($key,0,2);
       dump($res);




       $b="n1";
      $redis->incr($b);
      echo $redis->get($b);


        $all= $redis->hGetAll("user:18779873531");
        dump($all);
        $s=$redis->hGet("user:18779873531","mobile");
        dump($s);
      //只判断是否存在
       // $m=$redis->hExists("user:123","age");
        $m=$redis->exists("user:123");
      echo $m;

        //redis 验证用户是否注册，否则入redis
       if(isset($_POST['mobile'])&&!empty($_POST['mobile'])){
            $mobile=$_POST['mobile'];
            $hmobile="user:{$mobile}";
           $r= $redis->exists($hmobile);
            if ($r==0){
                $mobile=$_POST['mobile'];
                $name=$_POST['mobile'];
                $pwd=$_POST['pwd'];
                $age=$_POST['age'];
                $redis->hSet($hmobile,"name",$name);
                $redis->hSet($hmobile,"mobile",$mobile);
                $redis->hSet($hmobile,"pwd",$pwd);
                $redis->hSet($hmobile,"age",$age);
                echo "注册成功！";
            }else{
                echo "存在，不可注册";
            }
       }
       $this->display();

    }
    public function verify(){
    	import('ORG.Util.Image');
    	//图片验证码
    	Image::buildImageVerify(4,1,png,30,25);
    	//汉字验证码
    	//Image::GBVerify(4);
    }
    public function index(){

        $c=M('user')->count();
        $allinvests=M('user')->field("sum(invest_money) as allinvest")->find();
        $allinvest=$allinvests['allinvest'];
        $shouyi=$allinvest*0.026;
        $this->assign('c',$c);
        $this->assign('allinvest',$allinvest);
        $this->assign('shouyi',$shouyi);
        //标的展示
        $codeModel=M('code');
    	$data=M('invest as i')
	    	->field("*,i.id as id,i.progress*100 as progress,c.name as pay_way")
	    	->join("right JOIN {$codeModel->getTableName()} as c on i.pay_way=c.wid")
	    	->where("c.sign='pay_way'")
	    	->limit(3)
	    	->order("bulid_time")
	    	->select();
    	//排行榜
        $dataall=M('user')->field("mobile,invest_money")->order("invest_money desc")->limit(6)->select();
        $i=1;
        foreach ($dataall as $one){
            $v['id']=$i;
            $v['mobile']="*******".substr($one['mobile'],-4);
            $v['invest_money']=$one['invest_money'];
            $data1[]=$v;
            $i++;
        }
        $this->assign('data1',$data1);
    	$this->assign('data',$data);
    	$this->display();
    }
    public function borrow(){
        $this->display();
    }
    public function noticelist(){
    	if (empty($_GET['p'])){
    		$_GET['p']=1;
    	}
    	
    	$data=M('wzgg')->page($_GET['p'],3)->select();
    	$count=M('wzgg')->count();
    	import('ORG.Util.Page');
    	$page=new Page($count,3);
    	$show=$page->show();
    	$this->assign('page',$show);
    	$this->assign('data',$data);
    	$this->display();
    }
    
    public function article(){
    	if (isset($_POST['id'])&&!empty($_POST['id'])){
    		$id=$_POST['id'];
    		$data=M('wzgg')->where("id={$id}")->find();
    	
     		$html=<<<sss
     		
     			<div class="helpconright">
        	   	     <h3>{$data['title']}</h3>
        	   	     <h6>来源：{$data['source']}    {$data['ctime']}</h6>
        	   	     <p>{$data['content']}</p>
        	  
	   	     	  </div>
    			
sss;
    		echo $html;
    		
    	}
    }

}
?>
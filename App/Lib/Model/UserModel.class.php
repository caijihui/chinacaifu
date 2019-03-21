<?php
	class UserModel extends Model{
		 protected  $_map=array(
	    	'name'=>'username',//把值映射到username字段中
		     'pass'=>'password'
		
		);
		protected $_auto=array(
			    array('password','md5',3,'function'),
				array('regtime','time',1,'function')
		);
		protected $_validate = array(
				//   字段  验证方法 验证描述 
				array('mobile','checkMobile','手机号格式不正确'),
				array('password','require','密码不能为空'),
				array('fcode','require','验证码不能为空！'), //默认情况下用正则进行验证
			//	array('username','','帐号名称已经存在！',0,'unique',1), // 验证username字段是否唯一
				array('value',array(1,2,3),'值的范围不正确！',2,'in'), // 当值不为空的时候判断是否在一个范围内
				array('repassword','password','两次密码输入不一致',2,'confirm'), // 验证确认密码是否和密码一致
			//	array('password','checkPwd','密码格式不正确',0,'function'), // 自定义函数验证密码格式
			//  array('username','checkName','验证用户名的格式',2,'callback'),
			//	array('fcode','checkcode','验证码不一致',2,'callback'),
				array('email','email','邮箱格式不正确'),
				
		);
		
	/*	function checkcode(){
			if (md5($_POST['fcode'])!=$_SESSION['verify']){
				return  false;
			}
		}*/
		public function checkMobile(){
			if (!preg_match("/^1[34578]{1}\d{9}$/",$mobile)){
				return false;
			    }
		}
//		function checkpwd(){
//			if (strlen($password)<6){
//				return false;
//
//			}
//
//		}
        public function changepwd($mobile,$data){
                $uid=M('user')->where("mobile={$mobile}")->getField('id');
                $jpwd=$data['jpwd'];
                $newpwd=$data['newpwd'];
                if (empty($newpwd) or empty($jpwd)) {
                    echo "密码不能为空";
                    exit();
                }
                $where=array();
                $where['mobile']=$mobile;
                $password=md5($jpwd.'abc');
                $where['password']=$password;
                $count=M('user')->where($where)->count();
                if ($count!=1){
                    echo M()->getLastSql();
                    echo "密码错误";
                    exit();
                }
                if (strlen($newpwd)<6) {
                    echo "密码长度为大于6位";
                    exit();
                }
                $newpwd=md5($newpwd.'abc');
                $res=M('user')->where("mobile={$mobile}")->save(['password'=>$newpwd]);
                if (!$res){
                    echo M()->getLastSql();
                    echo "修改出错!";
                    exit();
                }
                $ctime=date('Y-m-d H:s:i',time());
                $title="{$ctime}修改了密码";
                $content="尊敬的{$mobile},您于{$ctime} 修改了登录密码，如不是您本人操作，请联系客服！";
                $news=M('news');
                $news->uid=$uid;
                $news->title=$title;
                $news->content=$content;
                $news->status=2;
                $news->ctime=$ctime;
                $news->add();
                echo "修改密码成功";
                exit();

        }
		public function changemobile($mobile,$data){
            $mobile1=$data['mobile'];
            $password=md5($data['password'].'abc');
            $count=M('user')->where(['mobile'=>$mobile,'password'=>$password])->count();
            if ($count!=1){
//                echo $count;
//                echo M()->getLastSql();
                echo "密码错误";
                exit();
            }
            $res1=M('user')->where("mobile={$mobile1}")->count();
            if ($res1!=0){
                echo "手机号已存在!";
                exit();
            }

            if (!preg_match("/^1[34578]{1}\d{9}$/",$mobile)){
                echo "手机号码不符合规范";
                exit();
            }
            $id=M('user')->where("mobile={$mobile}")->getField('id');
            $res2=M('user')->where("id={$id}")->save(['mobile'=>$mobile1]);
            //echo M()->getLastSql();
            if (!$res2){
                echo "修改出错";
                exit();
            }
            session(null);
            session('[destory]');
            cookie(session_name(),null);

            session('mobile',$mobile1);
            session("login",1);
            echo "修改成功";
            exit();
        }
        public function changepaypwd($mobile,$data){
            $uid=M('user')->where("mobile={$mobile}")->getField('id');
                $jpaypwd=$data['jpaypwd'];
                $newpaypwd=$data['newpaypwd'];
                if (empty($newpaypwd)or empty($jpaypwd)){
                    echo "密码不能为空";
                    exit();
                }
                if (strlen($newpaypwd)<6){
                    echo "支付密码长度为大于6位";
                    exit();
                }
                $jpaypwd=md5($jpaypwd.'cba');
                $newpaypwd=md5($newpaypwd.'cba');
                $where=array();
                $where['mobile']=$mobile;
                $where['pay_password']=$jpaypwd;
                $count=M('user')->where($where)->count();
                if ($count!=1){
                    echo "支付密码输入错误";
                    exit();
                }
                if ($jpaypwd==$newpaypwd){
                    echo "新旧密码不能一致";
                    exit();
                }
                $res=M('user')->where("mobile={$mobile}")->save(['pay_password'=>$newpaypwd]);
                if (!$res){
                    echo "修改出错!";
                    exit();
                }
                $ctime=date('Y-m-d H:s:i',time());
                $title="{$ctime}修改了支付密码";
                $content="尊敬的{$mobile},您于{$ctime} 修改了支付密码，如不是您本人操作，请联系客服！";
                $news=M('news');
                $news->uid=$uid;
                $news->content=$content;
                $news->title=$title;
                $news->status=2;
                $news->ctime=$ctime;
                $news->add();
                echo "修改支付密码成功";
                exit();

        }






		protected  $patchValidate=ture;
		public function download($row,$name){

            //require_once '';
//            require_once 'PHPExcel.php';
//            require_once 'PHPExcel/IOFactory.php';
//            require_once 'PHPExcel/Writer/Excel2007.php';

            import('ORG.PHPExcel.PHPExcel');
            $resultPHPExcel = new PHPExcel();

            /*----------------------------------表头----------------------------------*/

            $resultPHPExcel->getActiveSheet()->setCellValue('A1', '编号');
            $resultPHPExcel->getActiveSheet()->setCellValue('B1', '姓名');
            $resultPHPExcel->getActiveSheet()->setCellValue('C1', '手机号码');
            $resultPHPExcel->getActiveSheet()->setCellValue('D1', '邮箱');
            $resultPHPExcel->getActiveSheet()->setCellValue('E1', '投资总额');
            $resultPHPExcel->getActiveSheet()->setCellValue('F1', '可提现余额');
            $resultPHPExcel->getActiveSheet()->setCellValue('G1', '注册时间');
            $resultPHPExcel->getActiveSheet()->setCellValue('H1', '状态');

            $count=count($row);
            /*----------------------------------结束:表头----------------------------------*/

            for ($i = 2; $i <= $count+1; $i++) {
                $resultPHPExcel->getActiveSheet()->setCellValue('A' . $i, $row[$i-2]['id']);
                $resultPHPExcel->getActiveSheet()->setCellValue('B' . $i, "XXX");
                $resultPHPExcel->getActiveSheet()->setCellValue('C' . $i, $row[$i-2]['mobile']);
                $resultPHPExcel->getActiveSheet()->setCellValue('D' . $i, $row[$i-2]['email']);
                $resultPHPExcel->getActiveSheet()->setCellValue('E' . $i, $row[$i-2]['invest_money']);
                $resultPHPExcel->getActiveSheet()->setCellValue('F' . $i, $row[$i-2]['ye']);
                $resultPHPExcel->getActiveSheet()->setCellValue('G' . $i, $row[$i-2]['ctime']);
                $resultPHPExcel->getActiveSheet()->setCellValue('H' . $i, $row[$i-2]['status']);

            }



            /*---------------------------设置单元格宽度--------------------------*/

            $resultPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
            $resultPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(12);
            $resultPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
            $resultPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(18);
            $resultPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
            $resultPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
            $resultPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(22);
            $resultPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);

            /*---------------------------结束:设置单元格宽度--------------------------*/


            /*---------------------------设置表头行高--------------------------*/
            $resultPHPExcel->getActiveSheet()->getRowDimension(1)->setRowHeight(35);
            $resultPHPExcel->getActiveSheet()->getRowDimension(2)->setRowHeight(22);
            $resultPHPExcel->getActiveSheet()->getRowDimension(3)->setRowHeight(20);
            /*---------------------------结束:设置表头行高--------------------------*/



            /*----------------表头字体设置----------------*/
            /*	$resultPHPExcel->getActiveSheet()->getStyle('A1:B1')->getFont()->setName('微软雅黑');
                $resultPHPExcel->getActiveSheet()->getStyle('A2:B2')->getFont()->setName('微软雅黑');
                $resultPHPExcel->getActiveSheet()->getStyle('A3:B3')->getFont()->setName('微软雅黑');
            */
            /*----------------结束:表头字体设置----------------*/


            /*--------------设置水平居中-------------*/
            for($j=1;$j<$count+2;$j++){
                $resultPHPExcel->getActiveSheet()->getStyle('A'.$j.':'.'B'.$j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $resultPHPExcel->getActiveSheet()->getStyle('B'.$j.':'.'C'.$j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $resultPHPExcel->getActiveSheet()->getStyle('C'.$j.':'.'D'.$j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $resultPHPExcel->getActiveSheet()->getStyle('D'.$j.':'.'E'.$j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $resultPHPExcel->getActiveSheet()->getStyle('E'.$j.':'.'F'.$j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $resultPHPExcel->getActiveSheet()->getStyle('F'.$j.':'.'G'.$j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $resultPHPExcel->getActiveSheet()->getStyle('G'.$j.':'.'H'.$j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $resultPHPExcel->getActiveSheet()->getStyle('H'.$j.':'.'I'.$j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


            }
            /*--------------结束:设置水平居中-------------*/


            /*--------------设置垂直居中-------------*/
            for($j=1;$j<$count+2;$j++){
                $resultPHPExcel->getActiveSheet()->getStyle('A'.$j.':'.'B'.$j)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                $resultPHPExcel->getActiveSheet()->getStyle('B'.$j.':'.'C'.$j)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                $resultPHPExcel->getActiveSheet()->getStyle('C'.$j.':'.'D'.$j)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                $resultPHPExcel->getActiveSheet()->getStyle('D'.$j.':'.'E'.$j)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                $resultPHPExcel->getActiveSheet()->getStyle('E'.$j.':'.'F'.$j)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                $resultPHPExcel->getActiveSheet()->getStyle('F'.$j.':'.'G'.$j)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                $resultPHPExcel->getActiveSheet()->getStyle('G'.$j.':'.'H'.$j)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                $resultPHPExcel->getActiveSheet()->getStyle('H'.$j.':'.'I'.$j)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);


            }
            /*--------------结束:设置垂直居中-------------*/


            //设置单元格边框
            for($j=1;$j<$count+2;$j++){
                $resultPHPExcel->getActiveSheet()->getStyle('A'.$j.':'.'B'.$j)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                $resultPHPExcel->getActiveSheet()->getStyle('B'.$j.':'.'C'.$j)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                $resultPHPExcel->getActiveSheet()->getStyle('C'.$j.':'.'D'.$j)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                $resultPHPExcel->getActiveSheet()->getStyle('D'.$j.':'.'E'.$j)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                $resultPHPExcel->getActiveSheet()->getStyle('E'.$j.':'.'F'.$j)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                $resultPHPExcel->getActiveSheet()->getStyle('F'.$j.':'.'G'.$j)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                $resultPHPExcel->getActiveSheet()->getStyle('G'.$j.':'.'H'.$j)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                $resultPHPExcel->getActiveSheet()->getStyle('H'.$j.':'.'I'.$j)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

            }
            /*------------------------------设置导出文件名---------------------------------*/






            $outputFileName = "{$name}人记录".time().".xls";
            $xlsWriter = new PHPExcel_Writer_Excel5($resultPHPExcel);
            //ob_start(); ob_flush();
            ob_end_clean();
            header("Content-Type: application/force-download");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");
            header('Content-Disposition:inline;filename="'.$outputFileName.'"');
            header("Content-Transfer-Encoding: binary");
            header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
            header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Pragma: no-cache");
            $xlsWriter->save( "php://output" );

        }




	}


?>
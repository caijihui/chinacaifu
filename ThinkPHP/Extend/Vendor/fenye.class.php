<?php
	header("content-type:text/html;charset=utf-8");
	class Mypage{
		public  $total_num;
		public  $every_num;
		 
		public function __construct($total_num,$every_num){
			$this->every_num=$every_num;
			$this->total_num=$total_num;
		}
		function getpage($page){
    		
    	$total_page=ceil($this->total_num/$this->every_num);
    	if ($total_page<$page||$page<1){
    		$page=1;
    	}
    	//上一页
    	if ($page<=1){
    		$pre_page=1;
    	}else{
    		$pre_page=$page-1;
    	}
    	//下一页
    	if ($page>=$total_page){
    		$next_page=$page;
    	}else {
    		$next_page=$page+1;
    	}
   
    $url = "http://{$_SERVER['SERVER_NAME']}{$_SERVER['PHP_SELF']}";
  
    
    $getpage=<<<AA
    	<html>
    		<head></head>
    		<body>
	    		<table align="center" border="0">
	    		<tr>
	    		<td>总条数:{$this->total_num}</td>
	    		<td>总页数：{$total_page}</td>
	    		<td>第{$page}页</td>
	    		<td><a href="{$url}?page=1"><button>首页</button></a></td>
	    		<td><a href="{$url}?page={$pre_page}"><button>上一页</button></a></td>
	    		<td><a href="{$url}?page={$next_page}"><button>下一页</button></a></td>
	    		<td><a href="{$url}?page={$total_page}"><button>末页</button></a></td>
	    	
	    		<td>
	    		<form>
	    		第<input style="width:50px;" type="text" name="page">页<input type="submit" value="跳转">
	    		</form>
	    		</td>
	    		</tr>
	    		</table>		
    		</body>
    	</html>

AA;
	
    return $getpage;
   }   
}  

  
?>

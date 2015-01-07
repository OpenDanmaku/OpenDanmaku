<?php
require 'libMysqli.php';
require 'libFunction.php';
header("Access-Control-Allow-Origin: *");//无限制
	
//读取参数btih,并字符串化,小写化
$btih=getBtih();
//获取comment与c_index字段
$result=NULL;
$count=safe_query("SELECT `comment`, `c_index` FROM `video` WHERE `btih` = UNHEX(?);",&$result, array('s',$btih));
if($count!=1) die(json_err('btih_unavailable',-1,'Error: Video Not Yet Exists, Do You Want to Create It?'));
$c_index=json_decode($result[0]['c_index']);
$comment=$result[0]['comment'];

//获取任务
switch (isset($_REQUEST['action'])?strtolower(trim($_REQUEST['action'])):'all'){
case "cid":{//********按弹幕号[start,end]获取,计入view,参数为start,end
	//有效化start/end参数,cid范围[0,count-1]
	$cid_start = 0;	//设定起终点默认值
	$cid_end   = count($c_index)-1;
	//如果参数存在且有效,更新起终点
	if(isset($_REQUEST['start'])){
		$temp_start= intval(trim($_REQUEST['start']));
		if (0 <= $temp_start and $temp_start <= $cid_end) $cid_start = $temp_start;
	} 
	if(isset($_REQUEST['end'])){
		$temp_end  = intval(trim($_REQUEST['end']));
		if (0 <= $temp_end   and $temp_end   <= $cid_end) $cid_end   = $temp_end  ;//赋值前$cid_end仍等于上限
	} 	
	if($cid_end < $cid_start) {$temp=$cid_start; $cid_start=$cid_end; $cid_end=$temp;}
	//定位并输出弹幕,function substr(字符串,截取掉的开头字符数,输出的剩余字符数)
	$str_start = ($cid_start == 0)? 0 :($c_index[$cid_start-1][2]);	//省去的开头部分的长度,即剩余串第一个字符的下标
	$str_end  =$c_index[$cid_end][2]				//剩余串最后字符的下标,差值是剩余串长(含结尾逗号)
	echo "[",substr($comment,$str_start,$str_end-$str_start-1),"]";	//结尾逗号要去掉,echo接受多参数,且不会补充空格
	//计入view
	$blackhole=NULL;
	$count=safe_query("UPDATE `video` SET `view` =`view` + 1 WHERE `btih` = UNHEX(?);",&$result, array('s',$btih));
	exit;
	}
break;//其实无用
case "time":{//********按时间[start,end]来获取,计入view,参数为start,end
	//有效化start/end参数,time范围[0,now]
	$time_start = 0;	//设定起终点默认值
	$time_end   = time();
	//如果参数存在且有效,更新起终点
	if(isset($_REQUEST['start'])){
		$temp_start= intval(trim($_REQUEST['start']));
		if (0 <= $temp_start and $temp_start <= $time_end) $time_start = $temp_start;
	} 
	if(isset($_REQUEST['end'])){
		$temp_end  = intval(trim($_REQUEST['end']));
		if (0 <= $temp_end   and $temp_end   <= $time_end) $time_end   = $temp_end  ;//赋值前$cid_end仍等于上限
	} 	
	if($time_end < $time_start) {$temp=$time_start; $time_start=$time_end; $time_end=$temp;}
	//获取定位,借鉴STL算法http://www.it165.net/pro/html/201404/11813.html
	//查找右边界
	$len  = count($c_index);
	$last = $len - 1;
	//int $middle, $half;
	while(len > 0) {
		$half	= $len >> 1;
		$middle	= $last - $half;
		if($c_index[middle][1] > $time_end) {$last = $middle - 1; $len = $len - $half - 1;}//在左边子序列中查找
		else len = half;//在右边子序列（包含middle）中查找
	}
	//return $last;	//此时len=0,last=[-1,count-1],last为右边界元素的下标

	//查找左边界
	int first = 0;
	int middle, half, len;
	len = last + 1;

	while(len > 0) {
		half	= len >> 1;
		middle	= first + half;
		if(array[middle] < key) {	 
			first	= middle + 1;		  
			len	= len - half - 1;	//在右边子序列中查找
		}
		else
			len	= half;			//在左边子序列（包含middle）中查找
	}
	return first;
}

			//获取定位,借鉴STL算法http://www.cnblogs.com/cobbliu/archive/2012/05/21/2512249.html
			//首先从[0,count-1]获得终点(小于等于某时间的第一个),-1表示不存在
				$cid_start = $count - 1;
				$len       = $count;							//len =right-left+1
				while($len > 0){
					$half  = $len >> 1;							//half为长度减半向下取整(3->1,1->0)
					$middle= $cid_start - $half;				//中间点或右中间点,从起点左移half
					if($c_index[$middle][1] > $time_start){	//如果中间点值不小于等于时间点(偏右),在左边子序列中查找
						$cid_start = $middle - 1;				//起点为中间点左移1,即(原起点左移half)再左移1
						$len       = $len - $half - 1;			//长度因左移half并左移1而减小
						//cid_end  = 
					}else{ 								//否则(是小于等于)在右边子序列（包含middle）中查找
						//$cid_start = $cid_start;		    	//起点不动
						$len  = $half + 1;						//中间点作为终点<=======!????????????!
						//len = right - left + 1 = cid_start - (cid_start - half) + 1 = half + 1
					}
				}
			//然后从[0,终点]获得起点之前一条(小于某时间的第一个),-1表示不存在
				$cid_end = $cid_start;
				$len       = $cid_start+1;							//len =right-left+1
				while($len > 0){
					$half  = $len >> 1;							//half为长度减半向下取整(3->1,1->0)
					$middle= $cid_end - $half;				//中间点或右中间点,从起点左移half
					if($c_index[$middle][1] > $time_end){	//如果中间点值不小于等于时间点(偏右),在左边子序列中查找
						$cid_end = $middle - 1;				//起点为中间点左移1,即(原起点左移half)再左移1
						$len       = $len - $half - 1;			//长度因左移half并左移1而减小
						//cid_end  = 
					}else{ 								//否则(是小于等于)在右边子序列（包含middle）中查找
						//$cid_start = $cid_start;		    	//起点不动
						$len  = $half + 1;						//中间点作为终点<=======!????????????!
						//len = right - left + 1 = cid_start - (cid_start - half) + 1 = half + 1
					}
				}
			//我必须承认上面这一段代码八成是错的......坐等善人解答stackoverflow.com/questions/27322112
			//输出弹幕
				if($cid_start == 0) $str_start=0;//$c_index[-1][2]=0
				else $str_start=$c_index[$cid_start-1][2];//省去的开头部分的长度,即剩余串第一个字符的下标
						$str_end  =$c_index[$count-1][2];//count=last+1
				echo "[" . substr($comment,$str_start,$str_end-$str_start-1) . "]";//结尾逗号要去掉	
			
			//计入view
				$mysql = new SaeMysql();//打开数据库
				$sql ="UPDATE `video` SET `view` =`view` + 1 WHERE `btih` = x'" . $btih . "';";
				$mysql->closeDb();// 关闭数据库
				exit;
			}
	        break;//其实无用
	    case "recent":		//********获取下一条到最后一条,不计入view,参数为start
	    	{
			if(isset($_REQUEST['start']) and (int)$_REQUEST['start'] <= $count-1){//如果存在start参数并且小于等于上界$count-1
				//但不写小于$count,为得更好理解
			//获取cid起终点参数
					//获取起点
					$cid_start =(int)$_REQUEST['start'];//对于小于0的,下面有一句$cid_start <= 0
					//获取终点
					$cid_end = $count-1;
			//定位并输出弹幕
					$str_start = ($cid_start <= 0) ? 0 : ($c_index[$cid_start-1][2]);//省去的开头部分的长度,即剩余串第一个字符的下标
					$str_end  =$c_index[$cid_end][2];          						//剩余串最后字符的下标,差值是剩余串长(含结尾逗号)
					echo "[" . substr($comment,$str_start,$str_end-$str_start-1) . "]";//结尾逗号要去掉
					//substr(字符串,截取掉的开头字符数,输出的剩余字符数)
					exit;
				}else exit("{}");
	    	}
	        break;//其实无用
	    case "last":		//********获取最后count条,计入view,参数为count
	        {
		    if(isset($_REQUEST['count']) and (int)$_REQUEST['count']>0){//如果存在count参数并且参数为正(只有count不接受负值)
			//获取cid起终点参数
				//获取起点
				$cid_start =((int)$_REQUEST['count'] > $count) ? 0 :($count-(int)$_REQUEST['count']);//如果count参数大于$count,从头取
				//否则做减法,比如[0,1,2,3,4,5]这6条中取后4条就是从编号(6-4)=2开始取,取到(6-1)=5
				//获取终点
				$cid_end = $count-1;
			//定位并输出弹幕
				$str_start = ($cid_start == 0) ? 0 : ($c_index[$cid_start-1][2]);//省去的开头部分的长度,即剩余串第一个字符的下标
				$str_end  =$c_index[$cid_end][2];          						//剩余串最后字符的下标,差值是剩余串长(含结尾逗号)
				echo "[" . substr($comment,$str_start,$str_end-$str_start-1) . "]";//结尾逗号要去掉
				//substr(字符串,截取掉的开头字符数,输出的剩余字符数)
			//计入view
				$mysql = new SaeMysql();//打开数据库
				$sql ="UPDATE `video` SET `view` =`view` + 1 WHERE `btih` = x'" . $btih . "';";
				$mysql->closeDb();// 关闭数据库
				exit;
			}else exit("{}");
	        }
	        break;//其实无用
	}
}else{	//********都不是则视为获取全部,计入view	
	echo "[" . substr($comment,0,strlen($comment)-1) . "]";//结尾逗号要去掉
	//计入view
	$mysql = new SaeMysql();//打开数据库
	$sql ="UPDATE `video` SET `view` =`view` + 1 WHERE `btih` = x'" . $btih . "';";
	$mysql->closeDb();// 关闭数据库
	exit;
}

?>

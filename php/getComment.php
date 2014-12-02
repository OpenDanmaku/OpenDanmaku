<?php
//初始化
	header("Access-Control-Allow-Origin: *");
	//打开KVDB
	$kv = new SaeKV();
	if (!$kv->init()) die("Error:" . $kv->errno());//出错

//数据获取
	//检验BTIH有效性并小写化,"magnet:?xt=urn:btih:"长度为20,btih长度为40
	//即使btih仅由0-9组成也没关系,因为代码中不存在hex与unhex
	$btih=(string)$_REQUEST['btih'];//字符串
	if(strlen($btih)>=60 and strpos($btih,"magnet:?xt=urn:btih:")===0)
		$btih=substr($btih,20,40);
	if(strlen($btih)!==40 or !ctype_xdigit($btih)))//防注入
		die("Link Not Valid.");
	$btih= strtolower($btih);
	//获取弹幕和定位表
	if(!($c_index = $kv->get($btih . ",ci"))) die("Error:" . $kv->errno());//先查询,宁缺勿错
	if(!($comment = $kv->get($btih . ",c")) ) die("Error:" . $kv->errno());//赋值运算表达式的值也就是所赋的值
	$c_index=json_decode($c_index);
	$count = count($c_index);
	$latest= $c_index[$count-1][1];//数组最后一条,第二个元素:time


//获取任务
if (isset($_REQUEST['action'])){
	$action=strtolower($_REQUEST['action']);

//********按弹幕号获取,计入view,cid范围[0,count-1]
	if($action = "cid"){   
		//修改超过范围的起始点
		if(!isset($_REQUEST['start']) $cid_start = 0;
		else {	$cid_start=(int)$_REQUEST['start'];
				if ($cid_start<0 or $cid_start>$count-1) $cid_start = 0;}
		//修改超过范围的终止点
		if(!isset($_REQUEST['end']) $cid_end = $count-1;
		else {	$cid_end = (int)$_REQUEST['end'];
				if ($cid_end < 0 or $cid_end > $count-1) $cid_end = $count-1;}
		//输出弹幕
		//substr(字符串,截取掉的开头字符数,输出的剩余字符数)
		if  ($cid_start == 0) $str_start=0;
		else $str_start=$c_index[$cid_start-1][2];//省去的开头部分的长度,即剩余串第一个字符的下标
		$str_end  =$c_index[$cid_end][2];          //剩余串最后字符的下标,差值是剩余串长(含结尾逗号)
		echo "[" . substr($comment,$str_start,$str_end-$str_start-1) . "]";//结尾逗号要去掉
		//计入view
		$mysql = new SaeMysql();//打开数据库
		$sql ="UPDATE `video` SET `view` =`view` + 1 WHERE `btih` = x'" . $btih . "';";
		$mysql->closeDb();// 关闭数据库
		exit;
		}

//********按时间来获取,计入view
	if($action = "time"){  
		//修改超过范围的起始点
		if(!isset($_REQUEST['start']) $time_start = 0;
		else {	$time_start=(int)$_REQUEST['start'];
				if ($time_start<0 or $time_start>$latest) $time_start = 0;}
		//修改超过范围的终止点
		if(!isset($_REQUEST['end']) $time_end=$latest;
		else {	$time_end = (int)$_REQUEST['end'];
				if ($time_end < 0 or $time_end > $latest) $time_end = $latest;}
		//查找起点,STL_lower_bound/STL_upper_bound算法
	    //ForwardIter lower_bound(ForwardIter first, ForwardIter last,const _Tp& val)
	    $first = 0;
	    $len   = $count;
	    while($len > 0) {
	        $half = $len >> 1;
	        $middle = $first + $half;
	        if($c_index[$middle][1] < $time_start) 
	        	{$first= $middle + 1;$len  = $len-$half-1;}	//在右边子序列中查找
	        else $len  = $half;								//在左边子序列（包含middle）中查找
	    }
		//算法返回一个非递减序列[first, last)中的第一个大于等于值val的位置。
		$cid_start=$first
	    $str_start=$c_index[$first-1][2];//省去的开头部分的长度,即剩余串第一个字符的下标






		$str_start=$c_index[$cid_start][2];//省去的开头部分的长度,即剩余串第一个字符的下标
		$str_end  =$c_index[$cid_end][2];//剩余串最后字符的下标,差值是剩余串长(含结尾逗号)
		echo "[" . substr($comment,$str_start,$str_end-$str_start-1) . "]";//结尾逗号要去掉






		//计入view
		$mysql = new SaeMysql();//打开数据库
		$sql ="UPDATE `video` SET `view` =`view` + 1 WHERE `btih` = x'" . $btih . "';";
		$mysql->closeDb();// 关闭数据库
		exit;
		}
	
//********获取最近,忽略end参数,不计入view
	if($action = "recent"){
		//修改超过范围的起始点
		if(!isset($_REQUEST['start']) $start=0;
		else {
			$start=(int)$_REQUEST['start'];
			if ($start>=$count){
				echo "[]";
				exit;
				}		
			if ($start<0 or $start>$count) $start = 0;
			}
		//DO sth
		//DO sth
		//DO sth
		//DO sth
		//DO sth
		//DO sth
		//DO sth
		exit;
		}

//********都不是则视为获取全部,计入view
}else{	
	//DO sth
	//DO sth
	//DO sth
	//DO sth
	//DO sth
	//DO sth
	//DO sth
	//计入view
	$mysql = new SaeMysql();//打开数据库
	$sql ="UPDATE `video` SET `view` =`view` + 1 WHERE `btih` = x'" . $btih . "';";
	$mysql->closeDb();// 关闭数据库
exit;
}
/*
//ForwardIter lower_bound(ForwardIter first, ForwardIter last,const _Tp& val)
//算法返回一个非递减序列[first, last)中的第一个大于等于值val的位置。
//ForwardIter upper_bound(ForwardIter first, ForwardIter last, const _Tp& val)
//算法返回一个非递减序列[first, last)中第一个大于val的位置。

//这个算法中，first是最终要返回的位置
int lower_bound(int *array, int size, int key)
{
    int first = 0, middle;
    int half, len;
    len = size;

    while(len > 0) {
        half = len >> 1;
        middle = first + half;
        if(array[middle] < key) {     
            first = middle + 1;          
            len = len-half-1;       //在右边子序列中查找
        }
        else
            len = half;            //在左边子序列（包含middle）中查找
    }
    return first;
}
int upper_bound(int *array, int size, int key)
{
    int first = 0, len = size-1;
    int half, middle;

    while(len > 0){
        half = len >> 1;
        middle = first + half;
        if(array[middle] > key)     //中位数大于key,在包含last的左半边序列中查找。
            len = half;
        else{
            first = middle + 1;    //中位数小于等于key,在右半边序列中查找。
            len = len - half - 1;
        }
    }
    return first;
}
*/
?>
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
switch (isset($_REQUEST['action'])?strtolower(trim($_REQUEST['action'])):'all'){//默认为all,在case 'all';default处理
case "cid":{//********按弹幕号[start,end]获取,计入view,参数为start,end
	//计入view
	$blackhole=NULL;
	$count=safe_query("UPDATE `video` SET `view` =`view` + 1 WHERE `btih` = UNHEX(?);",&$result, array('s',$btih));
	//如果弹幕数少于1
	$c_count=count($c_index);//int count()不返回boolean
	if($c_count<1)) exit '[]';//是否计入view应取决于action的参数,因此代码应放在case里

	//有效化start/end参数,cid范围[0,count-1]
	$cid_start = 0;	//设定起终点默认值
	$cid_end   = $c_count - 1;
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
	$str_end   = $c_index[$cid_end][2];				//剩余串最后字符的下标,差值是剩余串长(含结尾逗号)
	exit ("[" . substr($comment,$str_start,$str_end-$str_start-1) . "]");//结尾逗号要去掉,echo接受多参数,且不会补充空格
	//因为$cid_start<=$cid_end,所以至少输出1项;又因为项长不为零,所以$str_end-$str_start-1不为负
	}
break;//其实无用
case "time":{//********按时间[start,end]来获取,计入view,参数为start,end
	//计入view
	$blackhole=NULL;
	$count=safe_query("UPDATE `video` SET `view` =`view` + 1 WHERE `btih` = UNHEX(?);",&$result, array('s',$btih));
	//如果弹幕数少于1
	$c_count=count($c_index);//int count()不返回boolean
	if($c_count<1)) exit '[]';//是否计入view应取决于action的参数,因此代码应放在case里

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
	//获取定位,借鉴STL算法http://www.it165.net/pro/html/201404/11813.html,参见stackoverflow.com/questions/27322112
	//查找右边界
	$len  = $c_count;//在[0,count-1]中搜索
	$last = $c_count - 1;
	//int $middle, $half;
	while($len > 0) {
		$half   = $len >> 1;
		$middle	= $last - $half;//此时$last,$half初始化完成
		if($c_index[$middle][1] > $time_end) {$last = $middle - 1; $len = $len - $half - 1;}//在左边子序列中查找
		else $len = $half;//在右边子序列（包含middle）中查找
	}
	//return $last;	//此时len=0,last=[-1,count-1],last为右边界元素的下标
	//查找左边界
	$len = $last + 1;//在[0,last]中搜索
	$first = 0;
	//int $middle, $half;
	while($len > 0) {
		$half   = $len >> 1;
		$middle	= $first + $half;//此时$last,$half二次初始化完成
		if($c_index[$middle][1] < > $time_start) {$first = $middle + 1; $len = $len - $half - 1;}//在右边子序列中查找
		else $len = $half;//在左边子序列（包含middle）中查找
	}
	//return $first; //此时len=0，first=[0,last],但是(而且只有)当last=count-1的时候first可能等于last+1,即count

	//返回空值的情况(其他case均不涉及)
	//当$time_end  早于所有弹幕发布时间,即last=-1时: len再次被赋为0,第二个while不执行,first=0
	//当$time_start晚于所有弹幕发布时间,即last=count-1,而first=count
	//当[$time_start,$time_end]居于相邻两条弹幕之间,即last=###,而first=### + 1
	//以上情况都是first=last + 1,(注意first=last时存在一条弹幕)
	if ($last<$first) exit("[]");//那么返回空值(而非报错)

	//定位并输出弹幕,function substr(字符串,截取掉的开头字符数,输出的剩余字符数)
	$str_start = ($first == 0)? 0 :($c_index[$first-1][2]);	//省去的开头部分的长度,即剩余串第一个字符的下标
	$str_end   = $c_index[$last][2];			//剩余串最后字符的下标,差值是剩余串长(含结尾逗号)
	exit ("[" . substr($comment,$str_start,$str_end-$str_start-1) . "]");//结尾逗号要去掉,echo接受多参数,且不会补充空格
	//因为first<=last,所以至少输出1项;又因为项长不为零,所以$str_end-$str_start-1不为负
	}
break;//其实无用
case "recent":{//********获取下一条到最后一条,不计入view,参数为start
	//如果弹幕数少于1
	$c_count=count($c_index);//int count()不返回boolean
	if($c_count<1)) exit '[]';//是否计入view应取决于action的参数,因此代码应放在case里

	//有效化start/end参数,cid范围[0,count-1]
	$cid_start = 0;	//设定起终点默认值
	$cid_end   = count($c_index)-1;
	//如果参数存在且有效,更新起终点
	if(isset($_REQUEST['start'])){
		$temp_start= intval(trim($_REQUEST['start']));
		if (0 <= $temp_start and $temp_start <= $cid_end) $cid_start = $temp_start;//当然start/end不需要交换了
	} 
	//定位并输出弹幕,function substr(字符串,截取掉的开头字符数,输出的剩余字符数)
	$str_start = ($cid_start == 0)? 0 :($c_index[$cid_start-1][2]);	//省去的开头部分的长度,即剩余串第一个字符的下标
	$str_end   = $c_index[$cid_end][2];				//剩余串最后字符的下标,差值是剩余串长(含结尾逗号)
	exit ("[" . substr($comment,$str_start,$str_end-$str_start-1) . "]");//结尾逗号要去掉,echo接受多参数,且不会补充空格
	//因为$cid_start<=$cid_end,所以至少输出1项;又因为项长不为零,所以$str_end-$str_start-1不为负
	}
break;//其实无用
case "last":{//********获取最后count条,计入view,参数为count
	//计入view
	$blackhole=NULL;
	$count=safe_query("UPDATE `video` SET `view` =`view` + 1 WHERE `btih` = UNHEX(?);",&$result, array('s',$btih));
	//如果弹幕数少于1
	$c_count=count($c_index);//int count()不返回boolean
	if($c_count<1)) exit '[]';//是否计入view应取决于action的参数,因此代码应放在case里

	//有效化start/end参数,cid范围[0,count-1]
	$cid_start = 0;	//设定起终点默认值
	$cid_end   = count($c_index)-1;
	if(isset($_REQUEST['count'])){
		$c_count = intval(trim($_REQUEST['count']));
		$c_count 
		$cid_start = ($c_count > $cid_end)? 0 :$cid_end + 1 - $c_count;//
		//比如有012三条,c_count=1则cid_start=2;c_count=2则cid_start=1;c_count=3则cid_start=0;c_count=4则cid_start=0
		$temp_start= intval(trim($_REQUEST['start']));
		if (0 <= $temp_start and $temp_start <= $cid_end) $cid_start = $temp_start;//当然start/end不需要交换了
	} 
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

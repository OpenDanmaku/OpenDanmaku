<?php
require 'libMysqli.php';
require 'libFunction.php';
header("Access-Control-Allow-Origin: *");//无限制

//硬直与禁言设定
$const_ScoreNewLink = 10;//加10分
$const_DelayNewLink = 60;//60秒硬直

//获取Cookie对应用户数据,如果key不符合,退出
	checkCookie();
	$uid=getUid();

//$_GET和$_REQUEST已经urldecode()了！
$linkage=explode(';',trim($_REQUEST['linkage']);//元素都是字符串
$head=trim(array_shift($linkage));
$btih_1=getBtih($head[0]);
$btih_2=getBtih($head[1]);
$count =intval ($head[2]);
if (!checkBtih($btih_1)) die(json_err('btih_unavailable',-1,'Error: First Video is Not Available.'));//返回空
if (!checkBtih($btih_1)) die(json_err('btih_unavailable',-1,'Error: Second Video is Not Available.'));//返回空
$linkage_1=array(array(implode$btih_1,$btih_2,strval($count)))

foreach ($linkage as $semicolon){//去掉头部的linkage
	$comma=explode(',',trim($semicolon));
	if (count($comma)<3 ) die(json_err('btih_incorrect',-1,'Error: Link is Not Valid'));
	$offset=array[];
	$offset[]=intval
	
}$element

//KV读取
	if(!$link_1   = $kv->get($btih1 . ",l" )) die("Error:" . $kv->errno());//array,赋值运算表达式的值也就是所赋的值
	if(!$l_1_index= $kv->get($btih1 . ",li")) die("Error:" . $kv->errno());//json, 赋值运算表达式的值也就是所赋的值
	if(!$link_2   = $kv->get($btih2 . ",l" )) die("Error:" . $kv->errno());//array,赋值运算表达式的值也就是所赋的值
	if(!$l_2_index= $kv->get($btih2 . ",li")) die("Error:" . $kv->errno());//json, 赋值运算表达式的值也就是所赋的值

//编辑键值
	$l_1_index = json_decode($l_1_index);//json->array
	$l_2_index = json_decode($l_2_index);//json->array
	if(!isset($link_1[$btih2_time2])) //当然,对应的另一个link也不存在
		$link_1[$btih2_time2]=array();//强制储存为一个数组,防止作为一个值储存
	if(!isset($link_2[$btih1_time1])) //但是我还是要独立处理
		$link_2[$btih1_time1]=array();//强制储存为一个数组,防止作为一个值储存
	
	$this_link1=$link_1[$btih2_time2];
	$this_link1[]=$userC['uid'];
	$this_link2=$link_2[$btih1_time1];
	$this_link2[]=$userC['uid'];
	
	if(in_array($uid,$this_link1)) die("Error: You Have Already Submitted the Cross Link!");
	if(in_array($uid,$this_link2)) die("Error: You Have Already Submitted the Cross Link!");

	$link_1[$btih2_time2]=$this_link1;
	$link_2[$btih1_time1]=$this_link2;
	if(count($this_link1)!=count($this_link2)) die("Fatal Error.");//但愿不会出现,也许这句话反而会制造麻烦
	$l_1_index[$btih2_time2]=count($this_link1);//这个自然是一个值,所以无所谓
	$l_2_index[$btih1_time1]=count($this_link2);//这个自然是一个值,所以无所谓
	$l_1_index = json_encode($l_1_index);//array->json
	$l_2_index = json_encode($l_2_index);//array->json

//KV赋值
	if(!$kv->set($btih1 . ",l", $link_1)) die("Error:" . $kv->errno());
	if(!$kv->set($btih1 . ",li",$l_1_index)) die("Error:" . $kv->errno());
	if(!$kv->set($btih2 . ",l", $link_2)) die("Error:" . $kv->errno());
	if(!$kv->set($btih2 . ",li",$l_2_index)) die("Error:" . $kv->errno());
	
	
	


//提高积分并暂时硬直
	$userC['score'] = (int)$userC['score']+$const_ScoreNewLink;
	$userC['time']  = time() +$const_DelayNewLink;
	$sql = "UPDATE `user` SET `score` = " . (int)$userC['score']; 
	$sql.= ", `time` = " . $userC['time'];
	$sql.= " WHERE `uid` = " . $_COOKIE['uid'] . ";";
	$mysql->runSql( $sql );
	if($mysql->errno() != 0) 
		die("Error:" . $mysql->errmsg());	//出错

//返回成功页面
	echo "Video Created Successfully!";

// 关闭数据库
	$mysql->closeDb();

//关闭kvdb无语句
	exit;
?>

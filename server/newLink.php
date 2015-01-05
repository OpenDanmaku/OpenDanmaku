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
$count =intval ($head[2]);//偏移量的计数,下面与shift过后的数组$linkage的元素数比较
if (count($linkage)<$count)die(json_err('link_incomplete',-1,'Error: Linkage is Not Complete.'));//正常应该相等,允许大于
//if (!checkBtih($btih_1))  die(json_err('btih_unavailable',-1,'Error: First Video is Not Available.'));//返回空
//if (!checkBtih($btih_1))  die(json_err('btih_unavailable',-1,'Error: Second Video is Not Available.'));//返回空
//直接在下面取l_index时检测好了,不要额外消耗sql资源
$linkage_1=array(implode(',',array($btih_1,$btih_2,strval($count))));//反正不用来索引,都是$btih_1开头又如何
$linkage_2=array(implode(',',array($btih_2,$btih_1,strval($count))));//反正不用来索引,都是$btih_2开头又如何
//其实数值会被implode自动转化成字符串的,http://php.net/manual/zh/function.implode.php#109916

$i=1;//一、接下来处理偏移量No.1
foreach ($linkage as $semicolon){//去掉头部的linkage
	$comma=explode(',',trim($semicolon));
	if (count($comma)<3 ) die(json_err('btih_incorrect',-1,'Error: Link is Not Valid'));
	$linkage_1[]=implode(',',array(intval($comma[0]),intval($comma[1]),intval($comma[2])));//012
	$linkage_2[]=implode(',',array(intval($comma[1]),intval($comma[0]),intval($comma[2])));//102
	//其实数值会被implode自动转化成字符串的,http://php.net/manual/zh/function.implode.php#109916
	//其实都应该用strval(intval(preg_replace('/[^0-9]/', '', $input))),暂时不管了
	//btih倒是不用[^0-9A-F],有xdigit在,而且要小心把magnet头当做数字处理的bug
	if ((++$i)>$count) break;//二、如果下一个偏移量超过计数,退出循环
}	//$i此时正常应该是$count+1,无论是正好还是多了
$linkage_1=implode(';',$linkage_1);
$linkage_2=implode(';',$linkage_2);

$result_1=NULL;
$count=safe_query("SELECT `linkage`, `l_index` FROM `video` WHERE `btih` = UNHEX(?);",&$result, array('s',$btih));
if($count!=1) 
	die(json_err('btih_unavailable',-1,'Error: Video Not Yet Exists, Do You Want to Create It?'));//无返回值
$result_2=NULL;
$count=safe_query("SELECT `linkage`, `l_index` FROM `video` WHERE `btih` = UNHEX(?);",&$result, array('s',$btih));
if($count!=1) 
	die(json_err('btih_unavailable',-1,'Error: Video Not Yet Exists, Do You Want to Create It?'));//无返回值

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
normalFreeze($uid, $const_ScoreNewLink, $const_DelayNewLink);
//返回成功页面
exit(json_err('newVideo',0,"Video Created Successfully!"));
?>

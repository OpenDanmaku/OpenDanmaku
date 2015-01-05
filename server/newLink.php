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
$key_1=array(implode(',',array($btih_1,$btih_2,strval($count))));//反正不用来索引,都是$btih_1开头又如何
$key_2=array(implode(',',array($btih_2,$btih_1,strval($count))));//反正不用来索引,都是$btih_2开头又如何
//其实数值会被implode自动转化成字符串的,http://php.net/manual/zh/function.implode.php#109916

$i=1;//一、接下来处理偏移量No.1
foreach ($linkage as $semicolon){//去掉头部的linkage
	$comma=explode(',',trim($semicolon));
	if (count($comma)<3 ) die(json_err('btih_incorrect',-1,'Error: Link is Not Valid'));
	$key_1[]=implode(',',array(intval($comma[0]),intval($comma[1]),intval($comma[2])));//012
	$key_2[]=implode(',',array(intval($comma[1]),intval($comma[0]),intval($comma[2])));//102
	//其实数值会被implode自动转化成字符串的,http://php.net/manual/zh/function.implode.php#109916
	//其实都应该用strval(intval(preg_replace('/[^0-9]/', '', $input))),暂时不管了
	//btih倒是不用[^0-9A-F],有xdigit在,而且要小心把magnet头当做数字处理的bug
	if ((++$i)>$count) break;//二、如果下一个偏移量超过计数,退出循环
}	//$i此时正常应该是$count+1,无论是正好还是多了
$key_1=implode(';',$key_1);
$key_2=implode(';',$key_2);

//获取linkage和l_index
$result_1=NULL;
if(1!=safe_query("SELECT `linkage`, `l_index` FROM `video` WHERE `btih` = UNHEX(?);",&$result, array('s',$btih))) 
	die(json_err('btih_unavailable',-1,'Error: Video Not Yet Exists, Do You Want to Create It?'));//无返回值
$linkage_1 = json_decode($result_1[0]['1_index']);//json->array
$l_index_1 = json_decode($result_1[0]['1_index']);//json->array

$result_2=NULL;
if(1!=safe_query("SELECT `linkage`, `l_index` FROM `video` WHERE `btih` = UNHEX(?);",&$result, array('s',$btih))) 
	die(json_err('btih_unavailable',-1,'Error: Video Not Yet Exists, Do You Want to Create It?'));//无返回值
$linkage_2 = json_decode($result_2[0]['1_index']);//json->array
$l_index_2 = json_decode($result_2[0]['1_index']);//json->array

//编辑键值
//如果有先例,不加分,但仍硬直
if(isset($linkage_1[$key_1]) and isset($linkage_2[$key_2])) $const_ScoreNewLink = 0;
//如果没有先例,强制储存为一个数组,防止作为一个值储存
if(!isset($linkage_1[$key_1])) $linkage_1[$key_1]=array();//如果不存在btih,强制储存为一个数组,防止作为一个值储存
if(!isset($linkage_2[$key_2])) $linkage_2[$key_2]=array();//当然,对应的另一个link也不存在,但是我还是要独立处理
//如果已经提交
if(in_array($uid,$linkage_1[$key_1]) and in_array($uid,$linkage_2[$key_2]))//By Val
	die(json_err('link_resubmit',-1,'Error: You Have Already Submitted This Link'))
//任意一个没提交
if(!in_array($uid,$linkage_1[$key_1])) $linkage_1[$key_1][]=$uid;//Add Elements to Multidimensional Array
if(!in_array($uid,$linkage_2[$key_2])) $linkage_2[$key_2][]=$uid;//http://stackoverflow.com/a/16308305

//if(count($linkage_1[$key_1])!=count($linkage_1[$key_1]))//但愿不会出现,也许这句话反而会制造麻烦
//	die(json_err('link_count_not_match',-1,'Fatal Error: Link Not Match!'))
$l_index_1[$key_1]=count($linkage_1[$key_1]);//这个自然是一个值,所以无所谓
$l_index_2[$key_1]=count($linkage_2[$key_2]);//这个自然是一个值,所以无所谓

//保存linkage和l_index
$linkage_1 = json_encode($linkage_1);//array->json
$l_index_1 = json_encode($l_index_1);//array->json
$linkage_2 = json_encode($linkage_2);//array->json
$l_index_2 = json_encode($l_index_2);//array->json
//我没办法在这里检查update成功，但失败lib_Mysqli必然报错退出
//修改表`video`[vid,uid,btih,time,view,reply,comment,c_index,linkage,l_index,dislike,d_index]
$blackhole=NULL;
$count=safe_query("UPDATE `video` SET `linkage` = ?, `l_index` = ? WHERE `btih` = UNHEX(?);",
		&$blackhole, array('sss', $linkage_1, $l_index_1, $bith_1));
$blackhole=NULL;
$count=safe_query("UPDATE `video` SET `linkage` = ?, `l_index` = ? WHERE `btih` = UNHEX(?);",
		&$blackhole, array('sss', $linkage_2, $l_index_2, $bith_2));

//提高积分并暂时硬直
normalFreeze($uid, $const_ScoreNewLink, $const_DelayNewLink);
//返回成功页面
exit(json_err('newLink',0,"Links Created Successfully!"));
?>

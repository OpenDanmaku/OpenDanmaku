<?php
require 'libMysqli.php';
require 'libFunction.php';
header("Access-Control-Allow-Origin: *");//无限制
//硬直与禁言设定
$const_ScoreNewComment = 1;//加1分
$const_DelayNewComment = 3;//3秒硬直

//如果没有Cookie
checkCookie();
$uid=getUid();
//获取btih
$btih=getBtih();
//查询视频是否已经存在,如btih不存在,退出
if(checkBtih()===false) die(json_err('btih_unavailable',-1,'Error: Video Not Yet Exists, Do You Want to Create It?'));

//读取参数comment,并字符串化
$new_comment=trim((string)$_REQUEST['comment']);
//$_GET和$_REQUEST已经urldecode()了！
//设置插入时间
$the_time_now=time();

//编辑弹幕{"c":"sec.000,color=FFFFFF,type(1),size(25),uid,timestamp","m":"text","cid":1},
$new_comment = json_decode($new_comment);		//json->array
	$array_comment = explode(",",$new_comment['c']);
		$array_comment[4]=strval($uid);			//strval是因为要合并字符串
		$array_comment[5]=strval($the_time_now);	//strval是因为要合并字符串,注意不需要乘以1000
	$new_comment['c']=implode(",",$array_comment);
	$new_comment['cid']=intval($result[0]['reply']);	//reply为弹幕总数,即最大下标+1
$new_comment = json_encode($new_comment);		//array->json
$new_comment.= ',';					//结尾添加逗号

//编辑索引[uid,time,size]
$c_index = json_decode($result[0]['c_index']);	//json->array
//检验错误
$c_count = count($c_index);
if($result[0]['reply']         !=$c_count)
	die(json_err('reply_countnotmatch',-1,'Error: Fatal Error! Counting Does not Match. Please Report to Admin!'));
if($result[0]['LEN(`comment`)']!=$c_index[$c_count-1]['size']){
	die(json_err('reply_lengthnotmatch',-1,'Error: Fatal Error! Length Does not Match. Please Report to Admin!'));
//编辑索引[uid,time,size]
$c_index[]=array($uid,$the_time_now,$result[0]['LENGTH(`comment`)']+strlen($new_comment));
$c_index = json_encode($c_index);	//array->json	
++$c_count;

//我没办法在这里检查update成功，但失败lib_Mysqli必然报错退出
//修改表`video`[vid,uid,btih,time,view,reply,comment,c_index,linkage,l_index,dislike,d_index]
$blackhole=NULL;
$count=safe_query("UPDATE `video` SET `reply` = ?, `comment` = CONCAT(`comment`, ?), `c_index` = ? WHERE `btih` = UNHEX(?);",
		&$blackhole, array('isss', $c_count, $new_comment, $c_index, $bith));

//提高积分并暂时硬直
normalFreeze($uid, $const_ScoreNewComment, $const_DelayNewComment);
//返回成功页面
exit(json_err('newComment',0,"Comment Created Successfully!"));
?>

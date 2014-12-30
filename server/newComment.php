<?php
require('libMysqli.php');
header("Access-Control-Allow-Origin: *");//无限制
	
//硬直与禁言设定
$const_ScoreNewComment = 1;//加1分
$const_DelayNewComment = 3;//3秒硬直
//$_GET和$_REQUEST已经urldecode()了！

//如果没有Cookie
if(!isset($_COOKIE['uid'])){
	$error_info=json_err('cookie_empty',-1,'Error: No Cookie Submitted');
	die($error_info);//返回空
}
$uid=intval($_COOKIE['uid']);
//获取Cookie对应用户数据,如果key不符合,退出
$result=NULL;
$count=safe_query('SELECT * FROM `user` WHERE `uid` = ?;', &$result, array('i',$uid));
if($count!=1){
	$error_info=json_err('cookie_invalid',-1,'Error: Invalid Cookie');
	die($error_info);//返回空
}
if($result[0]['key']!=$_COOKIE['key']){
	$error_info=json_err('cookie_wrongkey',-1,'Error: Cookie with Wrong Key');
	die($error_info);//key不符合,!=代表作为数字比较
}
if($result[0]['status']==0){
	$error_info=json_err('cookie_deleted',-1,'Error: Deleted Cookie');
	die($error_info);//status禁用,==代表作为数字比较
}
if($result[0]['time']>=0){
	$error_info=json_err('cookie_inactive',-1,'Error: Not Yet Active');
	die($error_info);//time还在硬直中,>=代表作为数字比较
}

//读取参数btih
	//检验btih有效性并小写化,"magnet:?xt=urn:btih:"长度为20,btih长度为40
	//即使btih仅由0-9组成也没关系,因为代码中不存在hex与unhex
$btih=(string)$_REQUEST['btih'];					//字符串
if(strlen($btih)>=60 and strpos($btih,"magnet:?xt=urn:btih:")===0)	//如果是完整磁链
	$btih=substr($btih,20,40);					//截取btih
if(strlen($btih)!==40 or !ctype_xdigit($btih)){				//防注入
	$error_info=json_err('btih_incorrect',-1,'Error: Link Not Correct');
	die($error_info);						//time还在硬直中,>=代表作为数字比较
}
$btih= strtolower($btih);
//读取参数comment
$new_comment=trim((string)$_REQUEST['comment']);//字符串
//设置插入时间
$the_time_now=time();

//查询视频是否已经存在,如btih不存在,退出
$result=NULL;
$count=safe_query("SELECT `reply`, `c_index` LEN(`comment`) FROM `video` WHERE `btih` = x?;", &$result, array('s',$btih));
//???????作为string处理是否可行?待验证
if($count!=1){
	$error_info=json_err('btih_unavailable',-1,'Error: Video Not Yet Exists, Do You Want to Create It?');
	die($error_info);//返回空
}


//编辑弹幕
//"{"c":"sec.000,color=FFFFFF,type(1),size(25),uid,timestamp","m":"text","cid":1},
$new_comment = json_decode($new_comment);	//json->array
$array_comment = explode(",",$new_comment['c']);
$array_comment[4]=strval($uid)	//strval是因为要合并字符串
$array_comment[5]=strval($the_time_now);	//strval是因为要合并字符串
$new_comment['c']=implode(",",$array_comment);
$new_comment['cid']=intval($result['reply']);	//reply为弹幕总数,即最大下标+1
$new_comment = json_encode($new_comment);	//array->json
$new_comment.= ',';

//编辑键值
//[uid,time,size]
$c_index = json_decode($c_index);	//json->array
$c_count = count($c_index)
if($result['reply']         !=$c_count                    ){
	$error_info=json_err('reply_countnotmatch',-1,'Error: Fatal Error! Counting Does not Match. Please Report to Admin!');
	die($error_info);//返回空
}
if($result['LEN(`comment`)']!=$c_index[$c_count-1]['size']){
	$error_info=json_err('reply_lengthnotmatch',-1,'Error: Fatal Error! Length Does not Match. Please Report to Admin!');
	die($error_info);//返回空
}
$c_index[]=array(
		$uid,
		$the_time_now,
		$result['LEN(`comment`)']+strlen($new_comment);
		);
$c_index = json_encode($c_index);	//array->json	
++$c_count;

//修改表`video`
//vid uid btih time view reply comment c_index linkage l_index dislike d_index

//Update `video`
$blackhole=NULL;
$count=safe_query("UPDATE `video` SET `reply` = ?, `comment` = CONCAT(`comment`, ?), `c_index` = ? WHERE `btih` = x?;",
		$blackhole, array('isss', $c_count, $new_comment, $c_index, $bith));
//我没办法检查成功，但失败lib_Mysqli必然报错退出

//提高积分并暂时硬直
//uid	key	time	point	status
$blackhole=NULL;
$count=safe_query("UPDATE `user` SET `score` = `score` + ?, `time` = `time` + ? WHERE `uid` = ?;", $blackhole, 
		array('iii', $const_ScoreNewComment, $const_DelayNewComment, $uid));
//我没办法检查成功，但失败lib_Mysqli必然报错退出

//返回成功页面
	echo "Video Created Successfully!";
	exit;
?>

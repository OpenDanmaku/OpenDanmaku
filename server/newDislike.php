<?php
require 'libMysqli.php';
require 'libFunction.php';
header("Access-Control-Allow-Origin: *");//无限制

//硬直与禁言设定
$const_PointNewDislike = -20;	//减20分
$const_DelayNewDislike = 30;	//30秒硬直
$const_DelayRate = 60*60*4;	//扣光积分后,每个人的10点仇恨后折合双方禁言4小时

//cid
$cid=intval(trim($_REQUEST['cid']));//注意cid本来是字符串
//$_GET和$_REQUEST已经urldecode()了！

//获取Cookie对应用户数据,如果key不符合,退出
	checkCookie();
	$uid=getUid();
//获取btih,查询视频是否已经存在,如btih不存在,退出
	$btih=getBtih();
	//checkBtih($btih);//用不着,下面语句解决了

$result=NULL;//d_index出错不会有严重影响,只要更新就好
$count=safe_query("SELECT `c_index`, `dislike`, `d_index` FROM `video` WHERE `btih` = UNHEX(?);",
		&$result, array('s',$btih));//http://stackoverflow.com/questions/1747894/
if($count!=1) die(json_err('btih_unavailable',-1,'Error: Video Not Yet Exists, Do You Want to Create It?'));//返回空

//编辑键值{"cid": count, "cid": count, ..., "cid": count},
$c_index = json_decode($result[0]['c_index'],true);		//json->array(rather than object)
$dislike = json_decode($result[0]['dislike'],true);		//json->array(rather than object)
$d_index = json_decode($result[0]['d_index'],true);		//json->array(rather than object)
if(!isset($c_index[$cid])) die(json_err('cid_unavailable',-1,'Error: Comment Item Not Yet Exists'));//那条弹幕不存在
if(!isset($dislike[$cid])) $dislike[$cid]=array();//强制储存为一个数组,防止作为一个值储存,$cid始终是字符串
//取键值
$this_uid=$c_index[$cid][0];
if($this_uid==$uid) die(json_err('uid_invalid',-1,'Error: You Cannot Dislike Yourself!'));//不许自己差评自己
$this_dislike=$dislike[$cid];		//$cid始终是字符串
if(in_array($uid,$this_dislike)) die(json_err('dislike_resubmit',-1,'Error: You Have Already Submitted a Dislike!'));
$this_dislike[]= $uid;			//$cid始终是字符串
$dislike[$cid] = $this_dislike;
$d_index[$cid] = count($this_dislike);	//这个自然是一个值,所以无所谓
$dislike       = json_encode($dislike);	//array->json,测试了一下$cid被自动转成字符串了
$d_index       = json_encode($d_index);	//array->json,那么我就不再折腾一遍strval()好了

//我没办法在这里检查update成功，但失败lib_Mysqli必然报错退出
//修改表`video`[vid,uid,btih,time,view,reply,comment,c_index,linkage,l_index,dislike,d_index]
$blackhole=NULL;
$count=safe_query("UPDATE `video` SET `dislike` = ?, `d_index` = ? WHERE `btih` = UNHEX(?);",
		&$blackhole, array('sss', $dislike, $d_index, $btih));

$now=time();
//差评对方$this_uid,对方uid必然存在，是由newComment.php保证的
$count=safe_query("UPDATE `user` SET `point` = (CASE WHEN `point` + ? > 0 THEN `point` + ? ELSE 0 END), 
`time`  = (CASE WHEN `point` + ? > 0 THEN `time` ELSE (CASE WHEN `time` > ? THEN `time` ELSE ? END) + ? END) 
WHERE `uid` = ?;",//只有积分扣光才会禁言,不硬直,`time`与当前时间孰大者
&$blackhole,array('iiiiiii',$const_PointNewDislike,$const_PointNewDislike,
$const_PointNewDislike,$now,$now,$const_DelayRate,$this_uid));

//减少我方$uid并暂时硬直	
$count=safe_query("UPDATE `user` SET `point` = (CASE WHEN `point` + ? > 0 THEN `point` + ? ELSE 0 END), 
`time`  = (CASE WHEN `point` + ? > 0 THEN `time` + ? ELSE (CASE WHEN `time` > ? THEN `time` ELSE ? END) + ? END) 
WHERE `uid` = ?;",//只有积分扣光才会禁言,要硬直,`time`与当前时间孰大者
&$blackhole,array('iiiiiiii',$const_PointNewDislike,$const_PointNewDislike,
$const_PointNewDislike,$const_DelayNewDislike,$now,$now,$const_DelayRate,$uid));

//返回成功页面
exit(json_err('newDislike',0,"Dislike Created Successfully!"));
?>

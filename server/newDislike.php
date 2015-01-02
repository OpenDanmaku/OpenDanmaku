<?php
require('libMysqli.php');
header("Access-Control-Allow-Origin: *");//无限制

//硬直与禁言设定
$const_ScoreNewDislike = -20;	//减20分
$const_DelayNewDislike = 30;	//30秒硬直
$const_DelayRate = 60*60*4;	//扣光积分后,每个人的10点仇恨后折合双方禁言4小时

//cid
$cid=intval(trim($_REQUEST['cid']));//注意cid始终是字符串
//$_GET和$_REQUEST已经urldecode()了！

//如果没有Cookie
if(!isset($_COOKIE['uid'])) die(json_err('cookie_empty',-1,'Error: No Cookie Submitted'));//返回空
$uid=strval(intval($_COOKIE['uid']));

//获取Cookie对应用户数据,如果key不符合,退出
$result=NULL;
$count=safe_query('SELECT * FROM `user` WHERE `uid` = ?;', &$result, array('i',$uid));
if($count!=1) die(json_err('cookie_invalid',-1,'Error: Invalid Cookie'));//返回空
//!= == >= 代表作为数字比较
if($result[0]['key']!=$_COOKIE['key']) die(json_err('cookie_wrongkey',-1,'Error: Cookie with Wrong Key'));//key不符合
if($result[0]['status']==0) die(json_err('cookie_deleted',-1,'Error: Deleted Cookie'));//status禁用
if($result[0]['time']>=0) die(json_err('cookie_inactive',-1,'Error: Not Yet Active'));//time还在硬直中

//读取参数btih,并字符串化,小写化
$btih=trim(strtolower(strval($_REQUEST['btih'])));//读取参数btih
//如果是完整磁链,截取btih,btih长度为40
$pos=strpos($btih,"btih:");//len('btih:')===5
$btih=($pos===FALSE)?substr($btih,$pos+5,40):substr($btih,0,40);//注意$pos会自动转换,而$pos=0和$pos=FALSE截取时有区别
//检验btih长度(应该<=40)与有效性,即使btih仅由0-9组成也没关系,见http://www.cnblogs.com/mincyw/archive/2011/02/10/1950733.html
if(strlen($btih)!==40 or !ctype_xdigit($btih)) die(json_err('btih_incorrect',-1,'Error: Link Not Correct'));
	
//查询视频是否已经存在,如btih不存在,退出
$result=NULL;//d_index出错不会有严重影响,只要更新就好
$count=safe_query("SELECT `c_index` `dislike` `d_index` FROM `video` WHERE `btih` = UNHEX(?);",
		&$result, array('s',$btih));//http://stackoverflow.com/questions/1747894/
if($count!=1) die(json_err('btih_unavailable',-1,'Error: Video Not Yet Exists, Do You Want to Create It?'));//返回空

//编辑键值{"cid": count, "cid": count, ..., "cid": count},
$c_index = json_decode($result[0]['c_index']);//json->array
$dislike = json_decode($result[0]['dislike']);//json->array
$d_index = json_decode($result[0]['d_index']);//json->array
if(!isset($c_index[$cid])) die(json_err('cid_unavailable',-1,'Error: Comment Item Not Yet Exists');//那条弹幕不存在
if(!isset($dislike[$cid])) $dislike[$cid]=array();//强制储存为一个数组,防止作为一个值储存,$cid始终是字符串
//取键值
$this_uid=$c_index[$cid][0];
$this_dislike=$dislike[$cid];		//$cid始终是字符串
if(in_array($uid,$this_dislike)) die(json_err('dislike_resubmit',-1,'Error: You Have Already Submitted a Dislike!');
$this_dislike[]= $uid;			//$cid始终是字符串
$dislike[$cid] = $this_dislike;
$d_index[$cid] = count($this_dislike);	//这个自然是一个值,所以无所谓
$dislike       = json_encode($dislike);	//array->json
$d_index       = json_encode($d_index);	//array->json

//我没办法在这里检查update成功，但失败lib_Mysqli必然报错退出
//修改表`video`[vid,uid,btih,time,view,reply,comment,c_index,linkage,l_index,dislike,d_index]
$blackhole=NULL;
$count=safe_query("UPDATE `video` SET `dislike` = ?, `d_index` = ? WHERE `btih` = UNHEX(?);",
		&$blackhole, array('sss', $dislike, $d_index, $bith));

$now=time();
//差评对方$this_uid,对方uid必然存在，是由newComment.php保证的
$count=safe_query("UPDATE `user` SET `score` = (CASE WHEN `score` + ? > 0 THEN `score` + ? ELSE 0 END) 
`time`  = (CASE WHEN `score` + ? > 0 THEN `time` ELSE (CASE WHEN `time` > ? THEN `time` ELSE ? END) + ? END) 
WHERE `uid` = ?;",//只有积分扣光才会禁言,不硬直,`time`与当前时间孰大者
&$blackhole,array('iiiiiii',$const_ScoreNewDislike,$const_ScoreNewDislike,
$const_ScoreNewDislike,$now,$now,$const_DelayRate,$this_uid));

//减少我方$uid并暂时硬直	
$count=safe_query("UPDATE `user` SET `score` = (CASE WHEN `score` + ? > 0 THEN `score` + ? ELSE 0 END) 
`time`  = (CASE WHEN `score` + ? > 0 THEN `time` + ? ELSE (CASE WHEN `time` > ? THEN `time` ELSE ? END) + ? END) 
WHERE `uid` = ?;",//只有积分扣光才会禁言,要硬直,`time`与当前时间孰大者
&$blackhole,array('iiiiiiii',$const_ScoreNewDislike,$const_ScoreNewDislike,
$const_ScoreNewDislike,$const_DelayNewDislike,$now,$now,$const_DelayRate,$uid));

//返回成功页面
exit(json_err('newDislike',0,"Dislike Created Successfully!"));
?>

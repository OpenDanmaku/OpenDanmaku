<?php
require('libMysqli.php');
header("Access-Control-Allow-Origin: *");//无限制
	
//硬直与禁言设定
$const_ScoreNewVideo = 10;//加10分
$const_DelayNewVideo = 60;//60秒硬直

//如果没有Cookie
if(!isset($_COOKIE['uid'])) die(json_err('cookie_empty',-1,'Error: No Cookie Submitted'));//返回空
$uid=intval($_COOKIE['uid']);
//获取Cookie对应用户数据,如果key不符合,退出
$result=NULL;
$count=safe_query('SELECT * FROM `user` WHERE `uid` = ?;', &$result, array('i',$uid));
if($count!=1) die(json_err('cookie_invalid',-1,'Error: Invalid Cookie'));//返回空
//!= == >= 代表作为数字比较
if($result[0]['key']!=$_COOKIE['key']) die(json_err('cookie_wrongkey',-1,'Error: Cookie with Wrong Key'));//key不符合
if($result[0]['status']==0) die(json_err('cookie_deleted',-1,'Error: Deleted Cookie'));//status禁用
if($result[0]['time']>=0) die(json_err('cookie_inactive',-1,'Error: Not Yet Active'));//time还在硬直中

//$_GET和$_REQUEST已经urldecode()了！
//读取参数btih,并字符串化,小写化
$btih=trim(strtolower(strval($_REQUEST['btih'])));//读取参数btih
//如果是完整磁链,截取btih,btih长度为40
$pos=strpos($btih,"btih:");//len('btih:')===5
$btih=($pos===FALSE)?substr($btih,$pos+5,40):substr($btih,0,40);//注意$pos会自动转换,而$pos=0和$pos=FALSE截取时有区别
//检验btih长度(应该<=40)与有效性,即使btih仅由0-9组成也没关系,参见http://www.cnblogs.com/mincyw/archive/2011/02/10/1950733.html
if(strlen($btih)!==40 or !ctype_xdigit($btih)) die(json_err('btih_incorrect',-1,'Error: Link Not Correct'));

//查询视频是否已经存在,如btih不存在,退出
$result=NULL;//d_index出错不会有严重影响,只要更新就好
$count=safe_query("SELECT `c_index` `dislike` `d_index` FROM `video` WHERE `btih` = UNHEX(?);",
		&$result, array('s',$btih));//http://stackoverflow.com/questions/1747894/
if($count!=0) die(json_err('btih_created',-1,'Error: Video Already Exists'));//返回空

//添加btih
$blackhole=NULL;
$count=safe_query("INSERT INTO `video` (uid`, `time`, `view`, `reply`, `btih`,
		`comment`, `c_index`, `linkage`, `l_index`, `dislike`, `d_index`) 
		VALUES (?, ?, 0, 0, ?, '', '[]', '[]', '[]', '[]', '[]');",
		&$blackhole, array('iis', $uid, time(), $bith));//主键自增,comment赋空字符串,其余元素赋空数组
if($count!=1){
	$error_info=json_err('video_notcreated',-1,'Error: Failed to Create New Video');
	die($error_info);//返回空
}

//提高积分并暂时硬直[uid,key,time,point,status]
$blackhole=NULL;
$count=safe_query("UPDATE `user` SET `score` = `score` + ?, `time` = `time` + ? WHERE `uid` = ?;", &$blackhole, 
		array('iii', $const_ScoreNewComment, $const_DelayNewComment, $uid));
//返回成功页面
exit(json_err('newComment',0,"Video Created Successfully!"));
?>

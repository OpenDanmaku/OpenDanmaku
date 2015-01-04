<?php
require_once 'libMysqli.php';//php5.2以上都优化过,不再考虑效率损失
//获取uid
//int getUid(void){}
function getUid(){
	if(!isset($_COOKIE['uid'])) 
		die(json_err('cookie_empty',-1,'Error: No Cookie Submitted'));
	return intval($_COOKIE['uid']);
}
//获取btih
//string getBtih([string $btih=NULL]){}
function getBtih($btih=NULL){
	//读取参数btih,并字符串化,小写化
	if (is_null($btih)) $btih=$_REQUEST['btih'];
	$btih=trim(strtolower(strval($btih)));
	
	//如果是完整磁链,截取btih,btih长度为40
	$pos=strpos($btih,"btih:");//len('btih:')===5
	//注意$pos会自动转换,而$pos=0和$pos=FALSE截取时有区别
	$btih=($pos===FALSE)?substr($btih,$pos+5,40):substr($btih,0,40);
	
	//检验btih长度(应该<=40)与有效性,即使btih仅由0-9组成也没关系,
	//见http://www.cnblogs.com/mincyw/archive/2011/02/10/1950733.html
	if(strlen($btih)!==40 or !ctype_xdigit($btih)) 
		die(json_err('btih_incorrect',-1,'Error: Link Not Correct'));
	return $btih;
}
//检查uid是否在数据库中存在
//boolean checkUid(int $uid){}
function checkUid($uid){
	$result=NULL;
	$count=safe_query('SELECT `uid` FROM `user` WHERE `btih` = ?;', &$result, array('i',$uid));
	if($count===1) return true;
	else return false;	
}
//检查btih是否在数据库中存在
//boolean checkBtih(string $uid){}
function checkBtih($btih){
	$result=NULL;
	$count=safe_query('SELECT `uid` FROM `video` WHERE `btih` = unhex(?);', &$result, array('s',$btih));
	if($count===1) return true;
	else return false;
}
//检验cookie是否正确
//boolean checkUid(void){}
function checkCookie(){
	$uid=getUid();
	//获取Cookie对应用户数据,如果key不符合,退出
	$result=NULL;
	$count=safe_query('SELECT * FROM `user` WHERE `uid` = ?;', &$result, array('i',$uid));
	if($count!=1) die(json_err('cookie_invalid',-1,'Error: Invalid Cookie'));//返回空
	//!= == >= 代表作为数字比较
	if($result[0]['key']!=intval($_COOKIE['key']) 
		die(json_err('cookie_wrongkey',-1,'Error: Cookie with Wrong Key'));//key不符合
	if($result[0]['status']==0) 
		die(json_err('cookie_deleted',-1,'Error: Deleted Cookie'));//status禁用
	if($result[0]['time']>=time()) 
		die(json_err('cookie_inactive',-1,'Error: Not Yet Active'));//time还在硬直中
	return true;
}
//提高积分并暂时硬直,字段[uid,key,time,point,status]
//boolean normalFreeze(int $uid,int $ScoreNewComment,int $DelayNewComment){}
function normalFreeze($uid,$score,$delay){
	$blackhole=NULL;
	$count=safe_query("UPDATE `user` SET `score` = `score` + ?, `time` = `time` + ? WHERE `uid` = ?;",
			&$blackhole, array('iii', $score, $delay, $uid));
	if($count!=1) die(json_err('freezing_error',-1,'Error: Freezing Failed'));//返回空
	return true;
}
?>

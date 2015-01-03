<?php
require_once('libMysqli.php');
function checkCookie (){
	if(!isset($_COOKIE['uid']))
		die(json_err('cookie_empty',-1,'Error: No Cookie Submitted'));//返回空
	$uid=strval(intval($_COOKIE['uid']));

	//获取Cookie对应用户数据,如果key不符合,退出
	$result=NULL;
	$count=safe_query('SELECT * FROM `user` WHERE `uid` = ?;', &$result, array('i',$uid));
	if($count!=1) die(json_err('cookie_invalid',-1,'Error: Invalid Cookie'));//返回空

	//!= == >= 代表作为数字比较
	if($result[0]['key']!=$_COOKIE['key']) 
		die(json_err('cookie_wrongkey',-1,'Error: Cookie with Wrong Key'));//key不符合
	if($result[0]['status']==0) 
		die(json_err('cookie_deleted',-1,'Error: Deleted Cookie'));//status禁用
	if($result[0]['time']>=0) 
		die(json_err('cookie_inactive',-1,'Error: Not Yet Active'));//time还在硬直中

	return intval($uid);
}



?>

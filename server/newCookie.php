<?php
require 'libMysqli.php';
require 'libFunction.php';
header("Access-Control-Allow-Origin: *");//无限制

//$_GET和$_REQUEST已经urldecode()了！
//检查验证码,解释器会视必要性决定是否转换为数字
session_start();
if($_SESSION['vcode'] != $_REQUEST['vcode']) {
	$_SESSION['vcode']=rand(0,2147483647);//清除vcode
	$error_info=json_err('session_vcode',-1,'Vcode error');
	die($error_info);
}

//如果有旧Cookie
if(isset($_COOKIE['uid'])) {//不能使用getCookie或者checkCookie来判断有无cookie!!!
	//获取Cookie对应用户数据,如果key不符合,退出
	checkCookie();
	$uid=getUid();
	//以上通过,则取最新用户数据,如果uid重复,退出
	$result=NULL;
	$count=safe_query('SELECT * FROM `user` ORDER BY `uid` DESC LIMIT 1;', &$result);
	//SELECT * FROM `USER` WHERE `uid` IN (SELECT max(id) FROM `USER`);
	if($count!=1) 
		die(json_err('user_notexist',-1,'Error: No Users in Database at All'));//必须先导入startup.sql
	if(($result[0]['uid']===$uid) 
		die(json_err('cookie_lastuser',-1,'Error: You Already Have the Latest Cookie'));//已经最新
	//以上通过,则关闭当前Cookie对应用户数据
	$blackhole=NULL;
	$count=safe_query('UPDATE `user` SET `status` = 0 WHERE `uid` = ?;', &$blackhole, array('i',intval($_COOKIE['uid'])));
	if($count!=1)
		die(json_err('user_notclosed',-1,'Error: Failed to Close Cookie'));//返回空
}else{//不论有没有Cookie都要获取最近Cookie数据
	$result=NULL;
	$count=safe_query('SELECT * FROM `user` ORDER BY `uid` DESC LIMIT 1;', &$result);
	//SELECT * FROM `USER` WHERE `uid` IN (SELECT max(id) FROM `USER`);
	if($count!=1)
		die(json_err('user_notexist',-1,'Error: No Users in Database at All'));//必须先导入startup.sql
}

//然后获取下一个Cookie
$uid   = $result[0]['uid']+1;//无论如何都要取最近user的原因,因为封装我不能访问last_affected_id,而我需要向cookie写入uid
$key   = rand(0, 4294967295);
$time  = time()+ 0;//观察期,暂定为新Cookie立刻可以发言
$point = 100; 
$status= 1;
	
//保存新账号到数据库
$blackhole=NULL;
$count=safe_query('INSERT INTO `user` VALUES (?, ?, ?, ?, ?);', &$blackhole, array('iiiii',$uid,$key,$time,$point,$status);
if($count!=1)
	die(json_err('user_notcreated',-1,'Error: Failed to Create New Cookie')_;//返回空

//设置Cookie
setcookie("uid", $uid, 2147483647);//Cookie永不过期
setcookie("key", $key, 2147483647);//Cookie永不过期
exit(json_err('newCookie',0,'New Cookie Begotten!'));// 用不着关闭MySQL
?>

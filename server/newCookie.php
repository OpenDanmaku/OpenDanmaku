<?php
require('libMysqli.php');
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
if(isset($_COOKIE['uid'])){
//获取Cookie对应用户数据,如果key不符合,退出
	$result=NULL;
	$count=safe_query('SELECT * FROM `user` WHERE `uid` = ?;', &$result, array('i',intval($_COOKIE['uid'])));
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

//以上通过,则取最新用户数据,如果uid重复,退出
	$result=NULL;
	$count=safe_query('SELECT * FROM `user` ORDER BY `uid` DESC LIMIT 1;', &$result);
	//SELECT * FROM `USER` WHERE `uid` IN (SELECT max(id) FROM `USER`);
	if($count!=1){
		$error_info=json_err('user_notexist',-1,'Error: No Users at All');
		die($error_info);//返回空
	}
	if(($result[0]['uid']==$_COOKIE['uid']){
		$error_info=json_err('cookie_lastuser',-1,'Error: You Already Have the Latest Cookie');
		die($error_info);//已经最新
	}

//以上通过,则关闭当前Cookie对应用户数据
	$result=NULL;
	$count=safe_query('UPDATE `user` SET `status` = 0 WHERE `uid` = ?;', &$result, array('i',intval($_COOKIE['uid'])));
		$sql.= (string)intval($_COOKIE['uid']) . ";";//防注入
		$mysql->runSql($sql);
		if($mysql->errno()!= 0)
			die("Error:" . $mysql->errmsg());//出错
}
	
	//然后获取下一个Cookie
	$uid   = $userN['uid']+1;
	$key   = rand(0, 4294967295);
	$time  = time()+ 0;//观察期,暂定为新Cookie立刻可以发言
	$point = 100; 
	$status= 1;
	
	//保存新账号到数据库
	$sql = "INSERT INTO `user` VALUES (";
	$sql.= $uid . ", " . $key . ", " . $time . ", " . $point . ", " . $status . ");";
	$mysql->runSql($sql);
	if($mysql->errno()!= 0)
		die("Error:" . $mysql->errmsg());//出错
	
	//设置Cookie
	setcookie("uid", $uid, 2147483647);//Cookie永不过期
	setcookie("key", $key, 2147483647);//Cookie永不过期
	echo "New Cookie Begotten!";

	//关闭MySQL
	$mysql->closeDb();

?>

<?php
	header("Access-Control-Allow-Origin: *");//无限制
	
	//$_GET和$_REQUEST已经urldecode()了！
	
	//检查验证码,解释器会视必要性决定是否转换为数字
	session_start();
	if ($_SESSION['vcode'] != $_REQUEST['vcode']) die('Vcode error');
	
	//打开MySQL。
	$mysql = new SaeMysql();
	
	//如果有旧Cookie
	if (isset($_COOKIE['uid'])){
	
	//获取Cookie对应用户数据,如果key不符合,退出
		$sql = "SELECT * FROM `user` WHERE `uid` = ";
		$sql.= (string)intval($_COOKIE['uid']) . ";";//防注入
		$userC= $mysql->getLine($sql);
		if ($mysql->errno()!= 0)
			die("Error:" . $mysql->errmsg());//出错
		if ($mysql->affectedRows()!=1)
			die("Error: Cookie Not Exists"); //返回空
		if ($userC['key']!=$_COOKIE['key'])
			die("Error: Invalid Cookie");    //key不符合,!=代表作为数字比较
	
	//否则获取最新用户数据,如果uid重复,退出
		$sql = "SELECT * FROM `user` ORDER BY `uid` DESC LIMIT 1;";
		//SELECT * FROM `USER` WHERE `uid` IN (SELECT max(id) FROM `USER`);
		$userN= $mysql->getLine($sql);//肯定存在,至少是uid=0那一行
		if ($mysql->errno()!= 0)
			die("Error:" . $mysql->errmsg());//出错
		if ($userN['uid']==$_COOKIE['uid'])
			die("Error: You Already Have the Last Cookie");//已经最新
	
	//否则关闭Cookie对应用户数据
		$sql = "UPDATE `user` SET `status` = 0 WHERE `uid` = ";
		$sql.= (string)intval($_COOKIE['uid']) . ";";//防注入
		$mysql->runSql($sql);
		if ($mysql->errno()!= 0)
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
	if ($mysql->errno()!= 0)
		die("Error:" . $mysql->errmsg());//出错
	
	//设置Cookie
	setcookie("uid", $uid, 2147483647);//Cookie永不过期
	setcookie("key", $key, 2147483647);//Cookie永不过期
	echo "New Cookie Begotten!";

	//关闭MySQL
	$mysql->closeDb();

?>
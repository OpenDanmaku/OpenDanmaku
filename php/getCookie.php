<?php
	header("Access-Control-Allow-Origin: *");
	
	//$_GET和$_REQUEST已经urldecode()了！
	
	//检查验证码
	session_start();
	if ($_SESSION['vcode'] != $_REQUEST['vcode']) die('Vcode error');
	
	//打开MySQL。
	$mysql = new SaeMysql();
	
	//如果有旧Cookie，删除旧Cookie的账号。
	if (isset($_COOKIE['uid'])){
		$sql = "DELETE FROM `user` WHERE uid = " . $_COOKIE['uid'];
		$mysql->runSql($sql);
		if ($mysql->errno() != 0) die("Error:" . $mysql->errmsg());
	}
	
	//接下来找到最大uid，加一产生新账号。所以反复刷Cookie的只会得到同一个账号。
	$sql = "SELECT CASE WHEN MAX(uid) IS NULL THEN 1 ELSE MAX(uid) + 1 END FROM `user`";
	$uid = $mysql->getLine($sql);//    <------------------
	if ($mysql->errno() != 0) die("Error:" . $mysql->errmsg());
	
	//其他信息
	$key = rand(0, 4294967295);
	$score = 100;
	$time = time();
	
	//保存新账号到数据库
	$sql = "INSERT INTO `user` VALUES (" . $uid . ", " . $key . ", " . $score . ", " . $time . ")";
	$mysql->runSql($sql);
	if ($mysql->errno() != 0) die("Error:" . $mysql->errmsg());
	
	//设置Cookie
	setcookie("uid", $uid, 2147483647);
	setcookie("key", $key, 2147483647);
	echo "New Cookie Begotten!"；

	//关闭MySQL
	$mysql->closeDb();

//insert into aa (id,name) values((select case when max(id) is null then 1 else max(id)+1 end from aa),'a');
//INSERT INTO `user` VALUES ((SELECT CASE WHEN MAX(uid) IS NULL THEN 1 ELSE MAX(uid) + 1 END FROM `user`),$session, $score, $datetime)
//$vcode = $_REQUEST['vcode'];
//session_start();
//if($_SESSION['vcode'] != $vcode)
//$name = strip_tags( $_REQUEST['name'] );
//$age = intval( $_REQUEST['age'] );
//$sql = "INSERT  INTO `user` ( `name`, `age`, `regtime`) VALUES ('"  . $mysql->escape( $name ) . "' , '" . intval( $age ) . "' , NOW() ) ";
?>
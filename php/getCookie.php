<?php
	//$_GET��$_REQUEST�Ѿ�urldecode()�ˣ�
	//�����֤�룬��MySQL��
	session_start();
	if ($_SESSION['vcode'] != $_REQUEST['vcode']) die('Vcode error');
	$mysql = new SaeMysql();
	
	//����о�Cookie��ɾ����Cookie���˺š�
	if (isset($_COOKIE['uid'])){
		$sql = "DELETE FROM `user` WHERE uid = " . $_COOKIE['uid'];
		$mysql->runSql($sql);
		if ($mysql->errno() != 0) die("Error:" . $mysql->errmsg());
	}
	
	//�������ҵ����uid����һ�������˺š����Է���ˢCookie��ֻ��õ�ͬһ���˺š�
	$sql = "SELECT CASE WHEN MAX(uid) IS NULL THEN 1 ELSE MAX(uid) + 1 END FROM `user`";
	$uid = $mysql->getLine($sql);//???????????????????????????????????????????????????????????????????
	if ($mysql->errno() != 0) die("Error:" . $mysql->errmsg());
	
	//������Ϣ
	$session = rand(0, 4294967295);
	$score = 100;
	$datetime = date("Y-m-d H:i:s");
	
	//�������˺ŵ����ݿ�
	$sql = "INSERT INTO `user` VALUES (" . $uid . ", " . $session . ", " . $score . ", '" . $datetime . "')";
	$mysql->runSql($sql);
	if ($mysql->errno() != 0) die("Error:" . $mysql->errmsg());
	
	//����Cookie
	setcookie("uid", $uid, 2147483647);
	setcookie("session",$session, 2147483647);
	echo "New Cookie Begotten!"��
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
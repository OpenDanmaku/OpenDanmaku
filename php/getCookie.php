<?php
	header("Access-Control-Allow-Origin: *");
	
	//$_GET��$_REQUEST�Ѿ�urldecode()�ˣ�
	
	//�����֤��
	session_start();
	if ($_SESSION['vcode'] != $_REQUEST['vcode']) die('Vcode error');
	
	//��MySQL��
	$mysql = new SaeMysql();
	
	//����о�Cookie
	if (isset($_COOKIE['uid'])){
	
	//��ȡCookie��Ӧ�û�����,���key������,�˳�
		$sql = "SELECT * FROM `user` WHERE `uid` = ";
		$sql.= $_COOKIE['uid'];
		$userC= $mysql->getLine($sql);
		if ($mysql->errno() != 0) die("Error:" . $mysql->errmsg());//����
		if ($mysql->affectedRows()!==1) die("Error: Cookie Not Exists");//���ؿ�
		if ($userC['key']!==$_COOKIE['key']) die("Error: Invalid Cookie");//key������
	
	//�����ȡ�����û�����,���uid�ظ�,�˳�
		$sql = "SELECT * FROM `USER` ORDER BY `uid` DESC LIMIT 1";
		//SELECT * FROM `USER` WHERE `uid` IN (SELECT max(id) FROM `USER`);
		$userN= $mysql->getLine($sql);//�϶�����,������uid=0��һ��
		if ($mysql->errno() != 0) die("Error:" . $mysql->errmsg());//����
		if ($userN['uid']===$_COOKIE['uid']) die("Error: You have the Last Cookie");//�Ѿ�����
	
	//����ر�Cookie��Ӧ�û�����
		$sql = "UPDATE `user` SET `state` = 0 WHERE `uid` = ";
		$sql.= $_COOKIE['uid'];
		$mysql->runSql($sql);
		if ($mysql->errno() != 0) die("Error:" . $mysql->errmsg());//����
	}
	
	//Ȼ���ȡ��һ��Cookie
	$uid  = $userN['uid']+1;
	$key  = rand(0, 4294967295);
	$time = time()+ 0;//��Cookie���̿��Է���,�Ժ���Կ�����߹۲���
	$point= 100; 
	$state= 1;
	
	//�������˺ŵ����ݿ�
	$sql = "INSERT INTO `user` VALUES ("
	$sql.= $uid . ", " . $key . ", " . $time . ", " . $point . ", " . $state . ")";
	$mysql->runSql($sql);
	if ($mysql->errno() != 0) die("Error:" . $mysql->errmsg());
	
	//����Cookie
	setcookie("uid", $uid, 2147483647);//Cookie������
	setcookie("key", $key, 2147483647);//Cookie������
	echo "New Cookie Begotten!"��

	//�ر�MySQL
	$mysql->closeDb();

?>
<?php
	header("Access-Control-Allow-Origin: *");
	//$_GET��$_REQUEST�Ѿ�urldecode()�ˣ�
	
	// ���Ȩ�ޣ������ݿ�
	if ($_REQUEST['name'] != xxxyyyzzz) die("Not Authenticated.");
	$mysql = new SaeMysql();
	
	//ɾ�����һ����û�л���û�
	$sql = "UPDATE `user` SET `state` = 0 WHERE `time` <" . time()-90*24*60*60;
	$mysql->runSql( $sql );
	if ($mysql->errno() != 0) die("Error:" . $mysql->errmsg());
	
	// �ر����ݿ�
	$mysql->closeDb();
?>

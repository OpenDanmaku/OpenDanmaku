<?php
	header("Access-Control-Allow-Origin: *");
	//$_GET��$_REQUEST�Ѿ�urldecode()�ˣ�
	
	// ���Ȩ�ޣ������ݿ�
	if ($_REQUEST['name'] != xxxyyyzzz) die("Not Authenticated.");
	$mysql = new SaeMysql();
	
	// ������user
	$sql = "CREATE TABLE IF NOT EXISTS `user` (
	  `uid`    INT(1) UNSIGNED NOT NULL
	  `key`    INT(1) UNSIGNED NOT NULL
	  `score`  INT(1) NOT NULL
	  `time`   INT(1) NOT NULL
	   PRIMARY KEY (`uid`)
	 ) ENGINE = MyIASM  DEFAULT CHARSET = utf8";
	$mysql->runSql( $sql );
	if ($mysql->errno() != 0) die("Error:" . $mysql->errmsg());
	
	// ������video
	$sql = "CREATE TABLE IF NOT EXISTS `video` (
	   `vid`   INT(1) UNSIGNED NOT NULL
	   `uid`   INT(1) UNSIGNED NOT NULL
	   `time`  INT(1) NOT NULL
	   `visit  INT(1) UNSIGNED NOT NULL
	   `reply` INT(1) UNSIGNED NOT NULL
	   `btih`  BINARY(10) NOT NULL
	   PRIMARY KEY (`btih`)
	   UNIQUE  KEY `btih` (`btih`)
	 ) ENGINE = MyIASM  DEFAULT CHARSET = utf8";
	$mysql->runSql( $sql );
	if ($mysql->errno() != 0) die("Error:" . $mysql->errmsg());
	
	// �ر����ݿ�
	$mysql->closeDb();
?>
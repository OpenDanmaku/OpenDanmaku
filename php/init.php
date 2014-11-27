<?php
	header("Access-Control-Allow-Origin: *");
	//$_GET和$_REQUEST已经urldecode()了！
	
	// 检查权限，打开数据库
	if ($_REQUEST['name'] != xxxyyyzzz) die("Not Authenticated.");
	$mysql = new SaeMysql();
	
	// 创建表user
	$sql = "CREATE TABLE IF NOT EXISTS `user` (
	  `uid`    INT(1) UNSIGNED NOT NULL
	  `key`    INT(1) UNSIGNED NOT NULL
	  `score`  INT(1) NOT NULL
	  `time`   INT(1) NOT NULL
	   PRIMARY KEY (`uid`)
	 ) ENGINE = MyIASM  DEFAULT CHARSET = utf8";
	$mysql->runSql( $sql );
	if ($mysql->errno() != 0) die("Error:" . $mysql->errmsg());
	
	// 创建表video
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
	
	// 关闭数据库
	$mysql->closeDb();
?>
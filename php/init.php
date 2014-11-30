<?php
	header("Access-Control-Allow-Origin: *");//无限制
	//$_GET和$_REQUEST已经urldecode()了！
	
	// 检查权限，打开数据库
	if ((string)$_REQUEST['name'] != "xxxyyyzzz") die("Not Authenticated.");//密钥待设定
	$mysql = new SaeMysql();
	
	// 创建表user
	$sql = "CREATE TABLE IF NOT EXISTS `user` (
        `uid`    INT(10) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT,
        `key`    INT(10) UNSIGNED ZEROFILL NOT NULL,
        `time`   INT(1)  NOT NULL,
        `point`  INT(1)  NOT NULL,
        `status` INT(1)  NOT NULL,
        PRIMARY KEY (`uid`))
    ENGINE=MyISAM
    DEFAULT CHARSET=utf8
    COLLATE=utf8_unicode_ci;";
	$mysql->runSql( $sql );
	if ($mysql->errno() != 0) die("Error:" . $mysql->errmsg());
	
	//添加初始元素
	$sql = "INSERT INTO `user` VALUES (0,FLOOR(4294967295*RAND()),0,0,0);";
	$mysql->runSql( $sql );
	if ($mysql->errno() != 0) die("Error:" . $mysql->errmsg());
	
	// 创建表video
	$sql = "CREATE TABLE IF NOT EXISTS `video` (
        `vid`    INT(10) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT,
        `uid`    INT(10) UNSIGNED ZEROFILL NOT NULL,
        `time`   INT(1)  NOT NULL,
        `view`   INT(1)  NOT NULL,
        `reply`  INT(1)  NOT NULL,
        `btih`   BINARY(10) NOT NULL,
        PRIMARY KEY (`vid`),
        UNIQUE  KEY `btih` (`btih`))
    ENGINE=MyISAM
    DEFAULT CHARSET=utf8
    COLLATE=utf8_unicode_ci;";
	$mysql->runSql( $sql );
	if ($mysql->errno() != 0) die("Error:" . $mysql->errmsg());

	//添加初始元素
	$sql = "INSERT INTO `video` VALUES (0,0,0,0,0,x'0000000000000000000000000000000000000000');";
	$mysql->runSql( $sql );
	if ($mysql->errno() != 0) die("Error:" . $mysql->errmsg());
	
	// 关闭数据库
	$mysql->closeDb();
	
	//打开KVDB
	$kv = new SaeKV();
	if (!$kv->init()) die("Error:" . $kv->errno());

	//添加初始元素
	$btih="0000000000000000000000000000000000000000";
	$danmaku="{\"c\":\"0,FFFFFF,1,25,0,0\",\"m\":\"Test测试\",\"cid\":1},";
	//Comment
	if (!$kv->set($btih . ",c",  $danmaku))
		die("Error:" . $kv->errno());
	if (!$kv->set($btih . ",ci", "[[0,0,". strlen($danmaku) . "]]")
		die("Error:" . $kv->errno());
	//Link
	if (!$kv->set($btih . ",l",  "{}"))
		die("Error:" . $kv->errno());
	if (!$kv->set($btih . ",li",  "{}"))
		die("Error:" . $kv->errno());
	//Dislike	
	if (!$kv->set($btih . ",d", "{"1":[0]}"))
		die("Error:" . $kv->errno());
	if (!$kv->set($btih . ",di", "{"1":1}"))
		die("Error:" . $kv->errno());
	
?>
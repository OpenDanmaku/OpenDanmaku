<?php
//$_GET和$_REQUEST已经urldecode()了！
//	检查权限，打开数据库
if ($_REQUEST['name'] != xxxyyyzzz) die("Not Authenticated.");
$mysql = new SaeMysql();
//	创建表user
$sql = "CREATE TABLE IF NOT EXISTS `user` (
	`uid`	INT(1) UNSIGNED	NOT NULL	// UID
	`key`	INT(1) UNSIGNED	NOT NULL	// 秘钥
	`score`	INT(1) UNSIGNED	NOT NULL	// 积分，可负。
	`time`	DATETIME NOT NULL 			// 解禁时间
	PRIMARY KEY (`uid`)					// UID作为主键
	) ENGINE = MyIASM  DEFAULT CHARSET = utf8";
$mysql->runSql( $sql );
if ($mysql->errno() != 0) die("Error:" . $mysql->errmsg());
//	创建表video
$sql = "CREATE TABLE IF NOT EXISTS `video` (
	`btih`	CHAR(40) NOT NULL			// UID
	`time`	DATETIME NOT NULL 			// 视频创建时间
	`visit	INT(1) UNSIGNED	NOT NULL	// 浏览次数统计
	`reply`	INT(1) UNSIGNED	NOT NULL	// 弹幕数量统计
	`link`	TEXT NOT NULL				// 视频交叉链接
	`abhor`	TEXT NOT NULL				// 视频举报储存
	`pool`	TEXT NOT NULL				// 视频弹幕索引
	PRIMARY KEY (`btih`)				// BTIH作为主键
	) ENGINE = MyIASM  DEFAULT CHARSET = utf8";
$mysql->runSql( $sql );
if ($mysql->errno() != 0) die("Error:" . $mysql->errmsg());
//	关闭数据库
$mysql->closeDb();
?>
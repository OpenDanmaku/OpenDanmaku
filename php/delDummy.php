﻿<?php
	header("Access-Control-Allow-Origin: *");//无限制
	//$_GET和$_REQUEST已经urldecode()了！
	
	// 检查权限，打开数据库
	if ((string)$_REQUEST['name'] != "xxxyyyzzz") die("Not Authenticated.");//密钥待设定
	$mysql = new SaeMysql();
	
	//删除最近一个月没有活动的用户
	$sql = "UPDATE `user` SET `status` = 0 WHERE `time` <" . time()-90*24*60*60 . ";";
	$mysql->runSql( $sql );
	if ($mysql->errno() != 0) die("Error:" . $mysql->errmsg());
	
	// 关闭数据库
	$mysql->closeDb();
?>

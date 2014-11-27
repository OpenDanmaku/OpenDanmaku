<?php
	$mysql = new SaeMysql();
	$sql = "SELECT btih, time,  FROM `video` WHERE btih = '" . strtoupper($_REQUEST['btih']) . "'";
	$video = $mysql->getLine($sql);
	if ($mysql->errno() != 0) die("Error:" . $mysql->errmsg());
	
	btih`	CHAR(40) NOT NULL			// UID
		`time`	DATETIME NOT NULL 			// 视频创建时间
		`visit	INT(1) UNSIGNED	NOT NULL	// 浏览次数统计
		`reply`	INT(1) UNSIGNED	NOT NULL	// 弹幕数量统计
		`link`	TEXT NOT NULL				// 视频交叉链接
		`abhor`	TEXT NOT NULL				// 视频举报储存
		`pool`	TEXT NOT NULL				// 视频弹幕索引
		

//读取request的btih
	$kv = new SaeKV();
	$ret = $kv->init();
	if	(=$kv->errno() ;)var_dump($ret);
打开数据库
如果错误var_dump退出
设置sql语句
获取第一行（也是唯一一行）
如果错误var_dump退出
json_encode返回的数组
echo返回，可能需要加头尾
退出
?>
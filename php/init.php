<?php
//$_GET��$_REQUEST�Ѿ�urldecode()�ˣ�
//	���Ȩ�ޣ������ݿ�
if ($_REQUEST['name'] != xxxyyyzzz) die("Not Authenticated.");
$mysql = new SaeMysql();
//	������user
$sql = "CREATE TABLE IF NOT EXISTS `user` (
	`uid`	INT(1) UNSIGNED	NOT NULL	// UID
	`key`	INT(1) UNSIGNED	NOT NULL	// ��Կ
	`score`	INT(1) UNSIGNED	NOT NULL	// ���֣��ɸ���
	`time`	DATETIME NOT NULL 			// ���ʱ��
	PRIMARY KEY (`uid`)					// UID��Ϊ����
	) ENGINE = MyIASM  DEFAULT CHARSET = utf8";
$mysql->runSql( $sql );
if ($mysql->errno() != 0) die("Error:" . $mysql->errmsg());
//	������video
$sql = "CREATE TABLE IF NOT EXISTS `video` (
	`btih`	CHAR(40) NOT NULL			// UID
	`time`	DATETIME NOT NULL 			// ��Ƶ����ʱ��
	`visit	INT(1) UNSIGNED	NOT NULL	// �������ͳ��
	`reply`	INT(1) UNSIGNED	NOT NULL	// ��Ļ����ͳ��
	`link`	TEXT NOT NULL				// ��Ƶ��������
	`abhor`	TEXT NOT NULL				// ��Ƶ�ٱ�����
	`pool`	TEXT NOT NULL				// ��Ƶ��Ļ����
	PRIMARY KEY (`btih`)				// BTIH��Ϊ����
	) ENGINE = MyIASM  DEFAULT CHARSET = utf8";
$mysql->runSql( $sql );
if ($mysql->errno() != 0) die("Error:" . $mysql->errmsg());
//	�ر����ݿ�
$mysql->closeDb();
?>
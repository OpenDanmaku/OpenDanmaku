<?php
	$mysql = new SaeMysql();
	$sql = "SELECT btih, time,  FROM `video` WHERE btih = '" . strtoupper($_REQUEST['btih']) . "'";
	$video = $mysql->getLine($sql);
	if ($mysql->errno() != 0) die("Error:" . $mysql->errmsg());
	
	btih`	CHAR(40) NOT NULL			// UID
		`time`	DATETIME NOT NULL 			// ��Ƶ����ʱ��
		`visit	INT(1) UNSIGNED	NOT NULL	// �������ͳ��
		`reply`	INT(1) UNSIGNED	NOT NULL	// ��Ļ����ͳ��
		`link`	TEXT NOT NULL				// ��Ƶ��������
		`abhor`	TEXT NOT NULL				// ��Ƶ�ٱ�����
		`pool`	TEXT NOT NULL				// ��Ƶ��Ļ����
		

//��ȡrequest��btih
	$kv = new SaeKV();
	$ret = $kv->init();
	if	(=$kv->errno() ;)var_dump($ret);
�����ݿ�
�������var_dump�˳�
����sql���
��ȡ��һ�У�Ҳ��Ψһһ�У�
�������var_dump�˳�
json_encode���ص�����
echo���أ�������Ҫ��ͷβ
�˳�
?>
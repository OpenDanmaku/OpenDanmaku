<?php
	header("Access-Control-Allow-Origin: *");
	
	//�����ݿ�
	$mysql = new SaeMysql();
	
	//����BTIH��Ч��,"magnet:?xt=urn:btih:"����Ϊ20,btih����Ϊ40
	$btih=$_REQUEST['btih'];
	if(strlen($btih)>=60 and strpos($btih,"magnet:?xt=urn:btih:")===0)
		$btih=substr($btih,20,40);
	if(strlen($btih)!==40 or !ctype_xdigit($btih)))
		die("Link Not Valid.");
		

	//����SQL���,��BTIHɸѡ,ȡ������Ϣ
	$sql = "SELECT `uid`, `time`, `visit`, `reply`, HEX(`btih`) FROM `video`";
	$sql.= "WHERE btih = x'" . strtoupper($btih) . "'";
	
	//ִ��SQL���,�����򱨴�
	$video = $mysql->getLine($sql);
	if ($mysql->errno() != 0)
		die("Error:" . $mysql->errmsg());
	
	//����json����
	echo json_encode($video);
	
	// �ر����ݿ�
	$mysql->closeDb();
?>
<?php
��ȡrequest��btih
��kvdb
�������var_dump�˳�
����key:btih_link
����������˳�
echo����ֵ��������Ҫ��ͷβ
�˳�
?>
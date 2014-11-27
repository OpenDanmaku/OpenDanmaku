<?php
	header("Access-Control-Allow-Origin: *");
	
	//打开数据库
	$mysql = new SaeMysql();
	
	//检验BTIH有效性,"magnet:?xt=urn:btih:"长度为20,btih长度为40
	$btih=$_REQUEST['btih'];
	if(strlen($btih)>=60 and strpos($btih,"magnet:?xt=urn:btih:")===0)
		$btih=substr($btih,20,40);
	if(strlen($btih)!==40 or !ctype_xdigit($btih)))
		die("Link Not Valid.");
		

	//设置SQL语句,按BTIH筛选,取所有信息
	$sql = "SELECT `uid`, `time`, `visit`, `reply`, HEX(`btih`) FROM `video`";
	$sql.= "WHERE btih = x'" . strtoupper($btih) . "'";
	
	//执行SQL语句,出错则报错
	$video = $mysql->getLine($sql);
	if ($mysql->errno() != 0)
		die("Error:" . $mysql->errmsg());
	
	//返回json对象
	echo json_encode($video);
	
	// 关闭数据库
	$mysql->closeDb();
?>
<?php
读取request的btih
打开kvdb
如果错误var_dump退出
查找key:btih_link
如果不存在退出
echo返回值，可能需要加头尾
退出
?>
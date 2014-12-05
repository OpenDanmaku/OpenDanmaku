<?php
	header("Access-Control-Allow-Origin: *");
	
	//打开数据库
	$mysql = new SaeMysql();
	
	//任务判断
	if (isset($_REQUEST['action'])){
	if(strtolower($_REQUEST['action'])= "time"){//按时间的倒序获取最近7天视频
		$sql = "SELECT LOWER(HEX(`btih`)), `uid`, `time`, `view`, `reply` FROM `video` ";
		$sql.= "WHERE ~time~ < " .time()-7*24*60*60 ." ORDER BY `time` DESC;";
		//执行SQL语句,出错则报错,否则返回值
		$video = $mysql->getData($sql);
		if ($mysql->errno() != 0) die("Error:" . $mysql->errmsg());//出错
		if ($mysql->affectedRows()<1) die("Error: BTIH Does Not Exists");//返回空
		echo json_encode($video);//返回json对象
		// 关闭数据库,退出
		$mysql->closeDb();
		exit;
		}
	if(strtolower($_REQUEST['action'])= "view"){//按播放量倒序获取最近7天视频
		$sql = "SELECT LOWER(HEX(`btih`)), `uid`, `time`, `view`, `reply` FROM `video` ";
		$sql.= "WHERE ~time~ < " .time()-7*24*60*60 ." ORDER BY `view` DESC;";
		//执行SQL语句,出错则报错,否则返回值
		$video = $mysql->getData($sql);
		if ($mysql->errno() != 0) die("Error:" . $mysql->errmsg());//出错
		if ($mysql->affectedRows()<1) die("Error: BTIH Does Not Exists");//返回空
		echo json_encode($video);//返回json对象
		// 关闭数据库,退出
		$mysql->closeDb();
		exit;
		}
	if(strtolower($_REQUEST['action'])="reply"){//按弹幕量倒序获取最近7天视频
		$sql = "SELECT LOWER(HEX(`btih`)), `uid`, `time`, `view`, `reply` FROM `video` ";
		$sql.= "WHERE ~time~ < " .time()-7*24*60*60 ." ORDER BY `reply` DESC;";
		//执行SQL语句,出错则报错,否则返回值
		$video = $mysql->getData($sql);
		if ($mysql->errno() != 0) die("Error:" . $mysql->errmsg());//出错
		if ($mysql->affectedRows()<1) die("Error: BTIH Does Not Exists");//返回空
		echo json_encode($video);//返回json对象
		// 关闭数据库,退出
		$mysql->closeDb();
		exit;
		}
	}else{	//都不是则视为查询btih,形式上建议用参数action=find,虽然不会去检验
		//查询BTIH
		if (!isset($_REQUEST['btih'])) die("Error: BTIH Is Not Specified");
		$btih=(string)$_REQUEST['btih'];
	
		//检验BTIH有效性并小写化,"magnet:?xt=urn:btih:"长度为20,btih长度为40
		if(strlen($btih)>=60 and strpos($btih,"magnet:?xt=urn:btih:")===0)	//如果是完整磁链
			$btih=substr($btih,20,40);										//截取btih
		if(strlen($btih)!==40 or !ctype_xdigit($btih)))//防注入
			die("Error: Link Not Valid.");
		$btih= strtolower($btih);
	
		//设置SQL语句,按BTIH筛选,取所有信息
		$sql = "SELECT LOWER(HEX(`btih`)), `uid`, `time`, `view`, `reply` FROM `video`";
		$sql.= "WHERE `btih` = x'" . $btih . "';";
		
		//执行SQL语句,出错则报错,否则返回值
		$video = $mysql->getLine($sql);
		if ($mysql->errno() != 0) die("Error:" . $mysql->errmsg());//出错
		if ($mysql->affectedRows()!==1) die("Error: BTIH Does Not Exists");//返回空
		echo json_encode($video);//返回json对象
		
		// 关闭数据库
		$mysql->closeDb();
	}
?>
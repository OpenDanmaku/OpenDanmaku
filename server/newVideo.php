<?php
	header("Access-Control-Allow-Origin: *");//无限制
	
	//硬直与禁言设定
	$const_ScoreNewVideo = 10;//加10分
	$const_DelayNewVideo = 60;//60秒硬直
	//$_GET和$_REQUEST已经urldecode()了！

	//打开MySQL。打开KVDB
	$mysql = new SaeMysql();
	$kv = new SaeKV();
	if(!$kv->init()) die("Error:" . $kv->errno());//出错

	//如果有旧Cookie
	if(isset($_COOKIE['uid'])){//获取Cookie对应用户数据,如果key不符合,退出
		$sql = "SELECT * FROM `user` WHERE `uid` = ";
		$sql.= (string)intval($_COOKIE['uid']) . ";";//防注入
		$userC= $mysql->getLine($sql);
		if($mysql->errno()!= 0)
			die("Error:" . $mysql->errmsg());//SQL出错
		if($mysql->affectedRows()!=1)
			die("Error: Cookie Not Exists"); //uid不存在
		if($userC['key']!=$_COOKIE['key'])
			die("Error: Invalid Cookie");    //key不符合,!=代表作为数字比较
		if($userC['status']==0)
			die("Error: Deleted Cookie");    //status不活跃,==代表作为数字比较
		if($userC['time']>time())
			die("Error: Not Yet ");          //time还在硬直中
	} else die("No Cookie");
	
//读取参数: BTIH
	//检验BTIH有效性并小写化,"magnet:?xt=urn:btih:"长度为20,btih长度为40
	//即使btih仅由0-9组成也没关系,因为代码中不存在hex与unhex
	$btih=(string)$_REQUEST['btih'];//字符串
	if(strlen($btih)>=60 and strpos($btih,"magnet:?xt=urn:btih:")===0)	//如果是完整磁链
		$btih=substr($btih,20,40);										//截取btih
	if(strlen($btih)!==40 or !ctype_xdigit($btih))//防注入
		die("Error: Link Not Valid.");
	$btih= strtolower($btih);

//查询btih是否还不存在
	//查询视频是否已经存在,如BTIH已存在,退出
	$sql = "SELECT * FROM `video` WHERE `btih` = x'" . $btih . "';";
	$check = $mysql->getLine($sql);
	if($mysql->errno()!= 0)
		die("Error:" . $mysql->errmsg());	//出错
	if($mysql->affectedRows()!=0)			
		die("Error: Video Already Exists"); //非空

//MySQL添加btih
	$sql = "INSERT INTO `video` (uid`, `time`, `view`, `reply`, `btih`) VALUES ('";
	$sql.= $userC['uid'] . ", " .time() . ", 0, 0, x'" . $btih . "';";
	$mysql->runSql( $sql );
	if($mysql->errno() != 0) 
		die("Error:" . $mysql->errmsg());	//出错

//KVDB添加初始元素: 弹幕池,链接池,举报池

	//Comment
	if(!$kv->set($btih . ",c", ""))//string
		die("Error:" . $kv->errno());
	if(!$kv->set($btih . ",ci", array()))//json
		die("Error:" . $kv->errno());
	//Link
	if(!$kv->set($btih . ",l",  array()))//array
		die("Error:" . $kv->errno());
	if(!$kv->set($btih . ",li", json_encode(array())))//json
		die("Error:" . $kv->errno());
	//Dislike	
	if(!$kv->set($btih . ",d",  array()))//array
		die("Error:" . $kv->errno());
	if(!$kv->set($btih . ",di", json_encode(array())))//json
		die("Error:" . $kv->errno());

//提高积分并暂时硬直
	$userC['score'] = (int)$userC['score']+$const_ScoreNewVideo;
	$userC['time']  = time() +$const_DelayNewVideo;
	$sql = "UPDATE `user` SET `score` = " . (int)$userC['score'];
	$sql.= ", `time` = " . $userC['time'];
	$sql.= " WHERE `uid` = " . $_COOKIE['uid'] . ";";
	$mysql->runSql( $sql );
	if($mysql->errno() != 0) 
		die("Error:" . $mysql->errmsg());	//出错

//返回成功页面
	echo "Video Created Successfully!";

// 关闭数据库
	$mysql->closeDb();

//关闭kvdb无语句
	exit;
?>

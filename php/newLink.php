<?php
	header("Access-Control-Allow-Origin: *");//无限制
	$const_ScoreNewLink = 10;//加10分
	$const_DelayNewLink = 60;//60秒
	//$_GET和$_REQUEST已经urldecode()了！

	//打开MySQL。打开KVDB
	$mysql = new SaeMysql();
	$kv = new SaeKV();
	if (!$kv->init()) die("Error:" . $kv->errno());//出错

	//如果有旧Cookie
	if (isset($_COOKIE['uid'])){
	
	//获取Cookie对应用户数据,如果key不符合,退出
		$sql = "SELECT * FROM `user` WHERE `uid` = ";
		$sql.= (string)intval($_COOKIE['uid']) . ";";//防注入
		$userC= $mysql->getLine($sql);
		if ($mysql->errno()!= 0)
			die("Error:" . $mysql->errmsg());//出错
		if ($mysql->affectedRows()!=1)
			die("Error: Cookie Not Exists"); //返回空
		if ($userC['key']!=$_COOKIE['key'])
			die("Error: Invalid Cookie");    //key不符合,!=代表作为数字比较
		if ($userC['status']==0)
			die("Error: Deleted Cookie");    //status不活跃,!=代表作为数字比较
	} else die("No Cookie");
	
//读取参数: BTIH1,BTIH2,time
	//检验BTIH有效性并小写化,"magnet:?xt=urn:btih:"长度为20,btih长度为40
	//即使btih仅由0-9组成也没关系,因为代码中不存在hex与unhex
	//btih1
	$btih1=(string)$_REQUEST['btih1'];//字符串
	if(strlen($btih1)>=60 and strpos($btih1,"magnet:?xt=urn:btih:")===0)
		$btih1=substr($btih1,20,40);
	if(strlen($btih1)!==40 or !ctype_xdigit($btih1)))//防注入
		die("Link Not Valid.");
	$btih1= strtolower($btih1);
	//btih2
	$btih2=(string)$_REQUEST['btih2'];//字符串
	if(strlen($btih2)>=60 and strpos($btih2,"magnet:?xt=urn:btih:")===0)
		$btih2=substr($btih2,20,40);
	if(strlen($btih2)!==40 or !ctype_xdigit($btih2)))//防注入
		die("Link Not Valid.");
	$btih2= strtolower($btih2);
	//time
	$time=(int)$_REQUEST['time'];//字符串
	
读取$_request，参数为btih1,btih2,time
//查询btih是否还不存在,Null不是空
如果kvdb(btih1_link)为Null var_dump退出
如果kvdb(btih2_link)为Null var_dump退出

//===============================
//读取link
link1=json_decode(kvdb(btih1_link))
link2=json_decode(kvdb(btih2_link))
//检验是否存在uid
如果abhor(btih1."_". time)不存在增加abhor(btih2."_". time)[]
如果abhor(btih2."_".-time)不存在增加abhor(btih2."_".-time)[]
如果array_search(abhor(btih1."_". time),uid)
且	array_search(abhor(btih2."_". time),uid)
	返回已举报终止
//写入abhor
abhor(btih1."_". time)+=md5(uid)
abhor(btih2."_".-time)+=md5(uid)
kvdb(btih1_link)=json_encode(link1)
kvdb(btih1_link)=json_encode(link2)


//提高积分并暂时禁言
	$userC['score'] = (int)$userC['score']+$const_ScoreNewLink;
	$userC['time']  = time() +$const_DelayNewLink;
	$sql = "UPDATE `user` SET `score` = " . (int)$userC['score'] 
	$sql.= ", `time` = " . $userC['time']
	$sql.= " WHERE `uid` = " . $_COOKIE['uid'] . ";";
	$mysql->runSql( $sql );
	if ($mysql->errno() != 0) 
		die("Error:" . $mysql->errmsg());	//出错

//返回成功页面
	echo "Video Created Successfully!";

// 关闭数据库
	$mysql->closeDb();

//关闭kvdb无语句
	exit;
?>

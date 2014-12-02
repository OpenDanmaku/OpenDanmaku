<?php
	header("Access-Control-Allow-Origin: *");//无限制
	$const_ScoreNewDislike = -5;//加10分
	$const_DelayNewDislike = 30;//60秒
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
	
//读取参数
	//检验BTIH有效性并小写化,"magnet:?xt=urn:btih:"长度为20,btih长度为40
	//即使btih仅由0-9组成也没关系,因为代码中不存在hex与unhex
	$btih=(string)$_REQUEST['btih'];//字符串
	if(strlen($btih)>=60 and strpos($btih,"magnet:?xt=urn:btih:")===0)
		$btih=substr($btih,20,40);
	if(strlen($btih)!==40 or !ctype_xdigit($btih)))//防注入
		die("Link Not Valid.");
	$btih= strtolower($btih);
	//cid
	$cid=(int)$_REQUEST['cid'];//字符串

//查询btih是否还不存在,Null不是空
如果kvdb(btih_abhor)为Null var_dump退出

//===============================
//读取abhor
abhor=json_decode(kvdb("btih_abhor"))
//检验是否存在md5(uid)
如果abhor(pid)不存在增加abhor(pid)[]
如果array_search(abhor(pid),md5(uid))返回已举报终止
//写入abhor
abhor(pid)+=md5(uid)
kvdb("btih_abhor")=json_encode(abhor)
//写入hated
sql="SELECT * FROM user WHERE uid= " . uid
hated=run_sql
如果错误var_dump退出
hated.score+=constScoreNewPool(负的)
如果hated.score<0
	delay=ceil((-hated.score)/constRate)
hated.time+=delay
hated.score=delay*constRate
sql="UPDATE"
run_sql
如果错误var_dump退出
//===============================
//减少积分并暂时禁言
user.score+=constScoreNewPool(负的)
user.time+=constdelayNewPool

sql="UPDATE"
run_sql
如果错误var_dump退出
//返回成功页面
echo成功页面
关闭数据库
关闭kvdb

//提高积分并暂时禁言
	$userC['score'] = (int)$userC['score']+$const_ScoreNewLink;
	$userC['time']  = time() +$const_DelayNewLink;
	
	
如果user.score<0
delay=ceil((-user.score)/constRate)
user.time+=delay
user.score=delay*constRate
	
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

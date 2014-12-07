<?php
	header("Access-Control-Allow-Origin: *");//无限制
	
	//硬直与禁言设定
	$const_ScoreNewComment = 1;//加1分
	$const_DelayNewComment = 3;//3秒硬直
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
	
//读取参数BTIH
	//检验BTIH有效性并小写化,"magnet:?xt=urn:btih:"长度为20,btih长度为40
	//即使btih仅由0-9组成也没关系,因为代码中不存在hex与unhex
	$btih=(string)$_REQUEST['btih'];//字符串
	if(strlen($btih)>=60 and strpos($btih,"magnet:?xt=urn:btih:")===0)	//如果是完整磁链
		$btih=substr($btih,20,40);										//截取btih
	if(strlen($btih)!==40 or !ctype_xdigit($btih))//防注入
		die("Error: Link Not Valid.");
	$btih= strtolower($btih);
	//danmaku
	$danmaku=trim((string)$_REQUEST['danmaku']);//字符串

//查询视频是否已经存在,如BTIH不存在,退出
	$sql = "SELECT * FROM `video` WHERE `btih` = x'" . $btih . "';";
	$video = $mysql->getLine($sql);
	if($mysql->errno()!= 0)
		die("Error:" . $mysql->errmsg());//出错
	if($mysql->affectedRows()!=1)
		die("Error: Video Not Yet Exists, Do You Want to Create It?"); //返回空

//KV读取
	if(!$comment = $kv->get($btih . ",c" )) die("Error:" . $kv->errno());//赋值运算表达式的值也就是所赋的值
	if(!$c_index = $kv->get($btih . ",ci")) die("Error:" . $kv->errno());//赋值运算表达式的值也就是所赋的值
	
//编辑键值
	$c_index = json_decode($c_index);//json->array
	$danmaku = json_decode($danmaku);//json->array
	if(count($c_index)!=$video["reply"]) die("Fatal Error! Please Report to Admin!");
	//"{"c":"sec.000,color=FFFFFF,type(1),size(25),uid,timestamp","m":"text","cid":1},
	$array_c = explode(",",$danmaku['c']);
	$array_c[4]=(string)$_COOKIE['uid'];
	$the_time_now=time();
	$array_c[5]=strval($the_time_now);
	$danmaku['c']=implode(",",$array_c);
	$danmaku['cid']=(int)$video["reply"];//reply为弹幕总数,即最大下标+1
	$danmaku=json_encode($danmaku);
	$comment.=$danmaku;
	//[uid,time,size]
	$c_index[]=array((string)$_COOKIE['uid'],$the_time_now,strlen($comment));
	
//KV赋值
	if(!$kv->set($btih . ",c", $comment)) die("Error:" . $kv->errno());
	if(!$kv->set($btih . ",ci",$c_index)) die("Error:" . $kv->errno());


//增加reply计数
	$sql = "UPDATE `video` SET `reply` = " . ((int)$video["reply"]+1); //不用自增,虽然应该自增就可以
	$sql.= " WHERE `btih` = x'" . $btih . "';";
	$mysql->runSql( $sql );
	if($mysql->errno() != 0) 
		die("Error:" . $mysql->errmsg());	//出错

//提高积分并暂时硬直
	$userC['score'] = (int)$userC['score']+$const_ScoreNewLink;
	$userC['time']  = time() +$const_DelayNewLink;
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

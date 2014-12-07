<?php
	header("Access-Control-Allow-Origin: *");//无限制
	
	//硬直与禁言设定
	$const_ScoreNewDislike = -20;//减20分
	$const_DelayNewDislike = 30;//30秒硬直
	$const_DelayRate = 60*60/5;//扣光积分后,每个人的10点仇恨后折合双方禁言4小时
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
	//cid
	$cid=(int)$_REQUEST['cid'];//数字
	$uid=intval($_COOKIE['uid']);
//查询btih是否还不存在
	//查询视频是否已经存在,如BTIH不存在,退出
	$sql = "SELECT * FROM `video` WHERE `btih` = x'" . $btih . "';";
	$check = $mysql->getLine($sql);
	if($mysql->errno()!= 0)
		die("Error:" . $mysql->errmsg());//出错
	if($mysql->affectedRows()!=1)
		die("Error: Video Not Yet Exists, Do You Want to Create It?"); //返回空

//KV读取
	if(!$dislike = $kv->get($btih . ",d" )) die("Error:" . $kv->errno());//赋值运算表达式的值也就是所赋的值
	if(!$d_index = $kv->get($btih . ",di")) die("Error:" . $kv->errno());//赋值运算表达式的值也就是所赋的值
	
//编辑键值
	$d_index = json_decode($d_index);//json->array
	if(!isset($dislike[(string)$cid])) 
		$dislike[(string)$cid]=array();//强制储存为一个数组,防止作为一个值储存
	$this_dislike=$dislike[(string)$cid];
	if(in_array($uid,$this_dislike)) die("Error: You Have Already Submitted a Dislike!");
	$this_dislike[]=$uid;
	$dislike[(string)$cid]=$this_dislike;
	$d_index[(string)$cid]=count($this_dislike);//这个自然是一个值,所以无所谓
	$d_index = json_encode($d_index);//array->json

//KV赋值
	if(!$kv->set($btih . ",c", $dislike)) die("Error:" . $kv->errno());
	if(!$kv->set($btih . ",ci",$d_index)) die("Error:" . $kv->errno());

//获取差评对象并差评
	if(!$c_index = $kv->get($btih . ",ci")) die("Error:" . $kv->errno());//赋值运算表达式的值也就是所赋的值
	$d_uid = intval($c_index[$cid][0]);
	$sql = "SELECT * FROM `user` WHERE `uid` = ";
	$sql.= $d_uid . ";";//防注入
	$userD= $mysql->getLine($sql);
	if($mysql->errno()!= 0)
		die("Error:" . $mysql->errmsg());//SQL出错
	if($mysql->affectedRows()!=1)
		die("Error: Cookie Not Exists"); //uid不存在

	$userD['score'] = (int)$userD['score']+$const_ScoreNewDislike;//增加负积分
	//$userD['time']  = time() +$const_DelayNewDislike;//不需要
	if($userD['score']<0) {
		$delay=ceil($userD['score']*$const_DelayRate);
		$userC['time']=$userD['time']+$delay;
		$userD['score']=1;//不给安全期,只象征性给1分作为剩余积分
	}
	
	$sql = "UPDATE `user` SET `score` = " . (int)$userD['score'];
	$sql.= ", `time` = " . $userD['time'];
	$sql.= " WHERE `uid` = " . (string)$d_uid . ";";
	$mysql->runSql( $sql );
	if($mysql->errno() != 0) 
		die("Error:" . $mysql->errmsg());	//出错

//减少我方积分并暂时硬直
	$userC['score'] = (int)$userC['score']+$const_ScoreNewDislike;//增加负积分
	$userC['time']  = time() +$const_DelayNewDislike;
	if($userC['score']<0) {
		$delay=ceil($userC['score']*$const_DelayRate);
		$userC['time']=$userC['time']+$delay;
		$userC['score']=1;//不给安全期,只象征性给1分作为剩余积分
	}
	
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

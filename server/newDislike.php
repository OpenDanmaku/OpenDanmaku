<?php
require('libMysqli.php');
header("Access-Control-Allow-Origin: *");//无限制

//硬直与禁言设定
$const_ScoreNewDislike = -20;//减20分
$const_DelayNewDislike = 30;//30秒硬直
$const_DelayRate = 60*60/5;//扣光积分后,每个人的10点仇恨后折合双方禁言4小时

//cid
$cid=intval(trim($_REQUEST['cid']));//注意cid始终是字符串
//$_GET和$_REQUEST已经urldecode()了！

//如果没有Cookie
if(!isset($_COOKIE['uid'])) die(json_err('cookie_empty',-1,'Error: No Cookie Submitted'));//返回空
$uid=strval(intval($_COOKIE['uid']));

//获取Cookie对应用户数据,如果key不符合,退出
$result=NULL;
$count=safe_query('SELECT * FROM `user` WHERE `uid` = ?;', &$result, array('i',$uid));
if($count!=1) die(json_err('cookie_invalid',-1,'Error: Invalid Cookie'));//返回空
//!= == >= 代表作为数字比较
if($result[0]['key']!=$_COOKIE['key']) die(json_err('cookie_wrongkey',-1,'Error: Cookie with Wrong Key'));//key不符合
if($result[0]['status']==0) die(json_err('cookie_deleted',-1,'Error: Deleted Cookie'));//status禁用
if($result[0]['time']>=0) die(json_err('cookie_inactive',-1,'Error: Not Yet Active'));//time还在硬直中

//读取参数btih,并字符串化,小写化
$btih=trim(strtolower(strval($_REQUEST['btih'])));//读取参数btih
//如果是完整磁链,截取btih,btih长度为40
$pos=strpos($btih,"btih:");//len('btih:')===5
$btih=($pos===FALSE)?substr($btih,$pos+5,40):substr($btih,0,40);//注意$pos会自动转换,而$pos=0和$pos=FALSE截取时有区别
//检验btih长度(应该<=40)与有效性,即使btih仅由0-9组成也没关系,见http://www.cnblogs.com/mincyw/archive/2011/02/10/1950733.html
if(strlen($btih)!==40 or !ctype_xdigit($btih)) die(json_err('btih_incorrect',-1,'Error: Link Not Correct'));
	
//查询视频是否已经存在,如btih不存在,退出
$result=NULL;
$count=safe_query("SELECT `dislike` `d_index` FROM `video` WHERE `btih` = UNHEX(?);",//d_index出错不会有严重影响,只要更新就好
		&$result, array('s',$btih));//http://stackoverflow.com/questions/1747894/
if($count!=1) die(json_err('btih_unavailable',-1,'Error: Video Not Yet Exists, Do You Want to Create It?'));//返回空

//编辑键值{"cid": count, "cid": count, ..., "cid": count},
$dislike = json_decode($result['dislike']);//json->array
$d_index = json_decode($result['d_index']);//json->array
if(!isset($dislike[strval($cid)])) $dislike[(string)$cid]=array();//强制储存为一个数组,防止作为一个值储存
//取键值
$this_dislike=$dislike[strval($cid)];
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

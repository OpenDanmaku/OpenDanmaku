<?php
	header("Access-Control-Allow-Origin: *");
	
	//打开KVDB
	$kv = new SaeKV();
	if (!$kv->init()) die("Error:" . $kv->errno());//出错
	
	//检验BTIH有效性并小写化,"magnet:?xt=urn:btih:"长度为20,btih长度为40
	$btih=(string)$_REQUEST['btih'];//字符串
	if(strlen($btih)>=60 and strpos($btih,"magnet:?xt=urn:btih:")===0)
		$btih=substr($btih,20,40);
	if(strlen($btih)!==40 or !ctype_xdigit($btih)))//防注入
		die("Link Not Valid.");
	$btih= strtolower($btih);
	//即使btih仅由0-9组成也没关系,因为代码中不存在hex与unhex

	//获取弹幕和定位表
	if(!($c_index = $kv->get($btih . ",ci"))) die("Error:" . $kv->errno());//先查询,宁缺勿错
	if(!($comment = $kv->get($btih . ",c")) ) die("Error:" . $kv->errno());//赋值运算表达式的值也就是所赋的值
	$c_index=json_decode($c_index);
	$count = count($c_index);
	$latest= $c_index[$count-1][1];//最后一条,第二个数(time)
	
	//任务判断
	if (isset($_REQUEST['action'])){
	$action=strtolower($_REQUEST['action']);
	if($action = "cid"){   //按弹幕号获取,计入view
		//修改超过范围的起始点
		if(!isset($_REQUEST['start']) $start=0;
		else {
			$start=(int)$_REQUEST['start'];
			if ($start<0 or $start>$count) $start = 0;
			}
		//修改超过范围的终止点
		if(!isset($_REQUEST['end']) $start=0;
		else {
			$end = (int)$_REQUEST['end'];
			if ($end < 0 or $end > $count) $end = $count;
			}
		//DO sth
		//DO sth
		//DO sth
		//DO sth
		//DO sth
		//DO sth
		//DO sth
		//计入view
		$mysql = new SaeMysql();//打开数据库
		$sql ="UPDATE `video` SET `view` =`view` + 1 WHERE `btih` = x'" . $btih . "';";
		$mysql->closeDb();// 关闭数据库
		exit;
		}
	if($action = "time"){  //按时间来获取,计入view
		//修改超过范围的起始点
		if(!isset($_REQUEST['start']) $start=0;
		else {
			$start=(int)$_REQUEST['start'];
			if ($start<0 or $start>$latest) $start = 0;
			}
		//修改超过范围的终止点
		if(!isset($_REQUEST['end']) $start=0;
		else {
			$end = (int)$_REQUEST['end'];
			if ($end < 0 or $end > $latest) $end = $latest;
			}
		//DO sth
		//DO sth
		//DO sth
		//DO sth
		//DO sth
		//DO sth
		//DO sth
		//计入view
		$mysql = new SaeMysql();//打开数据库
		$sql ="UPDATE `video` SET `view` =`view` + 1 WHERE `btih` = x'" . $btih . "';";
		$mysql->closeDb();// 关闭数据库
		exit;
		}
	if($action = "recent"){//获取最近,忽略end参数,不计入view
		//修改超过范围的起始点
		if(!isset($_REQUEST['start']) $start=0;
		else {
			$start=(int)$_REQUEST['start'];
			if ($start>=$count){
				echo "[]";
				exit;
				}		
			if ($start<0 or $start>$count) $start = 0;
			}
		//DO sth
		//DO sth
		//DO sth
		//DO sth
		//DO sth
		//DO sth
		//DO sth
		exit;
		}
	//都不是则视为获取全部,计入view
	}	
		//DO sth
		//DO sth
		//DO sth
		//DO sth
		//DO sth
		//DO sth
		//DO sth
	//计入view
	$mysql = new SaeMysql();//打开数据库
	$sql ="UPDATE `video` SET `view` =`view` + 1 WHERE `btih` = x'" . $btih . "';";
	$mysql->closeDb();// 关闭数据库

?>


echo返回值，可能需要加头尾
?>
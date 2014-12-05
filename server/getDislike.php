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
		die("Error: Link Not Valid.");
	$btih= strtolower($btih);
	//即使btih仅由0-9组成也没关系,因为代码中不存在hex与unhex

	if($ret = $kv->get($btih . ",di")) echo $ret;//赋值运算表达式的值也就是所赋的值
	else die("Error:" . $kv->errno());
?>

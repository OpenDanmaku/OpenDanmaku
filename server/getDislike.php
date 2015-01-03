<?php
require 'libMysqli.php';
require 'libFunction.php';
header("Access-Control-Allow-Origin: *");//无限制
	
//读取参数btih,并字符串化,小写化
$btih=getBtih();
$result=NULL;
$count=safe_query("SELECT `d_index` FROM `video` WHERE `btih` = UNHEX(?);",&$result, array('s',$btih));
if($count!=1) 
	die(json_err('btih_unavailable',-1,'Error: Video Not Yet Exists, Do You Want to Create It?'));//无返回值
exit(return[0]['d_index']);//返回字段d_index,该字段是json字符串
?>

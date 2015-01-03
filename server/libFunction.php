<?php
/* require_once('libMysqli.php');//php5.2以上都优化过,不再考虑效率损失 */
function getUid(){//int getUid(void){}
	if(!isset($_COOKIE['uid'])) die(json_err('cookie_empty',-1,'Error: No Cookie Submitted'));//返回空
	return intval($_COOKIE['uid']);
}
function getBtih(){//string getBtih(void){}
	//读取参数btih,并字符串化,小写化
	$btih=trim(strtolower(strval($_REQUEST['btih'])));
	
	//如果是完整磁链,截取btih,btih长度为40
	$pos=strpos($btih,"btih:");//len('btih:')===5
	//注意$pos会自动转换,而$pos=0和$pos=FALSE截取时有区别
	$btih=($pos===FALSE)?substr($btih,$pos+5,40):substr($btih,0,40);
	
	//检验btih长度(应该<=40)与有效性,即使btih仅由0-9组成也没关系,
	//见http://www.cnblogs.com/mincyw/archive/2011/02/10/1950733.html
	if(strlen($btih)!==40 or !ctype_xdigit($btih)) die(json_err('btih_incorrect',-1,'Error: Link Not Correct'));
	return $btih;
}
?>

<?php
require('libMysqli.php');
require('libFunction.php');
header("Access-Control-Allow-Origin: *");//无限制

//获取任务
if(isset($_REQUEST['action'])){
	switch (trim(strtolower($_REQUEST['action']))){
	case "time":{//按时间的倒序获取最近7天视频
		$result=NULL;
		$count=safe_query(
		"SELECT LOWER(HEX(`btih`)), `time`, `view`, `reply` FROM `video` WHERE `time` > ? ORDER BY `time` DESC;",
				&$result, array('i',time()-7*24*60*60));
		if($count!=1) //无返回值
			die(json_err('btih_unavailable',-1,'Error: Video Not Yet Exists, Do You Want to Create It?'));
		exit(json_encode(return));
		}//break无用
	case "view":{//按播放量倒序获取最近7天视频
		$result=NULL;
		$count=safe_query(
		"SELECT LOWER(HEX(`btih`)), `time`, `view`, `reply` FROM `video` WHERE `time` > ? ORDER BY `view` DESC;",
				&$result, array('i',time()-7*24*60*60));
		if($count!=1) //无返回值
			die(json_err('btih_unavailable',-1,'Error: Video Not Yet Exists, Do You Want to Create It?'));
		exit(json_encode(return));
		}//break无用
	case "reply":{//按播放量倒序获取最近7天视频
		$result=NULL;
		$count=safe_query(
		"SELECT LOWER(HEX(`btih`)), `time`, `view`, `reply` FROM `video` WHERE `time` > ? ORDER BY `reply` DESC;",
				&$result, array('i',time()-7*24*60*60));
		if($count!=1) //无返回值
			die(json_err('btih_unavailable',-1,'Error: Video Not Yet Exists, Do You Want to Create It?'));
		exit(json_encode(return));
		}//break无用
	//以上都不是则视为查询btih,形式上建议用参数action=find,虽然不会去检验
	case "find":break;//空语句也可以,总之是执行switch之外的代码
	}
}//没有else，继续执行下面的代码

		//读取参数btih,并字符串化,小写化
		$btih=getBtih();
		//按BTIH筛选,取所有信息
		$result=NULL;
		$count=safe_query("SELECT LOWER(HEX(`btih`)), `time`, `view`, `reply` FROM `video` WHERE `btih` = UNHEX(?);",
				&$result, array('s',$btih));
		if($count!=1) //无返回值
			die(json_err('btih_unavailable',-1,'Error: Video Not Yet Exists, Do You Want to Create It?'));
		exit(json_encode(return[0]));//既然只返回这一条,我想不应该再套一层数组
?>

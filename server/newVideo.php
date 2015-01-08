<?php
require 'libMysqli.php';
require 'libFunction.php';
header("Access-Control-Allow-Origin: *");//无限制
	
//硬直与禁言设定
$const_ScoreNewVideo = 10;//加10分
$const_DelayNewVideo = 60;//60秒硬直

//获取Cookie对应用户数据,如果key不符合,退出
	checkCookie();
	$uid=getUid();
//获取btih,查询视频是否已经存在,如btih不存在,退出
	$btih=getBtih();
	//checkBtih();//用不着,下面语句解决了
	
//查询视频是否已经存在,如btih不存在,退出
$result=NULL;//d_index出错不会有严重影响,只要更新就好
$count=safe_query("SELECT `c_index`, `dislike`, `d_index` FROM `video` WHERE `btih` = UNHEX(?);",
		&$result, array('s',$btih));//http://stackoverflow.com/questions/1747894/
if($count!=0) die(json_err('btih_created',-1,'Error: Video Already Exists'));//返回空

//添加到`video`
$blackhole=NULL;
$count=safe_query("INSERT INTO `video` (`uid`, `time`, `view`, `reply`, `btih`,
		`comment`, `c_index`, `linkage`, `l_index`, `dislike`, `d_index`) 
		VALUES (?, ?, 0, 0, ?, '', '[]', '[]', '[]', '[]', '[]');",
		&$blackhole, array('iis', $uid, time(), $bith));//主键自增,comment赋空字符串,其余元素赋空数组

//startup.sql有一句SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
//NO_AUTO_VALUE_ON_ZERO禁用0，但我事实上传递的是NULL(其实是没传递),可以生成下一个序列号
//为一个NOT NULL的整型赋NULL值，结果是0，它并不会出错,参见http://niutuku.com/tech/Mysql/237698.shtml
//MySQL会自动将NULL值转化为该字段的默认值,哪怕是你在表定义时没有明确地为该字段设置默认值
//newCookie.php因为已经获取了最新uid所以无须担心

if($count!=1)
	die(json_err('video_notcreated',-1,'Error: Failed to Create New Video'));//返回空

//提高积分并暂时硬直
normalFreeze($uid, $const_ScoreNewVideo, $const_DelayNewVideo);
//返回成功页面
exit(json_err('newVideo',0,"Video Created Successfully!"));
?>

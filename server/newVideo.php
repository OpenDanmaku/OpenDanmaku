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
$count=safe_query("SELECT `c_index` `dislike` `d_index` FROM `video` WHERE `btih` = UNHEX(?);",
		&$result, array('s',$btih));//http://stackoverflow.com/questions/1747894/
if($count!=0) die(json_err('btih_created',-1,'Error: Video Already Exists'));//返回空

//添加到`video`
$blackhole=NULL;
$count=safe_query("INSERT INTO `video` (uid`, `time`, `view`, `reply`, `btih`,
		`comment`, `c_index`, `linkage`, `l_index`, `dislike`, `d_index`) 
		VALUES (?, ?, 0, 0, ?, '', '[]', '[]', '[]', '[]', '[]');",
		&$blackhole, array('iis', $uid, time(), $bith));//主键自增,comment赋空字符串,其余元素赋空数组
if($count!=1)
	die(json_err('video_notcreated',-1,'Error: Failed to Create New Video'));//返回空

//提高积分并暂时硬直
normalFreeze($uid, $const_ScoreNewVideo, $const_DelayNewVideo);
//返回成功页面
exit(json_err('newVideo',0,"Video Created Successfully!"));
?>

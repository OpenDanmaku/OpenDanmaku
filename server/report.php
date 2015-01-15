<?php

$uid  = (isset($_REQUEST['uid']))? $_REQUEST['uid']  : "NULL";
$info =(isset($_REQUEST['info']))? $_REQUEST['info'] : "NULL";
$str  = $uid . "\r\n" . $info . "\r\n\r\n";
echo $str;
$fp   = fopen("report.txt", "a"); 
$flag = fwrite($fp, $str); 
fclose($fp); 

if(!$flag) die("写入文件失败");
else      exit("成功提交报告");

?>
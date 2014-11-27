<?php
$mysql = new SaeMysql();

$sql = "SELECT * FROM `user` LIMIT 10";
$data = $mysql->getData( $sql );
$name = strip_tags( $_REQUEST['name'] );
$age = intval( $_REQUEST['age'] );
$sql = "INSERT  INTO `user` ( `name`, `age`, `regtime`) VALUES ('"  . $mysql->escape( $name ) . "' , '" . intval( $age ) . "' , NOW() ) ";
$mysql->runSql($sql);
if ($mysql->errno() != 0)
{
    die("Error:" . $mysql->errmsg());
}

$mysql->closeDb();

读取request的btih
打开kvdb
如果错误var_dump退出
查找key:btih_poor
如果不存在退出
echo返回值，可能需要加头尾
然后才打开数据库
如果错误var_dump退出
设置sql语句,video.visit自加一
run_sql
如果错误var_dump退出
退出
?>
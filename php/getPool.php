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

��ȡrequest��btih
��kvdb
�������var_dump�˳�
����key:btih_poor
����������˳�
echo����ֵ��������Ҫ��ͷβ
Ȼ��Ŵ����ݿ�
�������var_dump�˳�
����sql���,video.visit�Լ�һ
run_sql
�������var_dump�˳�
�˳�
?>
<?php
//初始化数据库
打开数据库
如果错误var_dump退出
打开kvdb
如果错误var_dump退出
//读取user信息
sql="SELECT * FROM user WHERE uid= " . cookie.uid
user=run_sql
如果错误var_dump退出
//验证用户
读取cookie
如果user（key）!=cookie.key报错终止
//读取参数
读取$_request，参数为btih
//查询btih是否还不存在
设置sql语句,btih为request
run_sql
如果db.video(btih)非空 var_dump退出
//===============================
//添加btih
设置sql语句，
	btih为request,
	user为uid,
	time=time(),
	visit=0,
	reply=0
run_sql
如果错误var_dump退出
//添加弹幕池,链接池,举报池
新建kvdb(btih_pool)=""
如果错误var_dump退出
新建kvdb(btih_link)=""
如果错误var_dump退出
新建kvdb(btih_abhor)=""
如果错误var_dump退出
//===============================
//提高积分并暂时禁言
user.score+=constScoreNewPool(正的)
user.time+=constdelayNewPool
设置sql语句，写入user
run_sql
如果错误var_dump退出
//返回成功页面
echo成功页面
关闭数据库
关闭kvdb
?>
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

<?php
//初始化数据库
打开数据库
如果错误var_dump退出
打开kvdb
如果错误var_dump退出
//验证用户
sql="SELECT * FROM user WHERE uid= " . cookie.uid
user=run_sql
读取cookie
如果错误var_dump退出
//验证用户
读取cookie
如果user（key）!=cookie.key报错终止
//读取参数
读取$_request，参数为btih1,cid,uid
//查询btih是否还不存在,Null不是空
如果kvdb(btih_abhor)为Null var_dump退出

//===============================
//读取abhor
abhor=json_decode(kvdb("btih_abhor"))
//检验是否存在md5(uid)
如果abhor(pid)不存在增加abhor(pid)[]
如果array_search(abhor(pid),md5(uid))返回已举报终止
//写入abhor
abhor(pid)+=md5(uid)
kvdb("btih_abhor")=json_encode(abhor)
//写入hated
sql="SELECT * FROM user WHERE uid= " . uid
hated=run_sql
如果错误var_dump退出
hated.score+=constScoreNewPool(负的)
如果hated.score<0
	delay=ceil((-hated.score)/constRate)
hated.time+=delay
hated.score=delay*constRate
sql="UPDATE"
run_sql
如果错误var_dump退出
//===============================
//减少积分并暂时禁言
user.score+=constScoreNewPool(负的)
user.time+=constdelayNewPool
如果user.score<0
	delay=ceil((-user.score)/constRate)
user.time+=delay
user.score=delay*constRate
sql="UPDATE"
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

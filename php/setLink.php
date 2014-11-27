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
读取$_request，参数为btih1,btih2,time
//查询btih是否还不存在,Null不是空
如果kvdb(btih1_link)为Null var_dump退出
如果kvdb(btih2_link)为Null var_dump退出

//===============================
//读取link
link1=json_decode(kvdb(btih1_link))
link2=json_decode(kvdb(btih2_link))
//检验是否存在uid
如果abhor(btih1."_". time)不存在增加abhor(btih2."_". time)[]
如果abhor(btih2."_".-time)不存在增加abhor(btih2."_".-time)[]
如果array_search(abhor(btih1."_". time),uid)
且	array_search(abhor(btih2."_". time),uid)
	返回已举报终止
//写入abhor
abhor(btih1."_". time)+=md5(uid)
abhor(btih2."_".-time)+=md5(uid)
kvdb(btih1_link)=json_encode(link1)
kvdb(btih1_link)=json_encode(link2)


//===============================
//提高积分并暂时禁言
user.score+=constScoreNewLink(正的)
user.time+=constdelayNewLink
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

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
读取$_request，参数为btih,danmaku
//查询btih是否还不存在,Null不是空
如果kvdb(btih_pool)为Null var_dump退出


//===============================
//修改danmaku
danmaku=json_decode(danmaku)
danmaku(c)[4]=user.uid
danmaku(c)[5]=time()
danmaku(c)[6]=????????
danmaku(score)=????????
danmaku=json_encode(danmaku)
//添加danmaku
kvdb(btih_pool)+=danmaku
如果错误var_dump退出
//增加reply计数
设置sql语句,video.reply自加一
run_sql
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

danmaku=json_decode
danmaku+=pid->pid
danmaku+=uid->md5uid
danmaku+=time->date()
danmaku=json_encode


	{
	"c":"sec.000,color=FFFFFF,type(1),size(25),uid,timestamp,unknown-GUID",
	"m":"text",
	"score":"unknown-GUID"
	},
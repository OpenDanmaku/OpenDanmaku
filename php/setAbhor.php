<?php
//��ʼ�����ݿ�
�����ݿ�
�������var_dump�˳�
��kvdb
�������var_dump�˳�
//��֤�û�
sql="SELECT * FROM user WHERE uid= " . cookie.uid
user=run_sql
��ȡcookie
�������var_dump�˳�
//��֤�û�
��ȡcookie
���user��key��!=cookie.key������ֹ
//��ȡ����
��ȡ$_request������Ϊbtih1,cid,uid
//��ѯbtih�Ƿ񻹲�����,Null���ǿ�
���kvdb(btih_abhor)ΪNull var_dump�˳�

//===============================
//��ȡabhor
abhor=json_decode(kvdb("btih_abhor"))
//�����Ƿ����md5(uid)
���abhor(pid)����������abhor(pid)[]
���array_search(abhor(pid),md5(uid))�����Ѿٱ���ֹ
//д��abhor
abhor(pid)+=md5(uid)
kvdb("btih_abhor")=json_encode(abhor)
//д��hated
sql="SELECT * FROM user WHERE uid= " . uid
hated=run_sql
�������var_dump�˳�
hated.score+=constScoreNewPool(����)
���hated.score<0
	delay=ceil((-hated.score)/constRate)
hated.time+=delay
hated.score=delay*constRate
sql="UPDATE"
run_sql
�������var_dump�˳�
//===============================
//���ٻ��ֲ���ʱ����
user.score+=constScoreNewPool(����)
user.time+=constdelayNewPool
���user.score<0
	delay=ceil((-user.score)/constRate)
user.time+=delay
user.score=delay*constRate
sql="UPDATE"
run_sql
�������var_dump�˳�
//���سɹ�ҳ��
echo�ɹ�ҳ��
�ر����ݿ�
�ر�kvdb
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

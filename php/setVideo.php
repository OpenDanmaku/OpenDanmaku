<?php
//��ʼ�����ݿ�
�����ݿ�
�������var_dump�˳�
��kvdb
�������var_dump�˳�
//��ȡuser��Ϣ
sql="SELECT * FROM user WHERE uid= " . cookie.uid
user=run_sql
�������var_dump�˳�
//��֤�û�
��ȡcookie
���user��key��!=cookie.key������ֹ
//��ȡ����
��ȡ$_request������Ϊbtih
//��ѯbtih�Ƿ񻹲�����
����sql���,btihΪrequest
run_sql
���db.video(btih)�ǿ� var_dump�˳�
//===============================
//���btih
����sql��䣬
	btihΪrequest,
	userΪuid,
	time=time(),
	visit=0,
	reply=0
run_sql
�������var_dump�˳�
//��ӵ�Ļ��,���ӳ�,�ٱ���
�½�kvdb(btih_pool)=""
�������var_dump�˳�
�½�kvdb(btih_link)=""
�������var_dump�˳�
�½�kvdb(btih_abhor)=""
�������var_dump�˳�
//===============================
//��߻��ֲ���ʱ����
user.score+=constScoreNewPool(����)
user.time+=constdelayNewPool
����sql��䣬д��user
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

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
��ȡ$_request������Ϊbtih1,btih2,time
//��ѯbtih�Ƿ񻹲�����,Null���ǿ�
���kvdb(btih1_link)ΪNull var_dump�˳�
���kvdb(btih2_link)ΪNull var_dump�˳�

//===============================
//��ȡlink
link1=json_decode(kvdb(btih1_link))
link2=json_decode(kvdb(btih2_link))
//�����Ƿ����uid
���abhor(btih1."_". time)����������abhor(btih2."_". time)[]
���abhor(btih2."_".-time)����������abhor(btih2."_".-time)[]
���array_search(abhor(btih1."_". time),uid)
��	array_search(abhor(btih2."_". time),uid)
	�����Ѿٱ���ֹ
//д��abhor
abhor(btih1."_". time)+=md5(uid)
abhor(btih2."_".-time)+=md5(uid)
kvdb(btih1_link)=json_encode(link1)
kvdb(btih1_link)=json_encode(link2)


//===============================
//��߻��ֲ���ʱ����
user.score+=constScoreNewLink(����)
user.time+=constdelayNewLink
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

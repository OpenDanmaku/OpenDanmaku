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
��ȡ$_request������Ϊbtih,danmaku
//��ѯbtih�Ƿ񻹲�����,Null���ǿ�
���kvdb(btih_pool)ΪNull var_dump�˳�


//===============================
//�޸�danmaku
danmaku=json_decode(danmaku)
danmaku(c)[4]=user.uid
danmaku(c)[5]=time()
danmaku(c)[6]=????????
danmaku(score)=????????
danmaku=json_encode(danmaku)
//���danmaku
kvdb(btih_pool)+=danmaku
�������var_dump�˳�
//����reply����
����sql���,video.reply�Լ�һ
run_sql
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
# ReadMe
##	���ݿ�洢��ʽ
### TABLE USER
    CREATE TABLE IF NOT EXISTS `user` (
        `uid`    INT(10) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT,
        `key`    INT(10) UNSIGNED ZEROFILL NOT NULL,
        `time`   INT(1)  NOT NULL,
        `point`  INT(1)  NOT NULL,
        `status` INT(1)  NOT NULL,
        PRIMARY KEY (`uid`))
    ENGINE=MyISAM
    DEFAULT CHARSET=utf8
    COLLATE=utf8_unicode_ci;
### TABLE VIDEO
    CREATE TABLE IF NOT EXISTS `video` (
        `vid`    INT(10) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT,
        `uid`    INT(10) UNSIGNED ZEROFILL NOT NULL,
        `time`   INT(1)  NOT NULL,
        `view`   INT(1)  NOT NULL,
        `reply`  INT(1)  NOT NULL,
        `btih`   BINARY(10) NOT NULL,
        PRIMARY KEY (`vid`),
        UNIQUE  KEY `btih` (`btih`))
    ENGINE=MyISAM
    DEFAULT CHARSET=utf8
    COLLATE=utf8_unicode_ci;
    //MySQLҪ��SQL����ԷֺŽ�β
##	KVDB�����ʽ
### ��Ƶ��Ļ
####	STRING kvdb.comment: �±�[0,reply-1],
	//ע�����Ķ��ය�Żᱻ������������,��php�ᱨ��,������"[]"
	"{"c":"sec.000,color=FFFFFF,type(1),size(25),uid,timestamp","m":"text","cid":1},
	{"c":"sec.000,color=FFFFFF,type(1),size(25),uid,timestamp","m":"text","cid":2},
	...
	{"c":"sec.000,color=FFFFFF,type(1),size(25),uid,timestamp","m":"text","cid":cid},"
####	JSON kvdb.comment.index: �±�[0,reply-1],timeΪ����ʱ�䣬sizeΪ��Ļ��ȫ��
	[
	[uid,time,size],
	[uid,time,size],
	...
	[uid,time,size]
	]
### ��Ƶ����,ע������������ú����ò�����,ע��btih����Сд(json��keyҪ��Сд��ͷ)
####	ARRAY kvdb.link
	{
	"btih,ms":[uid,uid,...],
	"btih,ms":[uid,uid,...],
	...
	"btih,ms":[uid,uid,...]
	}
####	JSON kvdb.link.index
	{
	"btih,ms":count,
	"btih,ms":count,
	...
	"btih,ms":count
	}
### ��Ƶ�ٱ�,��ҪΪcid��JSON_FORCE_OBJECT,Ҫ��Ϊ�ַ�����д
####	ARRAY kvdb.dislike
	{
	"cid":[uid,uid,...],
	"cid":[uid,uid,...],
	...
	"cid":[uid,uid,...]
	}
####	JSON kvdb.dislike.index
	{
	"cid":count,
	"cid":count,
	...
	"cid":count
	}
	
##  �������ӿ�
###	��ʼ��
*   ./init.php      //��˽�У������ã�POST����������key
*   ./delDummy.php  //��˽�У������ã�POST����������key
*   ./newCookie.php //�̻�ȡ��Cookie��GET ����������vcode
*   ./getVcode.php  //�̻�ȡ��֤ͼƬ��GET ����������rand(α)

###	����
*   ./newVideo.php  //  ������Ƶ��Ϣ��POST����������btih
*   ./newLink.php   //  ����������Ϣ��POST����������btih1,btih2,time
*   ./newComment.php//  ������Ļ��Ϣ��POST����������btih,danmaku
*   ./newDislike.php//  ����Ͷ����Ϣ��POST����������btih,cid

###	��ȡ
*   ./getVideo.php  //  ��ȡ��Ƶ��Ϣ��GET ����������btih,action
*   ./getLink.pho   //  ��ȡ�������ݣ�GET ����������btih
*   ./getComment.php//  ��ȡ��Ļ���ݣ�GET ����������btih,action,start,end,count
*   ./getDislike.php//  ��ȡͶ�����ݣ�GET ����������btih

###	����getComment
*	����Ż�ȡ: bith=0000000000000000000000000000000000000000&action=cid&start=cid_start&end=cid_end
*	��ʱ���ȡ: bith=0000000000000000000000000000000000000000&action=timed&start=time_start&end=time_end
*	ĳ�ż��Ժ�: bith=0000000000000000000000000000000000000000&action=recent&start=cid_start
*	��������: bith=0000000000000000000000000000000000000000&action=timed&count=count
*	��ȡȫ��Ļ: bith=0000000000000000000000000000000000000000&action=all
		
##  �ͻ�����ҳ
###	��ҳ
*   ./index.htm     // ��ҳ������Cookie��Video��Link
*   ./css/style.css // ��ҳ��ʽ
*   ./img/logo.png  // վ��Logo
*   ./img/sae.png   // SAE Logo

###	����ҳ
*   ./player.htm    // ����ҳ�棬ͬʱ����Abhor��Pool
*   ./js/CommentCoreLibrary.min.js  // ��Ļ������
*   ./js/ABPlayer.min.js // �������ű�
*   ./js/ABPLibxml.js    // Usage Unknown
*   ./js/ABPMobile.js    // Usage Unknown
*   ./css/ext/styles.css // normalize.css,����base.min.css֮ǰ
*   ./css/base.min.css?1 // ��������ʽ
*   ./css/danmaku.png    // ��������ť
*   ./css/fullscreen.png // ��������ť
*   ./css/pause.png      // ��������ť
*   ./css/play.png       // ��������ť
# 
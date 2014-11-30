# ReadMe
##	���ݿ�洢��ʽ
### TABLE USER
    CREATE TABLE IF NOT EXISTS `user` (
        `uid`   INT(10) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT,
        `key`   INT(10) UNSIGNED ZEROFILL NOT NULL,
        `time`  INT(1)  NOT NULL,
        `point` INT(1)  NOT NULL,
        `state` INT(1)  NOT NULL,
        PRIMARY KEY (`uid`))
    ENGINE=MyISAM
    DEFAULT CHARSET=utf8
    COLLATE=utf8_unicode_ci;
### TABLE VIDEO
    CREATE TABLE IF NOT EXISTS `video` (
        `vid`   INT(10) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT,
        `uid`   INT(10) UNSIGNED ZEROFILL NOT NULL,
        `time`  INT(1)  NOT NULL,
        `view`  INT(1)  NOT NULL,
        `reply` INT(1)  NOT NULL,
        `btih`  BINARY(10) NOT NULL,
        PRIMARY KEY (`vid`),
        UNIQUE  KEY `btih` (`btih`))
    ENGINE=MyISAM
    DEFAULT CHARSET=utf8
    COLLATE=utf8_unicode_ci;
##	KVDB�����ʽ
### kvdb.pool: ���浯Ļ��,ע�����Ķ��ය�Żᱻ�������,������"[]"
	{"c":"sec.000,color=FFFFFF,type(1),size(25),uid,timestamp","m":"text","cid":1},
	{"c":"sec.000,color=FFFFFF,type(1),size(25),uid,timestamp","m":"text","cid":2},
	...
	{"c":"sec.000,color=FFFFFF,type(1),size(25),uid,timestamp","m":"text","cid":cid},
### kvdb.index: ��Ƶ��Ļ����,����[0,reply-1]��׷��poolʱ����,timeΪ����ʱ�䣬sizeΪ��Ļ��ȫ��
	[
	[uid,time,size],
	[uid,time,size],
	...
	[uid,time,size]
	]
### kvdb.link: ��Ƶ��������
	[
	"btih,ms":[uid,uid,...],
	"btih,ms":[uid,uid,...],
	...
	"btih,ms":[uid,uid,...]
	]
### kvdb.abhor: ��Ƶ�ٱ�����
	[
	cid:[uid,uid,...],
	cid:[uid,uid,...],
	...
	cid:[uid,uid,...]
	]
##  �������ӿ�
*   ./init.php      //��˽�У������ã�POST����������key
*   ./delDummy.php  //��˽�У������ã�POST����������key
*   ./getVcode.php  //�̻�ȡ��֤ͼƬ��GET ����������rand(α)
*   ./getCookie.php //�̻�ȡ��Cookie��GET ����������vcode
*   ./setVideo.php  //  ������Ƶ��Ϣ��POST����������btih
*   ./setLink.php   //  ����������Ϣ��POST����������btih1,btih2,time
*   ./setAbhor.php  //  ����Ͷ����Ϣ��POST����������btih,cid
*   ./setPool.php   //  ������Ļ��Ϣ��POST����������btih,danmaku
*   ./getVideo.php  //  ��ȡ��Ƶ��Ϣ��GET ����������btih
*   ./getLink.pho   //  ��ȡ�������ݣ�GET ����������btih
*   ./getAbhor.php  //  ��ȡͶ�����ݣ�GET ����������btih
*   ./getPool.php   //  ��ȡ��Ļ���ݣ�GET ����������btih,[type,start,end]

##  �ͻ�����ҳ
*   ./index.htm     // ��ҳ������Cookie��Video��Link
*   ./css/style.css // ��ҳ��ʽ
*   ./img/logo.png  // վ��Logo
*   ./img/sae.png   // SAE Logo
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
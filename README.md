#	ReadMe
##	���ݴ洢��ʽ
	CREATE TABLE IF NOT EXISTS `user` (		// db.user
		`uid`	INT(1) UNSIGNED	NOT NULL	// UID
		`key`	INT(1) UNSIGNED	NOT NULL	// ��Կ
		`score`	INT(1) UNSIGNED	NOT NULL	// ���֣��ɸ���
		`time`	DATETIME NOT NULL 			// ���ʱ��
		PRIMARY KEY (`uid`)					// UID��Ϊ����
		) ENGINE = MyIASM  DEFAULT CHARSET = utf8
	CREATE TABLE IF NOT EXISTS `video` (	// db.video
		`btih`	CHAR(40) NOT NULL			// UID
		`time`	DATETIME NOT NULL 			// ��Ƶ����ʱ��
		`visit	INT(1) UNSIGNED	NOT NULL	// �������ͳ��
		`reply`	INT(1) UNSIGNED	NOT NULL	// ��Ļ����ͳ��
		PRIMARY KEY (`btih`)				// BTIH��Ϊ����
		) ENGINE = MyIASM  DEFAULT CHARSET = utf8
	//kvdb.pool:	���浯Ļ��,ÿ����"{"��ͷ,��"},"��β,ע�����Ķ��ය�Żᱻ�������
		[{"c":"sec.000,color=FFFFFF,type(1),size(25),uid,timestamp","m":"text","no":serial},...,]
	//kvdb.index:	��Ƶ��Ļ����,����[0,reply-1],Ԫ��Ϊ[uid(�û���),time(����ʱ��),goto(�ַ���λ��)],��׷��poolʱ����
	//kvdb.link:	��Ƶ��������,key="btih_millisec",value=[uid,uid,...]
	//kvdb.abhor:	��Ƶ�ٱ�����,key=No.,value=[md5(uid),md5(uid),...]
##	�������ӿ�
��	./init.php			// ˽�У������ã�POST����������key
��	./delDummy.php		// ˽�У������ã�POST����������key,time
��	./getVcode.php		// ��ȡ��֤ͼƬ��GET ����������rand
��	./getCookie.php		// ��ȡ��Cookie��GET ����������vcode
	./setVideo.php		// ������Ƶ��Ϣ��POST����������btih
	./setLink.php		// ����������Ϣ��POST����������btih1,btih2,time
	./setAbhor.php		// ����Ͷ����Ϣ��POST����������btih,cid
	./setPool.php		// ������Ļ��Ϣ��POST����������btih,danmaku
	./getVideo.php		// ��ȡ��Ƶ��Ϣ��GET ����������btih
	./getLink.pho		// ��ȡ�������ݣ�GET ����������btih
	./getAbhor.php		// ��ȡͶ�����ݣ�GET ����������btih
	./getPool.php		// ��ȡ��Ļ���ݣ�GET ����������btih,[type,start,end]
##	�ͻ�����ҳ
	./index.htm			// ��ҳ������Cookie��Video��Link
	./css/style.css		// ��ҳ��ʽ
	./img/logo.png		// վ��Logo
	./img/sae.png		// SAE Logo
	./player.htm					// ����ҳ�棬ͬʱ����Abhor��Pool
	./js/CommentCoreLibrary.min.js	// ��Ļ������
	./js/ABPlayer.min.js			// �������ű�
	./js/ABPLibxml.js				// Usage Unknown
	./js/ABPMobile.js				// Usage Unknown
	./css/ext/styles.css			// normalize.css,����base.min.css֮ǰ
	./css/base.min.css?1			// ��������ʽ
	./css/danmaku.png				// ��������ť
	./css/fullscreen.png			// ��������ť
	./css/pause.png					// ��������ť
	./css/play.png					// ��������ť
# 
#	ReadMe
##	数据存储格式
	CREATE TABLE IF NOT EXISTS `user` (		// db.user
		`uid`	INT(1) UNSIGNED	NOT NULL	// UID
		`key`	INT(1) UNSIGNED	NOT NULL	// 秘钥
		`score`	INT(1) UNSIGNED	NOT NULL	// 积分，可负。
		`time`	DATETIME NOT NULL 			// 解禁时间
		PRIMARY KEY (`uid`)					// UID作为主键
		) ENGINE = MyIASM  DEFAULT CHARSET = utf8
	CREATE TABLE IF NOT EXISTS `video` (	// db.video
		`btih`	CHAR(40) NOT NULL			// UID
		`time`	DATETIME NOT NULL 			// 视频创建时间
		`visit	INT(1) UNSIGNED	NOT NULL	// 浏览次数统计
		`reply`	INT(1) UNSIGNED	NOT NULL	// 弹幕数量统计
		PRIMARY KEY (`btih`)				// BTIH作为主键
		) ENGINE = MyIASM  DEFAULT CHARSET = utf8
	//kvdb.pool:	储存弹幕行,每行以"{"开头,以"},"结尾,注意最后的多余逗号会被引擎忽略
		[{"c":"sec.000,color=FFFFFF,type(1),size(25),uid,timestamp","m":"text","no":serial},...,]
	//kvdb.index:	视频弹幕索引,数组[0,reply-1],元素为[uid(用户名),time(发表时间),goto(字符串位置)],在追加pool时生成
	//kvdb.link:	视频交叉链接,key="btih_millisec",value=[uid,uid,...]
	//kvdb.abhor:	视频举报储存,key=No.,value=[md5(uid),md5(uid),...]
##	服务器接口
√	./init.php			// 私有，调试用，POST方法，参数key
√	./delDummy.php		// 私有，调试用，POST方法，参数key,time
√	./getVcode.php		// 获取验证图片，GET 方法，参数rand
√	./getCookie.php		// 获取新Cookie，GET 方法，参数vcode
	./setVideo.php		// 创建视频信息，POST方法，参数btih
	./setLink.php		// 创建链接信息，POST方法，参数btih1,btih2,time
	./setAbhor.php		// 创建投诉信息，POST方法，参数btih,cid
	./setPool.php		// 创建弹幕信息，POST方法，参数btih,danmaku
	./getVideo.php		// 获取视频信息，GET 方法，参数btih
	./getLink.pho		// 获取链接数据，GET 方法，参数btih
	./getAbhor.php		// 获取投诉数据，GET 方法，参数btih
	./getPool.php		// 获取弹幕数据，GET 方法，参数btih,[type,start,end]
##	客户端网页
	./index.htm			// 主页，操作Cookie，Video，Link
	./css/style.css		// 主页样式
	./img/logo.png		// 站点Logo
	./img/sae.png		// SAE Logo
	./player.htm					// 播放页面，同时操作Abhor，Pool
	./js/CommentCoreLibrary.min.js	// 弹幕函数库
	./js/ABPlayer.min.js			// 播放器脚本
	./js/ABPLibxml.js				// Usage Unknown
	./js/ABPMobile.js				// Usage Unknown
	./css/ext/styles.css			// normalize.css,居于base.min.css之前
	./css/base.min.css?1			// 播放器样式
	./css/danmaku.png				// 播放器按钮
	./css/fullscreen.png			// 播放器按钮
	./css/pause.png					// 播放器按钮
	./css/play.png					// 播放器按钮
# 
OpenDanmaku
===========

A project of danmaku storage service.

http://h.acfun.tv/t/3235974

http://h.acfun.tv/t/4628811

#	ReadMe

##	数据存储格式

	//user
	CREATE TABLE IF NOT EXISTS `user` (
		`uid`	INT(1) UNSIGNED	NOT NULL	// UID
		`key`	INT(1) UNSIGNED	NOT NULL	// 秘钥
		`score`	INT(1) UNSIGNED	NOT NULL	// 积分，可负。
		`time`	DATETIME NOT NULL 			// 解禁时间
		PRIMARY KEY (`uid`)					// UID作为主键
		) ENGINE = MyIASM  DEFAULT CHARSET = utf8
	//video
	CREATE TABLE IF NOT EXISTS `video` (
		`btih`	CHAR(40) NOT NULL			// UID
		`time`	DATETIME NOT NULL 			// 视频创建时间
		`visit	INT(1) UNSIGNED	NOT NULL	// 浏览次数统计
		`reply`	INT(1) UNSIGNED	NOT NULL	// 弹幕数量统计
		`link`	TEXT NOT NULL				// 视频交叉链接
		`abhor`	TEXT NOT NULL				// 视频举报储存
		`pool`	TEXT NOT NULL				// 视频弹幕索引
		PRIMARY KEY (`btih`)				// BTIH作为主键
		) ENGINE = MyIASM  DEFAULT CHARSET = utf8

##	数据库接口

	./init.php			//√私有，调试用，POST方法，参数key
	./delDummy.php		//√私有，调试用，POST方法，参数key,time
	
	./getVcode.php		//√获取验证图片，GET 方法，参数rand
	./getCookie.php		//？获取新Cookie，GET 方法，参数vcode
	
	./setVideo.php		// 创建视频信息，POST方法，参数btih
	./getVideo.php		// 获取视频信息，GET 方法，参数btih

	./setLink.php		// 创建链接信息，POST方法，参数btih1,btih2
	./getLink.pho		// 获取链接数据，GET 方法，参数btih

	./setAbhor.php		// 创建投诉信息，POST方法，参数btih,danmaku_id
	./getAbhor.php		// 获取投诉数据，GET 方法，参数btih

	./setPool.php		// 创建弹幕信息，POST方法，参数btih,danmaku
	./getPool.php		// 获取弹幕数据，GET 方法，参数btih
	
	// 数据指多条信息

##	客户端接口

	index.htm			// 主页，操作Cookie，Video，Link
	css/style.css		// 主页样式
	img/logo.png		// 站点Logo
	img/sae.png			// SAE Logo
	
	player.htm			// 播放页面，同时操作Abhor，Pool
	css/style.css		// （同上）主页样式
	img/logo.png		// （同上）站点Logo
	img/sae.png			// （同上）SAE Logo
	js/player.js		// 播放器脚本
	js/CommonCoreLibrary.js	// 弹幕函数库
	js/jQuery-1.11.1.js	// jQuery库
	
#

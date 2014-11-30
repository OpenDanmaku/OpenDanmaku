# ReadMe
##	数据库存储格式
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
##	KVDB储存格式
### kvdb.pool: 储存弹幕行,注意最后的多余逗号会被引擎忽略,不储存"[]"
	{"c":"sec.000,color=FFFFFF,type(1),size(25),uid,timestamp","m":"text","cid":1},
	{"c":"sec.000,color=FFFFFF,type(1),size(25),uid,timestamp","m":"text","cid":2},
	...
	{"c":"sec.000,color=FFFFFF,type(1),size(25),uid,timestamp","m":"text","cid":cid},
### kvdb.index: 视频弹幕索引,数组[0,reply-1]在追加pool时生成,time为发表时间，size为弹幕池全长
	[
	[uid,time,size],
	[uid,time,size],
	...
	[uid,time,size]
	]
### kvdb.link: 视频交叉链接
	[
	"btih,ms":[uid,uid,...],
	"btih,ms":[uid,uid,...],
	...
	"btih,ms":[uid,uid,...]
	]
### kvdb.abhor: 视频举报储存
	[
	cid:[uid,uid,...],
	cid:[uid,uid,...],
	...
	cid:[uid,uid,...]
	]
##  服务器接口
*   ./init.php      //√私有，调试用，POST方法，参数key
*   ./delDummy.php  //√私有，调试用，POST方法，参数key
*   ./getVcode.php  //√获取验证图片，GET 方法，参数rand(伪)
*   ./getCookie.php //√获取新Cookie，GET 方法，参数vcode
*   ./setVideo.php  //  创建视频信息，POST方法，参数btih
*   ./setLink.php   //  创建链接信息，POST方法，参数btih1,btih2,time
*   ./setAbhor.php  //  创建投诉信息，POST方法，参数btih,cid
*   ./setPool.php   //  创建弹幕信息，POST方法，参数btih,danmaku
*   ./getVideo.php  //  获取视频信息，GET 方法，参数btih
*   ./getLink.pho   //  获取链接数据，GET 方法，参数btih
*   ./getAbhor.php  //  获取投诉数据，GET 方法，参数btih
*   ./getPool.php   //  获取弹幕数据，GET 方法，参数btih,[type,start,end]

##  客户端网页
*   ./index.htm     // 主页，操作Cookie，Video，Link
*   ./css/style.css // 主页样式
*   ./img/logo.png  // 站点Logo
*   ./img/sae.png   // SAE Logo
*   ./player.htm    // 播放页面，同时操作Abhor，Pool
*   ./js/CommentCoreLibrary.min.js  // 弹幕函数库
*   ./js/ABPlayer.min.js // 播放器脚本
*   ./js/ABPLibxml.js    // Usage Unknown
*   ./js/ABPMobile.js    // Usage Unknown
*   ./css/ext/styles.css // normalize.css,居于base.min.css之前
*   ./css/base.min.css?1 // 播放器样式
*   ./css/danmaku.png    // 播放器按钮
*   ./css/fullscreen.png // 播放器按钮
*   ./css/pause.png      // 播放器按钮
*   ./css/play.png       // 播放器按钮
# 
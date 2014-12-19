# ReadMe
##	数据库存储格式
### TABLE USER
    CREATE TABLE IF NOT EXISTS `user` (
        `uid`    INT(10) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT,
        `key`    INT(10) UNSIGNED ZEROFILL NOT NULL,
        `time`   INT(1)  NOT NULL,
        `point`  INT(1)  NOT NULL,
        `status` INT(1)  NOT NULL,
        PRIMARY KEY (`uid`))
    ENGINE=InnoDB
    DEFAULT CHARSET=utf8
    COLLATE=utf8_unicode_ci;
### TABLE VIDEO
CREATE TABLE IF NOT EXISTS `video` (
        `vid`    INT(10) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT,
        `uid`    INT(10) UNSIGNED ZEROFILL NOT NULL,
        `btih`   BINARY(20) NOT NULL,
        `time`   INT(1)  NOT NULL,
        `view`   INT(1)  NOT NULL,
        `reply`  INT(1)  NOT NULL,
	`comment`	LONGTEXT  NOT NULL,
	`c_index`	LONGTEXT  NOT NULL,
	`linkage`	LONGTEXT  NOT NULL,
	`l_index`	LONGTEXT  NOT NULL,
	`dislike`	LONGTEXT  NOT NULL,
	`d_index`	LONGTEXT  NOT NULL,
        PRIMARY KEY (`vid`),
        UNIQUE  KEY `btih` (`btih`))
    ENGINE=InnoDB
    DEFAULT CHARSET=utf8
    COLLATE=utf8_unicode_ci;
### Reset Auto Increment
ALTER TABLE `video` AUTO_INCREMENT=1;
    //MySQL要求SQL语句以分号结尾
##	KVDB储存格式
### 视频弹幕
####	STRING kvdb.comment: 下标[0,reply-1],
	//注意最后的多余逗号会被浏览器引擎忽略,但php会报错,不储存"[]"
	"{"c":"sec.000,color=FFFFFF,type(1),size(25),uid,timestamp","m":"text","cid":1},
	{"c":"sec.000,color=FFFFFF,type(1),size(25),uid,timestamp","m":"text","cid":2},
	...
	{"c":"sec.000,color=FFFFFF,type(1),size(25),uid,timestamp","m":"text","cid":cid},"
####	JSON kvdb.comment.index: 下标[0,reply-1],time为发表时间，size为弹幕池全长
	[
	[uid,time,size],
	[uid,time,size],
	...
	[uid,time,size]
	]
### 视频链接,注意提防自我引用和引用不存在,注意btih必须小写(json的key要以小写开头)
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
### 视频举报,不要为cid做JSON_FORCE_OBJECT,要作为字符串读写
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
	
##  服务器接口
###	初始化
*   ./init.php      //√私有，调试用，POST方法，参数key
*   ./delDummy.php  //√私有，调试用，POST方法，参数key
*   ./newCookie.php //√获取新Cookie，GET 方法，参数vcode
*   ./getVcode.php  //√获取验证图片，GET 方法，参数rand(伪)

###	建立
*   ./newVideo.php  //  创建视频信息，POST方法，参数btih
*   ./newLink.php   //  创建链接信息，POST方法，参数btih1,btih2,time
*   ./newComment.php//  创建弹幕信息，POST方法，参数btih,danmaku
*   ./newDislike.php//  创建投诉信息，POST方法，参数btih,cid

###	获取
*   ./getVideo.php  //  获取视频信息，GET 方法，参数btih,action
*   ./getLink.pho   //  获取链接数据，GET 方法，参数btih
*   ./getComment.php//  获取弹幕数据，GET 方法，参数btih,action,start,end,count
*   ./getDislike.php//  获取投诉数据，GET 方法，参数btih

###	关于getComment
*	按序号获取: bith=0000000000000000000000000000000000000000&action=cid&start=cid_start&end=cid_end
*	按时间获取: bith=0000000000000000000000000000000000000000&action=timed&start=time_start&end=time_end
*	某号及以后: bith=0000000000000000000000000000000000000000&action=recent&start=cid_start
*	最后多少条: bith=0000000000000000000000000000000000000000&action=timed&count=count
*	获取全弹幕: bith=0000000000000000000000000000000000000000&action=all
		
##  客户端网页
###	主页
*   ./index.htm     // 主页，操作Cookie，Video，Link
*   ./css/style.css // 主页样式
*   ./img/logo.png  // 站点Logo
*   ./img/sae.png   // SAE Logo

###	播放页
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

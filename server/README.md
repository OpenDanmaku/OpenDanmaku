ReadMe
========
详细的API在[API.md](API.md)中，如有冲突，以[API.md](API.md)为准
========
#	服务器接口
--------
##	饼干
--------
*	newCookie.php
	>获取新Cookie，GET 方法，参数vcode

*	getVcode.php
	>获取验证图片，GET 方法，建议加上伪参数rand

--------
##	创建
--------
*	newVideo.php
	>创建视频信息，POST方法，参数btih

*	newLink.php
	>创建链接信息，POST方法，参数linkage

*	newComment.php
	>创建弹幕信息，POST方法，参数btih,comment

*	newDislike.php
	>创建投诉信息，POST方法，参数btih,cid

--------
##	获取
--------
*	getVideo.php
	>获取视频信息，GET 方法，参数btih,action

*	getLink.pho
	>获取链接数据，GET 方法，参数btih

*	getComment.php
	>获取弹幕数据，GET 方法，参数btih,action,start,end,count

*	getDislike.php
	>获取投诉数据，GET 方法，参数btih

--------
##	关于getVideo
--------
*	按时间倒序取最近七天数据:
```
	getVideo.php?bith=0000000000000000000000000000000000000000&action=time
```
*	按播放排行取最近七天数据:
```
	getVideo.php?bith=0000000000000000000000000000000000000000&action=view
```
*	按弹幕排行取最近七天数据:
```
	getVideo.php?bith=0000000000000000000000000000000000000000&action=reply
```
*	取某视频:
```
	getVideo.php?bith=0000000000000000000000000000000000000000&action=find
```
*	暂不支持jsonP,视需求决定添加

--------
##	关于getComment
--------
*	按序号获取:
```
	getComment.php?bith=0000000000000000000000000000000000000000&action=cid&start=cid_start&end=cid_end
```
*	按时间获取:
```
	getComment.php?bith=0000000000000000000000000000000000000000&action=time&start=time_start&end=time_end
```
*	某号及以后:
```
	getComment.php?bith=0000000000000000000000000000000000000000&action=recent&start=cid_start
```
*	最后多少条:
```
	getComment.php?bith=0000000000000000000000000000000000000000&action=time&count=count
```
*	获取全弹幕:
```
	getComment.php?bith=0000000000000000000000000000000000000000&action=all
```
*	暂不支持jsonP,视需求决定添加

--------
#	数据库存储格式
--------
##	注意事项
--------
*	初始化插入第0项之后可能要求重置AUTO_INCREMENT为1
*	初始化user时key应为随机正整数(暂不考虑种子问题)
*	\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0是不是20字节的二进制数
*	mysqli会不会被另一个php进程调用使得last_affected_row破坏

--------
## TABLE `user`
--------
###	列名
--------

|  uid |  key | time | point | status |
| ---: | ---: | ---: | ----: | -----: |
| uint | uint | uint |  uint |  uint |

--------
###	表属性
--------

| ENGINE | CHARSET |         COLLATE | NOT NULL | ZEROFILL | PRIMARY | AUTO_INCREMENT |
| -----: | ------: | --------------: | -------: | -------: | ------: | -------------: |
| InnoDB |    utf8 | utf8_unicode_ci |      all | all uint |     uid |            uid |

--------
###	初始化
--------
```
INSERT INTO `user` (`uid`, `key`, `time`, `point`, `status`) VALUES
(0000000000, FLOOR(2147483647*RAND()), 0, 0, 0);
ALTER TABLE `user` AUTO_INCREMENT=1;
```

--------
## TABLE `video`
--------
###	列名
--------

|  vid |  uid |       btih | time | view | reply | --> |
| ---: | ---: | ---------: | ---: | ---: | ----: | :-: |
| uint | uint | binary(20) | sint | sint |  sint | --> |

| <-- |  comment |  c_index |  linkage |  l_index |  dislike |  d_index |
| :-: | -------: | -------: | -------: | -------: | -------: | -------: | 
| <-- | LONGTEXT | LONGTEXT | LONGTEXT | LONGTEXT | LONGTEXT | LONGTEXT | 

--------
###	表属性
--------

| ENGINE | CHARSET |         COLLATE | NOT NULL | ZEROFILL | PRIMARY | AUTO_INCREMENT | UNIQUE | 
| -----: | ------: | --------------: | -------: | -------: | ------: | -------------: | -----: | 
| InnoDB |    utf8 | utf8_unicode_ci |      all | all uint |     uid |            uid |   btih | 

--------
###	初始化
--------
```
INSERT INTO `video` (`vid`, `uid`, `btih`, `time`, `view`, `reply`, `comment`, `c_index`, `linkage`, `l_index`, `dislike`, `d_index`) VALUES
(0000000000, 0000000000, '\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0', 0, 0, 1, '{"c":"0,FFFFFF,1,25,0,0","m":"Test","cid":1},', '[[0,0,45]]', '{}', '{}', '{"0":[0]}', '{"0":1}');
ALTER TABLE `video` AUTO_INCREMENT=1;
```

--------
###	初始化数据与解析
--------

| 列名    | 初始数据                                        |
| :------ | :---------------------------------------------- |
| vid     | 0                                               |
| uid     | 0                                               |
| btih    | x'0000000000000000000000000000000000000000'     |
| time    | 0                                               |
| view    | 0                                               |
| reply   | 1                                               |
| comment | '{"c":"0,FFFFFF,1,25,0,0","m":"Test","cid":1},' |
| c_index | '[[0,0,45]]'                                    |
| linkage | '{}'                                            |
| l_index | '{}'                                            |
| dislike | '{"0":[0]}'                                     |
| d_index | '{"0":1}'                                       |

--------
#	字段储存格式
--------
##	comment
--------
不标准的JSON格式,下标[0,reply-1],不标准的地方在于不储存"[]",并在最后多储存一个逗号,不应有格式化的缩进和回车
这个逗号浏览器引擎会忽略,但php会报错,所以代码中注意定位
```
{"c":"sec.000,color=FFFFFF,type(1),size(25),uid,timestamp","m":"text","cid":1},{"c":"sec.000,color=FFFFFF,type(1),size(25),uid,timestamp","m":"text","cid":2},...,{"c":"sec.000,color=FFFFFF,type(1),size(25),uid,timestamp","m":"text","cid":cid},
```

--------
##	c_index
--------
JSON格式,下标[0,reply-1],`comment`字段的索引,time为发表时间，size为弹幕池全长
```
[
    [
        uid,
        time,
        size
    ],
    [
        uid,
        time,
        size
    ],
    ...,
    [
        uid,
        time,
        size
    ]
]
```
--------
##	linkage
--------
JSON格式,视频交叉链接,注意提防自我引用和引用不存在,注意btih必须小写(json的key要以小写开头)
key被分号分隔开,再被逗号分隔开,uid为数组,除btih外所有元素都是无符号整数
```
{
    "btih1,btih2,offsets;start_1,start_2,duration;start_1,start_2,duration;": [
        uid,
        uid,
        ...
    ],
    "btih1,btih2,offsets;start_1,start_2,duration;start_1,start_2,duration;": [
        uid,
        uid,
        ...
    ],
    "btih1,btih2,offsets;start_1,start_2,duration;start_1,start_2,duration;": [
        uid,
        uid,
        ...
    ]
}
```
key的第一段是引用对象:btih1属于本视频,btih2为引用视频
第二段是相同片段:start_1为片段在本视频的起始点(ms),start_2为片段在本视频的起始点(ms),duration为片段长度(ms)
第三段是下一个共同片段,与第二段表示方法一致,下同

--------
##	l_index
--------
JSON格式,与`linkage`相似,但对用户只计数
```
{
    "btih1,btih2,offsets;start_1,start_2,duration;start_1,start_2,duration;": count,
    "btih1,btih2,offsets;start_1,start_2,duration;start_1,start_2,duration;": count,
    ...,
    "btih1,btih2,offsets;start_1,start_2,duration;start_1,start_2,duration;": count
}
```

--------
##	dislike
--------
JSON格式,不要为cid做JSON_FORCE_OBJECT,要作为字符串读写
```
{
    "cid": [
        uid,
        uid,
        ...
    ],
    "cid": [
        uid,
        uid,
        ...
    ],
    ...,
    "cid": [
        uid,
        uid,
        ...
    ]
}
```

--------
##	d_index
--------
JSON格式,不要为cid做JSON_FORCE_OBJECT,要作为字符串读写
```
{
    "cid": count,
    "cid": count,
    ...,
    "cid": count
}
```

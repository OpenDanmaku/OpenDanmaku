### EXAMPLE
|Name                           |Magnet Link                                                 |
| :---------------------------- | :--------------------------------------------------------- |
|【RH字幕組x傲嬌零字幕組】憑物語|magnet:?xt=urn:btih:99df93b28299fa02335e0194595bc567fbee9386|
|【極影字幕社】★物語系列 憑物語|magnet:?xt=urn:btih:54e3d5732b2dfd8a69354d5d5fb06fc0ae3f5108|
|【极影字幕社】★物语系列 凭物语|magnet:?xt=urn:btih:588d1fb8530b6f2452dfd8bd658c184f65e95d2a|
|【Leopard-Raws】憑物語 (凭物语)|magnet:?xt=urn:btih:c8000e819cce54f3ea284c9c6604c0e5d71ad2ba|
### TYPE  
|Name  |Information                                                                               |
| :--- | :--------------------------------------------------------------------------------------- |
|bith  |160 bit Binary Value, submitted as 40 character HEX string.                               |
|time  |UNIX timestamp, '2015-01-01 00:00:00 UTC+8' is submitted as 32 bit integer '1420041600'.  |
|json  |Standard JSON string. Clearing whitespace character is encouraged.                        |
|option|Keys and values should be submitted in lower case. Whitespace character is not allowed.   |
### Loading Danmaku  
#### getVideo.php?action=find  
##### [Parameters]
|Key Name|Request |Value Type|Default Value|
| :----- | -----: | -------: | :---------- |
|btih    |  Forced|      btih|x Return Err |
|action  |Optional|    option|find         |
##### [Data Returned]
E.g. [RH字幕組x傲嬌零字幕組】憑物語](http://bt.ktxp.com/html/2015/0105/391659.html)
```
{"btih":"99df93b28299fa02335e0194595bc567fbee9386","time":1420410963,"view":100,"reply":30}
```
#### getLink.php
##### [Parameters]

|Key Name|Request |Value Type|Default Value|
|:-------|-------:|---------:|:------------|
|btih    |  Forced|      btih|x Return Err |
##### [Data Returned]
>	E.g. 【極影字幕社】 ★ 物語系列 憑物語 01-04 BIG5 MP4_720P
>	URL: http://bt.ktxp.com/html/2015/0103/391537.html
>	E.g. 【极影字幕社】 ★ 物语系列 凭物语 01-04 GB MP4_720P
>	URL: http://bt.ktxp.com/html/2015/0103/391534.html
>	[Leopard-Raws] 憑物語 (凭物语) Tsukimonogatari 01 ~ 04 END (BS11 1280x720 x264 AAC).mp4
>	URL: http://bt.ktxp.com/html/2015/0102/391524.html

http://bt.ktxp.com/down/1420244427/54e3d5732b2dfd8a69354d5d5fb06fc0ae3f5108
http://bt.ktxp.com/down/1420244427/54e3d5732b2dfd8a69354d5d5fb06fc0ae3f5108
http://bt.ktxp.com/down/1420237201/588d1fb8530b6f2452dfd8bd658c184f65e95d2a
http://bt.ktxp.com/down/1420203945/c8000e819cce54f3ea284c9c6604c0e5d71ad2ba



[Data Returned]
{}
*	getComment.php?btih=BTIH&action=all
[Optional Parameters]
[Data Returned]

*	getDislike.php?btih=BTIH
[Optional Parameters]
[Data Returned]

# When Playing
*	newComment.php
[Optional Parameters]
[Data Returned]

*	newDislike.php
[Optional Parameters]
[Data Returned]

*	getComment.php?btih=BTIH&action=cid
[Optional Parameters]
	start = default 0
	end   = default `reply` - 1
[Data Returned]

*	getComment.php?btih=BTIH&action=time
[Optional Parameters]
	start = timestamp default 0
	end   = timestamp default now()
[Data Returned]

*	getComment.php?btih=BTIH&action=recent
[Optional Parameters]
	start = default 0
[Data Returned]

*	getComment.php?btih=BTIH&action=last
[Optional Parameters]
	count = default `reply`
[Data Returned]

获取饼干
getVcode.php
newCookie.php
创建视频与引用
newVideo.php
newLink.php
### 获取视频列表

#### getVideo.php?btih=BTIH&action=time
getVideo.php?btih=BTIH&action=view
getVideo.php?btih=BTIH&action=reply

http://www.btspread.com/magnet/detail/hash/
[
	{"btih":"99df93b28299fa02335e0194595bc567fbee9386","time":1420410963,"view":100,"reply":30}
	{"btih":"54e3d5732b2dfd8a69354d5d5fb06fc0ae3f5108","time":1420244427,"view":100,"reply":30}
	{"btih":"588d1fb8530b6f2452dfd8bd658c184f65e95d2a","time":1420237201,"view":100,"reply":30}
	{"btih":"c8000e819cce54f3ea284c9c6604c0e5d71ad2ba","time":1420203945,"view":100,"reply":30}
]

# TYPE  
**bith**  
	160 bit Binary Value, submitted as 40 character HEX string.
**time**  
	UNIX timestamp, '2015-01-01 00:00:00 UTC+8' is submitted as 32 bit integer '1420041600'.
**json**  
	Standard JSON string. Clearing whitespace character is encouraged.
**option**  
	Keys and values should be submitted in lower case. Whitespace character is not allowed
# Usage  
## Loading Danmaku  
### getVideo.php?action=find  
*	[Parameters]  

|Key Name|Request |Value Type|Default Value|
|:-------|-------:|---------:|:------------|
|btih    |  Forced|      btih|x Return Err |
|action  |Optional|    option|find         |
*	[Data Returned]  
```
{"btih":"54e3d5732b2dfd8a69354d5d5fb06fc0ae3f5108","time":1420440845,"view":100,"reply":30}
```
*	getLink.php?btih=BTIH
[Optional Parameters]
[Data Returned]

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
获取视频列表
getVideo.php?btih=BTIH&action=time
getVideo.php?btih=BTIH&action=view
getVideo.php?btih=BTIH&action=reply

http://www.btspread.com/magnet/detail/hash/

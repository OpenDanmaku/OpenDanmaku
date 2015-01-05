bith：160bit二进制数,表示成40位16进制数
装载视频
getVideo.php?btih=BTIH&action=find
返回
{`btih`:###, `time`:1420440845, `view`, `reply` 
getLink.php?btih=BTIH
getComment.php?btih=BTIH&action=all
getDislike.php?btih=BTIH
播放视频中
*	newComment.php
newDislike.php

*	getComment.php?btih=BTIH&action=cid
[Optional Parameters]
		start = default 0
		end   = default `reply` - 1

*	getComment.php?btih=BTIH&action=time
[Optional Parameters]
		start = timestamp default 0
		end   = timestamp default now()

*	getComment.php?btih=BTIH&action=recent
[Optional Parameters]
		start = default 0

*	getComment.php?btih=BTIH&action=last
[Optional Parameters]
		count = default `reply`

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

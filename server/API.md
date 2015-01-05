# README  
[TOC]
## INFORMATION  
### TYPE  
|Name   |Information                                                                               |
| :---- | :--------------------------------------------------------------------------------------- |
|bith   |160 bit Binary Value, submitted as 40 character HEX string.                               |
|time   |UNIX timestamp, '2015-01-01 00:00:00 UTC+8' is submitted as 32 bit integer '1420041600'.  |
|json   |Standard JSON string. Clearing whitespace character is encouraged.                        |
|option |Keys and values should be submitted in lower case. Whitespace character is not allowed.   |
|linkage|btihA,btih2,offset_count[;offset1A,offset1B,duration1[;offset2A,offset2B,duration2[;...]]]|
### ERROR
When key `err_num` exists and its value is not 0, some error happens.  
**You Should Check Error Info from Source Files.**  
`err_num` is not been asigned specifically yet, though 0 means, of course, no error.  
**When Data are Transmitted, Error Info is Replaced by Data!**  
#### WHEN ERR
```json
{
"err_type":"btih_unavailable",
"err_num":-1,
"err_msg":"Error: Video Not Yet Exists, Do You Want to Create It?"
}
```
#### WHEN NO ERR AND NO DATA
```json
{
"err_type":"newComment",
"err_num":0,
"err_msg":"Comment Created Successfully!"
}
```
### EXAMPLE
|Name                           |Magnet Link                                                 |
| :---------------------------- | :--------------------------------------------------------- |
|【RH字幕組x傲嬌零字幕組】憑物語|magnet:?xt=urn:btih:99df93b28299fa02335e0194595bc567fbee9386|
|【極影字幕社】★物語系列 憑物語|magnet:?xt=urn:btih:54e3d5732b2dfd8a69354d5d5fb06fc0ae3f5108|
|【极影字幕社】★物语系列 凭物语|magnet:?xt=urn:btih:588d1fb8530b6f2452dfd8bd658c184f65e95d2a|
|【Leopard-Raws】憑物語 (凭物语)|magnet:?xt=urn:btih:c8000e819cce54f3ea284c9c6604c0e5d71ad2ba|
## LOADING DANMAKU  
### getVideo.php?action=find  
#### [Parameters]
|Key Name|Request |Value Type|Default Value|
| :----- | -----: | -------: | :---------- |
|btih    |  Forced|      btih|x RETURN ERR |
|action  |Optional|    option|find         |
#### [Data Returned]
E.g. [【RH字幕組x傲嬌零字幕組】憑物語](http://bt.ktxp.com/html/2015/0105/391659.html)
```json
{
"btih":"99df93b28299fa02335e0194595bc567fbee9386",
"time":1420410963,
"view":100,
"reply":30
}
```
### getLink.php  
#### [Parameters]  
|Key Name|Request |Value Type|Default Value|
|:-------|-------:|---------:|:------------|
|btih    |  Forced|      btih|x Return Err |
#### [Data Returned]  
```json
{
"99df93b28299fa02335e0194595bc567fbee9386,54e3d5732b2dfd8a69354d5d5fb06fc0ae3f5108,0":25,
"99df93b28299fa02335e0194595bc567fbee9386,588d1fb8530b6f2452dfd8bd658c184f65e95d2a,0":16,
"99df93b28299fa02335e0194595bc567fbee9386,c8000e819cce54f3ea284c9c6604c0e5d71ad2ba,2;0,1000,60000;60000,76000,7200000":45
}
```
### getComment.php?btih=BTIH&action=all  
#### [Parameters]  
|Key Name|Request |Value Type|Default Value|
| :----- | -----: | -------: | :---------- |
|btih    |  Forced|      btih|x RETURN ERR |
|action  |Optional|    option|all          |
#### [Data Returned]  
```json
[
{"c":"269.241,16777215,1,25,2782,1420100231","m":"这得几何原理多好才能看懂？","cid":0},
{"c":"151.506,16777215,1,25,2782,1420100114","m":"吸血鬼？","cid":1},
{"c":"120.576,16777215,1,25,2782,1420100083","m":"不不不，是返回好烦。。","cid":2},
{"c":"102.971,16777215,1,25,2782,1420100055","m":"男主是鬼吗？镜 子里都没影子。","cid":3},
{"c":"627.412,16711680,1,25,5602,1420099834","m":"这需要多少钱？","cid":4},
{"c":"102.531,16777215,1,25,6113,1420099663","m":"没人了吗？","cid":5},
{"c":"163.913,16777215,1,25,5740,1420098752","m":"量子物理的既视感~@虐猫狂人薛定谔","cid":6},
{"c":"101.719,16777215,1,25,6854,1420098741","m":"新房风，试试","cid":7}
]
```
## WHEN PLAYING  
### newComment.php  
#### [Parameters]  
|Key Name|Request |Value Type|Default Value|
| :----- | -----: | -------: | :---------- |
|btih    |  Forced|      btih|x RETURN ERR |
|comment |  Forced|      json|x RETURN ERR |
#### [Data Returned]
```json
{
"err_type":"newComment",
"err_num":0,
"err_msg":"Comment Created Successfully!"
}
```
#### [Side Effect]
|Score|Delay|Punishment|
| --: | --: | -------: |
|    1|    3|        No|
### newDislike.php
#### [Parameters]  
|Key Name|Request |Value Type|Default Value|
| :----- | -----: | -------: | :---------- |
|btih    |  Forced|      btih|x RETURN ERR |
|cid     |  Forced|   integer|x RETURN ERR |
[Data Returned]
#### [Data Returned]
```json
{
"err_type":"newDislike",
"err_num":0,
"err_msg":"Dislike Created Successfully!"
}
```
#### [Side Effect]
|Score|Delay|Punishment                  |
| --: | --: | -------------------------: |
|  -20|   30|4 more hours when score is 0|
### getComment.php?btih=BTIH&action=cid
[Optional Parameters]
	start = default 0
	end   = default `reply` - 1
[Data Returned]

### getComment.php?btih=BTIH&action=time
[Optional Parameters]
	start = timestamp default 0
	end   = timestamp default now()
[Data Returned]

### getComment.php?btih=BTIH&action=recent
[Optional Parameters]
	start = default 0
[Data Returned]

### getComment.php?btih=BTIH&action=last
[Optional Parameters]
	count = default `reply`
[Data Returned]

## GETTING COOKIES
### getVcode.php
#### [Parameters]  
|Key Name|Request |Value Type|Default Value|
| :----- | -----: | -------: | :---------- |
|random  |Pseudo  |  anything|      nothing|
#### [Data Returned]
**Content-type: image/png**
###newCookie.php
## CREATING NEW VIDEO AND LINK
#### [Data Returned]
```json
{
"err_type":"newCookie",
"err_num":0,
"err_msg":"New Cookie Begotten!"
}
```
#### [Side Effect]
**New Cookies**
|Key Name|Value Type| Overdue  |
| :----- | -------: | :------- |
|uid     |   integer|2147483647|
|key     |   integer|2147483647|
###newVideo.php
#### [Parameters]  
|Key Name|Request |Value Type|Default Value|
| :----- | -----: | -------: | :---------- |
|btih    |  Forced|      btih|x RETURN ERR |
#### [Data Returned]
```json
{
"err_type":"newVideo",
"err_num":0,
"err_msg":"Video Created Successfully!"
}
```
#### [Side Effect]
|Score|Delay|Punishment|
| --: | --: | -------: |
|   10|   60|        No|
###newLink.php
#### [Parameters]  
|Key Name|Request |Value Type|Default Value|
| :----- | -----: | -------: | :---------- |
|linkage |  Forced|   linkage|x RETURN ERR |
#### [Data Returned]
```json
{
"err_type":"newLink",
"err_num":0,
"err_msg":"Links Created Successfully!"
}
```
#### [Side Effect]
|Score|Delay|Punishment|
| --: | --: | -------: |
|   10|   60|        No|
## GETTING VIDEO LIST  
You can check its info from sites like:  
	http://www.btspread.com/magnet/detail/hash/99df93b28299fa02335e0194595bc567fbee9386  
	http://bt.ktxp.com/search.php?keyword=99df93b28299fa02335e0194595bc567fbee9386  
### getVideo.php?btih=BTIH&action=time  
#### [Parameters]  
|Key Name| Request |Value Type|Default Value|
| :----- | :-----: | -------: | :---------- |
|btih    |   Forced|      btih|x RETURN ERR |
|action  |Specified|    option|time         |
#### [Data Returned]  
Last 7 Days' Video Info Ordered Descendingly By Time  
```json
[
{"btih":"99df93b28299fa02335e0194595bc567fbee9386","time":1420410963,"view":100,"reply":30},
{"btih":"54e3d5732b2dfd8a69354d5d5fb06fc0ae3f5108","time":1420244427,"view":200,"reply":70},
{"btih":"588d1fb8530b6f2452dfd8bd658c184f65e95d2a","time":1420237201,"view":300,"reply":50},
{"btih":"c8000e819cce54f3ea284c9c6604c0e5d71ad2ba","time":1420203945,"view":400,"reply":90}
]
```
### getVideo.php?btih=BTIH&action=view  
#### [Parameters]  
|Key Name| Request |Value Type|Default Value|
| :----- | :-----: | -------: | :---------- |
|btih    |   Forced|      btih|x RETURN ERR |
|action  |Specified|    option|view         |
#### [Data Returned]  
Last 7 Days' Video Info Ordered Descendingly By View  
```json
[
{"btih":"c8000e819cce54f3ea284c9c6604c0e5d71ad2ba","time":1420203945,"view":400,"reply":90},
{"btih":"588d1fb8530b6f2452dfd8bd658c184f65e95d2a","time":1420237201,"view":300,"reply":50},
{"btih":"54e3d5732b2dfd8a69354d5d5fb06fc0ae3f5108","time":1420244427,"view":200,"reply":70},
{"btih":"99df93b28299fa02335e0194595bc567fbee9386","time":1420410963,"view":100,"reply":30}
]
```
### getVideo.php?btih=BTIH&action=reply  
#### [Parameters]  
|Key Name| Request |Value Type|Default Value|
| :----- | :-----: | -------: | :---------- |
|btih    |   Forced|      btih|x RETURN ERR |
|action  |Specified|    option|view         |
#### [Data Returned]  
Last 7 Days' Video Info Ordered Descendingly By Reply  
```json
[
{"btih":"c8000e819cce54f3ea284c9c6604c0e5d71ad2ba","time":1420203945,"view":400,"reply":90},
{"btih":"54e3d5732b2dfd8a69354d5d5fb06fc0ae3f5108","time":1420244427,"view":200,"reply":70},
{"btih":"588d1fb8530b6f2452dfd8bd658c184f65e95d2a","time":1420237201,"view":300,"reply":50},
{"btih":"99df93b28299fa02335e0194595bc567fbee9386","time":1420410963,"view":100,"reply":30}
]
```


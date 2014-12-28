#	Class SaeKV

##	Description

###	SAE KV 服务 API

	<?php
	$kv = new SaeKV();
	
	// 初始化SaeKV对象
	$ret = $kv->init();
	var_dump($ret);
	
	// 增加key-value
	$ret = $kv->add('abc', 'aaaaaa');
	var_dump($ret);
	
	// 更新key-value
	$ret = $kv->set('abc', 'bbbbbb');
	var_dump($ret);
	
	// 替换key-value
	$ret = $kv->replace('abc', 'cccccc');
	var_dump($ret);
	
	// 获得key-value
	$ret = $kv->get('abc');
	var_dump($ret);
	
	// 删除key-value
	$ret = $kv->delete('abc');
	var_dump($ret);
	
	// 一次获取多个key-values
	$keys = array();
	array_push($keys, 'abc1');
	array_push($keys, 'abc2');
	array_push($keys, 'abc3');
	$ret = $kv->mget($keys);
	var_dump($ret);
	
	// 前缀范围查找key-values
	$ret = $kv->pkrget('abc', 3);
	var_dump($ret);
	
	// 循环获取所有key-values
	$ret = $kv->pkrget('', 100);
	while (true) {
	var_dump($ret);
	end($ret);
	$start_key = key($ret);
	$i = count($ret);
	if ($i < 100) break;
	$ret = $kv->pkrget('', 100, $start_key);
	}
	
	// 获取选项信息
	$opts = $kv->get_options();
	print_r($opts);
	
	// 设置选项信息 (关闭默认urlencode key选项)
	$opts = array('encodekey' => 0);
	$ret = $kv->set_options($opts);
	var_dump($ret);

###	错误代码及错误提示消息：

	0 "Success"
	10 "AccessKey Error"
	20 "Failed to connect to KV Router Server"
	21 "Get Info Error From KV Router Server"
	22 "Invalid Info From KV Router Server"
	30 "KV Router Server Internal Error"
	31 "KVDB Server is uninited"
	32 "KVDB Server is not ready"
	33 "App is banned"
	34 "KVDB Server is closed"
	35 "Unknown KV status"
	40 "Invalid Parameters"
	41 "Interaction Error (%d) With KV DB Server"
	42 "ResultSet Generation Error"
	43 "Out Of Memory"
	44 "SaeKV constructor was not called"
	45 "Key does not exist"
	author: Chen Lei <simpcl2008@gmail.com>
	version: $Id$
	Located in /saekv.class.php (line 103)

##	Class Constant Summary

	EMPTY_PREFIXKEY = ''
	MAX_KEY_LENGTH = 200
	MAX_MGET_SIZE = 32
	MAX_PKRGET_SIZE = 100
	MAX_VALUE_LENGTH = 4194304

##	Method Summary

	SaeKV __construct ()
	bool add (string $key, string $value)
	bool delete (string $key)
	string errmsg ()
	int errno ()
	string|bool get (string $key)
	array get_info ()
	array get_options ()
	bool init ()
	array|bool mget (array $ary)
	array|bool pkrget (string $prefix_key, int $count, string $start_key)
	bool replace (string $key, string $value)
	bool set (string $key, string $value)
	bool set_options (array $options)
	
##	Methods

###	Constructor __construct (line 134)

	构造函数
	SaeKV __construct ()

###	add (line 174)

	增加key-value对，如果key存在则返回失败
		return: 成功返回true，失败返回false 时间复杂度 O(log N)
	bool add (string $key, string $value)
		string $key: 长度小于MAX_KEY_LENGTH字节，当不设置encodekey选项时，key中不允许出现非可见字符
		string $value: 长度小于MAX_VALUE_LENGTH

###	delete (line 195)

	删除key-value
		return: 成功返回true，失败返回false 时间复杂度 O(log N)
	bool delete (string $key)
		string $key: 长度小于MAX_KEY_LENGTH字节

###	errmsg (line 233)

	获得错误提示消息
		return: 返回错误提示消息字符串
	string errmsg ()

###	errno (line 225)

	获得错误代码
		return: 返回错误代码
	int errno ()

###	get (line 152)

	获得key对应的value
		return: 成功返回value值，失败返回false 时间复杂度 O(log N)
	string|bool get (string $key)
		string $key: 长度小于MAX_KEY_LENGTH字节

###	get_info (line 247)

	获得kv信息
		return: 返回kv信息数组 array(2) { ["total_size"]=> int(49) ["total_count"]=> int(1) }
	array get_info ()
	
###	get_options (line 259)

	获取选项值
			return: 成功返回选项数组，失败返回false array(1) { "encodekey" => 1 // 默认为1 // 1: 使用urlencode编码key；0：不使用urlencode编码key }
	array get_options ()
	
###	init (line 142)

	初始化Sae KV 服务
	bool init ()
	
###	mget (line 205)

	批量获得key-values
		return: 成功返回key-value数组，失败返回false 时间复杂度 O(m * log N), m为获取key-value对的个数
	array|bool mget (array $ary)
		array $ary: 一个包含多个key的数组，数组长度小于等于MAX_MGET_SIZE

###	pkrget (line 217)

	前缀范围查找key-values
		return: 成功返回key-value数组，失败返回false 时间复杂度 O(m + log N), m为获取key-value对的个数
	array|bool pkrget (string $prefix_key, int $count, string $start_key)
		string $prefix_key: 前缀，长度小于MAX_KEY_LENGTH字节
		int $count: 前缀查找最大返回的key-values个数，小于等于MAX_PKRGET_SIZE
		string $start_key: 在执行前缀查找时，返回大于该$start_key的key-values；默认值为空字符串（即忽略该参数）

###	replace (line 185)

	替换key对应的value，如果key不存在则返回失败
		return: 成功返回true，失败返回false 时间复杂度 O(log N)
	bool replace (string $key, string $value)
		string $key: 长度小于MAX_KEY_LENGTH字节，当不设置encodekey选项时，key中不允许出现非可见字符
		string $value: 长度小于MAX_VALUE_LENGTH

###	set (line 163)

	更新key对应的value
		return: 成功返回true，失败返回false 时间复杂度 O(log N)
	bool set (string $key, string $value)
		string $key: 长度小于MAX_KEY_LENGTH字节，当不设置encodekey选项时，key中不允许出现非可见字符
		string $value: 长度小于MAX_VALUE_LENGTH

###	set_options (line 271)

	设置选项值
		return: 成功返回true，失败返回false
	bool set_options (array $options)
		array $options: array (1) { "encodekey" => 1 // 默认为1 // 1: 使用urlencode编码key；0：不使用urlencode编码key }

##	Class Constants

###	EMPTY_PREFIXKEY = '' (line 108)

	空KEY前缀

###	MAX_KEY_LENGTH = 200 (line 123)

	KEY的最大长度

###	MAX_MGET_SIZE = 32 (line 113)

	mget获取的最大KEY个数

###	MAX_PKRGET_SIZE = 100 (line 118)

	pkrget获取的最大KEY个数

###	MAX_VALUE_LENGTH = 4194304 (line 128)

	VALUE的最大长度 (4 * 1024 * 1024)

#	Documentation generated on Wed, 03 Sep 2014 10:14:52 +0800 by phpDocumentor 1.4.3

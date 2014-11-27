#	Memcache

##	服务概述

	Memcache是SAE为开发者提供的分布式内存缓存服务，用来以共享的方式缓存用户的小数据。
	
	Memcache主要的使用场景有以下两个：
	
	*	需要共享某些 key-value 形式的小数据时。
		（因为SAE的Web服务是分布式环境，所以使用全局变量方式等方式是不行的）。
	*	缓存MySQL等后端存储的数据。快速进行数据响应，减轻后端存储的压力。
	
	用户需要先在在线管理平台创建Memcache，然后才可以通过API读写Memcache。

##	服务限制

	Memcache不适合存放大文件，目前服务配置为仅允许存放小于1M的数据。

##	API使用手册
	Memcache服务目前提供以下接口：

	*	memcache_init - 初始化MC链接
	*	memcache_get - 获取MC数据
	*	memcache_set - 存入MC数据

	除 memcache_init 外,其他接口和PHP的memcahe模块保持一致.

	需要注意的是 
	
	*	memcache_connect, 
	*	Memcache::connect, 
	*	memcache_pconnect, 
	*	Memcache::pconnect, 
	*	memcache_add_server, 
	*	Memcache::addServer, 
	*	memcache_set_server_params, 
	*	Memcache::setServerParams, 
	*	memcache_get_server_status, 
	*	Memcache::getServerStatus 
	
	等函数不建议使用。

##	使用示例

	<?php
	
	$mmc=memcache_init();
	if($mmc==false)
	    echo "mc init failed\n";
	else
	{
	    memcache_set($mmc,"key","value");
	    echo memcache_get($mmc,"key");
	}
	
	?>
#

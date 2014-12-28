#	Sina App Engine(SAE)入门教程(6)- memcache使用

	Memcache是一个高性能的分布式的内存对象缓存系统，
	包括图像、视频、文件以及数据库检索的结果等。
	简单的说就是将数据调用到内存中，然后从内存中读取，从而大大提高读取速度。
	sae同样提供memcache 缓存服务。

##	如何开启SAE memcache服务？

	http://skirt-wordpress.stor.sinaapp.com/uploads/2012/10/%E5%BC%80%E5%90%AFmemcache1.jpg
	http://skirt-wordpress.stor.sinaapp.com/uploads/2012/10/%E5%BC%80%E5%90%AFmemcache2.jpg
	


	注意：我们建议不要开始太大的memcache配额，因按照项目的需要酌情的选择，
	一般建议不需要开启超过20M。
	
	以下就通过一个小例子和一个综合的例子说明SAE memcache的使用。
	
###	实例1，sae memcache的连接，数据的插入和取出

		<?php
		$link = memcache_init();
		memcache_set($link, 'lazy', 'a lazy people', 0, 30);//set
		$re = memcache_get($link,'lazy');
		var_dump($re);
		?>

	注意：sae只需先调用memcache_init()(无参数,该函数完成了memcache的connect任务),
	然后就可以正常使用Memcache了。执行脚本输出：
		string(13) "a lazy people"

###	一个使用memcache实现api接口分钟配额的实例

	其实memcache有memcache_increment()函数和缓存过期，
	配合这两项，就可以实现现在很常见的分钟配额的实例，
	我们可以大胆的猜测，新浪微博开放平台接口就是基于这样实现的，给出代码。
	
		<?php
		define('RATEMAX','10');//配置分钟最多访问10次
		$link = memcache_init();
		$minute_now = date('i',time());//取当前的分钟数
		$key = 'rate'.$minute_now;
		if(memcache_get($link,$key) == null)
		{
		        memcache_set($link, $key, '1', 0, 60);//set
		        //注意此处的60是缓存过期时间，60秒为1分钟
		        exit(0);
		}
		$re = memcache_get($link,$key);
		var_dump($re);
		if($re>RATEMAX)
		{
		        die('Minute Rate Limit!');
		}else{
		        memcache_increment($link,$key,1);
		}
		?>
		
	在一分钟内刷新 http://lazydemo.sinaapp.com/memcache/api_rate.php 10次就能看到：
		string(2) "11" Minute Rate Limit!

#

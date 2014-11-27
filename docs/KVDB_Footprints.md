#	Sina App Engine(SAE)入门教程(3)-KVDB使用

##	简介

	因为传统关系型数据库在分布式环境下表现的扩展性不足等缺点，
	近年来NoSQL的概念渐渐成为业界关注的焦点，
	越来越多的技术人员也习惯于使用NoSQL数据库进行日常开发，
	SAE为了应对这种新需求，也进行了NoSQL方向的研发。
	
	KV DB是SAE开发的分布式key-value数据存储服务，用来支持公有云计算平台上的海量key-value存储。
	KV DB支持的存储容量很大，对每个用户支持100G的存储空间，可支持1,000,000,000条记录。
	用户可以用KV DB存放简单数据，如好友关系等。
	
##	用KVDB能做什么？

	存单键值(例如用作单篇文章的计数器)
	存数组(例如存取用户信息)
	存文件(这个也是可以的，虽然不是很推荐，下文中会给出例子)
	其他(尽你所想)
	
##	KVDB使用实例

	以上介绍了KVDB可以的用途，下面将从上面介绍的几个方面给出具体的实例介绍SAE KVDB的使用。
	
###	开启KVDB

	在使用之前，你需要到SAE的管理面板开始应用的KVDB。如下图所示，点击“服务列表”中的KVDB。
	在开启了之后，我们就可以使用SAE 提供的KVDB服务了，下面简单的写个脚本测试下KVDB服务。
	
###	存取用户键值

	顺便介绍下KVDB的第一种应用，直接用户键值的存取。测试的代码如下：
		<?php
		$kv = new SaeKV();
		// 初始化KVClient对象
		$ret = $kv->init();
		var_dump($ret);
		?>
	可以看到输出是:
		bool(true)
	说明我们已经成功的使用了KVDB服务。
	
###	计数器示例

	以下我们将使用KVDB用作我们的计数器，计数器的原理就是页面被刷新，计数器加1。代码如下:
		<?php
		$kv = new SaeKV();
		// 初始化KVClient对象
		$ret = $kv->init();
		if($kv->get('count') == null)
		{
			$kv->set('count','1');//初始化计数器的值为1,确保只在初始化时设置一个初值
		}
		$now = $kv->get('count');//取出当前的计数值
		$setvalue = $now+1;//计数器的值加1
		$kv->set('count',$setvalue);//写到计数器
		echo "This page has been visted ".$setvalue;
		?>
	访问http://lazydemo.sinaapp.com/saekv/count_kv.php 就可以看到效果了~

###	存取数组

	下面介绍使用KVDB存取数组，例如存取一个用户的信息。
		<?php
		$kv = new SaeKV();
		// 初始化KVClient对象
		$ret = $kv->init();
		$myinfo = array('name'=>'lazy','age'=>'21','email'=>'webmaster@changes.com.cn');
		$kv->set('lazy',$myinfo);//将我的信息的数组写到KVDB中
		//然后再取出来
		$myinfo_inkvdb = $kv->get('lazy');
		var_dump($myinfo_inkvdb);
		?>
	返回
		array(3) { ["name"]=> string(4) "lazy" ["age"]=> string(2) "21" ["email"]=> string(24) "webmaster@changes.com.cn" }
	可以看到，打出来的信息还是完整的数组，这个可比MySQL存取这样的关系方便多了~
	这只是一个简单的例子，当然衍生出的应用还有很多。

###	存取文件	
	以上已经介绍了KVDB的两种应用，下面介绍使用KVDB存取文件。
	说到文件，肯定都会想到二进制，KVDB的value的最大长度：4M （默认启用压缩）。
	也就是说我们要存取文件的大小不能超过4M。
	
	那么超过4M的文件是不是就不能存取了呢？答案是否定的。我的一个同事pagnee写出了一个实例。
	说明了KVDB在存取文件的时候可以打散然后在输出的时候将文件拼接起来输出。
	但是作为入门教程不再讲述，有兴趣的可以自行找相关的资料看下。
	
	下面的一个实例就是用KVDB来存取图片，具体的代码如下:
		<?php
		$kv = new SaeKV();
		// 初始化KVClient对象
		$ret = $kv->init();
		$f = new SaeFetchurl();//fetch使用将在后面的文章中介绍
		$img_data = $f->fetch( 'http://ss7.sinaimg.cn/bmiddle/488efcbbt7b5c4ae51ca6&690' );//取回二进制数据
		$kv->set('image',$img_data);
		//输入图片
		$img_data_inkvdb = $kv->get('image');
		header("content-type:image/jpg");
		echo($img_data_inkvdb);
		?>
	原图片：http://ss7.sinaimg.cn/bmiddle/488efcbbt7b5c4ae51ca6&690
	存取后输出的图片：http://lazydemo.sinaapp.com/saekv/storimage_kv.php

##	SAE kv的使用

	刚刚测试了下SAE新出的服务KV，确实蛮好用的。
	这是SAE研发的跟随大流的NOSQL，高达1T的存储容量确实不是盖得。
	
	而且有很高速的读写速度，等下想办法测试下读写的速度。
	现在给出在一个页面设置kv的值，然后在另外一个地方读出kv的值。
	
	我遇到的问题就是在另外一个页面读出的时候没有设置初始化。
	导致了读出的errno一直是30，一查是什么30 “KV Router Server Internal Error”。
	后来实验性的在index2.php的页面中加了一句初始化的命令就搞定了。

###	index.php的code
		<?php
		$kv = new SaeKVClient();
		$ret = $kv->init(); // 初始化KVClient对象
		// var_dump($ret);
		// $error_num=$kv->errno() ;
		//echo ($error_num);
		
		// 更新key-value
		$ret = $kv->set('abc', 'aaaaaa');
		$ret1= $kv->set('abcd','Just a test');
		//var_dump($ret);
		
		// 获得key-value
		$ret = $kv->get('abc');
		$ret1= $kv->get('abcd');
		echo($ret);
		echo($ret1);
		?>
###	index2.php 读出index设置的kv的值的代码
		<?php
		//test get kvaule in other file .
		$kvget = new SaeKVClient();
		$ret = $kvget->init(); // 初始化KVClient对象,test adding init
		// var_dump($ret);
		// $error_num=$kv->errno() ;
		//echo ($error_num);
		
		// 更新key-value
		// $ret = $kv->set('abc', 'aaaaaa');
		// $ret1= $kv->set('abcd','Just a test');
		//var_dump($ret);
		
		// 获得key-value
		$ret = $kvget->get('abc');
		$ret1= $kvget->get('abcd');
		$error_num=$kvget->errno();
		echo($error_num);//echo error number
		echo($ret);
		echo($ret1);
		?>
	一定要注意的就是index2.php中的初始化，没有初始化进行任何的操作都会出现问题的。

##	读写速度测试
	接下来就编一个大的程序测试下读写的速度吧：
	其实跑完还是没有发现能达到常规测试的10W条/s的速度。
	这个是kv的测试结果：
		the start time is1310528492
		the End time is1310528521
		Total cost time is29
		The speed is3448.27586207e/s
	相对来讲比MYSQL还是快点，MYSQL的测试结果在这：
		the start time is1310528478
		the End time is1310528483
		Total cost time is5
		The speed is2000e/s
	相对来讲还是快了快两倍了还是不错的。	

#

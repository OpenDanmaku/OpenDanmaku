#	Storage

##	服务概述

	Storage是SAE为开发者提供的分布式文件存储服务，用来存放用户的持久化存储的文件。
	
	开发者可以通过API读取文件、写入文件、获取文件属性、取得文件列表等操作，
	因为SAE平台是分布式环境，强烈建议开发者将所有的需要持久化的文件操作都通过Storage实现。
	
	用户需要先在Storage的管理界面中创建容器，创建完毕后，用户可以通过以下两种方式操作其中数据：
	
	*	通过 Cyberduck 或者 swift 等Storage客户端
	*	通过SAE提供的API接口。

##	Storage客户端

###	Windows及Mac客户端

	在Windows和Mac系统下面，你可以使用Cyberduck来操作Storage。
	下载地址： http://cyberduck.io
	
	打开Cyberduck，点击左上角的“新建连接”。
		../_images/cyberduck-new-connection.png
	
	在弹出的对话框中填写连接相关信息：
	
	*	类型：Swift。
	*	服务器：auth.sinas3.com
	*	端口：443（默认）
	*	用户名：应用AccessKey（在应用“汇总信息”页面中查看）
	*	密码：应用SecretKey（在应用“汇总信息”页面中查看）
	
	填写完成后点击连接。如果弹出auth.sinas3.com，api.sinas3.com相关证书问题，请选择信任。
		../_images/cyberduck-setting.png
	
	连接完成后，将会看到该应用Storage的所有Domain列表：
		../_images/cyberduck-ui.png
	
	操作：双击Domain名称，可进入Domain，列出文件和文件夹，
	此时，可进行文件的上传、下载、以及删除操作。

###	Linux客户端

	注解
	以下文档以Ubuntu系统为例。
	
	首先，安装客户端。
	
		apt-get install python-pip;
		pip install python-swiftclient;
		
	安装完成后，你可以通过 swift 这个命令来对应用的Storage进行操作。
	
	通过以下命令你可以查看该命令的帮助信息：
	
		swift -h
		
	在每次使用 swift 之前，请首先执行以下命令将以下配置信息加入到环境变量中去。
	
		export ST_AUTH='https://auth.sinas3.com/v1.0'
		export ST_USER='AccessKey'
		export ST_KEY='SecretKey'
		
	当然你也可以将这些配置写到一个文件中，每次需要运行 swift 命令前通过 . 文件名 的形式载入配置。
	
	下面列举了该命令一些常用的操作：

		####	创建一个Domain
		swift post DOMAIN_NAME -r:.r:*
		
		####	查看应用的Domain列表
		swift list
		
		####	查看某个Domain的属性信息
		swift stat DOMAIN_NAME
		
		####	列出某个Domain下的所有文件
		swift list DOMAIN_NAME
		
		####	上传文件
		swift upload DOMAIN_NAME LOCAL_FILENAME
		
		####	上传文件夹
		swift upload DOMAIN_NAME DIRECTORY_NAME
		
		####	下载文件
		swift download DOMAIN_NAME FILENAME
		
		####	下载某个Domain的所有文件
		swift download DOMAIN_NAME
		
		####	删除文件
		swift delete DOMAIN_NAME FILENAME
		
		####	删除Domain
		swift delete DOMAIN_NAME
		
		####	删除该应用Storage中的所有数据
		swift delete –all
`
##	API使用手册?
	
	Storage_API.md
	
#

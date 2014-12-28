#	Class SaeStorage

##	Description

	Located in /saestorage.class.php (line 15)
	
	SaeObject
	   |
	   --SaeStorage

##	Variable Summary

	mixed $swift_conn

##	Method Summary

	SaeStorage __construct ([string $accessKey = NULL], [string $secretKey = NULL])
	bool delete (string $domain, string $filename)
	void errmsg ()
	void errno ()
	bool fileExists (string $domain, string $filename)
	array getAttr (string $domain, string $filename, [array $attrKey = array()])
	string. getCDNUrl (string $domain, string $filename)
	void getDomainAttr ([$domain='' $domain = ''])
	int getDomainCapacity (string $domain)
	array getFilesNum (string $domain, [string $path = NULL])
	array getList (string $domain, [string $prefix = NULL], [int $limit = 10], [int $offset = 0])
	array getListByPath (string $domain, [string $path = NULL], [int $limit = 100], [int $offset = 0], [int $fold = true])
	string. getUrl (string $domain, string $filename)
	void listDomains ()
	string read (string $domain, string $filename)
	bool setDomainAttr (string $domain, [array $attr = array()])
	bool setFileAttr (string $domain, string $filename, [array $attr = array()])
	mixed upload (string $domain, string $destFileName, string $srcFileName, [array $attr = array()], [boolean $compress = false])
	mixed write (string $domain, string $destFileName, string $content, [int $size = -1], [array $attr = array()], [boolean $compress = false])

##	Variables

	mixed $swift_conn (line 26)
	*	access: protected

##	Methods

###	Constructor __construct (line 34)

	Class constructor

	SaeStorage __construct ([string $accessKey = NULL], [string $secretKey = NULL])
		string $accessKey: AccessKey of Appname
		string $secretKey: SecretKey of Appname

###	delete (line 647)
	
	删除文件
		access: public

	bool delete (string $domain, string $filename)
		string $domain
		string $filename

###	errmsg (line 85)

		exception: none
		access: public

	void errmsg ()

###	errno (line 102)

		exception: none
		access: public

	void errno ()

###	fileExists (line 578)

	检查文件是否存在
		access: public
		
	bool fileExists (string $domain, string $filename)
		string $domain: 存储域
		string $filename: 文件地址

###	getAttr (line 518)

	获取文件属性
		return: 执行成功以数组方式返回文件属性，否则返回false
		access: public

	array getAttr (string $domain, string $filename, [array $attrKey = array()])
		string $domain: 存储域
		string $filename: 文件地址
		array $attrKey: 属性值,如 array("fileName", "length")，
			当attrKey为空时，以关联数组方式返回该文件的所有属性。

###	getCDNUrl (line 139)

	获取文件CDN 地址
		access: public
		
	Example:	
		#Get a CDN url
		$stor = new SaeStorage();
		$cdn_url = $stor->getCDNUrl("domain","cdn_test.txt");


	string. getCDNUrl (string $domain, string $filename)
		string $domain: Domain name
		string $filename: Filename you save

###	getDomainAttr (line 950)

		exception: none
		access: public
		
	void getDomainAttr ([$domain='' $domain = ''])
		$domain='' $domain

###	getDomainCapacity (line 838)

	获取domain所占存储的大小
		access: public
		
	int getDomainCapacity (string $domain)
		string $domain

###	getFilesNum (line 490)

	获取指定domain下的文件数量
		return: 执行成功时返回文件数，否则返回false
		access: public
		
	array getFilesNum (string $domain, [string $path = NULL])
		string $domain: 存储域,在在线管理平台.storage页面可进行管理
		string $path: 目录(暂没实现)

###	getList (line 353)

	获取指定domain下的文件名列表
		return: 执行成功时返回文件列表数组，否则返回false
		access: public

	 Example:
		 <?php
		 // 列出 Domain 下所有路径以photo开头的文件
		 $stor = new SaeStorage();
		 
		 $num = 0;
		 while ( $ret = $stor->getList("test", "photo", 100, $num ) ) {
		      foreach($ret as $file) {
		          echo "{$file}\n";
		          $num ++;
		      }
		 }
		 
		 echo "\nTOTAL: {$num} files\n";
		 ?>
		 
	array getList (string $domain, [string $prefix = NULL], [int $limit = 10], [int $offset = 0])
		string $domain: 存储域,在在线管理平台.storage页面可进行管理
		string $prefix: 路径前缀
		int $limit: 返回条数,最大100条,默认10条
		int $offset: 起始条数。limit与offset之和最大为10000，超过此范围无法列出。

###	getListByPath (line 403)

	获取指定Domain、指定目录下的文件列表
		return: 执行成功时返回列表，否则返回false
		access: public
		
	array getListByPath (string $domain, [string $path = NULL], [int $limit = 100], [int $offset = 0], [int $fold = true])
		string $domain: 存储域
		string $path: 目录地址
		int $limit: 单次返回数量限制，默认100，最大1000
		int $offset: 起始条数
		int $fold: 是否折叠目录

###	getUrl (line 167)

	获取文件storage访问地址
		access: public

	Example:
		#Get the url of a stored file
		$stor = new SaeStorage();
		$file_url = $stor->getUrl("domain","cdn_test.txt");

	string. getUrl (string $domain, string $filename)
		string $domain: Domain name
		string $filename: Filename you save

###	listDomains (line 923)

		exception: none
		access: public
		
	void listDomains ()

###	read (line 600)

	获取文件的内容
		return: 成功时返回文件内容，否则返回false
		access: public

	string read (string $domain, string $filename)
		string $domain
		string $filename

###	setDomainAttr (line 793)

	设置Domain属性
		access: public
		
	目前支持的Domain属性
		expires: 浏览器缓存超时

	说明：
	*	expires 格式：[modified] TIME_DELTA，例如modified 1y或者1y，
		modified关键字用于指定expire时间相对于文件的修改时间。
		默认expire时间是相对于access time。
		如果TIME_DELTA为负， Cache-Control header会被设置为no-cache。
	*	TIME_DELTA，TIME_DELTA是一个表示时间的字符串，例如： 1y3M 48d 5s
	*	目前支持s/m/h/d/w/M/y
	*	expires_type 格式:TYPE [modified] TIME_DELTA,TYPE为文件的mimetype，
		例如text/html, text/plain, image/gif。
		多条expires-type规则之间以 , 隔开。例如：text/html 48h,image/png modified 1y
	*	allowReferer: 根据Referer防盗链
	*	private: 是否私有Domain
	*	404Redirect: 404跳转页面，只能是本应用页面，或本应用Storage中文件。
		例如http://appname.sinaapp.com/404.html
		或http://appname-domain.stor.sinaapp.com/404.png
	*	tag: Domain简介。格式：array('tag1', 'tag2')

	Example:
		<?php
		// 缓存过期设置
		$expires = '1 d
		';
		
		// 防盗链设置
		$allowReferer = array();
		$allowReferer['hosts'][] = '*.elmerzhang.com';       // 允许访问的来源域名，千万不要带 http://。支持通配符*和?
		$allowReferer['hosts'][] = 'elmer.sinaapp.com';
		$allowReferer['hosts'][] = '?.elmer.sinaapp.com';
		$allowReferer['redirect'] = 'http://elmer.sinaapp.com/'; // 盗链时跳转到的地址，仅允许跳转到本APP的页面，且不可使用独立域名。如果不设置或者设置错误，则直接拒绝访问。
		//$allowReferer = false;  // 如果要关闭一个Domain的防盗链功能，直接将allowReferer设置为false即可
		
		$stor = new SaeStorage();
		
		$attr = array('expires'=>$expires, 'allowReferer'=>$allowReferer);
		$ret = $stor->setDomainAttr("test", $attr);
		if ($ret === false) {
		  var_dump($stor->errno(), $stor->errmsg());
		}
		
		?>

	bool setDomainAttr (string $domain, [array $attr = array()])
		string $domain
		array $attr: Domain属性。格式：array('attr0'=>'value0', 'attr1'=>'value1', ......);

###	setFileAttr (line 705)

	设置文件属性
		access: public

	目前支持的文件属性
	*	expires: 浏览器缓存超时，设置规则和domain expires的规则一致
	*	encoding: 设置通过Web直接访问文件时，Header中的Content-Encoding。
	*	type: 设置通过Web直接访问文件时，Header中的Content-Type。
	*	private: 设置文件为私有，则文件不可被下载。

	Example:
		<?php
		$stor = new SaeStorage();
		
		$attr = array('expires' => '1 y');
		$ret = $stor->setFileAttr("test", "test.txt", $attr);
		if ($ret === false) {
		  var_dump($stor->errno(), $stor->errmsg());
		}
		?>

	bool setFileAttr (string $domain, string $filename, [array $attr = array()])
		string $domain
		string $filename: 文件名
		array $attr: 文件属性。格式：array('attr0'=>'value0', 'attr1'=>'value1', ......);

###	upload (line 278)

	将文件上传入存储
	
	Example:
		####
		$storage = new SaeStorage();
		$domain = 'domain';
		$destFileName = 'write_test.txt';
		$srcFileName = $_FILE['tmp_name']
		$attr = array('encoding'=>'gzip');
		$result = $storage->upload($domain,$destFileName, $srcFileName, -1, $attr, true);

	The `domain` must be Exist
		return: #Note: If success,return the url of the file If faild;return false
		access: public

	mixed upload (string $domain, string $destFileName, string $srcFileName, [array $attr = array()], [boolean $compress = false])
		string $domain: Domain name
		string $destFileName: The destiny fileName.
		string $srcFileName: The source of the uoload file
		array $attr: File attributes, set attributes refer to SaeStorage :: setFileAttr () method
		boolean $compress: #Note: Whether gzip compression. If true, the file after gzip compression and then stored in Storage, often associated with $attr=array('encoding'=>'gzip') used in conjunction

###	write (line 223)

	将数据写入存储
		return: #Note: If success,return the url of the file If faild;return false
		access: public

	Example:
	
		#### Write some content into a storage file
		####
		$storage = new SaeStorage();
		$domain = 'domain';
		$destFileName = 'write_test.txt';
		$content = 'Hello,I am from the method of write'
		$attr = array('encoding'=>'gzip');
		$result = $storage->write($domain,$destFileName, $content, -1, $attr, true);

	mixed write (string $domain, string $destFileName, string $content, [int $size = -1], [array $attr = array()], [boolean $compress = false])
		string $domain: Domain name
		string $destFileName: The destiny fileName.
		string $content: The content of the file
		int $size: The length of file content,the overflower will be truncated and by default there is no limit.
		array $attr: File attributes, set attributes refer to SaeStorage :: setFileAttr () method
		boolean $compress: #Note: Whether gzip compression. If true, the file after gzip compression and then stored in Storage, often associated with $attr=array('encoding'=>'gzip') used in conjunction

#	Documentation generated on Wed, 03 Sep 2014 10:14:57 +0800 by phpDocumentor 1.4.3

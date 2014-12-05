#	Class SaeMysql

##	Description

###	Sae Mysql Class

	<?php
	$mysql = new SaeMysql();
	
	$sql = "SELECT * FROM `user` LIMIT 10";
	$data = $mysql->getData( $sql );
	$name = strip_tags( $_REQUEST['name'] );
	$age = intval( $_REQUEST['age'] );
	$sql = "INSERT  INTO `user` ( `name` , `age` , `regtime` ) VALUES ( '"  . $mysql->escape( $name ) . "' , '" . intval( $age ) . "' , NOW() ) ";
	$mysql->runSql( $sql );
	if( $mysql->errno() != 0 )
	{
	 die( "Error:" . $mysql->errmsg() );
	}
	
	$mysql->closeDb();
	?>

	//author: EasyChen
	//Located in /saemysql.class.php (line 39)
	//
	//SaeObject
	//   |
	//   --SaeMysql
	   
##	Method Summary

	void __construct ([bool $do_replication = true])
	int affectedRows ()
	bool closeDb ()
	string errmsg ()
	int errno ()
	string error ()
	string escape (string $str)
	array getData (string $sql)
	array getLine (string $sql)
	mixxed getVar (string $sql)
	int lastId ()
	mysqli_result|bool runSql (string $sql)
	void setAppname (string $appname)
	void setAuth (string $akey, string $skey)
	void setCharset (string $charset)
	void setPort (string $port)

##	Methods

###	Constructor __construct (line 49)

	构造函数
		author: EasyChen
		
	void __construct ([bool $do_replication = true])
		bool $do_replication: 是否支持主从分离，true:支持，false:不支持，默认为true

###	affectedRows (line 278)

	同mysqli_affected_rows函数
		return: 成功返回行数,失败时返回-1
		author: Elmer Zhang
		access: public
		
	int affectedRows ()
	
###	closeDb (line 314)

	关闭数据库连接
		author: EasyChen
		access: public
		
	bool closeDb ()
	
###	errmsg (line 385)

	返回错误信息,error的别名
		author: EasyChen
		access: public
		
	string errmsg ()
	
###	errno (line 363)

	返回错误码
		author: EasyChen
		access: public
		
	int errno ()
	
###	error (line 374)

	返回错误信息
		author: EasyChen
		access: public
		
	string error ()
	
###	escape (line 343)

	同mysqli_real_escape_string
		author: EasyChen
		access: public

	string escape (string $str)
		string $str
		
###	getData (line 172)

	运行Sql,以多维数组方式返回结果集
		return: 成功返回数组，失败时返回false
		author: EasyChen
		access: public
		
	array getData (string $sql)
		string $sql
		
###	getLine (line 219)

	运行Sql,以数组方式返回结果集第一条记录
	
		return: 成功返回数组，失败时返回false
		author: EasyChen
		access: public
		
	array getLine (string $sql)
	string $sql

###	getVar (line 249)

	运行Sql,返回结果集第一条记录的第一个字段值
	
		return: 成功时返回一个值，失败时返回false
		author: EasyChen
		access: public
		
	mixxed getVar (string $sql)
	string $sql

###	lastId (line 290)

	同mysqli_insert_id函数
	
		return: 成功返回last_id,失败时返回false
		author: EasyChen
		access: public
		
	int lastId ()

###	runSql (line 140)

	运行Sql语句,不返回结果集
	
		access: public
		
	mysqli_result|bool runSql (string $sql)
	string $sql

###	setAppname (line 105)

	设置Appname
	
	当需要连接其他APP的数据库时使用
	
		author: EasyChen
		access: public
		
	void setAppname (string $appname)
	string $appname

###	setAuth (line 74)

	设置keys
	
	当需要连接其他APP的数据库时使用
	
		author: EasyChen
		access: public
		
	void setAuth (string $akey, string $skey)
	string $akey: AccessKey
	string $skey: SecretKey

###	setCharset (line 117)

	设置当前连接的字符集 , 必须在发起连接之前进行设置
	
		access: public
		
	void setCharset (string $charset)
	string $charset: 字符集,如GBK,GB2312,UTF8

###	setPort (line 89)

	设置Mysql服务器端口
	
	当需要连接其他APP的数据库时使用
	
		author: EasyChen
		access: public
		
	void setPort (string $port)
	string $port

#	Documentation generated on Wed, 03 Sep 2014 10:14:54 +0800 by phpDocumentor 1.4.3

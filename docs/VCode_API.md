#	Class SaeVCode

##	Description

	SAE 验证码服务

		<?php
		session_start();
		$vcode = new SaeVCode();
		if ($vcode === false)
		        var_dump($vcode->errno(), $vcode->errmsg());

		$_SESSION['vcode'] = $vcode->answer();
		$question=$vcode->question();
		echo $question['img_html'];
		?>
	错误码参考：

		errno: 0 成功
		errno: 3 参数错误
		errno: 500 服务内部错误
		errno: 999 未知错误
		errno: 403 权限不足或超出配额
	// author: Elmer Zhang
	// Located in /saevcode.class.php (line 40)

	// SaeObject
	//    |
	//    --SaeVCode

##	Method Summary

	SaeVCode __construct ([ $options = array()])
	string answer ()
	string errmsg ()
	int errno ()
	array question ()

##	Methods

###	Constructor __construct (line 58)
	SaeVCode __construct ([ $options = array()])
	*	$options

###	answer (line 84)
	取得验证码答案
	*	author: Elmer Zhang
	*	access: public
	string answer ()

###	errmsg (line 104)
	取得错误信息

	*	author: Elmer Zhang
	*	access: public
	string errmsg ()

###	errno (line 94)
	取得错误码
	*	author: Elmer Zhang
	*	access: public
	int errno ()

###	question (line 74)
	取得验证码问题
	*	author: Elmer Zhang
	*	access: public
	图片验证码返回格式: 
		array("img_url"=>"验证码图片URL", "img_html"=>"用于显示验证码图片的HTML代码")
	array question ()

#	Documentation generated on Wed, 03 Sep 2014 10:15:04 +0800 by phpDocumentor 1.4.3

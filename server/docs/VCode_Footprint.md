#	Sina App Engine(SAE)入门教程(4)- SaeVCode（验证码服务）使用
##	使用教程

	所有的验证码原理都是生成一个vcode字符串，存到session中，和用户的输入进行比较判断.
	以下是一个使用验证码服务的完整实例：

###	首页index.html
		<html>
		<title>Sae Vcode demo</title>
		<body>
			<form action="check.php">
				<input type="text" name="vcode"><img src="vcode.php">
				<input type="submit" value="Submit">
			</form>
		</body>
		</html>
###	生成验证码的脚本vcode.php
		<?php
		session_start();
		$vcode = new SaeVCode();
		if ($vcode === false)
			var_dump($vcode->errno(), $vcode->errmsg());
		$_SESSION['vcode'] = $vcode->answer();
		$question=$vcode->question();
		$imgdata = file_get_contents($question['img_url']);
		header("content-type:image/jpg");
		echo($imgdata);
		?>
###	用户提交验证码判断脚本check.php
		<?php
		$vcode = $_REQUEST['vcode'];
		session_start();
		if($_SESSION['vcode'] != $vcode)
		{
			echo 'Vcode error';
		}else{
			echo "Vcode right";
		}
#

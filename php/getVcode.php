<?php
	header("Access-Control-Allow-Origin: *");
	session_start();
	$vcode = new SaeVCode();
	if ($vcode === false) var_dump($vcode->errno(), $vcode->errmsg());
	$_SESSION['vcode'] = $vcode->answer();
	$question=$vcode->question();
	$imgdata = file_get_contents($question['img_url']);
	header("content-type:image/jpg");
	echo($imgdata);
?>
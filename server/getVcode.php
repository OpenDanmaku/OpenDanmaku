<?php
session_start();
getCode(100, 24);

function getCode($w, $h) {//helloweba的算数验证码
	$im = imagecreate($w, $h);

	//imagecolorallocate($im, 14, 114, 180); // background color
	$red = imagecolorallocate($im, 255, 0, 0);
	$white = imagecolorallocate($im, 255, 255, 255);

	$num1 = rand(1, 20);
	$num2 = rand(1, 20);

	$_SESSION['vcode'] = $num1 + $num2;

	$gray = imagecolorallocate($im, 118, 151, 199);//本地化修改
	$black = imagecolorallocate($im, mt_rand(0, 100), mt_rand(0, 100), mt_rand(0, 100));

	//画背景
	imagefilledrectangle($im, 0, 0, 100, 24, $black);
	//在画布上随机生成大量点，起干扰作用;
	for ($i = 0; $i < 80; $i++) {
		imagesetpixel($im, rand(0, $w), rand(0, $h), $gray);
	}

	imagestring($im, 5, 5, 4, $num1, $red);
	imagestring($im, 5, 30, 3, "+", $red);
	imagestring($im, 5, 45, 4, $num2, $red);
	imagestring($im, 5, 70, 3, "=", $red);
	imagestring($im, 5, 80, 2, "?", $white);

	header("Content-type: image/png");
	header("Access-Control-Allow-Origin: *");//本地化修改,不限制跨域
	imagepng($im);
	imagedestroy($im);

	//echo($imgdata);//废弃原方法
}
?>

<?php
//$_GET��$_REQUEST�Ѿ�urldecode()�ˣ�
if ($_REQUEST['name'] != xxxyyyzzz) die("Not Authenticated.");
$mysql = new SaeMysql();
$sql = "DELETE FROM user WHERE time < '" . $_REQUEST['time'] ."'";
$mysql->runSql( $sql );
if ($mysql->errno() != 0) die("Error:" . $mysql->errmsg());
$mysql->closeDb();
?>

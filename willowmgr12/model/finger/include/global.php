<?php
require '../../config.php';

// $base_path		= "http://localhost/wsdm/willowmgr12/model/finger/";
$base_path		= HTTP_SERVER . 'model/finger/';

$db_name		= DB_DATABASE;
$db_user		= DB_USERNAME;
$db_pass		= DB_PASSWORD;
$db_host		= DB_HOSTNAME;
$port			= DB_PORT;

$time_limit_reg = '15';
$time_limit_ver = '10';

$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name, $port);

date_default_timezone_set("Asia/Jakarta");

if (!$conn) die("Connection refused!");
?>
<?php
if (isset($_GET['customer_id']) && !empty($_GET['customer_id'])) {
	include 'include/global.php';
	include 'include/function.php';

	$customer_id = $_GET['customer_id'];
	
	$finger	= getFinger($conn, $customer_id);

	echo "$customer_id;" . $finger[0]['finger_data'] . ";SecurityKey;" . $time_limit_ver . ";" . $base_path . "process_verification.php;" . $base_path . "getac.php;extraParams";

	include 'include/close.php';
}
?>
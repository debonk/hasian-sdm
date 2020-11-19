<?php
if (isset($_GET['customer_id']) && !empty($_GET['customer_id']) && isset($_GET['action']) && !empty($_GET['action']) && isset($_GET['schedule_date']) && !empty($_GET['schedule_date'])) {
	include 'include/global.php';
	include 'include/function.php';
	
	$customer_id = $_GET['customer_id'];
	$schedule_date = $_GET['schedule_date'];
	$action = $_GET['action'];
	
	$finger	= getFinger($conn, $customer_id);

	echo "$customer_id;" . $finger[0]['finger_data'] . ";SecurityKey;" . $time_limit_ver . ";" . $base_path . "process_verification.php?action=$action;" . $base_path . "getac.php;" . $schedule_date;

	include 'include/close.php';
}
?>
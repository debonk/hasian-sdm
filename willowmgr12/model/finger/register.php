<?php
if (isset($_GET['customer_id']) && !empty($_GET['customer_id']) && isset($_GET['user_id']) && !empty($_GET['user_id'])) {
	if (isset($_GET['token']) && !empty($_GET['token'])) {
		include 'include/global.php';

		$customer_id = $_GET['customer_id'];
		$user_id = $_GET['user_id'];
		$token = $_GET['token'];
		
		echo "$customer_id;SecurityKey;" . $time_limit_reg . ";" . $base_path . "process_register.php?token=" . $token . "&user_id=" . $user_id . ";" . $base_path . "getac.php";
	
		include 'include/close.php';
	}
}
?>
<?php
if (isset($_POST['RegTemp']) && !empty($_POST['RegTemp']) && isset($_GET['user_id']) && !empty($_GET['user_id'])) {
	include 'include/global.php';
	include 'include/function.php';
	
	if (isset($_GET['user_id'])) {
		$user_id = $_GET['user_id'];
	} else {
		$user_id = 0;
	}

	// $token = $_GET['token']; //apply token verification later
	
	$data 			= explode(";", $_POST['RegTemp']);
	$vStamp 		= $data[0];
	$sn 			= $data[1];
	$customer_id	= $data[2];
	$regTemp 		= $data[3];
	
	$device = getDeviceBySn($conn, $sn);
	
	$salt = md5($device[0]['ac'] . $device[0]['vkey'] . $regTemp . $sn . $customer_id);
	
	if (strtoupper($vStamp) == strtoupper($salt)) {
		addFinger($conn, $customer_id, $regTemp, $user_id);
	}
	
	// include 'include/close.php';
}
?>

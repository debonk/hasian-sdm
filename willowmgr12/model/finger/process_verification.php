<?php
if (isset($_POST['VerPas']) && !empty($_POST['VerPas'])) {
	include 'include/global.php';
	include 'include/function.php';

//VerPas = 3;E141182C1304FA9CCE3D36A0E4605002;20190529173859;J720J00250;extraParams

	$data 			= explode(";",$_POST['VerPas']);
	$customer_id	= $data[0];
	$vStamp 		= $data[1];
	$time			= $data[2];
	$sn 			= $data[3];
	
	$fingerData = getFinger($conn, $customer_id);
	
	$device 	= getDeviceBySn($conn, $sn);
	
	$salt = md5($sn . $fingerData[0]['finger_data'] . $device[0]['vc'] . $time . $customer_id . $device[0]['vkey']);
	
	if (strtoupper($vStamp) == strtoupper($salt)) {
		$name = getCustomerName($conn, $customer_id);
		
		echo $base_path . "messages.php?name=$name&time=$time";
		// $msg = $name;
		// echo $base_path . "messages.php?msg=$msg";
	}
	
	include 'include/close.php';
}
?>
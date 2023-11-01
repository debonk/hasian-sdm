<?php
if (isset($_POST['VerPas']) && !empty($_POST['VerPas']) && isset($_GET['action']) && !empty($_GET['action'])) {
	include 'include/global.php';
	include 'include/function.php';

//VerPas = 10x12;821987BB2F602CA95A2C371418D7B2E4;20231030165354;PX20J13772;2023-10-30
	// $msg = $_POST['VerPas'];
	// echo $base_path . "messages.php?msg=$msg";
	// die(' ---breakpoint--- ');

	$data 			= explode(";",$_POST['VerPas']);
	$customer_id	= $data[0];
	$vStamp 		= $data[1];
	$time			= $data[2];
	$sn 			= $data[3];
	$schedule_date	= $data[4];
	
	$fingerData = getFinger($conn, $customer_id);
	
	$device 	= getDeviceBySn($conn, $sn);
	
	$salt = md5($sn . $fingerData[0]['finger_data'] . $device[0]['vc'] . $time . $customer_id . $device[0]['vkey']);
	
	if (strtoupper($vStamp) == strtoupper($salt)) {
		// $datetime = date('Y-m-d H:i:s', strtotime($time));
		$action = $_GET['action'];
		
		$log = addLog($conn, $customer_id, $schedule_date, $action);
	}
	
	// include 'include/close.php';//Jangan di close untuk pengecekan status log
}
?>
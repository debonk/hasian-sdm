<?php
if (isset($_POST['VerPas']) && !empty($_POST['VerPas'])) {
	include 'include/global.php';
	include 'include/function.php';

	//VerPas = 3;E141182C1304FA9CCE3D36A0E4605002;20190529173859;J720J00250;extraParams
	//VerPas = 10;6a6b3cff0e1f74e6dd64b9f05d53b410;20210511120224;k520j03896;2021-05-11
	// $msg = $_POST['VerPas'];
	// echo $base_path . "messages.php?msg=$msg";
	// die(' ---breakpoint--- ');

	$data 			= explode(";", $_POST['VerPas']);
	$customer_id	= $data[0];
	$vStamp 		= $data[1];
	$time			= $data[2];
	$sn 			= $data[3];

	$fingerData = getFinger($conn, $customer_id);

	$device 	= getDeviceBySn($conn, $sn);

	$salt = md5($sn . $fingerData[0]['finger_data'] . $device[0]['vc'] . $time . $customer_id . $device[0]['vkey']);

	if (strtoupper($vStamp) == strtoupper($salt)) {
		$name = getCustomerName($conn, $customer_id);

		// // $server_info = get_headers('https://wsdm.willowbabyshop.com/', true);
		// $server_info = get_headers(HTTP_SERVER, true);

		// if (is_array($server_info['date'])) {
		// 	$date_info = $server_info['date'][0];
		// } else {
		// 	$date_info = $server_info['date'];
		// }

		// $datetime = date("Y-m-d H:i:s", strtotime($date_info));

		$datetime = getServerTime($conn);

		echo $base_path . "messages.php?name=$name&time=$datetime";
	}

	include 'include/close.php';
} else {
	$msg = 'no VerPass';
	// echo HTTP_SERVER . 'model/finger/' . "messages.php?msg=$msg";
	echo $base_path . "messages.php?msg=$msg";
}

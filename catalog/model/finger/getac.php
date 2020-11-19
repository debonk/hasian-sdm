<?php
if (isset($_GET['vc']) && !empty($_GET['vc'])) {
	include 'include/global.php';
	include 'include/function.php';
	
	$data = getDeviceByVc($conn, $_GET['vc']);
	
	echo $data[0]['ac'].$data[0]['sn'];
	
	include 'include/close.php';
}
?>
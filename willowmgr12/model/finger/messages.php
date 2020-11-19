<?php
if (isset($_GET['msg']) && !empty($_GET['msg'])) {
	
	echo $_GET['msg'];

} elseif (isset($_GET['name']) && !empty($_GET['name']) && isset($_GET['time']) && !empty($_GET['time'])) {
	
	$name	= $_GET['name'];
	$time	= date('Y-m-d H:i:s', strtotime($_GET['time']));
	
	echo 'Verifikasi ' . $name . ' sukses pada ' . date('Y-m-d H:i:s', strtotime($time)) . '.';
	
} else {
		
	$msg = "Parameter invalid..";
	
	echo "$msg";
	
}
?>
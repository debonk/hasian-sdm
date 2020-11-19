<?php
	function getFinger($conn, $customer_id) {
		$sql 	= "SELECT * FROM oc_customer_finger WHERE customer_id = '" . (int)$customer_id . "'";
		$result	= mysqli_query($conn, $sql);
		$arr 	= array();
		$i	= 0;

		while($row = mysqli_fetch_array($result)) {
			$arr[$i] = array(
				'finger_id'		=>$row['finger_id'],
				'customer_id'	=>$row['customer_id'],
				'finger_data'	=>$row['finger_data']
			);
				
			$i++;

		}

		return $arr;
	}
	
	function getDeviceBySn($conn, $sn) {
		$sql 	= "SELECT * FROM oc_finger_device WHERE sn = '" . $sn . "'";
		$result	= mysqli_query($conn, $sql);
		$arr 	= array();
		$i 	= 0;

		while ($row = mysqli_fetch_array($result)) {
			$arr[$i] = array(
				'device_name'	=> $row['device_name'],
				'sn'		=> $row['sn'],
				'vc'		=> $row['vc'],
				'ac'		=> $row['ac'],
				'vkey'		=> $row['vkey']
			);

			$i++;
		}

		return $arr;
	}

	function getDeviceByVc($conn, $vc) {
		$sql 	= "SELECT * FROM oc_finger_device WHERE vc = '" . $vc . "'";
		$result	= mysqli_query($conn, $sql);
		$arr 	= array();
		$i 	= 0;

		while ($row = mysqli_fetch_array($result)) {
			$arr[$i] = array(
				'device_name'	=> $row['device_name'],
				'sn'		=> $row['sn'],
				'vc'		=> $row['vc'],
				'ac'		=> $row['ac'],
				'vkey'		=> $row['vkey']
			);

			$i++;
		}

		return $arr;
	}
	
	function addLog($conn, $customer_id, $schedule_date, $datetime, $action) {
		if ($action == 'login') {
			$sql = "UPDATE oc_presence_log SET time_login = '" . $datetime . "' WHERE customer_id = '" . (int)$customer_id . "' AND date = '" . $schedule_date . "'";
		} elseif ($action == 'logout') {
			$sql = "UPDATE oc_presence_log SET time_logout = '" . $datetime . "' WHERE customer_id = '" . (int)$customer_id . "' AND date = '" . $schedule_date . "'";
		}
		
		$result	= mysqli_query($conn, $sql);
		
		if ($result) {
			return 1;				
		} else {
			return "Error insert log data!";
		}
	}
?>
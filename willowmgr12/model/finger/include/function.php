<?php
	function getDeviceBySn($conn, $sn) {//ok
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
	
	function addFinger($conn, $customer_id, $regTemp, $user_id) {
		list($customer_id, $finger_index) = explode('x', $customer_id);

		$sql 	= "INSERT INTO oc_customer_finger SET customer_id = '" . (int)$customer_id . "', finger_index = '" . (int)$finger_index . "', finger_data = '" . $regTemp . "', user_id = '" . (int)$user_id . "'";
		$result = mysqli_query($conn, $sql);
	
		return 1;
	}

	function getFinger($conn, $customer_id) {
		list($customer_id, $finger_index) = explode('x', $customer_id);

		$sql 	= "SELECT * FROM oc_customer_finger WHERE customer_id = '" . (int)$customer_id . "' AND finger_index = '" . (int)$finger_index . "'";
		$result	= mysqli_query($conn, $sql);
		$arr 	= array();
		$i	= 0;

		while($row = mysqli_fetch_array($result)) {
			$arr[$i] = array(
				'finger_id'		=>$row['finger_id'],
				'customer_id'	=>$row['customer_id'] . 'x' . $row['finger_index'],
				'finger_data'	=>$row['finger_data']
			);
				
			$i++;

		}

		return $arr;
	}
	
	function getCustomerName($conn, $customer_id) {
		$customer_id = explode('x', $customer_id)[0];

		$sql 	= "SELECT CONCAT(firstname, ' [', lastname, ']') AS name FROM oc_customer WHERE customer_id = '" . (int)$customer_id . "'";
		$result	= mysqli_query($conn, $sql);

		if ($row = mysqli_fetch_row($result)) {
			$str = $row[0];
		}

		return $str;
	}
	
	function getServerTime($conn) {
		$sql 	= "SELECT NOW() as time";
		$result	= mysqli_query($conn, $sql);

		if ($row = mysqli_fetch_row($result)) {
			$str = $row[0];
		}

		return $str;
	}
?>
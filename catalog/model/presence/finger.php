<?php
class ModelPresenceFinger extends Model {
	// public function getFingerByCustomerId($customer_id) {
	// 	$sql = "SELECT DISTINCT cf.*, CONCAT(c.firstname, ' [', c.lastname, ']') AS name, u.username FROM " . DB_PREFIX . "customer_finger cf LEFT JOIN " . DB_PREFIX . "customer c ON (c.customer_id = cf.customer_id) LEFT JOIN " . DB_PREFIX . "user u ON (u.user_id = cf.user_id) WHERE cf.customer_id = '" . (int)$customer_id . "'";

	// 	$query = $this->db->query($sql);

	// 	return $query->row;
	// }

	// function getFingersCount($customer_id) {
	// 	$sql = "SELECT COUNT(finger_id) as total FROM " . DB_PREFIX . "customer_finger WHERE customer_id = '" . (int)$customer_id . "'";
		
	// 	$query = $this->db->query($sql);
	
	// 	return $query->row['total'];
	// }
	
	// public function deleteFingerByCustomerId($customer_id) {
	// 	$this->db->query("DELETE FROM " . DB_PREFIX . "customer_finger WHERE customer_id = '" . (int)$customer_id . "'");
	// }

	public function getFingers($data = array())
	{
		$this->createView();

		$sql = "SELECT * FROM " . DB_PREFIX . "v_customer_finger";

		$implode = array();

		if (!empty($data['filter']['location_id'])) {
			$implode[] = "location_id = '" . (int)$data['filter']['location_id'] . "'";
		}

		$implode[] = "date_start <= CURDATE()";
		$implode[] = "(date_end IS NULL OR date_end = '0000-00-00' OR date_end >= CURDATE())";
		$implode[] = "status = 1";
		
		$implode[] = "customer_id = 10"; // For testing

		// if (!empty($data['filter']['customer_id'])) {
		// 	$implode[] = "customer_id = '" . (int)$data['filter']['customer_id'] . "'";
		// }

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		// $sort_data = array(
		// 	'customer_id',
		// 	'firstname',
		// 	'location_id'
		// );

		// if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
		// 	$sql .= " ORDER BY " . $data['sort'];
		// } else {
		// 	$sql .= " ORDER BY firstname";
		// }

		// if (isset($data['order']) && ($data['order'] == 'DESC')) {
		// 	$order = " DESC";
		// } else {
		// 	$order = " ASC";
		// }

		// $sql .= $order;

		// if (isset($data['start']) || isset($data['limit'])) {
		// 	if ($data['start'] < 0) {
		// 		$data['start'] = 0;
		// 	}

		// 	if ($data['limit'] < 1) {
		// 		$data['limit'] = 20;
		// 	}

		// 	$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		// }

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function createView($view_name = 'v_customer_finger')
	{
		$view_name = DB_PREFIX . $view_name;
		
		$sql = "SELECT cf.*, c.firstname AS customer, c.location_id, c.date_start, c.date_end, c.status FROM " . DB_PREFIX . "customer_finger cf LEFT JOIN " . DB_PREFIX . "customer c ON (c.customer_id = cf.customer_id)";

		return $this->db->createView($view_name, $sql);
	}
}

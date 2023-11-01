<?php
class ModelCustomerFinger extends Model
{
	private $finger_indexes = ['thumbs', 'index', 'middle', 'ring', 'pinkie'];

	public function manageFinger($customer_id, $data) {
		if (isset($data['active_finger'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "customer_add_data SET active_finger = '" . json_encode($data['active_finger']) . "' WHERE customer_id = '" . (int)$customer_id . "'");
		}
	}

	public function deleteFingerByCustomerId($customer_id, $finger_index)
	{
		$this->db->query("DELETE FROM " . DB_PREFIX . "customer_finger WHERE customer_id = '" . (int)$customer_id . "' AND finger_index = '" . (int)$finger_index . "'");
	}

	public function getFingerByCustomerId($customer_id, $finger_index = 0)
	{
		$sql = "SELECT DISTINCT cf.*, CONCAT(c.firstname, ' [', c.lastname, ']') AS name, u.username FROM " . DB_PREFIX . "customer_finger cf LEFT JOIN " . DB_PREFIX . "customer c ON (c.customer_id = cf.customer_id) LEFT JOIN " . DB_PREFIX . "user u ON (u.user_id = cf.user_id) WHERE cf.customer_id = '" . (int)$customer_id . "' AND finger_index = '" . (int)$finger_index . "'";

		$query = $this->db->query($sql);

		return $query->row;
	}

	public function getFingersByCustomerId($customer_id)
	{
		$sql = "SELECT DISTINCT cf.*, u.username FROM " . DB_PREFIX . "customer_finger cf LEFT JOIN " . DB_PREFIX . "user u ON (u.user_id = cf.user_id) WHERE cf.customer_id = '" . (int)$customer_id . "' ORDER BY finger_index ASC";

		$query = $this->db->query($sql);

		$fingers_data = [];

		foreach ($query->rows as $value) {
			$fingers_data[$value['finger_index']] = $value;
		}

		return $fingers_data;
	}

	public function getFingersCount($customer_id, $finger_index = 0)
	{
		$sql = "SELECT COUNT(finger_id) as total FROM " . DB_PREFIX . "customer_finger WHERE customer_id = '" . (int)$customer_id . "' AND finger_index = '" . (int)$finger_index . "'";

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	// public function createView($view_name = 'v_finger')
	// {
	// 	$view_name = DB_PREFIX . $view_name;

	// 	# Remove description for simpler query
	// 	$sql = "SELECT cf.*, c.firstname AS customer, c.location_id, c.date_start, c.date_end, c.status FROM " . DB_PREFIX . "customer_finger cf LEFT JOIN " . DB_PREFIX . "customer c ON (c.customer_id = cf.customer_id)";

	// 	return $this->db->createView($view_name, $sql);
	// }

	public function getFingerIndexes()
	{
		return $this->finger_indexes;
	}
}

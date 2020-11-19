<?php
class ModelCustomerFinger extends Model {
	public function getFingerByCustomerId($customer_id) {
		$sql = "SELECT DISTINCT cf.*, CONCAT(c.firstname, ' [', c.lastname, ']') AS name, u.username FROM " . DB_PREFIX . "customer_finger cf LEFT JOIN " . DB_PREFIX . "customer c ON (c.customer_id = cf.customer_id) LEFT JOIN " . DB_PREFIX . "user u ON (u.user_id = cf.user_id) WHERE cf.customer_id = '" . (int)$customer_id . "'";

		$query = $this->db->query($sql);

		return $query->row;
	}

	function getFingersCount($customer_id) {
		$sql = "SELECT COUNT(finger_id) as total FROM " . DB_PREFIX . "customer_finger WHERE customer_id = '" . (int)$customer_id . "'";
		
		$query = $this->db->query($sql);
	
		return $query->row['total'];
	}
	
	public function deleteFingerByCustomerId($customer_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "customer_finger WHERE customer_id = '" . (int)$customer_id . "'");
	}
}

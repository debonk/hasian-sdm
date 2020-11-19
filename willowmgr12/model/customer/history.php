<?php
class ModelCustomerHistory extends Model {
	public function addHistory($key, $data) {
		if (isset($data['customer_id'])) {
			$customer_id = $data['customer_id'];
		} else {
			$customer_id = 0;
		}

		if (isset($data['date'])) {
			$date = date('Y-m-d', strtotime($data['date']));
		} else {
			$date = 'null';
		}

		$this->db->query("INSERT INTO `" . DB_PREFIX . "customer_history` SET `customer_id` = '" . (int)$customer_id . "', `date` = '" . $this->db->escape($date) . "', `key` = '" . $this->db->escape($key) . "', `data` = '" . $this->db->escape(json_encode($data)) . "', `date_added` = NOW(), `user_id` = '" . (int)$this->user->getId() . "'");
	}
}
<?php
class ModelAccountUser extends Model {
	public function getUser($user_id) {
		$query = $this->db->query("SELECT user_id, full_coverage, customer_department_ids, location_ids FROM `" . DB_PREFIX . "user` u WHERE u.user_id = '" . (int)$user_id . "'");

		$user_data = $query->row;

		$user_data['customer_department_ids'] = $user_data['customer_department_ids'] ? (array)json_decode($user_data['customer_department_ids'], true) : [];
		$user_data['location_ids'] = $user_data['location_ids'] ? (array)json_decode($user_data['location_ids'], true) : [];

		return $user_data;
	}
}
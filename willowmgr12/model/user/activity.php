<?php
class ModelUserActivity extends Model {
	public function addActivity($key, $data) {
		if (isset($data['user_id'])) {
			$user_id = $data['user_id'];
		} else {
			$user_id = 0;
		}

		$this->db->query("INSERT INTO `" . DB_PREFIX . "user_activity` SET `user_id` = '" . (int)$user_id . "', `key` = '" . $this->db->escape($key) . "', `data` = '" . $this->db->escape(json_encode($data)) . "', `ip` = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', `date_added` = NOW()");
	}
}
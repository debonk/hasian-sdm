<?php
class ModelToolActivity extends Model {
	public function addOnline($ip, $user_id, $url, $referer) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "user_online` WHERE date_added < '" . date('Y-m-d H:i:s', strtotime('-3 months')) . "'");

		$this->db->query("INSERT INTO `" . DB_PREFIX . "user_online` SET `ip` = '" . $this->db->escape($ip) . "', `user_id` = '" . (int)$user_id . "', `url` = '" . $this->db->escape($url) . "', `referer` = '" . $this->db->escape($referer) . "', `date_added` = '" . $this->db->escape(date('Y-m-d H:i:s')) . "'");
	}

	public function addActivity($key, $data) {
		if (isset($data['user_id'])) {
			$user_id = $data['user_id'];
		} else {
			$user_id = 0;
		}

		$this->db->query("INSERT INTO `" . DB_PREFIX . "user_activity` SET `user_id` = '" . (int)$user_id . "', `key` = '" . $this->db->escape($key) . "', `data` = '" . $this->db->escape(json_encode($data)) . "', `ip` = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', `date_added` = NOW()");
	}
}
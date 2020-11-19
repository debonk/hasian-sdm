<?php
class ModelLocalisationPresenceStatus extends Model {
	public function addPresenceStatus($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "presence_status SET name = '" . $this->db->escape($data['name']) . "', code = '" . $this->db->escape($data['code']) . "', last_notif = '" . (int)$data['last_notif'] . "'");

		$this->cache->delete('presence_status');
	}

	public function editPresenceStatus($presence_status_id, $data) {
		if (isset($data['code'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "presence_status SET name = '" . $this->db->escape($data['name']) . "', code = '" . $this->db->escape($data['code']) . "', last_notif = '" . (int)$data['last_notif'] . "' WHERE presence_status_id = '" . (int)$presence_status_id . "'");
		} else {
			$this->db->query("UPDATE " . DB_PREFIX . "presence_status SET name = '" . $this->db->escape($data['name']) . "', last_notif = '" . (int)$data['last_notif'] . "' WHERE presence_status_id = '" . (int)$presence_status_id . "'");
		}
			
		$this->cache->delete('presence_status');
	}

	public function deletePresenceStatus($presence_status_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "presence_status WHERE presence_status_id = '" . (int)$presence_status_id . "'");

		$this->cache->delete('presence_status');
	}

	public function getPresenceStatus($presence_status_id) { //used by: absence
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "presence_status WHERE presence_status_id = '" . (int)$presence_status_id . "'");

		return $query->row;
	}

	public function getPresenceStatuses($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "presence_status ORDER BY presence_status_id";

			if (isset($data['order']) && ($data['order'] == 'DESC')) {
				$sql .= " DESC";
			} else {
				$sql .= " ASC";
			}

			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}

				if ($data['limit'] < 1) {
					$data['limit'] = 20;
				}

				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}

			$query = $this->db->query($sql);

			return $query->rows;
		} else {
			$presence_status_data = $this->cache->get('presence_status');

			if (!$presence_status_data) {
				$query = $this->db->query("SELECT presence_status_id, code, name FROM " . DB_PREFIX . "presence_status ORDER BY presence_status_id");

				$presence_status_data = $query->rows;

				$this->cache->set('presence_status', $presence_status_data);
			}

			return $presence_status_data;
		}
	}

	public function getPresenceStatusesCount() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "presence_status");

		return $query->row['total'];
	}
	
	public function getPresenceStatusesCountByCode($code) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "presence_status WHERE code = '" . $this->db->escape($code) . "'");

		return $query->row['total'];
	}
}

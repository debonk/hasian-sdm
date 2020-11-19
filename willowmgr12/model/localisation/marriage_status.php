<?php
class ModelLocalisationMarriageStatus extends Model {
	public function addMarriageStatus($data) {
		foreach ($data['marriage_status'] as $language_id => $value) {
			if (isset($marriage_status_id)) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "marriage_status SET marriage_status_id = '" . (int)$marriage_status_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', code = '" . $this->db->escape($value['code']) . "'");
			} else {
				$this->db->query("INSERT INTO " . DB_PREFIX . "marriage_status SET language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', code = '" . $this->db->escape($value['code']) . "'");

				$marriage_status_id = $this->db->getLastId();
			}
		}

		$this->cache->delete('marriage_status');
		
		return $marriage_status_id;
	}

	public function editMarriageStatus($marriage_status_id, $data) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "marriage_status WHERE marriage_status_id = '" . (int)$marriage_status_id . "'");

		foreach ($data['marriage_status'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "marriage_status SET marriage_status_id = '" . (int)$marriage_status_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', code = '" . $this->db->escape($value['code']) . "'");
		}

		$this->cache->delete('marriage_status');
	}

	public function deleteMarriageStatus($marriage_status_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "marriage_status WHERE marriage_status_id = '" . (int)$marriage_status_id . "'");

		$this->cache->delete('marriage_status');
	}

	public function getMarriageStatus($marriage_status_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "marriage_status WHERE marriage_status_id = '" . (int)$marriage_status_id . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function getMarriageStatuses($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "marriage_status WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'";

			$sql .= " ORDER BY name";

			if (isset($data['order']) && ($data['order'] == 'ASC')) {
				$sql .= " ASC";
			} else {
				$sql .= " DESC";
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
			$marriage_status_data = $this->cache->get('marriage_status.' . (int)$this->config->get('config_language_id'));

			if (!$marriage_status_data) {
				$query = $this->db->query("SELECT marriage_status_id, name FROM " . DB_PREFIX . "marriage_status WHERE language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY name");

				$marriage_status_data = $query->rows;

				$this->cache->set('marriage_status.' . (int)$this->config->get('config_language_id'), $marriage_status_data);
			}

			return $marriage_status_data;
		}
	}

	public function getMarriageStatusDescriptions($marriage_status_id) {
		$marriage_status_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "marriage_status WHERE marriage_status_id = '" . (int)$marriage_status_id . "'");

		foreach ($query->rows as $result) {
			$marriage_status_data[$result['language_id']] = array(
				'name' => $result['name'],
				'code' => $result['code']
			);
		}

		return $marriage_status_data;
	}

	public function getTotalMarriageStatuses() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "marriage_status WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row['total'];
	}
}

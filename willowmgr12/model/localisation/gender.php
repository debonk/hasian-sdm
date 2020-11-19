<?php
class ModelLocalisationGender extends Model {
	public function addGender($data) {
		foreach ($data['gender'] as $language_id => $value) {
			if (isset($gender_id)) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "gender SET gender_id = '" . (int)$gender_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', code = '" . $this->db->escape($value['code']) . "'");
			} else {
				$this->db->query("INSERT INTO " . DB_PREFIX . "gender SET language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', code = '" . $this->db->escape($value['code']) . "'");

				$gender_id = $this->db->getLastId();
			}
		}

		$this->cache->delete('gender');
		
		return $gender_id;
	}

	public function editGender($gender_id, $data) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "gender WHERE gender_id = '" . (int)$gender_id . "'");

		foreach ($data['gender'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "gender SET gender_id = '" . (int)$gender_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', code = '" . $this->db->escape($value['code']) . "'");
		}

		$this->cache->delete('gender');
	}

	public function deleteGender($gender_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "gender WHERE gender_id = '" . (int)$gender_id . "'");

		$this->cache->delete('gender');
	}

	public function getGender($gender_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "gender WHERE gender_id = '" . (int)$gender_id . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function getGenders($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "gender WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'";

			$sql .= " ORDER BY name";

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
			$gender_data = $this->cache->get('gender.' . (int)$this->config->get('config_language_id'));

			if (!$gender_data) {
				$query = $this->db->query("SELECT gender_id, name FROM " . DB_PREFIX . "gender WHERE language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY name");

				$gender_data = $query->rows;

				$this->cache->set('gender.' . (int)$this->config->get('config_language_id'), $gender_data);
			}

			return $gender_data;
		}
	}

	public function getGenderDescriptions($gender_id) {
		$gender_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "gender WHERE gender_id = '" . (int)$gender_id . "'");

		foreach ($query->rows as $result) {
			$gender_data[$result['language_id']] = array(
				'name' => $result['name'],
				'code' => $result['code']
			);
		}

		return $gender_data;
	}

	public function getTotalGenders() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "gender WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row['total'];
	}
}

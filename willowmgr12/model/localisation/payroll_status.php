<?php
class ModelLocalisationPayrollStatus extends Model {
//End List
	public function addPayrollStatus($data) {
		foreach ($data['payroll_status'] as $language_id => $value) {
			if (isset($payroll_status_id)) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "payroll_status SET payroll_status_id = '" . (int)$payroll_status_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
			} else {
				$this->db->query("INSERT INTO " . DB_PREFIX . "payroll_status SET language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");

				$payroll_status_id = $this->db->getLastId();
			}
		}

		$this->cache->delete('payroll_status');
		
		return $payroll_status_id;
	}

	public function editPayrollStatus($payroll_status_id, $data) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "payroll_status WHERE payroll_status_id = '" . (int)$payroll_status_id . "'");

		foreach ($data['payroll_status'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "payroll_status SET payroll_status_id = '" . (int)$payroll_status_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
		}

		$this->cache->delete('payroll_status');
	}

	public function deletePayrollStatus($payroll_status_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "payroll_status WHERE payroll_status_id = '" . (int)$payroll_status_id . "'");

		$this->cache->delete('payroll_status');
	}

	public function getPayrollStatus($payroll_status_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "payroll_status WHERE payroll_status_id = '" . (int)$payroll_status_id . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function getPayrollStatuses($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "payroll_status WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'";

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
			$payroll_status_data = $this->cache->get('payroll_status.' . (int)$this->config->get('config_language_id'));

			if (!$payroll_status_data) {
				$query = $this->db->query("SELECT payroll_status_id, name FROM " . DB_PREFIX . "payroll_status WHERE language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY name");

				$payroll_status_data = $query->rows;

				$this->cache->set('payroll_status.' . (int)$this->config->get('config_language_id'), $payroll_status_data);
			}

			return $payroll_status_data;
		}
	}

	public function getPayrollStatusDescriptions($payroll_status_id) {
		$payroll_status_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "payroll_status WHERE payroll_status_id = '" . (int)$payroll_status_id . "'");

		foreach ($query->rows as $result) {
			$payroll_status_data[$result['language_id']] = array('name' => $result['name']);
		}

		return $payroll_status_data;
	}

	public function getTotalPayrollStatuses() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "payroll_status WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row['total'];
	}
}

<?php
class ModelLocalisationPayrollMethod extends Model {
	public function getPayrollMethod($payroll_method_id) { //Used by payroll_release
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "payroll_method WHERE payroll_method_id = '" . (int)$payroll_method_id . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function addPayrollMethod($data) {
		foreach ($data['payroll_method'] as $language_id => $value) {
			if (isset($payroll_method_id)) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "payroll_method SET payroll_method_id = '" . (int)$payroll_method_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', code = '" . $this->db->escape(strtolower($data['code'])) . "'");
			} else {
				$this->db->query("INSERT INTO " . DB_PREFIX . "payroll_method SET language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', code = '" . $this->db->escape(strtolower($data['code'])) . "'");

				$payroll_method_id = $this->db->getLastId();
			}
		}

		$this->cache->delete('payroll_method');
		
		return $payroll_method_id;
	}

	public function editPayrollMethod($payroll_method_id, $data) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "payroll_method WHERE payroll_method_id = '" . (int)$payroll_method_id . "'");

		foreach ($data['payroll_method'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "payroll_method SET payroll_method_id = '" . (int)$payroll_method_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', code = '" . $this->db->escape(strtolower($data['code'])) . "'");
		}

		$this->cache->delete('payroll_method');
	}

	public function deletePayrollMethod($payroll_method_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "payroll_method WHERE payroll_method_id = '" . (int)$payroll_method_id . "'");

		$this->cache->delete('payroll_method');
	}

	public function getPayrollMethods($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "payroll_method WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'";

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
			$payroll_method_data = $this->cache->get('payroll_method.' . (int)$this->config->get('config_language_id'));

			if (!$payroll_method_data) {
				$query = $this->db->query("SELECT payroll_method_id, code, name FROM " . DB_PREFIX . "payroll_method WHERE language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY name");

				$payroll_method_data = $query->rows;

				$this->cache->set('payroll_method.' . (int)$this->config->get('config_language_id'), $payroll_method_data);
			}

			return $payroll_method_data;
		}
	}

	public function getPayrollMethodDescriptions($payroll_method_id) {
		$payroll_method_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "payroll_method WHERE payroll_method_id = '" . (int)$payroll_method_id . "'");

		foreach ($query->rows as $result) {
			$payroll_method_data[$result['language_id']] = array('name' => $result['name']);
		}

		return $payroll_method_data;
	}

	public function getTotalPayrollMethods() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "payroll_method WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row['total'];
	}
}

<?php
class ModelCustomerCustomerDepartment extends Model {
	public function addCustomerDepartment($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "customer_department SET sort_order = '" . (int)$data['sort_order'] . "'");

		$customer_department_id = $this->db->getLastId();

		foreach ($data['customer_department_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "customer_department_description SET customer_department_id = '" . (int)$customer_department_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "'");
		}
		
		return $customer_department_id;
	}

	public function editCustomerDepartment($customer_department_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "customer_department SET sort_order = '" . (int)$data['sort_order'] . "' WHERE customer_department_id = '" . (int)$customer_department_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "customer_department_description WHERE customer_department_id = '" . (int)$customer_department_id . "'");

		foreach ($data['customer_department_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "customer_department_description SET customer_department_id = '" . (int)$customer_department_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "'");
		}
	}

	public function deleteCustomerDepartment($customer_department_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "customer_department WHERE customer_department_id = '" . (int)$customer_department_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "customer_department_description WHERE customer_department_id = '" . (int)$customer_department_id . "'");
	}

	public function getCustomerDepartment($customer_department_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "customer_department cd LEFT JOIN " . DB_PREFIX . "customer_department_description cdd ON (cd.customer_department_id = cdd.customer_department_id) WHERE cd.customer_department_id = '" . (int)$customer_department_id . "' AND cdd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function getCustomerDepartments($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "customer_department cd LEFT JOIN " . DB_PREFIX . "customer_department_description cdd ON (cd.customer_department_id = cdd.customer_department_id) WHERE cdd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		$sort_data = array(
			'cdd.name',
			'cd.sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY cd.sort_order ASC, cdd.name";
		}

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
	}

	public function getCustomerDepartmentDescriptions($customer_department_id) {
		$customer_department_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_department_description WHERE customer_department_id = '" . (int)$customer_department_id . "'");

		foreach ($query->rows as $result) {
			$customer_department_data[$result['language_id']] = array(
				'name'        => $result['name'],
				'description' => $result['description']
			);
		}

		return $customer_department_data;
	}

	public function getCustomerDepartmentsCount() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer_department");

		return $query->row['total'];
	}
}

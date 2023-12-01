<?php
class ModelCustomerContractType extends Model {
	public function addContractType($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "contract_type SET name = '" . $this->db->escape($data['name']) . "', description = '" . $this->db->escape($data['description']) . "', duration = '" . (int)$data['duration'] . "', sort_order = '" . (int)$data['sort_order'] . "', status = '" . (int)$data['status'] . "'");
	}

	public function editContractType($contract_type_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "contract_type SET name = '" . $this->db->escape($data['name']) . "', description = '" . $this->db->escape($data['description']) . "', duration = '" . (int)$data['duration'] . "', sort_order = '" . (int)$data['sort_order'] . "', status = '" . (int)$data['status'] . "' WHERE contract_type_id = '" . (int)$contract_type_id . "'");
	}

	public function deleteContractType($contract_type_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "contract_type WHERE contract_type_id = '" . (int)$contract_type_id . "'");
	}

	public function getContractType($contract_type_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "contract_type WHERE contract_type_id = '" . (int)$contract_type_id . "'");

		return $query->row;
	}

	public function getContractTypes($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "contract_type";

		$sort_data = array(
			'name',
			'sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY sort_order";
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

	public function getContractTypesCount() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "contract_type");

		return $query->row['total'];
	}
}

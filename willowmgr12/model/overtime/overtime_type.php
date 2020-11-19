<?php
class ModelOvertimeOvertimeType extends Model {
	public function addOvertimeType($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "overtime_type SET name = '" . $this->db->escape($data['name']) . "', description = '" . $this->db->escape($data['description']) . "', wage = '" . (int)$data['wage'] . "', duration = '" . (int)$data['duration'] . "', sort_order = '" . (int)$data['sort_order'] . "'");
	}

	public function editOvertimeType($overtime_type_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "overtime_type SET name = '" . $this->db->escape($data['name']) . "', description = '" . $this->db->escape($data['description']) . "', wage = '" . (int)$data['wage'] . "', duration = '" . (int)$data['duration'] . "', sort_order = '" . (int)$data['sort_order'] . "' WHERE overtime_type_id = '" . (int)$overtime_type_id . "'");
	}

	public function deleteOvertimeType($overtime_type_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "overtime_type WHERE overtime_type_id = '" . (int)$overtime_type_id . "'");
	}

	public function getOvertimeType($overtime_type_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "overtime_type WHERE overtime_type_id = '" . (int)$overtime_type_id . "'");

		return $query->row;
	}

	public function getOvertimeTypes($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "overtime_type";

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

	public function getOvertimeTypesCount() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "overtime_type");

		return $query->row['total'];
	}
}

<?php
class ModelCustomerDocumentType extends Model {
	public function addDocumentType($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "document_type SET title = '" . $this->db->escape($data['title']) . "', description = '" . $this->db->escape($data['description']) . "', required = '" . (int)$data['required'] . "', status = '" . (int)$data['status'] . "',sort_order = '" . (int)$data['sort_order'] . "'");
	}

	public function editDocumentType($document_type_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "document_type SET title = '" . $this->db->escape($data['title']) . "', description = '" . $this->db->escape($data['description']) . "', required = '" . (int)$data['required'] . "', status = '" . (int)$data['status'] . "', sort_order = '" . (int)$data['sort_order'] . "' WHERE document_type_id = '" . (int)$document_type_id . "'");
	}

	public function deleteDocumentType($document_type_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "document_type WHERE document_type_id = '" . (int)$document_type_id . "'");
	}

	public function getDocumentType($document_type_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "document_type WHERE document_type_id = '" . (int)$document_type_id . "'");

		return $query->row;
	}

	public function getDocumentTypes($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "document_type";

		$sort_data = array(
			'title',
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

	public function getDocumentTypesCount() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "document_type");

		return $query->row['total'];
	}

	public function getActiveDocumentTypes() { //Used by: document
		$sql = "SELECT * FROM " . DB_PREFIX . "document_type WHERE status = 1 ORDER BY sort_order ASC";

		$query = $this->db->query($sql);
		
		return $query->rows;
	}
}

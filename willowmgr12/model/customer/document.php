<?php
class ModelCustomerDocument extends Model {
	public function addDocument($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "document SET customer_id = '" . (int)$data['customer_id'] . "', document_type_id = '" . (int)$data['document_type_id'] . "', filename = '" . $this->db->escape($data['filename']) . "', mask = '" . $this->db->escape($data['mask']) . "', user_id = '" . (int)$this->user->getId() . "'");
	}

	public function getDocuments() {
		$sql = "SELECT * FROM " . DB_PREFIX . "document";
		
		$query = $this->db->query($sql);
		
		return $query->rows;
	}

	public function deleteDocumentByCustomer($customer_id, $document_type_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "document WHERE customer_id = '" . (int)$customer_id . "' AND document_type_id = '" . (int)$document_type_id . "'");
	}

	public function getDocumentsByCustomer($customer_id, $document_type_id = 0) {
		$sql = "SELECT d.*, u.username  FROM " . DB_PREFIX . "document d LEFT JOIN " . DB_PREFIX . "user u ON (u.user_id = d.user_id) WHERE customer_id = '" . (int)$customer_id . "'";

		if ($document_type_id) {
			$sql .= " AND document_type_id = '" . (int)$document_type_id . "'";
		}
		
		$query = $this->db->query($sql);
		
		return $query->rows;
	}
}

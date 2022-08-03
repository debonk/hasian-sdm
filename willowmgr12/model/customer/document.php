<?php
class ModelCustomerDocument extends Model
{
	public function addDocument($data)
	{
		$this->db->query("INSERT INTO " . DB_PREFIX . "document SET customer_id = '" . (int)$data['customer_id'] . "', document_type_id = '" . (int)$data['document_type_id'] . "', filename = '" . $this->db->escape($data['filename']) . "', mask = '" . $this->db->escape($data['mask']) . "', user_id = '" . (int)$this->user->getId() . "'");
	}

	public function getDocument($document_id)
	{
		$sql = "SELECT DISTINCT * FROM " . DB_PREFIX . "document WHERE document_id = '" . (int)$document_id . "'";

		$query = $this->db->query($sql);

		return $query->row;
	}

	public function getDocuments()
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "document";

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function deleteDocument($document_id)
	{
		$this->db->query("DELETE FROM " . DB_PREFIX . "document WHERE document_id = '" . (int)$document_id . "'");
	}

	public function getDocumentsByCustomer($customer_id, $document_type_id = 0)
	{
		$sql = "SELECT d.*, u.username  FROM " . DB_PREFIX . "document d LEFT JOIN " . DB_PREFIX . "user u ON (u.user_id = d.user_id) WHERE customer_id = '" . (int)$customer_id . "'";

		if ($document_type_id) {
			$sql .= " AND document_type_id = '" . (int)$document_type_id . "'";
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getImage($file_data, $width = 0, $height = 0)
	{
		if (!is_file($file_data['tmp_name'])) {
			return;
		}

		list($width_orig, $height_orig) = getimagesize($file_data['tmp_name']);

		if (($width && $width_orig > $width) || ($height && $height_orig > $height)) {
			$image = new Image($file_data['tmp_name']);
			$image->resize($width, $height);
			$image->save(DIR_DOCUMENT . $file_data['name']);

			rename(DIR_DOCUMENT . $file_data['name'], DIR_DOCUMENT . $file_data['filename']);

			unlink($file_data['tmp_name']);
		} else {
			move_uploaded_file($file_data['tmp_name'], DIR_DOCUMENT . $file_data['filename']);
		}

		if ($this->request->server['HTTPS']) {
			return HTTPS_CATALOG . 'document/' . $file_data['filename'];
		} else {
			return HTTP_CATALOG . 'document/' . $file_data['filename'];
		}
	}
}

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
		$document_data = [];

		$sql = "SELECT * FROM " . DB_PREFIX . "v_document";

		$query = $this->db->query($sql);

		foreach ($query->rows as $document) {
			$document_data[$document['customer_id']][$document['document_type_id']] = $document;
		}

		return $document_data;
	}

	public function getCustomerDocuments($data = array())
	{
		$sql = "SELECT c.customer_id, nip, name, date_start, c.date_added, customer_department_id, customer_department, customer_group_id, customer_group, location_id, location FROM " . DB_PREFIX . "v_customer c LEFT JOIN " . DB_PREFIX . "v_document d ON d.customer_id = c.customer_id WHERE (language_id = '" . (int)$this->config->get('config_language_id') . "' OR language_id IS NULL)";
		// $sql = "SELECT * FROM " . DB_PREFIX . "v_customer WHERE (language_id = '" . (int)$this->config->get('config_language_id') . "' OR language_id IS NULL)";

		$implode = array();

		if (!empty($data['filter']['name'])) {
			$implode[] = "c.name LIKE '%" . $this->db->escape($data['filter']['name']) . "%'";
		}

		if (!empty($data['filter']['customer_department_id'])) {
			$implode[] = "c.customer_department_id = '" . (int)$data['filter']['customer_department_id'] . "'";
		}

		if (!empty($data['filter']['customer_group_id'])) {
			$implode[] = "c.customer_group_id = '" . (int)$data['filter']['customer_group_id'] . "'";
		}

		if (!empty($data['filter']['location_id'])) {
			$implode[] = "c.location_id = '" . (int)$data['filter']['location_id'] . "'";
		}

		if (isset($data['filter']['active']) && $data['filter']['active'] != '*') {
			if ($data['filter']['active'] == 1) {
				$implode[] = "(date_end IS NULL OR date_end >= CURDATE())";
			} else {
				$implode[] = "date_end < CURDATE()";
			}
		}

		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}

		$sql .= " GROUP BY c.customer_id ";

		if (!empty($data['filter']['requirement'])) {
			$requirement_query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "document_type WHERE required = 1");
			$requirement_count = $requirement_query->row['total'];

			$sql_null = " OR SUM(d.required) IS NULL";

			if ($data['filter']['requirement'] == 1) {
				$sql .= " HAVING (SUM(d.required) = '" . (int)$requirement_count . "'" . (!$requirement_count ? $sql_null : '') . ")";
			} else {
				$sql .= " HAVING (SUM(d.required) < '" . (int)$requirement_count . "'" . ($requirement_count ? $sql_null : '') . ")";
			}
		}

		$sort_data = array(
			'nip',
			'name',
			'customer_group',
			'customer_department',
			'location'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY name";
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
				$data['limit'] = 40;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getCustomerDocumentsCount($data = array())
	{
		$sql = "SELECT 1 FROM " . DB_PREFIX . "v_customer c LEFT JOIN " . DB_PREFIX . "v_document d ON d.customer_id = c.customer_id WHERE (language_id = '" . (int)$this->config->get('config_language_id') . "' OR language_id IS NULL)";

		$implode = array();

		if (!empty($data['filter']['name'])) {
			$implode[] = "c.name LIKE '%" . $this->db->escape($data['filter']['name']) . "%'";
		}

		if (!empty($data['filter']['customer_department_id'])) {
			$implode[] = "c.customer_department_id = '" . (int)$data['filter']['customer_department_id'] . "'";
		}

		if (!empty($data['filter']['customer_group_id'])) {
			$implode[] = "c.customer_group_id = '" . (int)$data['filter']['customer_group_id'] . "'";
		}

		if (!empty($data['filter']['location_id'])) {
			$implode[] = "c.location_id = '" . (int)$data['filter']['location_id'] . "'";
		}

		if (isset($data['filter']['active']) && $data['filter']['active'] != '*') {
			if ($data['filter']['active'] == 1) {
				$implode[] = "(date_end IS NULL OR date_end >= CURDATE())";
			} else {
				$implode[] = "date_end < CURDATE()";
			}
		}

		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}

		$sql .= " GROUP BY c.customer_id";

		if (!empty($data['filter']['requirement'])) {
			$requirement_query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "document_type WHERE required = 1");
			$requirement_count = $requirement_query->row['total'];

			$sql_null = " OR SUM(d.required) IS NULL";

			if ($data['filter']['requirement'] == 1) {
				$sql .= " HAVING (SUM(d.required) = '" . (int)$requirement_count . "'" . (!$requirement_count ? $sql_null : '') . ")";
			} else {
				$sql .= " HAVING (SUM(d.required) < '" . (int)$requirement_count . "'" . ($requirement_count ? $sql_null : '') . ")";
			}
		}

		$sql_count = "SELECT COUNT(*) AS total FROM (" . $sql . ") counter";

		$query = $this->db->query($sql_count);

		return $query->row['total'];
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

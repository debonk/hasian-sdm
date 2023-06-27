<?php
class ModelCustomerPresenceMethod extends Model
{
	private $presence_types_data = ['finger', 'gps', 'qr'];

	public function addPresenceMethod($data)
	{
		$this->db->query("INSERT INTO " . DB_PREFIX . "presence_method SET customer_id = '" . (int)$data['customer_id'] . "', presence_method_type_id = '" . (int)$data['presence_method_type_id'] . "', filename = '" . $this->db->escape($data['filename']) . "', mask = '" . $this->db->escape($data['mask']) . "', user_id = '" . (int)$this->user->getId() . "'");
	}

	public function getPresenceMethod($presence_method_id)
	{
		$sql = "SELECT DISTINCT * FROM " . DB_PREFIX . "presence_method WHERE presence_method_id = '" . (int)$presence_method_id . "'";

		$query = $this->db->query($sql);

		return $query->row;
	}

	public function getPresenceMethods()
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "presence_method";

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function deletePresenceMethod($presence_method_id)
	{
		$this->db->query("DELETE FROM " . DB_PREFIX . "presence_method WHERE presence_method_id = '" . (int)$presence_method_id . "'");
	}

	public function getPresenceMethodsByCustomer($customer_id, $presence_method_type_id = 0)
	{
		$sql = "SELECT d.*, u.username  FROM " . DB_PREFIX . "presence_method d LEFT JOIN " . DB_PREFIX . "user u ON (u.user_id = d.user_id) WHERE customer_id = '" . (int)$customer_id . "'";

		if ($presence_method_type_id) {
			$sql .= " AND presence_method_type_id = '" . (int)$presence_method_type_id . "'";
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getPresenceTypes()
	{
		return $this->presence_types_data;
	}
}

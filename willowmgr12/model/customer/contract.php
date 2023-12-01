<?php
class ModelCustomerContract extends Model
{
	private $contract_statuses = ['none', 'active', 'permanent', 'end_soon', 'end_today', 'expired', 'inactive'];

	public function AddContract($customer_id, $data)
	{
		if (empty($data['contract_end'])) {
			$contract_end = 'NULL';
			$end_reason = 'NULL';
		} else {
			$contract_end = "'" . date('Y-m-d', strtotime($this->db->escape($data['contract_end']))) . "'";
			$end_reason = "'" . $this->db->escape($this->language->get('text_contract_expired')) . "'";
		}

		$sql = "INSERT INTO " . DB_PREFIX . "contract SET customer_id = '" . (int)$customer_id . "', contract_type_id = '" . (int)$data['contract_type_id'] . "', contract_start = STR_TO_DATE('" . $this->db->escape($data['contract_start']) . "', '%e %b %Y'), contract_end = " . $contract_end . ", description = '" . $this->db->escape($data['description']) . "', end_reason = " . $end_reason . ", user_id = '" . (int)$this->user->getId() . "'";

		$this->db->query($sql);

		$contract_id = $this->db->getLastId();

		$this->db->query("UPDATE " . DB_PREFIX . "customer SET contract_id = '" . (int)$contract_id . "', date_end = " . $contract_end . " WHERE customer_id = '" . (int)$customer_id . "'");
		// $this->db->query("UPDATE " . DB_PREFIX . "customer c LEFT JOIN " . DB_PREFIX . "customer_add_data cad ON cad.customer_id = c.customer_id SET c.contract_id = '" . (int)$contract_id . "', c.date_end = " . $contract_end . ", cad.end_reason = " . $end_reason . " WHERE c.customer_id = '" . (int)$customer_id . "'");
	}

	public function deleteContract($customer_id)
	{
		$deleted_contract = $this->getCustomerContract($customer_id);

		$this->db->query("DELETE FROM " . DB_PREFIX . "contract WHERE contract_id = '" . (int)$deleted_contract['contract_id'] . "'");

		$contract_info = $this->db->query("SELECT * FROM " . DB_PREFIX . "contract WHERE customer_id = '" . (int)$customer_id . "' ORDER BY contract_start DESC, contract_id DESC LIMIT 1");

		if ($contract_info->row) {
			if (empty($contract_info->row['contract_end'])) {
				$contract_end = 'NULL';
			} else {
				$contract_end = "'" . date('Y-m-d', strtotime($this->db->escape($contract_info->row['contract_end']))) . "'";
			}

			$this->db->query("UPDATE " . DB_PREFIX . "customer SET contract_id = '" . (int)$contract_info->row['contract_id'] . "', date_end = " . $contract_end . " WHERE customer_id = '" . (int)$customer_id . "'");
		} else {
			$this->db->query("UPDATE " . DB_PREFIX . "customer SET contract_id = NULL, date_end = NULL WHERE customer_id = '" . (int)$customer_id . "'");
		}
	}

	public function endContract($customer_id, $data)
	{
		$sql = "INSERT INTO " . DB_PREFIX . "contract SET customer_id = '" . (int)$customer_id . "', contract_type_id = 0, contract_end = STR_TO_DATE('" . $this->db->escape($data['date_end']) . "', '%e %b %Y'), end_reason = '" . $this->db->escape($data['end_reason']) . "', user_id = '" . (int)$this->user->getId() . "'";

		$this->db->query($sql);

		$contract_id = $this->db->getLastId();

		$this->db->query("UPDATE " . DB_PREFIX . "customer SET contract_id = '" . (int)$contract_id . "', date_end = STR_TO_DATE('" . $this->db->escape($data['date_end']) . "', '%e %b %Y') WHERE customer_id = '" . (int)$customer_id . "'");
	}

	// public function getContract($contract_id)
	// {
	// 	$sql = "SELECT DISTINCT * FROM " . DB_PREFIX . "contract WHERE contract_id = '" . (int)$contract_id . "'";

	// 	$query = $this->db->query($sql);

	// 	return $query->row;
	// }

	public function getCustomerContract($customer_id)
	{
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "v_contract WHERE customer_id = '" . (int)$customer_id . "'");

		return $query->row;
	}

	public function getCustomerContracts($data = array())
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "v_contract WHERE (language_id = '" . (int)$this->config->get('config_language_id') . "' OR language_id IS NULL)";

		$implode = array();

		if (!empty($data['filter']['name'])) {
			$implode[] = "name LIKE '%" . $this->db->escape($data['filter']['name']) . "%'";
		}

		if (!empty($data['filter']['customer_department_id'])) {
			$implode[] = "customer_department_id = '" . (int)$data['filter']['customer_department_id'] . "'";
		}

		if (!empty($data['filter']['customer_group_id'])) {
			$implode[] = "customer_group_id = '" . (int)$data['filter']['customer_group_id'] . "'";
		}

		if (!empty($data['filter']['location_id'])) {
			$implode[] = "location_id = '" . (int)$data['filter']['location_id'] . "'";
		}

		if (!empty($data['filter']['contract_status'])) {
			$implode[] = "contract_status = '" . $this->db->escape($data['filter']['contract_status']) . "'";
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

		$sort_data = array(
			'nip',
			'name',
			'customer_group',
			'customer_department',
			'location',
			'duration',
			'contract_type',
			'contract_start',
			'contract_end',
			'contract_status'
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

	public function getCustomerContractsCount($data = array())
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "v_contract WHERE (language_id = '" . (int)$this->config->get('config_language_id') . "' OR language_id IS NULL)";

		$implode = array();

		if (!empty($data['filter']['name'])) {
			$implode[] = "name LIKE '%" . $this->db->escape($data['filter']['name']) . "%'";
		}

		if (!empty($data['filter']['customer_department_id'])) {
			$implode[] = "customer_department_id = '" . (int)$data['filter']['customer_department_id'] . "'";
		}

		if (!empty($data['filter']['customer_group_id'])) {
			$implode[] = "customer_group_id = '" . (int)$data['filter']['customer_group_id'] . "'";
		}

		if (!empty($data['filter']['location_id'])) {
			$implode[] = "location_id = '" . (int)$data['filter']['location_id'] . "'";
		}

		if (!empty($data['filter']['contract_status'])) {
			$implode[] = "contract_status = '" . $this->db->escape($data['filter']['contract_status']) . "'";
		}

		if (isset($data['filter']['active']) && $data['filter']['active'] != '*') {
			if ($data['filter']['active'] == 1) {
				$implode[] = "(date_end IS NULL OR date_end = '0000-00-00' OR date_end > CURDATE())";
			} else {
				$implode[] = "(date_end <> '0000-00-00' AND date_end <= CURDATE())";
			}
		}

		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getCustomerContractSummaries()
	{
		$sql = "SELECT contract_status, COUNT(*) AS total FROM " . DB_PREFIX . "v_contract WHERE (language_id = '" . (int)$this->config->get('config_language_id') . "' OR language_id IS NULL) GROUP BY contract_status";

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getContractHistories($customer_id, $start = 0, $limit = 10)
	{
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 10;
		}

		$query = $this->db->query("SELECT cn.*, ct.name AS contract_type, ct.duration, u.username FROM " . DB_PREFIX . "contract cn LEFT JOIN " . DB_PREFIX . "contract_type ct ON (ct.contract_type_id = cn.contract_type_id) LEFT JOIN " . DB_PREFIX . "user u ON (u.user_id = cn.user_id) WHERE customer_id = '" . (int)$customer_id . "' ORDER BY contract_id DESC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}

	public function getTotalContractHistories($customer_id)
	{
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "contract WHERE customer_id = '" . (int)$customer_id . "'");

		return $query->row['total'];
	}

	public function getContractStatuses()
	{
		$contract_status_data = [];

		foreach ($this->contract_statuses as $contract_status) {
			$contract_status_data[] = [
				'value'	=> $contract_status,
				'text'	=> $this->language->get('text_contract_' . $contract_status)
			];
		}

		return $contract_status_data;
	}
}

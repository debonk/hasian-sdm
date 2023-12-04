<?php
class ModelCutoffCutoff extends Model
{
	public function addCutoff($data)
	{
		$this->db->query("INSERT INTO " . DB_PREFIX . "cutoff SET customer_id = '" . (int)$data['customer_id'] . "', date = STR_TO_DATE('" . $this->db->escape($data['date']) . "', '%e %b %Y'), description = '" . $this->db->escape($data['description']) . "', principle = '" . $this->db->escape($data['principle']) . "', business_name = '" . $this->db->escape($data['business_name']) . "', amount = '" . (int)$data['amount'] . "', user_id = '" . (int)$this->user->getId() . "'");
	}

	public function editCutoff($cutoff_id, $data)
	{
		$this->db->query("UPDATE " . DB_PREFIX . "cutoff SET date = STR_TO_DATE('" . $this->db->escape($data['date']) . "', '%e %b %Y'), description = '" . $this->db->escape($data['description']) . "', principle = '" . $this->db->escape($data['principle']) . "', business_name = '" . $this->db->escape($data['business_name']) . "', amount = '" . (int)$data['amount'] . "', user_id = '" . (int)$this->user->getId() . "' WHERE cutoff_id = '" . (int)$cutoff_id . "'");
	}

	public function deleteCutoff($cutoff_id)
	{
		$this->db->query("DELETE FROM " . DB_PREFIX . "cutoff WHERE cutoff_id = '" . (int)$cutoff_id . "'");
	}

	public function getCutoff($cutoff_id)
	{
		$sql = "SELECT DISTINCT * FROM " . DB_PREFIX . "v_cutoff WHERE cutoff_id = '" . (int)$cutoff_id . "'";

		$query = $this->db->query($sql);

		return $query->row;
	}

	public function getCutoffs($data = array())
	{
		// $sql = "SELECT co.*, CONCAT(c.firstname, ' [', c.lastname, ']') AS name, username, pcv.presence_period_id, pp.period FROM " . DB_PREFIX . "cutoff co LEFT JOIN " . DB_PREFIX . "customer c ON (c.customer_id = co.customer_id) LEFT JOIN " . DB_PREFIX . "user u ON (u.user_id = co.user_id) LEFT JOIN " . DB_PREFIX . "payroll_component_value pcv ON (pcv.item = co.cutoff_id AND pcv.code = 'cutoff') LEFT JOIN " . DB_PREFIX . "presence_period pp ON (pp.presence_period_id = pcv.presence_period_id)";
		$sql = "SELECT * FROM " . DB_PREFIX . "v_cutoff WHERE (language_id = '" . (int)$this->config->get('config_language_id') . "' OR language_id IS NULL)";

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

		if (!empty($data['filter']['period'])) {
			$this->load->model('common/payroll');
			$date = date_create_from_format('d M Y', '01 ' . $data['filter']['period']);

			$period_info = $this->model_common_payroll->getPeriodByDate(date_format($date, 'Y-m-d'));

			if ($period_info) {
				$implode[] = "date >= '" . $this->db->escape($period_info['date_start']) . "' AND date <= '" . $this->db->escape($period_info['date_end']) . "'";
			}
		}

		if (!empty($data['filter']['description'])) {
			$implode[] = "description LIKE '%" . $this->db->escape($data['filter']['description']) . "%'";
		}

		if (isset($data['filter']['status']) && !is_null($data['filter']['status'])) {
			if (!empty($data['filter']['status'])) {
				$implode[] = "presence_period_id > 0";
			}
		} else {
			$implode[] = "presence_period_id IS NULL";
		}

		if (!empty($data['customer_id'])) {
			$implode[] = "customer_id = '" . (int)$data['customer_id'] . "'";
		}

		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}

		$sort_data = array(
			'date',
			'name',
			'customer_group',
			'customer_department',
			'location',
			'period',
			'principle',
			'business_name',
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY date";
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

	public function getCutoffsCount($data = array())
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "v_cutoff WHERE (language_id = '" . (int)$this->config->get('config_language_id') . "' OR language_id IS NULL)";

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

		if (!empty($data['filter']['period'])) {
			$this->load->model('common/payroll');
			$date = date_create_from_format('d M Y', '01 ' . $data['filter']['period']);

			$period_info = $this->model_common_payroll->getPeriodByDate(date_format($date, 'Y-m-d'));

			if ($period_info) {
				$implode[] = "date >= '" . $this->db->escape($period_info['date_start']) . "' AND date <= '" . $this->db->escape($period_info['date_end']) . "'";
			}
		}

		if (!empty($data['filter']['description'])) {
			$implode[] = "description LIKE '%" . $this->db->escape($data['filter']['description']) . "%'";
		}

		if (isset($data['filter']['status']) && !is_null($data['filter']['status'])) {
			if (!empty($data['filter']['status'])) {
				$implode[] = "presence_period_id > 0";
			}
		} else {
			$implode[] = "presence_period_id IS NULL";
		}

		if (!empty($data['customer_id'])) {
			$implode[] = "customer_id = '" . (int)$data['customer_id'] . "'";
		}

		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getCutoffsTotal($data = array())
	{
		$sql = "SELECT SUM(amount) AS total FROM " . DB_PREFIX . "v_cutoff WHERE (language_id = '" . (int)$this->config->get('config_language_id') . "' OR language_id IS NULL)";

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

		if (!empty($data['filter']['period'])) {
			$this->load->model('common/payroll');
			$date = date_create_from_format('d M Y', '01 ' . $data['filter']['period']);

			$period_info = $this->model_common_payroll->getPeriodByDate(date_format($date, 'Y-m-d'));

			if ($period_info) {
				$implode[] = "date >= '" . $this->db->escape($period_info['date_start']) . "' AND date <= '" . $this->db->escape($period_info['date_end']) . "'";
			}
		}

		if (!empty($data['filter']['description'])) {
			$implode[] = "description LIKE '%" . $this->db->escape($data['filter']['description']) . "%'";
		}

		if (isset($data['filter']['status']) && !is_null($data['filter']['status'])) {
			if (!empty($data['filter']['status'])) {
				$implode[] = "presence_period_id > 0";
			}
		} else {
			$implode[] = "presence_period_id IS NULL";
		}

		if (!empty($data['customer_id'])) {
			$implode[] = "customer_id = '" . (int)$data['customer_id'] . "'";
		}

		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	// public function getCutoffPaidStatus($cutoff_id)
	// {
	// 	$sql = "SELECT presence_period_id FROM " . DB_PREFIX . "payroll_component_value WHERE code = 'cutoff' AND item = '" . (int)$cutoff_id . "'";

	// 	$query = $this->db->query($sql);

	// 	return $query->row;
	// }
}

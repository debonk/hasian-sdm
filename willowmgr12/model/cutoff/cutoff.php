<?php
class ModelCutoffCutoff extends Model {
	public function addCutoff($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "cutoff SET customer_id = '" . (int)$data['customer_id'] . "', date = STR_TO_DATE('" . $this->db->escape($data['date']) . "', '%e %b %Y'), inv_no = '" . $this->db->escape($data['inv_no']) . "', principle = '" . $this->db->escape($data['principle']) . "', business_name = '" . $this->db->escape($data['business_name']) . "', amount = '" . (int)$data['amount'] . "', user_id = '" . (int)$this->user->getId() . "', date_added = NOW()");
	}

	public function editCutoff($cutoff_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "cutoff SET cutoff_id = '" . (int)$cutoff_id . "', date = STR_TO_DATE('" . $this->db->escape($data['date']) . "', '%e %b %Y'), inv_no = '" . $this->db->escape($data['inv_no']) . "', principle = '" . $this->db->escape($data['principle']) . "', business_name = '" . $this->db->escape($data['business_name']) . "', amount = '" . (int)$data['amount'] . "' WHERE cutoff_id = '" . (int)$cutoff_id . "'");
	}

	public function deleteCutoff($cutoff_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "cutoff WHERE cutoff_id = '" . (int)$cutoff_id . "'");
	}

	public function getCutoff($cutoff_id) {
		$sql = "SELECT DISTINCT * FROM " . DB_PREFIX . "cutoff WHERE cutoff_id = '" . (int)$cutoff_id . "'";

		$query = $this->db->query($sql);

		return $query->row;
	}

	public function getCutoffs($data = array()) {
		$sql = "SELECT co.*, CONCAT(c.firstname, ' [', c.lastname, ']') AS name, username, pcv.presence_period_id, pp.period FROM " . DB_PREFIX . "cutoff co LEFT JOIN " . DB_PREFIX . "customer c ON (c.customer_id = co.customer_id) LEFT JOIN " . DB_PREFIX . "user u ON (u.user_id = co.user_id) LEFT JOIN " . DB_PREFIX . "payroll_component_value pcv ON (pcv.item = co.cutoff_id AND pcv.code = 'cutoff') LEFT JOIN " . DB_PREFIX . "presence_period pp ON (pp.presence_period_id = pcv.presence_period_id)";

		$implode = array();

		if (!empty($data['filter_name'])) {
			$implode[] = "CONCAT(c.firstname, ' [', c.lastname, ']') LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_inv_no'])) {
			$implode[] = "co.inv_no LIKE '%" . $this->db->escape($data['filter_inv_no']) . "%'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			if (!empty($data['filter_status'])) {
				$implode[] = "pcv.presence_period_id > 0";
			}
		} else {
			$implode[] = "pcv.presence_period_id IS NULL";
		}

		if (!empty($data['customer_id'])) {
			$implode[] = "co.customer_id = " . (int)$data['customer_id'] . "'";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$sql .= " ORDER BY date DESC";

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 40;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
// print_r($sql);
		$query = $this->db->query($sql);
		
		return $query->rows;
	}

	public function getCutoffsCount($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "cutoff co LEFT JOIN " . DB_PREFIX . "customer c ON (c.customer_id = co.customer_id) LEFT JOIN " . DB_PREFIX . "payroll_component_value pcv ON (pcv.item = co.cutoff_id AND pcv.code = 'cutoff')";

		$implode = array();

		if (!empty($data['filter_name'])) {
			$implode[] = "CONCAT(c.firstname, ' [', c.lastname, ']') LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_inv_no'])) {
			$implode[] = "co.inv_no LIKE '%" . $this->db->escape($data['filter_inv_no']) . "%'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			if (!empty($data['filter_status'])) {
				$implode[] = "pcv.presence_period_id > 0";
			}
		} else {
			$implode[] = "pcv.presence_period_id IS NULL";
		}

		if (!empty($data['customer_id'])) {
			$implode[] = "co.customer_id = " . (int)$data['customer_id'] . "'";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);
		
		return $query->row['total'];
	}

	public function getCutoffsTotal($data = array()) {
		$sql = "SELECT SUM(co.amount) AS total FROM " . DB_PREFIX . "cutoff co LEFT JOIN " . DB_PREFIX . "customer c ON (c.customer_id = co.customer_id) LEFT JOIN " . DB_PREFIX . "payroll_component_value pcv ON (pcv.item = co.cutoff_id AND pcv.code = 'cutoff')";

		$implode = array();

		if (!empty($data['filter_name'])) {
			$implode[] = "CONCAT(c.firstname, ' [', c.lastname, ']') LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_inv_no'])) {
			$implode[] = "co.inv_no LIKE '%" . $this->db->escape($data['filter_inv_no']) . "%'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			if (!empty($data['filter_status'])) {
				$implode[] = "pcv.presence_period_id > 0";
			}
		} else {
			$implode[] = "pcv.presence_period_id IS NULL";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);
		
		return $query->row['total'];
	}

	// public function getCutoffPaidStatus($cutoff_id) {
		// $sql = "SELECT presence_period_id FROM " . DB_PREFIX . "payroll_component_value WHERE code = 'cutoff' AND item = '" . (int)$cutoff_id . "'";

		// $query = $this->db->query($sql);

		// return $query->row;
	// }
}

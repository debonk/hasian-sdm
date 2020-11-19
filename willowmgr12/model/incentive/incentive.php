<?php
class ModelIncentiveIncentive extends Model {
	public function addIncentive($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "incentive SET customer_id = '" . (int)$data['customer_id'] . "', date = STR_TO_DATE('" . $this->db->escape($data['date']) . "', '%e %b %Y'), description = '" . $this->db->escape($data['description']) . "', amount = '" . (int)$data['amount'] . "', user_id = '" . (int)$this->user->getId() . "', date_added = NOW()");
	}

	public function editIncentive($incentive_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "incentive SET date = STR_TO_DATE('" . $this->db->escape($data['date']) . "', '%e %b %Y'), description = '" . $this->db->escape($data['description']) . "', amount = '" . (int)$data['amount'] . "', user_id = '" . (int)$this->user->getId() . "', date_added = NOW() WHERE incentive_id = '" . (int)$incentive_id . "'");
	}

	public function deleteIncentive($incentive_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "incentive WHERE incentive_id = '" . (int)$incentive_id . "'");
	}

	public function getIncentive($incentive_id) {
		$sql = "SELECT DISTINCT * FROM " . DB_PREFIX . "incentive WHERE incentive_id = '" . (int)$incentive_id . "'";

		$query = $this->db->query($sql);

		return $query->row;
	}

	public function getIncentives($data = array()) {
		$sql = "SELECT i.*, CONCAT(c.firstname, ' [', c.lastname, ']') AS name, cgd.name AS customer_group, username, pcv.presence_period_id, pp.period FROM " . DB_PREFIX . "incentive i LEFT JOIN " . DB_PREFIX . "customer c ON (c.customer_id = i.customer_id) LEFT JOIN " . DB_PREFIX . "customer_group_description cgd ON (c.customer_group_id = cgd.customer_group_id) LEFT JOIN " . DB_PREFIX . "user u ON (u.user_id = i.user_id AND cgd.language_id = '" . (int)$this->config->get('config_language_id') . "') LEFT JOIN " . DB_PREFIX . "payroll_component_value pcv ON (pcv.item = i.incentive_id AND pcv.code = 'incentive') LEFT JOIN " . DB_PREFIX . "presence_period pp ON (pp.presence_period_id = pcv.presence_period_id)";

		$implode = array();

		if (!empty($data['filter_name'])) {
			$implode[] = "CONCAT(c.firstname, ' [', c.lastname, ']') LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_description'])) {
			$implode[] = "i.description LIKE '%" . $this->db->escape($data['filter_description']) . "%'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			if (!empty($data['filter_status'])) {
				$implode[] = "pcv.presence_period_id > 0";
			}
		} else {
			$implode[] = "pcv.presence_period_id IS NULL";
		}

		if (!empty($data['customer_id'])) {
			$implode[] = "i.customer_id = " . (int)$data['customer_id'] . "'";
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

		$query = $this->db->query($sql);
		
		return $query->rows;
	}

	public function getIncentivesCount($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "incentive i LEFT JOIN " . DB_PREFIX . "customer c ON (c.customer_id = i.customer_id) LEFT JOIN " . DB_PREFIX . "payroll_component_value pcv ON (pcv.item = i.incentive_id AND pcv.code = 'incentive')";

		$implode = array();

		if (!empty($data['filter_name'])) {
			$implode[] = "CONCAT(c.firstname, ' [', c.lastname, ']') LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_description'])) {
			$implode[] = "i.description LIKE '%" . $this->db->escape($data['filter_description']) . "%'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			if (!empty($data['filter_status'])) {
				$implode[] = "pcv.presence_period_id > 0";
			}
		} else {
			$implode[] = "pcv.presence_period_id IS NULL";
		}

		if (!empty($data['customer_id'])) {
			$implode[] = "i.customer_id = " . (int)$data['customer_id'] . "'";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);
		
		return $query->row['total'];
	}

	public function getIncentivesTotal($data = array()) {
		$sql = "SELECT SUM(i.amount) AS total FROM " . DB_PREFIX . "incentive i LEFT JOIN " . DB_PREFIX . "customer c ON (c.customer_id = i.customer_id) LEFT JOIN " . DB_PREFIX . "payroll_component_value pcv ON (pcv.item = i.incentive_id AND pcv.code = 'incentive')";

		$implode = array();

		if (!empty($data['filter_name'])) {
			$implode[] = "CONCAT(c.firstname, ' [', c.lastname, ']') LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_description'])) {
			$implode[] = "i.description LIKE '%" . $this->db->escape($data['filter_description']) . "%'";
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

	public function getIncentivePaidStatus($incentive_id) {
		$sql = "SELECT presence_period_id FROM " . DB_PREFIX . "payroll_component_value WHERE code = 'incentive' AND item = '" . (int)$incentive_id . "'";

		$query = $this->db->query($sql);

		return $query->row;
	}
}

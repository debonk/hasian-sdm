<?php
class ModelReleaseFreeTransfer extends Model {
	public function addFreeTransfer($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "free_transfer SET description = '" . $this->db->escape($data['description']) . "', date_process = STR_TO_DATE('" . $this->db->escape($data['date_process']) . "', '%e %b %Y'), fund_account_id = '" . (int)$data['fund_account_id'] . "', user_id = '" . (int)$this->user->getId() . "', date_modified = NOW()");

		$free_transfer_id = $this->db->getLastId();

		if (isset($data['free_transfer_customer'])) {
			foreach ($data['free_transfer_customer'] as $value) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "free_transfer_customer SET free_transfer_id = '" . (int)$free_transfer_id . "', customer_id = '" . (int)$value['customer_id'] . "', note = '" . $this->db->escape($value['note']) . "', amount = '" . (int)$value['amount'] . "'");
			}
		}
	}

	public function editFreeTransfer($free_transfer_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "free_transfer SET description = '" . $this->db->escape($data['description']) . "', date_process = STR_TO_DATE('" . $this->db->escape($data['date_process']) . "', '%e %b %Y'), fund_account_id = '" . (int)$data['fund_account_id'] . "', user_id = '" . (int)$this->user->getId() . "', date_modified = NOW() WHERE free_transfer_id = '" . (int)$free_transfer_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "free_transfer_customer WHERE free_transfer_id = '" . (int)$free_transfer_id . "'");

		if (isset($data['free_transfer_customer'])) {
			foreach ($data['free_transfer_customer'] as $value) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "free_transfer_customer SET free_transfer_id = '" . (int)$free_transfer_id . "', customer_id = '" . (int)$value['customer_id'] . "', note = '" . $this->db->escape($value['note']) . "', amount = '" . (int)$value['amount'] . "'");
			}
		}
	}

	public function deleteFreeTransfer($free_transfer_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "free_transfer WHERE free_transfer_id = '" . (int)$free_transfer_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "free_transfer_customer WHERE free_transfer_id = '" . (int)$free_transfer_id . "'");
	}

	public function getFreeTransfer($free_transfer_id) {
		$sql = "SELECT DISTINCT ft.* , (SELECT username FROM " . DB_PREFIX . "user u WHERE u.user_id = ft.user_id) AS username FROM " . DB_PREFIX . "free_transfer ft WHERE ft.free_transfer_id = '" . (int)$free_transfer_id . "'";

		$query = $this->db->query($sql);

		return $query->row;
	}

	public function getFreeTransfers($data = array()) {
		$sql = "SELECT ft.*, fa.bank_name, fa.acc_no, fa.acc_name, COUNT(ftc.amount) as count, SUM(ftc.amount) as total, u.username FROM " . DB_PREFIX . "free_transfer ft LEFT JOIN " . DB_PREFIX . "fund_account fa ON (fa.fund_account_id = ft.fund_account_id) LEFT JOIN " . DB_PREFIX . "user u ON (u.user_id = ft.user_id) LEFT JOIN " . DB_PREFIX . "free_transfer_customer ftc ON (ftc.free_transfer_id = ft.free_transfer_id) GROUP BY ftc.free_transfer_id";

		$sort_data = array(
			'ft.date_process',
			'ft.date_modified'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY ft.date_modified";
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

	public function getFreeTransfersCount($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "free_transfer";

		$query = $this->db->query($sql);
		
		return $query->row['total'];
	}

	public function getFreeTransferCustomers($free_transfer_id) {
		$sql = "SELECT ftc.*, CONCAT(c.firstname, ' [', c.lastname, ']') AS name, cgd.name AS customer_group, l.name AS location, pm.name AS payroll_method FROM " . DB_PREFIX . "free_transfer_customer ftc LEFT JOIN " . DB_PREFIX . "customer c ON (c.customer_id = ftc.customer_id) LEFT JOIN " . DB_PREFIX . "customer_group_description cgd ON (cgd.customer_group_id = c.customer_group_id) LEFT JOIN " . DB_PREFIX . "location l ON (l.location_id = c.location_id) LEFT JOIN " . DB_PREFIX . "payroll_method pm ON (pm.payroll_method_id = c.payroll_method_id) WHERE free_transfer_id = '" . (int)$free_transfer_id . "'";
		
		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getFreeTransferCustomersByMethod($free_transfer_id, $method) {
		$sql = "SELECT DISTINCT ftc.*, c.lastname, c.email, c.acc_no, pm.name AS payroll_method FROM " . DB_PREFIX . "free_transfer_customer ftc LEFT JOIN " . DB_PREFIX . "customer c ON (c.customer_id = ftc.customer_id) LEFT JOIN " . DB_PREFIX . "payroll_method pm ON (pm.payroll_method_id = c.payroll_method_id) WHERE pm.language_id = '" . (int)$this->config->get('config_language_id') . "' AND ftc.free_transfer_id = '" . (int)$free_transfer_id . "'";

		if ($method) {
			$sql .= " AND pm.name = '" . $this->db->escape($method) . "' AND c.acc_no <> ''";
		}

		$sql .= " ORDER BY c.lastname ASC";

		$query = $this->db->query($sql);

		return $query->rows;
	}
	
	public function getFreeTransferCustomerCountByMethod($free_transfer_id, $method) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "free_transfer_customer ftc";

		if ($method) {
			$sql .= " LEFT JOIN " . DB_PREFIX . "customer c ON (c.customer_id = ftc.customer_id) INNER JOIN " . DB_PREFIX . "payroll_method pm ON (pm.payroll_method_id = c.payroll_method_id AND pm.name = '" . $this->db->escape($method) . "' AND c.acc_no <> '')";
		}

		$sql .= " WHERE ftc.free_transfer_id = '" . (int)$free_transfer_id . "'";
		
		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getFreeTransferCustomerTotalByMethod($free_transfer_id, $method) {
		$sql = "SELECT SUM(ftc.amount) AS total FROM " . DB_PREFIX . "free_transfer_customer ftc";

		if ($method) {
			$sql .= " LEFT JOIN " . DB_PREFIX . "customer c ON (c.customer_id = ftc.customer_id) INNER JOIN " . DB_PREFIX . "payroll_method pm ON (pm.payroll_method_id = c.payroll_method_id AND pm.name = '" . $this->db->escape($method) . "' AND c.acc_no <> '')";
		}

		$sql .= " WHERE ftc.free_transfer_id = '" . (int)$free_transfer_id . "'";
		
		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function checkFreeTransferProcessed($free_transfer_id) {
		$sql = "SELECT DISTINCT * FROM " . DB_PREFIX . "free_transfer WHERE free_transfer_id = '" . (int)$free_transfer_id . "' AND date_process < CURDATE()";

		$query = $this->db->query($sql);

		if ($query->row) {
			return true;
		} else {
			return false;
		}
	}
}

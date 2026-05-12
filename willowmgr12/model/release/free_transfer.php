<?php
class ModelReleaseFreeTransfer extends Model {
	public function addFreeTransfer(array $data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "free_transfer SET description = '" . $this->db->escape($data['description']) . "', date_process = STR_TO_DATE('" . $this->db->escape($data['date_process']) . "', '%e %b %Y'), fund_account_id = '" . (int)$data['fund_account_id'] . "', user_id = '" . (int)$this->user->getId() . "', date_modified = NOW()");

		$free_transfer_id = $this->db->getLastId();

		if (isset($data['free_transfer_customer'])) {
			foreach ($data['free_transfer_customer'] as $value) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "free_transfer_customer SET free_transfer_id = '" . (int)$free_transfer_id . "', customer_id = '" . (int)$value['customer_id'] . "', note = '" . $this->db->escape($value['note']) . "', amount = '" . (int)getNumber($value['amount']) . "'");
			}
		}
	}

	public function editFreeTransfer(int $free_transfer_id, array $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "free_transfer SET description = '" . $this->db->escape($data['description']) . "', date_process = STR_TO_DATE('" . $this->db->escape($data['date_process']) . "', '%e %b %Y'), fund_account_id = '" . (int)$data['fund_account_id'] . "', user_id = '" . (int)$this->user->getId() . "', date_modified = NOW() WHERE free_transfer_id = '" . (int)$free_transfer_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "free_transfer_customer WHERE free_transfer_id = '" . (int)$free_transfer_id . "'");

		if (isset($data['free_transfer_customer'])) {
			foreach ($data['free_transfer_customer'] as $value) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "free_transfer_customer SET free_transfer_id = '" . (int)$free_transfer_id . "', customer_id = '" . (int)$value['customer_id'] . "', note = '" . $this->db->escape($value['note']) . "', amount = '" . (int)getNumber($value['amount']) . "'");
			}
		}
	}

	public function editFreeTransferStatus(int $free_transfer_id, bool $status = true) {
		$this->db->query("UPDATE " . DB_PREFIX . "free_transfer SET status_process = '" . (int)$status . "', user_id = '" . (int)$this->user->getId() . "', date_modified = NOW() WHERE free_transfer_id = '" . (int)$free_transfer_id . "'");
	}

	public function deleteFreeTransfer(int $free_transfer_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "free_transfer WHERE free_transfer_id = '" . (int)$free_transfer_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "free_transfer_customer WHERE free_transfer_id = '" . (int)$free_transfer_id . "'");
	}

	public function getFreeTransfer(int $free_transfer_id) {
		$sql = "SELECT DISTINCT ft.*, fa.payroll_method_id, u.username FROM " . DB_PREFIX . "free_transfer ft LEFT JOIN " . DB_PREFIX . "v_fund_account fa ON fa.fund_account_id = ft.fund_account_id LEFT JOIN " . DB_PREFIX . "user u ON u.user_id = ft.user_id WHERE ft.free_transfer_id = '" . (int)$free_transfer_id . "'";

		$query = $this->db->query($sql);

		return $query->row;
	}

	public function getFreeTransfers(array $data = array()) {
		$sql = "SELECT ft.*, fa.bank_name, fa.acc_no, fa.acc_name, COUNT(ftc.amount) as count, SUM(ftc.amount) as total, u.username FROM " . DB_PREFIX . "free_transfer ft LEFT JOIN " . DB_PREFIX . "v_fund_account fa ON (fa.fund_account_id = ft.fund_account_id) LEFT JOIN " . DB_PREFIX . "user u ON (u.user_id = ft.user_id) LEFT JOIN " . DB_PREFIX . "free_transfer_customer ftc ON (ftc.free_transfer_id = ft.free_transfer_id) GROUP BY ftc.free_transfer_id";

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

	public function getFreeTransfersCount() {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "free_transfer";

		$query = $this->db->query($sql);
		
		return $query->row['total'];
	}

	public function getFreeTransferCustomers(int $free_transfer_id) {
		$sql = "SELECT ftc.*, c.name, c.customer_group, c.location, c.email, c.acc_no, pm.name AS payroll_method FROM " . DB_PREFIX . "free_transfer_customer ftc LEFT JOIN " . DB_PREFIX . "v_customer c ON (c.customer_id = ftc.customer_id) LEFT JOIN " . DB_PREFIX . "payroll_method pm ON (pm.payroll_method_id = c.payroll_method_id) WHERE free_transfer_id = '" . (int)$free_transfer_id . "'";
		
		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getFreeTransferCustomersByMethod(int $free_transfer_id, string $method) {
		$sql = "SELECT DISTINCT ftc.*, c.lastname, c.email, c.acc_no, pm.name AS payroll_method FROM " . DB_PREFIX . "free_transfer_customer ftc LEFT JOIN " . DB_PREFIX . "customer c ON (c.customer_id = ftc.customer_id) LEFT JOIN " . DB_PREFIX . "payroll_method pm ON (pm.payroll_method_id = c.payroll_method_id) WHERE pm.language_id = '" . (int)$this->config->get('config_language_id') . "' AND ftc.free_transfer_id = '" . (int)$free_transfer_id . "'";

		if ($method) {
			$sql .= " AND pm.code = '" . $this->db->escape($method) . "' AND c.acc_no <> ''";
		}

		$sql .= " ORDER BY c.lastname ASC";

		$query = $this->db->query($sql);

		return $query->rows;
	}
	
	public function getFreeTransferCustomerCountByMethod(int $free_transfer_id, string $method) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "free_transfer_customer ftc";

		if ($method) {
			$sql .= " LEFT JOIN " . DB_PREFIX . "customer c ON (c.customer_id = ftc.customer_id) INNER JOIN " . DB_PREFIX . "payroll_method pm ON (pm.payroll_method_id = c.payroll_method_id AND pm.code = '" . $this->db->escape($method) . "' AND c.acc_no <> '')";
		}

		$sql .= " WHERE ftc.free_transfer_id = '" . (int)$free_transfer_id . "'";
		
		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getFreeTransferCustomerTotalByMethod(int $free_transfer_id, string $method) {
		$sql = "SELECT SUM(ftc.amount) AS total FROM " . DB_PREFIX . "free_transfer_customer ftc";

		if ($method) {
			$sql .= " LEFT JOIN " . DB_PREFIX . "customer c ON (c.customer_id = ftc.customer_id) INNER JOIN " . DB_PREFIX . "payroll_method pm ON (pm.payroll_method_id = c.payroll_method_id AND pm.code = '" . $this->db->escape($method) . "' AND c.acc_no <> '')";
		}

		$sql .= " WHERE ftc.free_transfer_id = '" . (int)$free_transfer_id . "'";
		
		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function checkFreeTransferProcessed(int $free_transfer_id) {
		$sql = "SELECT 1 FROM " . DB_PREFIX . "free_transfer WHERE free_transfer_id = '" . (int)$free_transfer_id . "' AND status_process = 1";

		$query = $this->db->query($sql);

		if ($query->row) {
			return true;
		} else {
			return false;
		}
	}
}

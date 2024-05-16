<?php
class ModelPayrollPayrollType extends Model {
	public function addPayrollType($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "payroll_type SET description = '" . $this->db->escape($data['description']) . "', date_process = STR_TO_DATE('" . $this->db->escape($data['date_process']) . "', '%e %b %Y'), fund_account_id = '" . (int)$data['fund_account_id'] . "', user_id = '" . (int)$this->user->getId() . "', date_modified = NOW()");

		$payroll_type_id = $this->db->getLastId();

		if (isset($data['payroll_type_component'])) {
			foreach ($data['payroll_type_component'] as $value) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "payroll_type_component SET payroll_type_id = '" . (int)$payroll_type_id . "', customer_id = '" . (int)$value['customer_id'] . "', note = '" . $this->db->escape($value['note']) . "', amount = '" . (int)$value['amount'] . "'");
			}
		}
	}

	public function editPayrollType($payroll_type_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "payroll_type SET description = '" . $this->db->escape($data['description']) . "', date_process = STR_TO_DATE('" . $this->db->escape($data['date_process']) . "', '%e %b %Y'), fund_account_id = '" . (int)$data['fund_account_id'] . "', user_id = '" . (int)$this->user->getId() . "', date_modified = NOW() WHERE payroll_type_id = '" . (int)$payroll_type_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "payroll_type_component WHERE payroll_type_id = '" . (int)$payroll_type_id . "'");

		if (isset($data['payroll_type_component'])) {
			foreach ($data['payroll_type_component'] as $value) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "payroll_type_component SET payroll_type_id = '" . (int)$payroll_type_id . "', customer_id = '" . (int)$value['customer_id'] . "', note = '" . $this->db->escape($value['note']) . "', amount = '" . (int)$value['amount'] . "'");
			}
		}
	}

	public function deletePayrollType($payroll_type_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "payroll_type WHERE payroll_type_id = '" . (int)$payroll_type_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "payroll_type_component WHERE payroll_type_id = '" . (int)$payroll_type_id . "'");
	}

	public function getPayrollType($payroll_type_id) {
		$sql = "SELECT * FROM " . DB_PREFIX . "payroll_type WHERE payroll_type_id = '" . (int)$payroll_type_id . "'";

		$query = $this->db->query($sql);

		return $query->row;
	}

	public function getPayrollTypes($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "payroll_type ft LEFT JOIN " . DB_PREFIX . "fund_account fa ON (fa.fund_account_id = ft.fund_account_id) LEFT JOIN " . DB_PREFIX . "user u ON (u.user_id = ft.user_id) LEFT JOIN " . DB_PREFIX . "payroll_type_component ftc ON (ftc.payroll_type_id = ft.payroll_type_id) GROUP BY ftc.payroll_type_id";

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

	public function getPayrollTypesCount($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "payroll_type";

		$query = $this->db->query($sql);
		
		return $query->row['total'];
	}

	public function getPayrollTypeComponents($payroll_type_id) {
		$sql = "SELECT * FROM " . DB_PREFIX . "payroll_type_component WHERE payroll_type_id = '" . (int)$payroll_type_id . "' ORDER BY sort_order ASC";
		
		$query = $this->db->query($sql);

		$payroll_type_component_data = [
			'addition'		=> [],
			'deduction'		=> []
		];

		foreach ($query->rows as $value) {
			if ($value['direction'] == 1) {
				$payroll_type_component_data['addition'][] = $value;
			} elseif ($value['direction'] == -1) {
				$payroll_type_component_data['deduction'][] = $value;
			}
		}

		return $payroll_type_component_data;
	}

	// public function getPayrollTypeDetail($payroll_type_id = 1) {
	// 	$payroll_type_data = $this->getPayrollType($payroll_type_id);

	// 	if ($payroll_type_data) {
	// 		$payroll_type_data['component'] = $this->getPayrollTypeComponents($payroll_type_id);
	// 	}
		
	// 	return $payroll_type_data;
	// }
	
	public function getPayrollTypeComponentCountByMethod($payroll_type_id, $method) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "payroll_type_component ftc";

		if ($method) {
			$sql .= " LEFT JOIN " . DB_PREFIX . "customer c ON (c.customer_id = ftc.customer_id) LEFT JOIN " . DB_PREFIX . "payroll_method pm ON (pm.payroll_method_id = c.payroll_method_id) AND pm.name = '" . $this->db->escape($method) . "' AND c.acc_no <> ''";
		}

		$sql .= " WHERE ftc.payroll_type_id = '" . (int)$payroll_type_id . "'";
		
		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getPayrollTypeComponentTotalByMethod($payroll_type_id, $method) {
		$sql = "SELECT SUM(ftc.amount) AS total FROM " . DB_PREFIX . "payroll_type_component ftc";

		if ($method) {
			$sql .= " LEFT JOIN " . DB_PREFIX . "customer c ON (c.customer_id = ftc.customer_id) LEFT JOIN " . DB_PREFIX . "payroll_method pm ON (pm.payroll_method_id = c.payroll_method_id) AND pm.name = '" . $this->db->escape($method) . "' AND c.acc_no <> ''";
		}

		$sql .= " WHERE ftc.payroll_type_id = '" . (int)$payroll_type_id . "'";
		
		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function checkPayrollTypeProcessed($payroll_type_id) {
		$sql = "SELECT DISTINCT * FROM " . DB_PREFIX . "payroll_type WHERE payroll_type_id = '" . (int)$payroll_type_id . "' AND date_process < CURDATE()";

		$query = $this->db->query($sql);

		if ($query->row) {
			return true;
		} else {
			return false;
		}
	}
}

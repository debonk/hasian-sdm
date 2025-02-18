<?php
class ModelReleaseAllowance extends Model
{
	public function addAllowance($data)
	{
		$this->db->query("INSERT INTO " . DB_PREFIX . "allowance SET allowance_period = STR_TO_DATE('" . $this->db->escape($data['allowance_period']) . "', '%e %b %Y'), fund_account_id = '" . (int)$data['fund_account_id'] . "', date_process = STR_TO_DATE('" . $this->db->escape($data['date_process']) . "', '%e %b %Y'), user_id = '" . (int)$this->user->getId() . "', date_modified = NOW()");

		$allowance_id = $this->db->getLastId();

		$this->load->model('common/payroll');
		$this->load->model('presence/presence');
		$this->load->model('payroll/payroll_basic');

		$allowance_period = date('Y-m-d', strtotime($this->db->escape($data['allowance_period'])));

		$presence_period = $this->model_common_payroll->getPeriodByDate($allowance_period);

		$allowance_components = $this->config->get('config_components');
		$hke = $this->config->get('payroll_setting_default_hke');

		$filter_data = array(
			'filter_payroll_include' => 1,
			'presence_period_id'	=> $presence_period['presence_period_id']
		);

		$customers = $this->model_presence_presence->getCustomers($filter_data);

		$date_allowance = date_create($allowance_period);

		foreach ($customers as $customer) {
			$payroll_basic_info = $this->model_payroll_payroll_basic->getPayrollBasicByCustomer($customer['customer_id']);

			if ($payroll_basic_info) {
				$amount = 0;

				foreach ($payroll_basic_info as $key => $value) {
					if (in_array($key, $allowance_components)) {
						if ($key == 'uang_makan') {
							$amount += $value * $hke;
						} else {
							$amount += $value;
						}
					}
				}

				$date_start = date_create($customer['date_start']);
				$diff = date_diff($date_start, $date_allowance);

				if ($diff->format('%y')) {
					$portion = 1;
				} elseif ($diff->format('%m') > 2) {
					$portion = $diff->format('%m') / 12;
				} else {
					$portion = 0;
				}

				$amount	= ceil($amount * $portion / 5000) * 5000;
			} else {
				$amount = 0;
			}

			$this->db->query("INSERT INTO " . DB_PREFIX . "allowance_customer SET allowance_id = '" . (int)$allowance_id . "', customer_id = '" . (int)$customer['customer_id'] . "', amount = '" . (int)$amount . "'");
		}

		return $allowance_id;
	}

	public function editAllowance($allowance_id, $data)
	{
		$this->db->query("UPDATE " . DB_PREFIX . "allowance SET date_process = STR_TO_DATE('" . $this->db->escape($data['date_process']) . "', '%e %b %Y'), fund_account_id = '" . (int)$data['fund_account_id'] . "', user_id = '" . (int)$this->user->getId() . "', date_modified = NOW() WHERE allowance_id = '" . (int)$allowance_id . "'");
	}

	public function deleteAllowance($allowance_id)
	{
		$this->db->query("DELETE FROM " . DB_PREFIX . "allowance_customer WHERE allowance_id = '" . (int)$allowance_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "allowance WHERE allowance_id = '" . (int)$allowance_id . "'");
	}

	public function getAllowance($allowance_id)
	{
		$sql = "SELECT DISTINCT a.* , (SELECT username FROM " . DB_PREFIX . "user u WHERE u.user_id = a.user_id) AS username FROM " . DB_PREFIX . "allowance a WHERE a.allowance_id = '" . (int)$allowance_id . "'";

		$query = $this->db->query($sql);

		return $query->row;
	}

	public function getAllowances($data = array())
	{
		$sql = "SELECT a.*, fa.bank_name, fa.acc_no, fa.acc_name, COUNT(ac.amount) as count, SUM(ac.amount) as total, u.username FROM " . DB_PREFIX . "allowance a LEFT JOIN " . DB_PREFIX . "v_fund_account fa ON (fa.fund_account_id = a.fund_account_id) LEFT JOIN " . DB_PREFIX . "user u ON (u.user_id = a.user_id) LEFT JOIN " . DB_PREFIX . "allowance_customer ac ON (ac.allowance_id = a.allowance_id AND ac.amount > 0) GROUP BY ac.allowance_id";

		$sort_data = array(
			'a.allowance_period',
			'a.date_process',
			'a.date_modified'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY a.allowance_period";
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

	public function getAllowancesCount($data = array())
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "allowance";

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function addAllowanceCustomer($allowance_id, $customer_id)
	{
		$allowance_info = $this->getAllowance($allowance_id);

		$this->load->model('common/payroll');
		$customer_info = $this->model_common_payroll->getCustomer($customer_id);

		$this->load->model('payroll/payroll_basic');
		$payroll_basic_info = $this->model_payroll_payroll_basic->getPayrollBasicByCustomer($customer_id);

		$allowance_components = $this->config->get('config_components');

		if ($payroll_basic_info) {
			$amount = 0;

			foreach ($payroll_basic_info as $key => $value) {
				if (in_array($key, $allowance_components)) {
					if ($key == 'uang_makan') {
						$hke = $this->config->get('payroll_setting_default_hke');

						$amount += $value * $hke;
					} else {
						$amount += $value;
					}
				}
			}

			$date_start = date_create($customer_info['date_start']);
			$date_allowance = date_create($allowance_info['allowance_period']);
			$diff = date_diff($date_start, $date_allowance);

			if ($diff->format('%y')) {
				$portion = 1;
			} elseif ($diff->format('%m') > 2) {
				$portion = $diff->format('%m') / 12;
			} else {
				$portion = 0;
			}

			$amount	= ceil($amount * $portion / 5000) * 5000;
		} else {
			$amount = 0;
		}

		$this->db->query("INSERT INTO " . DB_PREFIX . "allowance_customer SET allowance_id = '" . (int)$allowance_id . "', customer_id = '" . (int)$customer_id . "', amount = '" . (int)$amount . "'");
	}

	public function addAllowanceCustomerBak($allowance_id, $customer_id, $amount)
	{
		$this->db->query("INSERT INTO " . DB_PREFIX . "allowance_customer SET allowance_id = '" . (int)$allowance_id . "', customer_id = '" . (int)$customer_id . "', amount = '" . (int)$amount . "'");
	}

	public function editAllowanceCustomer($allowance_id, $customer_id, $amount)
	{
		$this->db->query("UPDATE " . DB_PREFIX . "allowance_customer SET amount = '" . (int)$amount . "' WHERE allowance_id = '" . (int)$allowance_id . "' AND customer_id = '" . (int)$customer_id . "'");
	}

	public function deleteAllowanceCustomer($allowance_id, $customer_id)
	{
		$this->db->query("DELETE FROM " . DB_PREFIX . "allowance_customer WHERE allowance_id = '" . (int)$allowance_id . "' AND customer_id = '" . (int)$customer_id . "'");
	}

	public function getAllowanceCustomers($allowance_id, $data = array())
	{
		$sql = "SELECT DISTINCT ac.*, c.lastname, c.email, c.acc_no, c.date_start, pm.name AS payroll_method, CONCAT(c.firstname, ' [', c.lastname, ']') AS name, cgd.name AS customer_group FROM " . DB_PREFIX . "allowance_customer ac LEFT JOIN " . DB_PREFIX . "customer c ON (c.customer_id = ac.customer_id) LEFT JOIN " . DB_PREFIX . "payroll_method pm ON (pm.payroll_method_id = c.payroll_method_id) LEFT JOIN " . DB_PREFIX . "customer_group_description cgd ON (cgd.customer_group_id = c.customer_group_id) WHERE pm.language_id = '" . (int)$this->config->get('config_language_id') . "' AND ac.allowance_id = '" . (int)$allowance_id . "'";

		$sql .= " ORDER BY c.firstname ASC";

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

	public function getAllowanceCustomersCount($allowance_id)
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "allowance_customer WHERE allowance_id = '" . (int)$allowance_id . "'";

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getAllowanceCustomersByMethod($allowance_id, $method)
	{
		$sql = "SELECT DISTINCT ac.*, c.lastname, c.email, c.acc_no, c.date_start, pm.name AS payroll_method, CONCAT(c.firstname, ' [', c.lastname, ']') AS name, cgd.name AS customer_group FROM " . DB_PREFIX . "allowance_customer ac LEFT JOIN " . DB_PREFIX . "customer c ON (c.customer_id = ac.customer_id) LEFT JOIN " . DB_PREFIX . "payroll_method pm ON (pm.payroll_method_id = c.payroll_method_id) LEFT JOIN " . DB_PREFIX . "customer_group_description cgd ON (cgd.customer_group_id = c.customer_group_id) WHERE pm.language_id = '" . (int)$this->config->get('config_language_id') . "' AND ac.allowance_id = '" . (int)$allowance_id . "' AND ac.amount > 0";

		$sql .= " AND pm.name = '" . $this->db->escape($method) . "' AND c.acc_no <> ''";

		$sql .= " ORDER BY c.firstname ASC";

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getAllowanceCustomerCountByMethod($allowance_id, $method)
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "allowance_customer ac";

		$sql .= " LEFT JOIN " . DB_PREFIX . "customer c ON (c.customer_id = ac.customer_id) LEFT JOIN " . DB_PREFIX . "payroll_method pm ON (pm.payroll_method_id = c.payroll_method_id) AND pm.name = '" . $this->db->escape($method) . "' AND c.acc_no <> ''";

		$sql .= " WHERE ac.allowance_id = '" . (int)$allowance_id . "' AND ac.amount > 0";

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getAllowanceCustomerTotalByMethod($allowance_id, $method)
	{
		$sql = "SELECT SUM(ac.amount) AS total FROM " . DB_PREFIX . "allowance_customer ac";

		$sql .= " LEFT JOIN " . DB_PREFIX . "customer c ON (c.customer_id = ac.customer_id) LEFT JOIN " . DB_PREFIX . "payroll_method pm ON (pm.payroll_method_id = c.payroll_method_id) AND pm.name = '" . $this->db->escape($method) . "' AND c.acc_no <> ''";

		$sql .= " WHERE ac.allowance_id = '" . (int)$allowance_id . "'";

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function checkAllowanceProcessed($allowance_id)
	{
		$sql = "SELECT DISTINCT * FROM " . DB_PREFIX . "allowance WHERE allowance_id = '" . (int)$allowance_id . "' AND date_process < CURDATE()";

		$query = $this->db->query($sql);

		if ($query->row) {
			return true;
		} else {
			return false;
		}
	}

	public function getBlankAllowanceCustomers($allowance_id) {
		$allowance_info = $this->getAllowance($allowance_id);

		$this->load->model('common/payroll');
		$period_info = $this->model_common_payroll->getPeriodByDate($allowance_info['allowance_period']);

		$availability = (int)$this->config->get('config_customer_last');

		$date_end = date($this->language->get('Y-m-d'), strtotime('-' . $availability . ' months', strtotime($period_info['date_start']))); // Customer still available in selection until this month of custoemr's date_end.

		$sql = "SELECT c.customer_id, CONCAT(c.firstname, ' [', c.lastname, ']') AS name, cgd.name AS customer_group, l.name AS location FROM " . DB_PREFIX . "customer c LEFT JOIN (" . DB_PREFIX . "customer_group_description cgd, " . DB_PREFIX . "location l) ON (cgd.customer_group_id = c.customer_group_id AND l.location_id = c.location_id) LEFT JOIN " . DB_PREFIX . "allowance_customer ac ON (ac.customer_id = c.customer_id AND ac.allowance_id = '" . (int)$allowance_id . "') WHERE c.status = 1 AND c.payroll_include = 1 AND c.date_start <= '" . $this->db->escape($period_info['date_end']) . "' AND (date_end IS NULL OR date_end >= '" . $this->db->escape($date_end) . "') AND ac.allowance_id IS NULL";

		$query = $this->db->query($sql);
		
		return $query->rows;
	}
}

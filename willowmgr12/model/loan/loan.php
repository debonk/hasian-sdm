<?php
class ModelLoanLoan extends Model
{
	public function addLoan($data)
	{
		$this->db->query("INSERT INTO " . DB_PREFIX . "loan SET customer_id = '" . (int)$data['customer_id'] . "', amount = '" . (int)$data['amount'] . "', description = '" . $this->db->escape($data['description']) . "', installment = '" . (int)$data['installment'] . "', date_start = STR_TO_DATE('01 " . $this->db->escape($data['date_start']) . "', '%d %b %Y'), user_id = '" . (int)$this->user->getId() . "'");

		$item_id = $this->db->getLastId();

		$this->db->query("INSERT INTO " . DB_PREFIX . "payroll_component_value SET presence_period_id = '0', customer_id = '" . (int)$data['customer_id'] . "', code = 'loan', item = '" . (int)$item_id . "', title = '#" . (int)$item_id . ": " . $this->db->escape($data['description']) . "', value = '" . (int)$data['amount'] . "', type = '1', sort_order = '0', user_id = '" . (int)$this->user->getId() . "', date_added = NOW()");
	}

	public function editLoan($loan_id, $data)
	{ //edit installment only
		$this->db->query("UPDATE " . DB_PREFIX . "loan SET installment = '" . (int)$data['installment'] . "' WHERE loan_id = '" . (int)$loan_id . "'");
	}

	public function deleteLoan($loan_id)
	{
		$this->db->query("DELETE FROM " . DB_PREFIX . "loan WHERE loan_id = '" . (int)$loan_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "payroll_component_value WHERE code = 'loan' AND item = '" . (int)$loan_id . "'");
	}

	public function getLoan($loan_id)
	{
		$sql = "SELECT DISTINCT * FROM " . DB_PREFIX . "v_loan WHERE loan_id = '" . (int)$loan_id . "'";

		$query = $this->db->query($sql);

		return $query->row;
	}

	public function getLoans($data = array())
	{
		// $sql = "SELECT l.*, SUM(pcv.value) as balance, c.customer_id, CONCAT(c.firstname, ' [', c.lastname, ']') AS name, cgd.name AS customer_group, username FROM " . DB_PREFIX . "loan l LEFT JOIN " . DB_PREFIX . "customer c ON (c.customer_id = l.customer_id) LEFT JOIN `" . DB_PREFIX . "customer_group_description` cgd ON (c.customer_group_id = cgd.customer_group_id) LEFT JOIN " . DB_PREFIX . "user u ON (u.user_id = l.user_id) LEFT JOIN " . DB_PREFIX . "payroll_component_value pcv ON (pcv.item = l.loan_id AND pcv.code = 'loan')";
		$sql = "SELECT * FROM " . DB_PREFIX . "v_loan WHERE (language_id = '" . (int)$this->config->get('config_language_id') . "' OR language_id IS NULL)";
	
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


		if (!empty($data['filter']['description'])) {
			$implode[] = "description LIKE '%" . $this->db->escape($data['filter']['description']) . "%'";
		}

		if (isset($data['filter']['status']) && $data['filter']['status'] != '*') {
			if (($data['filter']['status']) == 1) {
				$implode[] = "balance = 0";
			} else {
				$implode[] = "balance > 0";
			}
		}

		if (!empty($data['customer_id'])) {
			$implode[] = "customer_id = '" . (int)$data['customer_id'] . "'";
		}

		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}

		// $sql .= " GROUP BY pcv.item";

		// if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
		// 	if (!empty($data['filter_status'])) {
		// 		$sql .= " HAVING balance = 0";
		// 	}
		// } else {
		// 	$sql .= " HAVING balance > 0";
		// }

		$sort_data = array(
			'date_added',
			'name',
			'customer_group',
			'customer_department',
			'location',
			'balance'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY date_added";
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

	public function getLoansCount($data = array())
	{
		// $sql = "SELECT SUM(pcv.value) as balance FROM " . DB_PREFIX . "loan l LEFT JOIN " . DB_PREFIX . "customer c ON (c.customer_id = l.customer_id) LEFT JOIN `" . DB_PREFIX . "customer_group_description` cgd ON (c.customer_group_id = cgd.customer_group_id) LEFT JOIN " . DB_PREFIX . "payroll_component_value pcv ON (pcv.item = l.loan_id AND pcv.code = 'loan')";
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "v_loan WHERE (language_id = '" . (int)$this->config->get('config_language_id') . "' OR language_id IS NULL)";

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


		if (!empty($data['filter']['description'])) {
			$implode[] = "description LIKE '%" . $this->db->escape($data['filter']['description']) . "%'";
		}

		if (isset($data['filter']['status']) && $data['filter']['status'] != '*') {
			if (($data['filter']['status']) == 1) {
				$implode[] = "balance = 0";
			} else {
				$implode[] = "balance > 0";
			}
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

	public function getLoansTotal($data = array())
	{
		// $sql = "SELECT SUM(pcv.value) as balance FROM " . DB_PREFIX . "loan l LEFT JOIN " . DB_PREFIX . "customer c ON (c.customer_id = l.customer_id) LEFT JOIN `" . DB_PREFIX . "customer_group_description` cgd ON (c.customer_group_id = cgd.customer_group_id) LEFT JOIN " . DB_PREFIX . "payroll_component_value pcv ON (pcv.item = l.loan_id AND pcv.code = 'loan')";
		$sql = "SELECT SUM(balance) AS total FROM " . DB_PREFIX . "v_loan WHERE (language_id = '" . (int)$this->config->get('config_language_id') . "' OR language_id IS NULL)";

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


		if (!empty($data['filter']['description'])) {
			$implode[] = "description LIKE '%" . $this->db->escape($data['filter']['description']) . "%'";
		}

		if (isset($data['filter']['status']) && $data['filter']['status'] != '*') {
			if (($data['filter']['status']) == 1) {
				$implode[] = "balance = 0";
			} else {
				$implode[] = "balance > 0";
			}
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

	public function addTransaction($customer_id, $description = '', $amount = '', $loan_id = 0)
	{
		$this->load->model('common/payroll');
		$customer_info = $this->model_common_payroll->getCustomer($customer_id);

		if ($customer_info) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "payroll_component_value SET presence_period_id = '0', customer_id = '" . (int)$customer_id . "', code = 'loan', item = '" . (int)$loan_id . "', title = '" . $this->db->escape($description) . "', value = '" . (int)ceil($amount) . "', user_id = '" . (int)$this->user->getId() . "', date_added = NOW()");
		}
	}

	public function getTransactions($customer_id, $start = 0, $limit = 10)
	{
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 10;
		}

		$query = $this->db->query("SELECT pcv.*, u.username AS username FROM " . DB_PREFIX . "payroll_component_value pcv LEFT JOIN " . DB_PREFIX . "user u ON (pcv.user_id = u.user_id) WHERE customer_id = '" . (int)$customer_id . "' AND pcv.code = 'loan' ORDER BY date_added DESC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}

	public function getTotalTransactions($customer_id)
	{
		$query = $this->db->query("SELECT COUNT(*) AS total  FROM " . DB_PREFIX . "payroll_component_value WHERE customer_id = '" . (int)$customer_id . "' AND code = 'loan'");

		return $query->row['total'];
	}

	public function getTransactionTotal($customer_id)
	{
		$query = $this->db->query("SELECT SUM(value) AS total FROM " . DB_PREFIX . "payroll_component_value WHERE customer_id = '" . (int)$customer_id . "' AND code = 'loan'");

		return $query->row['total'];
	}

	public function getTransactionCountByLoanId($loan_id)
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "payroll_component_value WHERE item = '" . (int)$loan_id . "' AND code = 'loan'";

		$query = $this->db->query($sql);

		return $query->row['total'];
	}
}

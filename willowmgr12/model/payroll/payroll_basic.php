<?php
class ModelPayrollPayrollBasic extends Model
{
	public function editPayrollBasic($customer_id, $data)
	{
		$data = preg_replace('/(?!-)[^0-9.]/', '', $data);

		# Delete unapproved payroll_basic
		$this->db->query("DELETE FROM " . DB_PREFIX . "payroll_basic WHERE customer_id = '" . (int)$customer_id . "' AND date_approved IS NULL");

		$this->db->query("INSERT INTO " . DB_PREFIX . "payroll_basic SET customer_id = '" . (int)$customer_id . "', gaji_pokok = '" . (int)$data['gaji_pokok'] . "', tunj_jabatan = '" . (int)$data['tunj_jabatan'] . "', tunj_hadir = '" . (int)$data['tunj_hadir'] . "', tunj_pph = '" . (int)$data['tunj_pph'] . "', uang_makan = '" . (int)$data['uang_makan'] . "', date_added = NOW(), user_id = '" . (int)$this->user->getId() . "'");

		$payroll_basic_id = $this->db->getLastId();

		$config_auto_approve = $this->config->get('config_payroll_basic_auto_approve'); // Belum masuk di setting 

		if ($config_auto_approve == 'always') {
			$this->approvePayrollBasic($payroll_basic_id);

		} elseif ($config_auto_approve == 'first') {
			$this->load->model('common/payroll');
			$customer_info = $this->model_common_payroll->getCustomer($customer_id);

			if (empty($customer_info['payroll_basic_id'])) {
				$this->approvePayrollBasic($payroll_basic_id);
			}
		}
	}

	public function approvePayrollBasic($payroll_basic_id)
	{
		$payroll_basic_info = $this->getPayrollBasic($payroll_basic_id);

		$this->db->query("UPDATE " . DB_PREFIX . "payroll_basic SET date_approved = NOW(), approval_user_id = '" . (int)$this->user->getId() . "' WHERE payroll_basic_id = '" . (int)$payroll_basic_id . "'");

		$this->db->query("UPDATE " . DB_PREFIX . "customer SET payroll_basic_id = '" . (int)$payroll_basic_id . "' WHERE customer_id = '" . (int)$payroll_basic_info['customer_id'] . "'");
	}

	public function getPayrollBasic($payroll_basic_id)
	{
		$sql = "SELECT DISTINCT * FROM " . DB_PREFIX . "payroll_basic WHERE payroll_basic_id = '" . (int)$payroll_basic_id . "'";

		$query = $this->db->query($sql);

		return $query->row;
	}

	// public function getPayrollBasics($data = [])
	// {
	// 	$hke_default = $this->config->has('payroll_setting_default_hke') ? $this->config->get('payroll_setting_default_hke') : 25;

	// 	$sql = "SELECT pb.*, (pb.gaji_pokok + pb.tunj_jabatan + pb.tunj_hadir + pb.tunj_pph + (" . (int)$hke_default . " * pb.uang_makan)) AS gaji_dasar, c.*, u.username FROM " . DB_PREFIX . "payroll_basic pb LEFT JOIN " . DB_PREFIX . "v_customer c ON (c.customer_id = pb.customer_id) LEFT JOIN " . DB_PREFIX . "user u ON (u.user_id = pb.user_id)";

	// 	$implode = array();

	// 	if (!empty($data['filter']['name'])) {
	// 		$implode[] = "CONCAT(c.firstname, ' [', c.lastname, ']') LIKE '%" . $this->db->escape($data['filter']['name']) . "%'";
	// 	}

	// 	if (!empty($data['filter']['customer_department_id'])) {
	// 		$implode[] = "c.customer_department_id = '" . (int)$data['filter']['customer_department_id'] . "'";
	// 	}

	// 	if (!empty($data['filter']['customer_group_id'])) {
	// 		$implode[] = "c.customer_group_id = '" . (int)$data['filter']['customer_group_id'] . "'";
	// 	}

	// 	if (!empty($data['filter']['location_id'])) {
	// 		$implode[] = "c.location_id = '" . (int)$data['filter']['location_id'] . "'";
	// 	}

	// 	if (isset($data['filter']['active']) && $data['filter']['active'] != '*') {
	// 		if ($data['filter']['active'] == 1) {
	// 			$implode[] = "(date_end IS NULL OR date_end >= CURDATE())";
	// 		} else {
	// 			$implode[] = "date_end < CURDATE()";
	// 		}
	// 	}

	// 	if (!empty($data['filter']['customer_ids'])) {
	// 		$implode[] = "c.customer_id IN (" . implode(',', $data['filter']['customer_ids']) . ")";
	// 	}

	// 	if ($implode) {
	// 		$sql .= " WHERE " . implode(" AND ", $implode);
	// 	}

	// 	$sort_data = array(
	// 		'c.nip',
	// 		'name',
	// 		'customer_group',
	// 		'customer_department',
	// 		'location',
	// 		'pb.gaji_pokok',
	// 		'pb.tunj_jabatan',
	// 		'pb.tunj_hadir',
	// 		'pb.tunj_pph',
	// 		'pb.uang_makan',
	// 		'gaji_dasar',
	// 		'pb.date_added'
	// 	);

	// 	if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
	// 		$sql .= " ORDER BY " . $data['sort'];
	// 	} else {
	// 		$sql .= " ORDER BY name";
	// 	}

	// 	if (isset($data['order']) && ($data['order'] == 'DESC')) {
	// 		$sql .= " DESC";
	// 	} else {
	// 		$sql .= " ASC";
	// 	}

	// 	if (isset($data['start']) || isset($data['limit'])) {
	// 		if ($data['start'] < 0) {
	// 			$data['start'] = 0;
	// 		}

	// 		if ($data['limit'] < 1) {
	// 			$data['limit'] = 40;
	// 		}

	// 		$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
	// 	}

	// 	$query = $this->db->query($sql);

	// 	return $query->rows;
	// }

	public function getUnapprovedPayrollBasics()
	{
		// $hke_default = $this->config->has('payroll_setting_default_hke') ? $this->config->get('payroll_setting_default_hke') : 25;
		$hke_default = 25;

		$sql = "SELECT pb.*, (pb.gaji_pokok + pb.tunj_jabatan + pb.tunj_hadir + pb.tunj_pph + (" . (int)$hke_default . " * pb.uang_makan)) AS gaji_dasar, u.username FROM " . DB_PREFIX . "payroll_basic pb LEFT JOIN " . DB_PREFIX . "user u ON (u.user_id = pb.user_id) WHERE date_approved IS NULL ORDER BY date_added ASC";

		$query = $this->db->query($sql);

		$payroll_basic_data = [];

		foreach ($query->rows as $payroll_basic) {
			$payroll_basic_data[$payroll_basic['customer_id']] = $payroll_basic;
		}

		return $payroll_basic_data;
	}

	public function getPayrollBasicByCustomer($customer_id)
	{
		$query = $this->db->query("SELECT DISTINCT payroll_basic_id FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$customer_id . "'");

		$payroll_basic_data = $this->getPayrollBasic($query->row['payroll_basic_id']);

		return $payroll_basic_data;
	}

	public function getCustomerPayrollBasics($data = array())
	{
		// $hke_default = $this->config->has('payroll_setting_default_hke') ? $this->config->get('payroll_setting_default_hke') : 25;
		$hke_default = 25;

		$sql = "SELECT pb.*, c.customer_id, c.nip, CONCAT(c.firstname, ' [', c.lastname, ']') AS name, c.customer_department_id, cdd.name AS customer_department, c.customer_group_id, cgd.name AS customer_group, c.location_id, l.name AS location, (pb.gaji_pokok + pb.tunj_jabatan + pb.tunj_hadir + pb.tunj_pph + (" . (int)$hke_default . " * pb.uang_makan)) AS gaji_dasar, u.username FROM " . DB_PREFIX . "customer c LEFT JOIN (" . DB_PREFIX . "customer_group_description cgd, " . DB_PREFIX . "location l) ON (cgd.customer_group_id = c.customer_group_id AND l.location_id = c.location_id) LEFT JOIN " . DB_PREFIX . "customer_department_description cdd ON (cdd.customer_department_id = c.customer_department_id) LEFT JOIN " . DB_PREFIX . "payroll_basic pb ON (pb.payroll_basic_id = c.payroll_basic_id) LEFT JOIN " . DB_PREFIX . "user u ON (u.user_id = pb.user_id) WHERE cgd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND c.payroll_include = 1";

		$implode = array();

		if (!empty($data['filter']['name'])) {
			$implode[] = "CONCAT(c.firstname, ' [', c.lastname, ']') LIKE '%" . $this->db->escape($data['filter']['name']) . "%'";
		}

		if (!empty($data['filter']['customer_department_id'])) {
			$implode[] = "c.customer_department_id = '" . (int)$data['filter']['customer_department_id'] . "'";
		}

		if (!empty($data['filter']['customer_group_id'])) {
			$implode[] = "c.customer_group_id = '" . (int)$data['filter']['customer_group_id'] . "'";
		}

		if (!empty($data['filter']['location_id'])) {
			$implode[] = "c.location_id = '" . (int)$data['filter']['location_id'] . "'";
		}

		if (isset($data['filter']['active']) && $data['filter']['active'] != '*') {
			if ($data['filter']['active'] == 1) {
				$implode[] = "(date_end IS NULL OR date_end >= CURDATE())";
			} else {
				$implode[] = "date_end < CURDATE()";
			}
		}

		if (!empty($data['filter']['customer_ids'])) {
			$implode[] = "c.customer_id IN (" . implode(',', $data['filter']['customer_ids']) . ")";
		}

		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}

		$sort_data = array(
			'c.nip',
			'name',
			'customer_group',
			'customer_department',
			'location',
			'pb.gaji_pokok',
			'pb.tunj_jabatan',
			'pb.tunj_hadir',
			'pb.tunj_pph',
			'pb.uang_makan',
			'gaji_dasar',
			'pb.date_added'
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

	public function getCustomerPayrollBasicsCount($data = array())
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer c WHERE payroll_include = 1";

		$implode = array();

		if (!empty($data['filter']['name'])) {
			$implode[] = "CONCAT(c.firstname, ' [', c.lastname, ']') LIKE '%" . $this->db->escape($data['filter']['name']) . "%'";
		}

		if (!empty($data['filter']['customer_department_id'])) {
			$implode[] = "c.customer_department_id = '" . (int)$data['filter']['customer_department_id'] . "'";
		}

		if (!empty($data['filter']['customer_group_id'])) {
			$implode[] = "c.customer_group_id = '" . (int)$data['filter']['customer_group_id'] . "'";
		}

		if (!empty($data['filter']['location_id'])) {
			$implode[] = "c.location_id = '" . (int)$data['filter']['location_id'] . "'";
		}

		if (isset($data['filter']['active']) && $data['filter']['active'] != '*') {
			if ($data['filter']['active'] == 1) {
				$implode[] = "(date_end IS NULL OR date_end >= CURDATE())";
			} else {
				$implode[] = "date_end < CURDATE()";
			}
		}

		if (!empty($data['filter']['customer_ids'])) {
			$implode[] = "c.customer_id IN (" . implode(',', $data['filter']['customer_ids']) . ")";
		}

		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getPayrollBasicHistories($customer_id, $start = 0, $limit = 10)
	{
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 10;
		}

		$query = $this->db->query("SELECT pb.*, u.username FROM " . DB_PREFIX . "payroll_basic pb LEFT JOIN " . DB_PREFIX . "user u ON (u.user_id = pb.user_id) WHERE customer_id = '" . (int)$customer_id . "' ORDER BY date_added DESC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}

	public function getTotalPayrollBasicHistories($customer_id)
	{
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "payroll_basic WHERE customer_id = '" . (int)$customer_id . "'");

		return $query->row['total'];
	}
}

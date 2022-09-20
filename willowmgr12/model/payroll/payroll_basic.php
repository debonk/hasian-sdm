<?php
class ModelPayrollPayrollBasic extends Model {
	public function editPayrollBasic($customer_id, $data) {
		$data = preg_replace('/(?!-)[^0-9.]/', '', $data);
		
		$this->db->query("INSERT INTO " . DB_PREFIX . "payroll_basic SET customer_id = '" . (int)$customer_id . "', gaji_pokok = '" . (int)$data['gaji_pokok'] . "', tunj_jabatan = '" . (int)$data['tunj_jabatan'] . "', tunj_hadir = '" . (int)$data['tunj_hadir'] . "', tunj_pph = '" . (int)$data['tunj_pph'] . "', uang_makan = '" . (int)$data['uang_makan'] . "', date_added = NOW(), user_id = '" . (int)$this->user->getId() . "'");

		$payroll_basic_id = $this->db->getLastId();

		$this->db->query("UPDATE " . DB_PREFIX . "customer SET payroll_basic_id = '" . (int)$payroll_basic_id . "' WHERE customer_id = '" . (int)$customer_id . "'");
	}

	public function getPayrollBasic($payroll_basic_id) {
		$sql = "SELECT DISTINCT * FROM " . DB_PREFIX . "payroll_basic WHERE payroll_basic_id = '" . (int)$payroll_basic_id . "'";

		$query = $this->db->query($sql);

		return $query->row;
	}

	public function getPayrollBasicByCustomer($customer_id) {
		$query = $this->db->query("SELECT DISTINCT payroll_basic_id FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$customer_id . "'");

		$payroll_basic_data = $this->getPayrollBasic($query->row['payroll_basic_id']);

		return $payroll_basic_data;
	}

	public function getCustomerPayrollBasics($data = array()) {
		$hke_default = $this->config->has('payroll_setting_default_hke') ? $this->config->get('payroll_setting_default_hke') : 25;

		$sql = "SELECT pb.*, c.customer_id, c.nip, CONCAT(c.firstname, ' [', c.lastname, ']') AS name, c.customer_department_id, cdd.name AS customer_department, c.customer_group_id, cgd.name AS customer_group, c.location_id, l.name AS location, (pb.gaji_pokok + pb.tunj_jabatan + pb.tunj_hadir + pb.tunj_pph + (" . (int)$hke_default . " * pb.uang_makan)) AS gaji_dasar FROM " . DB_PREFIX . "customer c LEFT JOIN (" . DB_PREFIX . "customer_group_description cgd, " . DB_PREFIX . "location l) ON (cgd.customer_group_id = c.customer_group_id AND l.location_id = c.location_id) LEFT JOIN " . DB_PREFIX . "customer_department_description cdd ON (cdd.customer_department_id = c.customer_department_id) LEFT JOIN " . DB_PREFIX . "payroll_basic pb ON (pb.payroll_basic_id = c.payroll_basic_id) WHERE cgd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND c.payroll_include = 1";

		$implode = array();

		if (!empty($data['filter_name'])) {
			$implode[] = "CONCAT(c.firstname, ' [', c.lastname, ']') LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_customer_department_id'])) {
			$implode[] = "c.customer_department_id = '" . (int)$data['filter_customer_department_id'] . "'";
		}
		
		if (!empty($data['filter_customer_group_id'])) {
			$implode[] = "c.customer_group_id = '" . (int)$data['filter_customer_group_id'] . "'";
		}
		
		if (!empty($data['filter_location_id'])) {
			$implode[] = "c.location_id = '" . (int)$data['filter_location_id'] . "'";
		}
		
		if (isset($data['filter_active'])) {
			if ($data['filter_active']) {
				$implode[] = "(c.date_end <> '0000-00-00' AND c.date_end <= CURDATE())";
			}
		} else {
			$implode[] = "(c.date_end IS NULL OR c.date_end = '0000-00-00' OR c.date_end > CURDATE())";
		}

		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}

		$sort_data = array(
			'c.nip',
			'name',
			'customer_group',
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
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer WHERE payroll_include = 1";

		$implode = array();

		if (!empty($data['filter_name'])) {
			$implode[] = "CONCAT(firstname, ' [', lastname, ']') LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_customer_department_id'])) {
			$implode[] = "customer_department_id = '" . (int)$data['filter_customer_department_id'] . "'";
		}

		if (!empty($data['filter_customer_group_id'])) {
			$implode[] = "customer_group_id = '" . (int)$data['filter_customer_group_id'] . "'";
		}

		if (!empty($data['filter_location_id'])) {
			$implode[] = "location_id = '" . (int)$data['filter_location_id'] . "'";
		}

		if (isset($data['filter_active'])) {
			if ($data['filter_active']) {
				$implode[] = "(date_end <> '0000-00-00' AND date_end <= CURDATE())";
			}
		} else {
			$implode[] = "(date_end IS NULL OR date_end = '0000-00-00' OR date_end > CURDATE())";
		}
		
		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getPayrollBasicHistories($customer_id, $start = 0, $limit = 10) {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 10;
		}

		$query = $this->db->query("SELECT pb.*, u.username FROM " . DB_PREFIX . "payroll_basic pb LEFT JOIN " . DB_PREFIX . "user u ON (u.user_id = pb.user_id) WHERE customer_id = '" . (int)$customer_id . "' ORDER BY date_added DESC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}

	public function getTotalPayrollBasicHistories($customer_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "payroll_basic WHERE customer_id = '" . (int)$customer_id . "'");

		return $query->row['total'];
	}
}

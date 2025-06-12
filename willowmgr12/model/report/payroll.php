<?php
class ModelReportPayroll extends Model
{
	public function getTotalPayrollByPeriod($period_y_m)
	{ //dashboard
		$payroll_status_id = $this->config->get('payroll_setting_generated_status_id'); //Change to completed

		$query = $this->db->query("SELECT SUM(gaji_pokok + tunj_jabatan + tunj_hadir + tunj_pph + total_uang_makan) AS sum_earning, SUM(pot_sakit + pot_bolos + pot_tunj_hadir + pot_gaji_pokok + pot_terlambat) AS sum_deduction, period FROM `" . DB_PREFIX . "presence_period` pp LEFT JOIN `" . DB_PREFIX . "payroll` p ON (p.presence_period_id = pp.presence_period_id) WHERE pp.payroll_status_id = '" . (int)$payroll_status_id . "' AND DATE_FORMAT(pp.period,'%Y-%c') = '" . $this->db->escape($period_y_m) . "'");

		return $query->row;
	}

	public function getTotalPayrollsByYear($component = 'grandtotal')
	{ //chart
		$payroll_data = array();

		for ($i = 1; $i <= 12; $i++) {
			$payroll_data[$i] = array(
				'month' => date('M', mktime(0, 0, 0, $i)),
				'total' => 0
			);
		}

		//change to completed status
		$payroll_status_id = $this->config->get('payroll_setting_generated_status_id');

		$query = $this->db->query("SELECT SUM(gaji_pokok + tunj_jabatan + tunj_hadir + tunj_pph + total_uang_makan) AS sum_earning, SUM(pot_sakit + pot_bolos + pot_tunj_hadir + pot_gaji_pokok + pot_terlambat) AS sum_deduction, pp.period FROM `" . DB_PREFIX . "presence_period` pp LEFT JOIN `" . DB_PREFIX . "payroll` p ON (p.presence_period_id = pp.presence_period_id) WHERE pp.payroll_status_id = '" . (int)$payroll_status_id . "' AND YEAR(pp.period) = YEAR(NOW()) GROUP BY MONTH(pp.period)");

		foreach ($query->rows as $result) {
			switch ($component) {
				default:
				case 'earning':
					$total = $result['sum_earning'];
					break;
				case 'deduction':
					$total = $result['sum_deduction'];
					break;
				case 'grandtotal':
					$total = $result['sum_earning'] - $result['sum_deduction'];
					break;
			}

			$payroll_data[date('n', strtotime($result['period']))] = array(
				'month' => date('M', strtotime($result['period'])),
				'total' => $total
			);
		}

		return $payroll_data;
	}

	public function getPayrolls($presence_period_id, $data = array())
	{
		$sql = "SELECT customer_id, addition_0, net_salary, component, nip, `name`, customer_group, customer_department, `location` FROM " . DB_PREFIX . "v_payroll WHERE presence_period_id = '" . (int)$presence_period_id . "'";
		// $sql = "SELECT p.customer_id, lastname AS customer, net_salary, component, nip, p.name, customer_group, customer_department, `location`, id_card_address_id, ca.children, ca.npwp, ca.npwp_address, g.code AS gender_code, ms.code AS marriage_status_code FROM " . DB_PREFIX . "v_payroll p LEFT JOIN " . DB_PREFIX . "customer_add_data ca ON p.customer_id = ca.customer_id LEFT JOIN " . DB_PREFIX . "gender g ON ca.gender_id = g.gender_id LEFT JOIN " . DB_PREFIX . "marriage_status ms ON ca.marriage_status_id = ms.marriage_status_id WHERE presence_period_id = '" . (int)$presence_period_id . "'";

		// $implode = array();

		// if (!empty($data['filter_name'])) {
		// 	$implode[] = "name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		// }

		// if (!empty($data['filter_customer_department_id'])) {
		// 	$implode[] = "customer_department_id = '" . (int)$data['filter_customer_department_id'] . "'";
		// }

		// if (!empty($data['filter_location_id'])) {
		// 	$implode[] = "location_id = '" . (int)$data['filter_location_id'] . "'";
		// }

		// if ($implode) {
		// 	$sql .= " AND " . implode(" AND ", $implode);
		// }

		$sort_data = array(
			'nip',
			'name',
			'customer_group',
			'customer_department',
			'location'
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

	public function getPayrollsGrouped($presence_period_id, $data = array())
	{
		if (empty($data['filter']['group'])) {
			return;
		}

		$sql = "SELECT SUM(net_salary) AS net_salary_total, SUM(component) AS component_total, COUNT(customer_id) AS customer_count, customer_group, COUNT(DISTINCT customer_group_id) AS customer_group_count, customer_department, COUNT(DISTINCT customer_department_id) AS customer_department_count, `location`, COUNT(DISTINCT location_id) AS location_count FROM " . DB_PREFIX . "v_payroll WHERE presence_period_id = '" . (int)$presence_period_id . "'";

		$sql .= " GROUP BY " . $data['filter']['group'] . '_id';

		$sort_data = array(
			// 'customer_group',
			// 'customer_department',
			// 'location',
			'group_item',
			'customer_count',
			'customer_group_count',
			'customer_department_count',
			'location_count'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			if ($data['sort'] == 'group_item') {
				$sql .= " ORDER BY " . $data['filter']['group'];
			} else {
				$sql .= " ORDER BY " . $data['sort'];
			}
		} else {
			$sql .= " ORDER BY " . $data['filter']['group'];
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

	public function getPayrollComponentCodes($presence_period_id, $customer_id = 0)
	{
		$sql = "SELECT DISTINCT(code) FROM " . DB_PREFIX . "payroll_component_value WHERE presence_period_id = '" . (int)$presence_period_id . "'";

		if ($customer_id) {
			$sql .= " AND customer_id = '" . (int)$customer_id . "'";
		}

		$sql .= " ORDER BY sort_order ASC";

		$query = $this->db->query($sql);

		$component_codes = array();

		foreach ($query->rows as $result) {
			$component_codes[] = $result['code'];
		}

		return $component_codes;
	}

	public function getPayrollComponentTotal($presence_period_id, $customer_id = 0, $group_by = 'code', $data = [])
	{
		$group_data = array(
			'code',
			'type'
		);

		$implode = array();

		// if (!empty($data['filter']['name'])) {
		// 	$implode[] = "c.name LIKE '%" . $this->db->escape($data['filter']['name']) . "%'";
		// }

		// if (!empty($data['filter']['customer_group_id'])) {
		// 	$implode[] = "c.customer_group_id = '" . (int)$data['filter']['customer_group_id'] . "'";
		// }

		// if (!empty($data['filter']['customer_department_id'])) {
		// 	$implode[] = "c.customer_department_id = '" . (int)$data['filter']['customer_department_id'] . "'";
		// }

		// if (!empty($data['filter']['location_id'])) {
		// 	$implode[] = "c.location_id = '" . (int)$data['filter']['location_id'] . "'";
		// }

		if (in_array($group_by, $group_data)) {
			$sql = "SELECT pcv." . $group_by . ", SUM(pcv.value) as total FROM " . DB_PREFIX . "payroll_component_value pcv LEFT JOIN " . DB_PREFIX . "v_customer c ON c.customer_id = pcv.customer_id WHERE presence_period_id = '" . (int)$presence_period_id . "'";

			if ($customer_id) {
				$sql .= " AND pcv.customer_id = '" . (int)$customer_id . "'";
			}

			if ($implode) {
				$sql .= " AND " . implode(" AND ", $implode);
			}

			$sql .= " GROUP BY pcv." . $group_by;
		} else {
			$sql = "SELECT SUM(pcv.value) as total FROM " . DB_PREFIX . "payroll_component_value pcv LEFT JOIN " . DB_PREFIX . "v_customer c ON c.customer_id = pcv.customer_id WHERE presence_period_id = '" . (int)$presence_period_id . "'";

			if ($customer_id) {
				$sql .= " AND pcv.customer_id = '" . (int)$customer_id . "'";
			}

			if ($implode) {
				$sql .= " AND " . implode(" AND ", $implode);
			}
		}

		$query = $this->db->query($sql);

		$component_total_data = array('grandtotal' => 0);

		switch ($group_by) {
			case 'code':
				$component_codes = $this->getPayrollComponentCodes($presence_period_id);

				foreach ($component_codes as $component_code) {
					$component_total_data[$component_code] = 0;
				}

				break;

			case 'type':
				$component_total_data[0] = 0;
				$component_total_data[1] = 0;

				break;

			default:
		}

		foreach ($query->rows as $result) {
			$component_total_data[$result[$group_by]] = $result['total'];
			$component_total_data['grandtotal'] += $result['total'];
		}

		return $component_total_data;
	}

	public function getPayrollsCount($presence_period_id)
	{ //report_payroll_tax
		$sql = "SELECT COUNT(*) as total FROM " . DB_PREFIX . "payroll WHERE presence_period_id = '" . (int)$presence_period_id . "'";

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getPayrollsGroupedCount($presence_period_id, $data)
	{
		if (empty($data['filter']['group'])) {
			return;
		}

		$sql = "SELECT COUNT(DISTINCT " . $data['filter']['group'] . "_id) as total FROM " . DB_PREFIX . "v_payroll WHERE presence_period_id = '" . (int)$presence_period_id . "'";

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getComponents($presence_period_id, $customer_id = 0, $code = '')
	{ //report_payroll_tax, report_payroll_insurance
		$sql = "SELECT * FROM " . DB_PREFIX . "payroll_component_value WHERE presence_period_id = '" . (int)$presence_period_id . "'";

		if ($customer_id) {
			$sql .= " AND customer_id = '" . (int)$customer_id . "'";
		}

		if ($code) {
			$code = explode(', ', $code);

			$sql .= " AND code IN ('" . implode("', '", $code) . "')";
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getComponentsTotalGroupByType($presence_period_id, $customer_id, $code = '')
	{ //report_payroll_tax
		$sql = "SELECT customer_id, type, SUM(value) AS total FROM " . DB_PREFIX . "payroll_component_value WHERE presence_period_id = " . (int)$presence_period_id . " AND customer_id = " . (int)$customer_id;

		if ($code) {
			$code = explode(', ', $code);

			$sql .= " AND code IN ('" . implode("', '", $code) . "')";
		}

		$sql .= " GROUP BY customer_id, type";

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getComponentCustomers($presence_period_id, $data = [])
	{ //Used by: report_payroll_insurance
		// $sql = "SELECT pcv.customer_id, c.nip, CONCAT(c.firstname, ' [', c.lastname, ']') AS name, cgd.name AS customer_group FROM " . DB_PREFIX . "payroll_component_value pcv LEFT JOIN " . DB_PREFIX . "customer c ON (c.customer_id = pcv.customer_id) LEFT JOIN " . DB_PREFIX . "customer_group_description cgd ON (cgd.customer_group_id = c.customer_group_id) WHERE presence_period_id = '" . (int)$presence_period_id . "'";
		$sql = "SELECT pcv.customer_id, c.nip, c.name, c.customer_group, c.customer_department, c.location FROM " . DB_PREFIX . "payroll_component_value pcv LEFT JOIN " . DB_PREFIX . "v_customer c ON (c.customer_id = pcv.customer_id) WHERE presence_period_id = '" . (int)$presence_period_id . "'";

		$implode = array();

		if (!empty($data['code'])) {
			$implode[] = "pcv.code = '" . $this->db->escape($data['code']) . "'";
		}

		if (!empty($data['filter_name'])) {
			$implode[] = "c.name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
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

		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}

		$sql .= " GROUP BY pcv.customer_id ORDER BY name ASC";

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

	public function getComponentCustomersCount($presence_period_id, $data = [])
	{ //Used by: report_payroll_insurance
		// $sql = "SELECT COUNT(DISTINCT customer_id) AS total FROM " . DB_PREFIX . "payroll_component_value WHERE presence_period_id = '" . (int)$presence_period_id . "'";
		$sql = "SELECT COUNT(DISTINCT pcv.customer_id) AS total FROM " . DB_PREFIX . "payroll_component_value pcv LEFT JOIN " . DB_PREFIX . "v_customer c ON (c.customer_id = pcv.customer_id) WHERE presence_period_id = '" . (int)$presence_period_id . "'";

		$implode = array();

		if (!empty($data['code'])) {
			$implode[] = "pcv.code = '" . $this->db->escape($data['code']) . "'";
		}

		if (!empty($data['filter_name'])) {
			$implode[] = "c.name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
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

		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	private $ter_tariff = [
		'A' => [5400000 => 0, 5650000 => 0.25, 5950000 => 0.5, 6300000 => 0.75, 6750000 => 1, 7500000 => 1.25, 8550000 => 1.5, 9650000 => 1.75, 10050000 => 2, 10350000 => 2.25, 10700000 => 2.5, 11050000 => 3, 11600000 => 3.5, 12500000 => 4, 13750000 => 5, 15100000 => 6, 16950000 => 7, 19750000 => 8, 24150000 => 9, 26450000 => 10, 28000000 => 11, 30050000 => 12, 32400000 => 13, 35400000 => 14, 39100000 => 15, 43850000 => 16, 47800000 => 17, 51400000 => 18, 56300000 => 19, 62200000 => 20, 68600000 => 21, 77500000 => 22, 89000000 => 23, 103000000 => 24, 125000000 => 25, 157000000 => 26, 206000000 => 27, 337000000 => 28, 454000000 => 29, 550000000 => 30, 695000000 => 31, 910000000 => 32, 1400000000 => 33],
		'B'	=> [6200000 => 0, 6500000 => 0.25, 6850000 => 0.5, 7300000 => 0.75, 9200000 => 1, 10750000 => 1.5, 11250000 => 2, 11600000 => 2.5, 12600000 => 3, 13600000 => 4, 14950000 => 5, 16400000 => 6, 18450000 => 7, 21850000 => 8, 26000000 => 9, 27700000 => 10, 29350000 => 11, 31450000 => 12, 33950000 => 13, 37100000 => 14, 41100000 => 15, 45800000 => 16, 49500000 => 17, 53800000 => 18, 58500000 => 19, 64000000 => 20, 71000000 => 21, 80000000 => 22, 93000000 => 23, 109000000 => 24, 129000000 => 25, 163000000 => 26, 211000000 => 27, 374000000 => 28, 459000000 => 29, 555000000 => 30, 704000000 => 31, 957000000 => 32, 1405000000 => 33],
		'C'	=> [6600000 => 0, 6950000 => 0.25, 7350000 => 0.5, 7800000 => 0.75, 8850000 => 1, 9800000 => 1.25, 10950000 => 1.5, 11200000 => 1.75, 12050000 => 2, 12950000 => 3, 14150000 => 4, 15550000 => 5, 17050000 => 6, 19500000 => 7, 22700000 => 8, 26600000 => 9, 28100000 => 10, 30100000 => 11, 32600000 => 12, 35400000 => 13, 38900000 => 14, 43000000 => 15, 47400000 => 16, 51200000 => 17, 55800000 => 18, 60400000 => 19, 66700000 => 20, 74500000 => 21, 83200000 => 22, 95000000 => 23, 110000000 => 24, 134000000 => 25, 169000000 => 26, 221000000 => 27, 390000000 => 28, 463000000 => 39, 561000000 => 30, 709000000 => 31, 965000000 => 32, 1419000000 => 33],
	];

	public function getTaxes($presence_period_id, $data)
	{ //report_payroll_tax
		$results = $this->getPayrolls($presence_period_id, $data);

		$taxes_data = array();

		$this->load->model('report/customer');
		$this->load->model('release/allowance');

		$customers_insurance_data = [];
		$customers_insurance_components = $this->getComponents($presence_period_id, 0, 'insurance');

		foreach ($customers_insurance_components as $component) {
			if ($component['title'] == 'BPJS Kesehatan') {
				$customers_insurance_data['health'][$component['customer_id']] = isset($customers_insurance_data['health'][$component['customer_id']]) ? $customers_insurance_data['health'][$component['customer_id']] + $component['value'] : $component['value'];
			} else {
				$customers_insurance_data['employment'][$component['customer_id']] = isset($customers_insurance_data['employment'][$component['customer_id']]) ? $customers_insurance_data['employment'][$component['customer_id']] + $component['value'] : $component['value'];
			}
		}

		# THR
		$holiday_allowance_data = [];

		$period_info = $this->model_common_payroll->getPeriod($presence_period_id);

		$allowances = $this->model_release_allowance->getAllowanceByDate($period_info['period']);

		if ($allowances) {
			foreach ($allowances as $allowance) {
				$allowance_customers = $this->model_release_allowance->getAllowanceCustomers($allowance['allowance_id']);

				foreach ($allowance_customers as $allowance_customer) {
					$holiday_allowance_data[$allowance_customer['customer_id']] = ($holiday_allowance_data[$allowance_customer['customer_id']] ?? 0) + $allowance_customer['amount'];
				}
			}
		}

		foreach ($results as $result) {
			$customer_info = $this->model_report_customer->getCustomer($result['customer_id']);

			if ($customer_info['id_card_address_id']) {
				$address_info = $this->model_report_customer->getAddress($customer_info['id_card_address_id']);

				$id_card_address = $address_info['address_1'];

				if ($address_info['address_2']) {
					$id_card_address .= ', ' . $address_info['address_2'];
				}

				$id_card_address .= ', ' . $address_info['city_name'] . ', ' . $address_info['zone'] . ', ' . $address_info['country'];

				if ($address_info['postcode']) {
					$id_card_address .= ' - ' . $address_info['postcode'];
				}
			} else {
				$id_card_address = '';
			}

			if ($customer_info['gender_code'] == 'L') {
				$non_taxed_category = $customer_info['marriage_status_code'] . '/' . $customer_info['children'];
			} else {
				$non_taxed_category = 'TK/0';
			}

			// $salary = $result['gaji_pokok'] + $result['tunj_jabatan'] + $result['tunj_hadir'] + $result['tunj_pph'] + $result['total_uang_makan'] - $result['pot_sakit'] - $result['pot_bolos'] - $result['pot_tunj_hadir'] - $result['pot_gaji_pokok'] - $result['pot_terlambat'];
			$salary = $result['net_salary'];
			// $gross_salary = $result['net_salary'] + $result['component'];
			$basic_salary = $result['addition_0'];

			$component_data = array(
				'1'	=> 0,
				'0'	=> 0
			);

			// if (isset($data['code'])) {
			// 	$code = $data['code'];
			// } else {
			// 	$code = '';
			// }

			$code = 'insurance, overtime, incentive, dayoff, cutoff';

			$components_total = $this->getComponentsTotalGroupByType($presence_period_id, $result['customer_id'], $code);
			// 			$components_total = $this->getComponents($presence_period_id, $result['customer_id'], $code);

			if (!empty($components_total)) {
				foreach ($components_total as $value) {
					$component_data[$value['type']] =  $value['total'];
				}
			}

			$gross_salary = $salary + $component_data[1] + $component_data[0];

			$allowance = max(0, $gross_salary - $basic_salary);

			$functional_expense = min(500000, 0.05 * $gross_salary);

			# THR
			$holiday_allowance = $holiday_allowance_data[$result['customer_id']] ?? 0;
			if ($holiday_allowance > 0) {
				$gross_salary += $holiday_allowance;
			}

			$ter_point = 0;

			// $non_taxed_income = 4500000;
			if ($customer_info['gender_code'] == 'L') {
				if ($customer_info['marriage_status_code'] == 'K') {
					$ter_point++;
					// $non_taxed_income += 375000;
				}

				if ($customer_info['children']) {
					$ter_point += min(3, $customer_info['children']);
					// $non_taxed_income += min(3, $customer_info['children']) * 375000;
				}
			}

			if ($ter_point < 2) {
				$ter_category = 'A';
			} elseif ($ter_point < 4) {
				$ter_category = 'B';
			} else {
				$ter_category = 'C';
			}

			$ter_tariff = 34; // Maximum tariff

			foreach ($this->ter_tariff[$ter_category] as $salary_range => $tariff) {
				if ($gross_salary <= $salary_range) {
					$ter_tariff = $tariff;
					break;
				}
			}

			if (date('n', strtotime($period_info['period'])) != 12) {
				$tax = $gross_salary * $ter_tariff / 100;
			} else {
				$tax = 0;
			}



			// $net_salary = $gross_salary - min(500000, 0.05 * $gross_salary) + $component_data[0];

			// $taxed_income = max(0, $net_salary - $non_taxed_income);

			// switch (true) {
			// 	case ($taxed_income > 500000000):
			// 		$tax = 0.3;
			// 		break;
			// 	case ($taxed_income > 250000000):
			// 		$tax = 0.25;
			// 		break;
			// 	case ($taxed_income > 50000000):
			// 		$tax = 0.15;
			// 		break;
			// 	default:
			// 		$tax = 0.05;
			// }

			// if (empty($result['npwp'])) {
			// 	$tax *= 1.2;
			// }

			// $tax_value = floor($taxed_income * $tax / 1000) * 1000;

			$taxes_data[$result['customer_id']] = array(
				'customer_id'			=> $result['customer_id'],
				'customer'				=> $customer_info['lastname'],
				// 'customer'				=> $result['name'],
				'nik'					=> $customer_info['nik'],
				'id_card_address'		=> $id_card_address,
				'gender_code'			=> $customer_info['gender_code'],
				'non_taxed_category'	=> $non_taxed_category,
				'customer_group'		=> $result['customer_group'],
				'npwp'					=> $customer_info['npwp'],
				'npwp_address'			=> $customer_info['npwp_address'],
				'location'				=> $result['location'],
				'ter_category'			=> $ter_category,
				'basic_salary'			=> $basic_salary,
				'allowance'				=> $allowance,
				'holiday_allowance'		=> $holiday_allowance,
				'gross_salary'			=> $gross_salary,
				'ter_tariff'			=> $ter_tariff,
				'functional_expense'	=> $functional_expense,
				'insurance_employment'	=> $customers_insurance_data['employment'][$result['customer_id']] ?? 0,
				'insurance_health'		=> $customers_insurance_data['health'][$result['customer_id']] ?? 0,
				'tax'					=> $tax,
				'ter_point'				=> $ter_point,
			);
		}

		return $taxes_data;
	}

	public function getFinalTaxes($presence_period_id, $data)
	{
		$taxes_data = $this->getTaxes($presence_period_id, $data);

		$period_info = $this->model_common_payroll->getPeriod($presence_period_id);

		for ($i = 1; $i < 12; $i++) {
			$past_period_info = $this->model_common_payroll->getPeriodByDate(date('Y-m-d', strtotime($period_info['period'] . " -$i month")));
			if (!$past_period_info) {
				continue;
			}

			$past_taxes = $this->getTaxes($past_period_info['presence_period_id'], []);
			if (!$past_taxes) {
				continue;
			}

			foreach ($taxes_data as $tax_data) {
				if (isset($past_taxes[$tax_data['customer_id']])) {
					$taxes_data[$tax_data['customer_id']]['basic_salary'] += $past_taxes[$tax_data['customer_id']]['basic_salary'];
					$taxes_data[$tax_data['customer_id']]['gross_salary'] += $past_taxes[$tax_data['customer_id']]['gross_salary'];
					$taxes_data[$tax_data['customer_id']]['allowance'] += $past_taxes[$tax_data['customer_id']]['allowance'];
					$taxes_data[$tax_data['customer_id']]['holiday_allowance'] += $past_taxes[$tax_data['customer_id']]['holiday_allowance'];
					$taxes_data[$tax_data['customer_id']]['functional_expense'] += $past_taxes[$tax_data['customer_id']]['functional_expense'];
					$taxes_data[$tax_data['customer_id']]['insurance_employment'] += $past_taxes[$tax_data['customer_id']]['insurance_employment'];
					$taxes_data[$tax_data['customer_id']]['insurance_health'] += $past_taxes[$tax_data['customer_id']]['insurance_health'];
					$taxes_data[$tax_data['customer_id']]['tax'] += $past_taxes[$tax_data['customer_id']]['tax'];
				}
			}
		}

		foreach ($taxes_data as $customer_id => $result) {
			$non_taxed_income = 54000000 + $result['ter_point'] * 4500000;
			$taxes_data[$customer_id]['functional_expense'] = min(6000000, 0.05 * $result['gross_salary']);

			$net_salary = $result['gross_salary'] - $result['functional_expense'];
			$taxed_income = max(0, $net_salary - $non_taxed_income);

			$final_tax = 0;

			if ($taxed_income > 0) {
				$final_tax = min(60000000, $taxed_income) * 5 / 100; # 5% for the first 60 million
				$taxed_income -= 60000000;

				if ($taxed_income > 0) {
					$final_tax += min(190000000, $taxed_income) * 15 / 100; # 15% for the next 190 million (60 - 250 million)
					$taxed_income -= 190000000;

					if ($taxed_income > 0) {
						$final_tax += min(250000000, $taxed_income) * 25 / 100; # 25% for the next 250 million (250 - 500 million)
						$taxed_income -= 250000000;
					}
				}
			}

			$taxes_data[$customer_id]['tax'] = $final_tax - $result['tax'];
		}

		return $taxes_data;
	}
}

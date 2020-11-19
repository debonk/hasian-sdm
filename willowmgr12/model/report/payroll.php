<?php
class ModelReportPayroll extends Model {
	public function getTotalPayrollByPeriod($period_y_m) { //dashboard
		$payroll_status_id = $this->config->get('payroll_setting_generated_status_id'); //Change to completed

		$query = $this->db->query("SELECT SUM(gaji_pokok + tunj_jabatan + tunj_hadir + tunj_pph + total_uang_makan) AS sum_earning, SUM(pot_sakit + pot_bolos + pot_tunj_hadir + pot_gaji_pokok + pot_terlambat) AS sum_deduction, period FROM `" . DB_PREFIX . "presence_period` pp LEFT JOIN `" . DB_PREFIX . "payroll` p ON (p.presence_period_id = pp.presence_period_id) WHERE pp.payroll_status_id = '" . (int)$payroll_status_id . "' AND DATE_FORMAT(pp.period,'%Y-%c') = '" . $this->db->escape($period_y_m) . "'");

		return $query->row;
	}

	public function getTotalPayrollsByYear($component = 'grandtotal') { //chart
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
	
	public function getPayrolls($presence_period_id, $data = array()) {//model_report_payroll_tax
		$sql = "SELECT p.*, c.lastname as customer, cgd.name AS customer_group, l.name AS location, cad.*, g.name AS gender, g.code AS gender_code, ms.name AS marriage_status, ms.code AS marriage_status_code FROM " . DB_PREFIX . "payroll p LEFT JOIN " . DB_PREFIX . "customer c ON (c.customer_id = p.customer_id) LEFT JOIN " . DB_PREFIX . "customer_group_description cgd ON (cgd.customer_group_id = c.customer_group_id) LEFT JOIN " . DB_PREFIX . "location l ON (l.location_id = c.location_id) LEFT JOIN " . DB_PREFIX . "customer_add_data cad ON (cad.customer_id = c.customer_id) LEFT JOIN " . DB_PREFIX . "gender g ON (g.gender_id = cad.gender_id) LEFT JOIN " . DB_PREFIX . "marriage_status ms ON (ms.marriage_status_id = cad.marriage_status_id) WHERE presence_period_id = '" . (int)$presence_period_id . "'";

		$sort_data = array(
			'customer',
			'customer_group',
			'location'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY customer";
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

	public function getPayrollsCount($presence_period_id) {//report_payroll_tax
		$sql = "SELECT COUNT(*) as total FROM " . DB_PREFIX . "payroll WHERE presence_period_id = '" . (int)$presence_period_id . "'";

		$query = $this->db->query($sql);
		
		return $query->row['total'];
	}

	public function getComponents($presence_period_id, $customer_id = 0, $code = '') { //report_payroll_tax, report_payroll_insurance
		$sql = "SELECT * FROM " . DB_PREFIX . "payroll_component_value WHERE presence_period_id = '" . (int)$presence_period_id . "'";

		if ($customer_id) {
			$sql .= " AND customer_id = '" . (int)$customer_id . "'";
		}

		if ($code) {
			$code = explode (', ', $code);
			
			$sql .=" AND code IN ('" . implode("', '", $code) . "')";
		}
			
		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getComponentsTotalGroupByType($presence_period_id, $customer_id, $code = '') { //report_payroll_tax
		$sql = "SELECT customer_id, type, SUM(value) AS total FROM " . DB_PREFIX . "payroll_component_value WHERE presence_period_id = " . (int)$presence_period_id . " AND customer_id = ". (int)$customer_id;

		if ($code) {
			$code = explode (', ', $code);
			
			$sql .= " AND code IN ('" . implode("', '", $code) . "')";
		}
		
		$sql .= " GROUP BY customer_id, type";

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getComponentCustomers($presence_period_id, $data = array()) { //Used by: report_payroll_insurance
		$sql = "SELECT pcv.customer_id, c.nip, CONCAT(c.firstname, ' [', c.lastname, ']') AS name, cgd.name AS customer_group FROM " . DB_PREFIX . "payroll_component_value pcv LEFT JOIN " . DB_PREFIX . "customer c ON (c.customer_id = pcv.customer_id) LEFT JOIN " . DB_PREFIX . "customer_group_description cgd ON (cgd.customer_group_id = c.customer_group_id) WHERE presence_period_id = '" . (int)$presence_period_id . "'";

		if ($data['code']) {
			$sql .= " AND pcv.code = '" . $this->db->escape($data['code']) . "'";
		}

		$sql .= " GROUP BY pcv.customer_id ASC";
		
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

	public function getComponentCustomersCount($presence_period_id, $code = '') { //Used by: report_payroll_insurance
		$sql = "SELECT COUNT(DISTINCT customer_id) AS total FROM " . DB_PREFIX . "payroll_component_value WHERE presence_period_id = '" . (int)$presence_period_id . "'";

		if ($code) {
			$sql .= " AND code = '" . $this->db->escape($code) . "'";
		}

		$query = $this->db->query($sql);
		
		return $query->row['total'];
	}

	public function getTaxes($presence_period_id, $data) {//report_payroll_tax
		$results = $this->getPayrolls($presence_period_id, $data);

		$taxes_data = array();
	
		foreach ($results as $result) {
			if ($result['gender_code'] == 'L') {
				$marriage_status = $result['marriage_status_code'] . '/' . $result['children'];
			} else {
				$marriage_status = 'TK/0';
			}
			
			$salary = $result['gaji_pokok'] + $result['tunj_jabatan'] + $result['tunj_hadir'] + $result['tunj_pph'] + $result['total_uang_makan'] - $result['pot_sakit'] - $result['pot_bolos'] - $result['pot_tunj_hadir'] - $result['pot_gaji_pokok'] - $result['pot_terlambat'];
			
			$component_data = array(
				'1'	=> 0,
				'0'	=> 0
			);
			
			if (isset($data['code'])) {
				$code = $data['code'];
			} else {
				$code = '';
			}

			$components_total = $this->getComponentsTotalGroupByType($presence_period_id, $result['customer_id'], $code);
			
			if (!empty($components_total)) {
				foreach ($components_total as $value) {
					$component_data[$value['type']] =  $value['total'];
				}
			}
				
			$gross_salary = $salary + $component_data[1];
			$net_salary = $gross_salary - min(500000, 0.05 * $gross_salary) + $component_data[0];
			
			$non_taxed_income = 4500000;
			if ($result['gender_code'] == 'L') {
				if ($result['marriage_status_code'] == 'K') {
					$non_taxed_income += 375000;
				}

				if ($result['children']) {
					$non_taxed_income += min(3, $result['children']) * 375000;
				}
			}
			
			$taxed_income = max(0, $net_salary - $non_taxed_income);

			switch (true) {
				case ($taxed_income > 500000000):
					$tax = 0.3;
					break;
				case ($taxed_income > 250000000):
					$tax = 0.25;
					break;
				case ($taxed_income > 50000000):
					$tax = 0.15;
					break;
				default:
					$tax = 0.05;
			}
			
			if (empty($result['npwp'])) {
				$tax *= 1.2;
			}
			
			$tax_value = floor($taxed_income * $tax / 1000) * 1000;

			$taxes_data[] = array(
				'customer_id'		=> $result['customer_id'],
				'customer'			=> $result['customer'],
				'gender_code'		=> $result['gender_code'],
				'marriage_status'	=> $marriage_status,
				'customer_group'	=> $result['customer_group'],
				'npwp'				=> $result['npwp'],
				'npwp_address'		=> $result['npwp_address'],
				'location'			=> $result['location'],
				// 'salary'			=> $salary,
				'salary'			=> $salary + $component_data[1] + $component_data[0],
				'tax_value'			=> $tax_value
			);
		}

		return $taxes_data;
	}	
}

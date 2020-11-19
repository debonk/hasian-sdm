<?php
class ModelPayrollPayroll extends Model {
	public function getOvertimeStatus($customer_id) {
		$query = $this->db->query("SELECT DISTINCT full_overtime FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$customer_id . "'");

		return $query->row['full_overtime'];
	}

	public function getPayrollPeriods($data = array()) {
		$sql = "SELECT pp.*, ps.name AS payroll_status FROM " . DB_PREFIX . "presence_period pp LEFT JOIN " . DB_PREFIX . "payroll_status ps ON (ps.payroll_status_id = pp.payroll_status_id)";
		
		if (isset($data['filter_payroll_status'])) {
			$implode = array();

			$payroll_statuses = explode(',', $data['filter_payroll_status']);

			foreach ($payroll_statuses as $payroll_status_id) {
				$implode[] = "pp.payroll_status_id = '" . (int)$payroll_status_id . "'";
			}

			if ($implode) {
				$sql .= " WHERE (" . implode(" OR ", $implode) . ")";
			}
		} else {
			$sql .= " WHERE pp.payroll_status_id > '0'";
		}

		if (!empty($data['filter_period'])) {
			$sql .= " AND DATE_FORMAT(pp.period,'%b %y') = '" . $this->db->escape($data['filter_period']) . "'";
		}

		$sql .= " ORDER BY pp.presence_period_id DESC";

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getTotalPayrollPeriods($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "presence_period`";

		if (isset($data['filter_payroll_status'])) {
			$implode = array();

			$payroll_statuses = explode(',', $data['filter_payroll_status']);

			foreach ($payroll_statuses as $payroll_status_id) {
				$implode[] = "payroll_status_id = '" . (int)$payroll_status_id . "'";
			}

			if ($implode) {
				$sql .= " WHERE (" . implode(" OR ", $implode) . ")";
			}
		} else {
			$sql .= " WHERE payroll_status_id > '0'";
		}

		if (!empty($data['filter_period'])) {
			$sql .= " AND DATE_FORMAT(period,'%b %y') = '" . $this->db->escape($data['filter_period']) . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}
	
	public function addPayroll($presence_period_id, $customer_id, $data = array()) {
		if ($presence_period_id && $customer_id) {
			// $this->db->query("DELETE FROM " . DB_PREFIX . "payroll WHERE presence_period_id = '" . (int)$presence_period_id . "' AND customer_id = '" . (int)$customer_id . "'");		
			$this->db->query("INSERT INTO " . DB_PREFIX . "payroll SET presence_period_id = '" . (int)$presence_period_id . "', customer_id = '" . (int)$customer_id . "', gaji_pokok = '" . (int)$data['gaji_pokok'] . "', tunj_jabatan = '" . (int)$data['tunj_jabatan'] . "', tunj_hadir = '" . (int)$data['tunj_hadir'] . "', tunj_pph = '" . (int)$data['tunj_pph'] . "', uang_makan = '" . (int)$data['uang_makan'] . "', total_uang_makan = '" . (int)$data['total_uang_makan'] . "', pot_sakit = '" . (int)$data['pot_sakit'] . "', pot_bolos = '" . (int)$data['pot_bolos'] . "', pot_tunj_hadir = '" . (int)$data['pot_tunj_hadir'] . "', pot_gaji_pokok = '" . (int)$data['pot_gaji_pokok'] . "', pot_terlambat = '" . (int)$data['pot_terlambat'] . "', date_added = NOW()");
		} else {
			return;
		}
	}
	
	public function deletePayroll($presence_period_id, $customer_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "payroll WHERE presence_period_id = '" . (int)$presence_period_id . "' AND customer_id = '" . (int)$customer_id . "'");		
	}

	public function getPayroll($presence_period_id, $customer_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "payroll WHERE presence_period_id = '" . (int)$presence_period_id . "' AND customer_id = '" . (int)$customer_id . "'");

		return $query->row;
	}

	public function getPayrolls($presence_period_id, $data = array()) {
		$sql = "SELECT DISTINCT p.*, c.nip, c.email, CONCAT(c.firstname, ' [', c.lastname, ']') AS name, c.acc_no, c.date_start, c.date_end, cgd.name AS customer_group, pm.name AS payroll_method FROM " . DB_PREFIX . "payroll p LEFT JOIN " . DB_PREFIX . "customer c ON (c.customer_id = p.customer_id) LEFT JOIN " . DB_PREFIX . "customer_group_description cgd ON (cgd.customer_group_id = c.customer_group_id) LEFT JOIN " . DB_PREFIX . "payroll_method pm ON (pm.payroll_method_id = c.payroll_method_id) WHERE cgd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.presence_period_id = '" . (int)$presence_period_id . "'";

		$implode = array();

		if (!empty($data['filter_name'])) {
			$implode[] = "CONCAT(c.firstname, ' [', c.lastname, ']') LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_customer_group_id'])) {
			$implode[] = "c.customer_group_id = '" . (int)$data['filter_customer_group_id'] . "'";
		}

		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}

		$sort_data = array(
			'nip',
			'name',
			'customer_group'
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

	public function getTotalPayrolls($presence_period_id, $data = array()) {//Used by: payroll_release
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "payroll p LEFT JOIN " . DB_PREFIX . "customer c ON (c.customer_id = p.customer_id) WHERE p.presence_period_id = '" . (int)$presence_period_id . "'";

		$implode = array();

		if (!empty($data['filter_name'])) {
			$implode[] = "CONCAT(c.firstname, ' [', c.lastname, ']') LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_customer_group_id'])) {
			$implode[] = "c.customer_group_id = '" . (int)$data['filter_customer_group_id'] . "'";
		}

		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	protected function calculatePresenceSummary($presence_period_id, $customer_id) {//getPayrollDetail
		$this->load->model('presence/presence');
		
		$presence_summary_info = $this->model_presence_presence->getPresenceSummary($presence_period_id, $customer_id);

		if (!empty($presence_summary_info)) {
			$h 						= $presence_summary_info['total_h'];
			$s     					= $presence_summary_info['total_s'];
			$i    					= $presence_summary_info['total_i'];
			$ns     				= $presence_summary_info['total_ns'];
			$ia     				= $presence_summary_info['total_ia'];
			$a     					= $presence_summary_info['total_a'];
			$c     					= $presence_summary_info['total_c'];
			$t1     				= $presence_summary_info['total_t1'];
			$t2     				= $presence_summary_info['total_t2'];
			$t3     				= $presence_summary_info['total_t3'];
			$hke     				= $presence_summary_info['hke'];
			$total_sakit			= $presence_summary_info['total_sakit'];
			$total_bolos			= $presence_summary_info['total_bolos'];
			$total_t    			= $presence_summary_info['total_t'];
			$full_overtimes_count	= $presence_summary_info['full_overtimes_count'];
			
			$presence_summary_check = 1;
			
		} else {
			$h 					= 0;
			$s     				= 0;
			$i    				= 0;
			$ns     			= 0;
			$ia     			= 0;
			$a     				= 0;
			$c     				= 0;
			$t1     			= 0;
			$t2     			= 0;
			$t3     			= 0;
			$hke				= 0;
			$total_sakit		= 0;
			$total_bolos		= 0;
			$total_t			= 0;
			$full_overtimes_count = 0;

			$presence_summary_check = 0;
		}

		return array(
			'h'      				=> $h,
			's'      				=> $s,
			'i'       				=> $i,
			'ns'       				=> $ns,
			'ia'       				=> $ia,
			'a'       				=> $a,
			'c'       				=> $c,
			't1'       				=> $t1,
			't2'       				=> $t2,
			't3'       				=> $t3,
			'hke'					=> $hke,
			'total_sakit'       	=> $total_sakit,
			'total_bolos'       	=> $total_bolos,
			'total_t'       		=> $total_t,
			'full_overtimes_count'	=> $full_overtimes_count,
			'presence_summary_check'=> $presence_summary_check,
		);
	}

	public function calculatePayrollBasic($customer_id, $presence_data) {
		// $this->load->model('presence/presence');
		
		$hke     				= $presence_data['hke'];
		$full_overtimes_count	= $presence_data['full_overtimes_count'];
		$total_sakit			= $presence_data['total_sakit'];
		$total_bolos			= $presence_data['total_bolos'];
		$total_t				= $presence_data['total_t'];
		$total_absen			= $total_sakit + $total_bolos;

		$this->load->model('payroll/payroll_basic');
		
		$payroll_basic_info = $this->model_payroll_payroll_basic->getPayrollBasic($customer_id);
		
		$full_overtime_status = $this->getOvertimeStatus($customer_id);

		if (!empty($payroll_basic_info)) {
			if ($full_overtime_status) {
				$gaji_pokok     = $payroll_basic_info['gaji_pokok'] * 1.6;
				$tunj_jabatan   = $payroll_basic_info['tunj_jabatan'];
				$tunj_hadir     = $payroll_basic_info['tunj_hadir'] * 1.5;
				$tunj_pph       = $payroll_basic_info['tunj_pph'] * 1.5;
				$uang_makan     = $payroll_basic_info['uang_makan'] * 2;
				$date_added     = $payroll_basic_info['date_added'];
			} else {
				$gaji_pokok     = $payroll_basic_info['gaji_pokok'];
				$tunj_jabatan   = $payroll_basic_info['tunj_jabatan'];
				$tunj_hadir     = $payroll_basic_info['tunj_hadir'];
				$tunj_pph       = $payroll_basic_info['tunj_pph'];
				$uang_makan     = $payroll_basic_info['uang_makan'];
				$date_added     = $payroll_basic_info['date_added'];
			}

			$payroll_basic_check = 1;
			
			//jika cuti melahirkan (1 bulan penuh), koreksi lagi jika cuti tidak penuh 1 bulan
			if ($hke < 10) {
				$tunj_pph       = 0;
			}

			$total_uang_makan	= ($hke - $full_overtimes_count) * $uang_makan;
			$gaji_dasar			= $gaji_pokok + $tunj_jabatan + $tunj_hadir + $tunj_pph + $total_uang_makan;

			$pot_sakit			= $total_sakit * $uang_makan;

			if ($total_bolos) {
				$pot_bolos		= $total_bolos * $uang_makan + $tunj_pph;
			} else {
				$pot_bolos		= 0;
			}
			
			$pot_tunj_hadir		= min(5, $total_absen) * 0.2 * $tunj_hadir;

			if ($total_absen > 5) {
				$pot_gaji_pokok		= floor(((($gaji_pokok + $tunj_jabatan) / ($hke - $full_overtimes_count - 5)) * ($total_absen - 5)) / 5000) * 5000;
			} else {
				$pot_gaji_pokok		= 0;
			}
			
			$pot_terlambat		= $total_t * $uang_makan;

			$total_potongan		= $pot_sakit + $pot_bolos + $pot_tunj_hadir + $pot_gaji_pokok + $pot_terlambat;
		} else {
			$gaji_pokok     = 0;
			$tunj_jabatan   = 0;
			$tunj_hadir     = 0;
			$tunj_pph       = 0;
			$uang_makan     = 0;
			$date_added     = '';
			$total_uang_makan = 0;
			$gaji_dasar		= 0;
			$pot_sakit		= 0;
			$pot_bolos		= 0;
			$pot_tunj_hadir	= 0;
			$pot_gaji_pokok	= 0;
			$pot_terlambat	= 0;
			$total_potongan	= 0;

			$payroll_basic_check = 0;
		}

		return array(
			'gaji_pokok'            => $gaji_pokok,
			'tunj_jabatan'          => $tunj_jabatan,
			'tunj_hadir'            => $tunj_hadir,
			'tunj_pph'              => $tunj_pph,
			'uang_makan'            => $uang_makan,
			'date_added'            => $date_added,
			'total_uang_makan'      => $total_uang_makan,
			'gaji_dasar'			=> $gaji_dasar,
			'pot_sakit'		        => $pot_sakit,
			'pot_bolos'		        => $pot_bolos,
			'pot_tunj_hadir'	    => $pot_tunj_hadir,
			'pot_gaji_pokok'	    => $pot_gaji_pokok,
			'pot_terlambat'         => $pot_terlambat,
			'total_potongan'        => $total_potongan,
			'payroll_basic_check'	=> $payroll_basic_check
		);
	}

	public function getPayrollDetail($presence_period_id, $customer_id) {
		$presence_summary_info = $this->calculatePresenceSummary($presence_period_id, $customer_id);

		if ($this->model_common_payroll->checkPeriodStatus($presence_period_id, 'approved, released, completed')) {
			$payroll_info = $this->getPayroll($presence_period_id, $customer_id);
			$payroll_basic_check = 1;
		
		} else {
			$presence_data = array(
				'hke'					=> $presence_summary_info['hke'],
				'full_overtimes_count'	=> $presence_summary_info['full_overtimes_count'],
				'total_sakit'   		=> $presence_summary_info['total_sakit'],
				'total_bolos'   		=> $presence_summary_info['total_bolos'],
				'total_t'       		=> $presence_summary_info['total_t'],
			);
			
			$payroll_info = $this->calculatePayrollBasic($customer_id, $presence_data);
			
			$payroll_basic_check = $payroll_info['payroll_basic_check'];
		}

		$gaji_dasar			= $payroll_info['gaji_pokok'] + $payroll_info['tunj_jabatan'] + $payroll_info['tunj_hadir'] + $payroll_info['tunj_pph'] + $payroll_info['total_uang_makan'];
		$total_potongan		= $payroll_info['pot_sakit'] + $payroll_info['pot_bolos'] + $payroll_info['pot_tunj_hadir'] + $payroll_info['pot_gaji_pokok'] + $payroll_info['pot_terlambat'];

		return array(
			'presence_period_id'    => $presence_period_id,
			'customer_id'           => $customer_id,
			'hke'					=> $presence_summary_info['hke'],
			'h'      				=> $presence_summary_info['h'],
			's'      				=> $presence_summary_info['s'],
			'i'       				=> $presence_summary_info['i'],
			'ns'       				=> $presence_summary_info['ns'],
			'ia'       				=> $presence_summary_info['ia'],
			'a'       				=> $presence_summary_info['a'],
			'c'       				=> $presence_summary_info['c'],
			't1'       				=> $presence_summary_info['t1'],
			't2'       				=> $presence_summary_info['t2'],
			't3'       				=> $presence_summary_info['t3'],
			'full_overtimes_count'  => $presence_summary_info['full_overtimes_count'],
			'total_sakit'       	=> $presence_summary_info['total_sakit'],
			'total_bolos'       	=> $presence_summary_info['total_bolos'],
			'total_t'       		=> $presence_summary_info['total_t'],
			'presence_summary_check'=> $presence_summary_info['presence_summary_check'],
			'gaji_pokok'            => $payroll_info['gaji_pokok'],
			'tunj_jabatan'          => $payroll_info['tunj_jabatan'],
			'tunj_hadir'            => $payroll_info['tunj_hadir'],
			'tunj_pph'              => $payroll_info['tunj_pph'],
			'uang_makan'            => $payroll_info['uang_makan'],
			'date_added'            => $payroll_info['date_added'],
			'total_uang_makan'      => $payroll_info['total_uang_makan'],
			'gaji_dasar'			=> $gaji_dasar,
			'pot_sakit'		        => $payroll_info['pot_sakit'],
			'pot_bolos'		        => $payroll_info['pot_bolos'],
			'pot_tunj_hadir'	    => $payroll_info['pot_tunj_hadir'],
			'pot_gaji_pokok'	    => $payroll_info['pot_gaji_pokok'],
			'pot_terlambat'         => $payroll_info['pot_terlambat'],
			'total_potongan'        => $total_potongan,
			'payroll_basic_check'	=> $payroll_basic_check
		);
	}

	public function calculatePayrollComponent($presence_period_id, $customer_id) {
		$component_data = array();

		$this->load->model('extension/extension');

		$results = $this->model_extension_extension->getExtensions('component');

		foreach ($results as $result) {
			if ($this->config->get($result['code'] . '_status')) {
				$this->load->model('component/' . $result['code']);

				$component = $this->{'model_component_' . $result['code']}->getQuote($presence_period_id, $customer_id);

				if ($component) {
					$component_data[$result['code']] = $component;
				}
 			}
		}

		$sort_order = array();

		foreach ($component_data as $key => $value) {
			$sort_order[$key] = $value['sort_order'];
		}

		array_multisort($sort_order, SORT_ASC, $component_data);

		return $component_data;
	}

	public function addPayrollComponent($presence_period_id, $customer_id, $data = array()) {
		if ($presence_period_id && $customer_id) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "payroll_component_value SET presence_period_id = '" . (int)$presence_period_id . "', customer_id = '" . (int)$customer_id . "', code = '" . $this->db->escape($data['code']) . "', item = '" . (int)$data['item'] . "', title = '" . $this->db->escape($data['title']) . "', value = '" . (int)$data['value'] . "', type = '" . (int)$data['type'] . "', sort_order = '" . (int)$data['sort_order'] . "', user_id = '" . (int)$this->user->getId() . "', date_added = NOW()");
		} else {
			return;
		}
	}
	
	public function deletePayrollComponent($presence_period_id, $customer_id) {
		if ($presence_period_id && $customer_id) {
			$this->db->query("DELETE FROM " . DB_PREFIX . "payroll_component_value WHERE presence_period_id = '" . (int)$presence_period_id . "' AND customer_id = '" . (int)$customer_id . "'");		
		} else {
			return;
		}
	}
	
	public function getPayrollComponents($presence_period_id, $customer_id = 0) {
		$sql = "SELECT * FROM " . DB_PREFIX . "payroll_component_value WHERE presence_period_id = '" . (int)$presence_period_id . "'";

		if ($customer_id) {
			$sql .= " AND customer_id = '" . (int)$customer_id . "'";
		}

		$sql .= " ORDER BY sort_order ASC";
		
		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getPayrollComponentCodes($presence_period_id, $customer_id = 0) {
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

	public function getPayrollComponentTotal($presence_period_id, $customer_id = 0, $group_by = 'code') { //used by payroll_release
		$group_data = array(
			'code',
			'type'
		);

		if (in_array($group_by, $group_data)) {
			$sql = "SELECT " . $group_by . ", SUM(value) as total FROM " . DB_PREFIX . "payroll_component_value WHERE presence_period_id = '" . (int)$presence_period_id . "'";

			if ($customer_id) {
				$sql .= " AND customer_id = '" . (int)$customer_id . "'";
			}

			$sql .= " GROUP BY " . $group_by;

		} else {
			$sql = "SELECT SUM(value) as total FROM " . DB_PREFIX . "payroll_component_value WHERE presence_period_id = '" . (int)$presence_period_id . "'";

			if ($customer_id) {
				$sql .= " AND customer_id = '" . (int)$customer_id . "'";
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

	public function getTotalPayroll($presence_period_id, $data = array()) {//Summary
		$sql = "SELECT presence_period_id, COUNT(*) as total_customer, SUM(gaji_pokok + tunj_jabatan + tunj_hadir + tunj_pph + total_uang_makan) AS total_earning, SUM(pot_sakit + pot_bolos + pot_tunj_hadir + pot_gaji_pokok + pot_terlambat) AS total_deduction FROM " . DB_PREFIX . "payroll p LEFT JOIN " . DB_PREFIX . "customer c ON (c.customer_id = p.customer_id)WHERE presence_period_id = '" . (int)$presence_period_id . "'";
		
		$implode = array();

		if (!empty($data['filter_name'])) {
			$implode[] = "CONCAT(c.firstname, ' [', c.lastname, ']') LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_customer_group_id'])) {
			$implode[] = "c.customer_group_id = '" . (int)$data['filter_customer_group_id'] . "'";
		}

		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return $query->row;
	}

	public function getBlankPayrollCustomers($presence_period_id) {
		$period_info = $this->model_common_payroll->getPeriod($presence_period_id);
		
		$sql = "SELECT c.customer_id, c.nip, CONCAT(c.firstname, ' [', c.lastname, ']') AS name FROM " . DB_PREFIX . "customer c LEFT JOIN " . DB_PREFIX . "payroll p ON (p.customer_id = c.customer_id AND p.presence_period_id = '" . (int)$presence_period_id . "') WHERE status = 1 AND payroll_include = 1 AND date_start <= '" . $period_info['date_end'] . "' AND (date_end IS NULL OR date_end = '0000-00-00' OR date_end > '" . $period_info['date_start'] . "') AND p.payroll_id IS NULL";

		$query = $this->db->query($sql);
		
		return $query->rows;
	}
}

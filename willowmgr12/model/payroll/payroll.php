<?php
class ModelPayrollPayroll extends Model
{
	public function getOvertimeStatus($customer_id)
	{
		$query = $this->db->query("SELECT DISTINCT full_overtime FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$customer_id . "'");

		return $query->row['full_overtime'];
	}

	public function getPayrollPeriods($data = array())
	{
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

	public function getTotalPayrollPeriods($data = array())
	{
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

	public function addPayroll($presence_period_id, $customer_id, $data = array())
	{
		if ($presence_period_id && $customer_id) {
			$sql = "INSERT INTO " . DB_PREFIX . "payroll SET presence_period_id = '" . (int)$presence_period_id . "', customer_id = '" . (int)$customer_id . "', payroll_basic_id = '" . (int)$data['payroll_basic_id'] . "', statement_sent = 0";

			$implode = [];
			$title = [];

			foreach ($data['addition'] as $key => $addition) {
				$implode[] = "addition_" . $key . " = '" . (int)$addition['value'] . "'";
				$title['addition'][$key] = $addition['title'];
			}

			foreach ($data['deduction'] as $key => $deduction) {
				$implode[] = "deduction_" . $key . " = '" . (int)$deduction['value'] . "'";
				$title['deduction'][$key] = $deduction['title'];
			}

			if ($implode) {
				$sql .= ", " . implode(", ", $implode);
			}

			$sql .= ", title = '" . $this->db->escape(json_encode($title)) . "'";

			$this->db->query($sql);
		} else {
			return;
		}
	}

	public function deletePayroll($presence_period_id, $customer_id)
	{
		$this->db->query("DELETE FROM " . DB_PREFIX . "payroll WHERE presence_period_id = '" . (int)$presence_period_id . "' AND customer_id = '" . (int)$customer_id . "'");
	}

	public function getPayroll($presence_period_id, $customer_id)
	{
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "v_payroll WHERE presence_period_id = '" . (int)$presence_period_id . "' AND customer_id = '" . (int)$customer_id . "'");

		return $query->row;
	}

	public function getPayrolls($presence_period_id, $data = array())
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "v_payroll WHERE presence_period_id = '" . (int)$presence_period_id . "'";

		$implode = array();

		if (!empty($data['filter_name'])) {
			$implode[] = "name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_customer_group_id'])) {
			$implode[] = "customer_group_id = '" . (int)$data['filter_customer_group_id'] . "'";
		}

		if (!empty($data['filter_customer_department_id'])) {
			$implode[] = "customer_department_id = '" . (int)$data['filter_customer_department_id'] . "'";
		}

		if (!empty($data['filter_location_id'])) {
			$implode[] = "location_id = '" . (int)$data['filter_location_id'] . "'";
		}

		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}

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

	public function getTotalPayrolls($presence_period_id, $data = array())
	{
		$sql = "SELECT COUNT(payroll_id) AS total FROM " . DB_PREFIX . "v_payroll WHERE presence_period_id = '" . (int)$presence_period_id . "'";
		// $sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "payroll p LEFT JOIN " . DB_PREFIX . "customer c ON (c.customer_id = p.customer_id) WHERE p.presence_period_id = '" . (int)$presence_period_id . "'";

		$implode = array();

		if (!empty($data['filter_name'])) {
			$implode[] = "name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_customer_group_id'])) {
			$implode[] = "customer_group_id = '" . (int)$data['filter_customer_group_id'] . "'";
		}

		if (!empty($data['filter_customer_department_id'])) {
			$implode[] = "customer_department_id = '" . (int)$data['filter_customer_department_id'] . "'";
		}

		if (!empty($data['filter_location_id'])) {
			$implode[] = "location_id = '" . (int)$data['filter_location_id'] . "'";
		}

		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	protected function calculatePresenceSummary($presence_period_id, $customer_id)
	{ //getPayrollDetail
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
			$total_absen			= $presence_summary_info['total_absen'];
			// $total_t    			= $presence_summary_info['total_t'];
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
			$total_absen		= 0;
			// $total_t			= 0;
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
			'total_absen'       	=> $total_absen,
			// 'total_t'       		=> $total_t, //remove
			'full_overtimes_count'	=> $full_overtimes_count,
			'presence_summary_check' => $presence_summary_check,
		);
	}

	private function calculateMainComponent($formula, $presence_data)
	{
		$value_data = 0;
		var_dump($formula, $presence_data);
		switch ($formula) {
			case '{gp}':
				$value_data = $presence_data['gp'];

				break;

			default:
				# code...
				break;
		}

		return $value_data;
	}

	private $main_component = [
		'addition'			=> [
			'gp'			=> '',
			'tj'			=> '',
			'th'			=> '',
			'pph'			=> '',
			'total_um'		=> '{hke} x {um}'
		],
		'deduction'	=> [
			'pot_um'		=> '{total_absen} * {um}',
			'pot_pph'		=> '',
			'pot_th'		=> '',
			'pot_gp_tj'		=> '',
			'pot_t'			=> '({t1}+{t2}+{t3})*{um}',
			'pot_t_2'		=> '({t1}+3*{t2}+5*{t3})*{um}',
		]
	];

	public function calculatePayrollBasic($customer_id, $presence_data)
	{
		$hke     				= $presence_data['hke'];
		$full_overtimes_count	= $presence_data['full_overtimes_count'];
		// $total_sakit			= $presence_data['total_sakit'];
		$total_bolos			= $presence_data['total_bolos'];
		// $total_t				= $presence_data['total_t'];
		$total_absen			= $presence_data['total_absen'];

		$this->load->model('payroll/payroll_basic');
		$payroll_basic_info = $this->model_payroll_payroll_basic->getPayrollBasicByCustomer($customer_id);

		$full_overtime_status = $this->getOvertimeStatus($customer_id);

		if (!empty($payroll_basic_info)) {
			if ($full_overtime_status) {
				$payroll_basic_info['gaji_pokok'] *=  1.6;
				// $tunj_jabatan   = $payroll_basic_info['tunj_jabatan'];
				$payroll_basic_info['tunj_hadir'] *= 1.5;
				$payroll_basic_info['tunj_pph'] *= 1.5;
				$payroll_basic_info['uang_makan'] *= 2;
			}

			// $gaji_pokok     = $payroll_basic_info['gaji_pokok'];
			// $tunj_jabatan   = $payroll_basic_info['tunj_jabatan'];
			// $tunj_hadir     = $payroll_basic_info['tunj_hadir'];
			$tunj_pph       = $payroll_basic_info['tunj_pph'];
			// $uang_makan     = $payroll_basic_info['uang_makan'];
			// $date_added     = $payroll_basic_info['date_added'];

			$payroll_basic_check = 1;

			//jika cuti melahirkan (1 bulan penuh), koreksi lagi jika cuti tidak penuh 1 bulan
			if ($hke < 10) {
				$tunj_pph       = 0;
			}

			$main_component = [];

			$customer_info = $this->model_common_payroll->getCustomer($customer_id);

			$this->load->model('payroll/payroll_type');
			$payroll_type_info = $this->model_payroll_payroll_type->getPayrollTypeComponents($customer_info['payroll_type_id']);

			if ($payroll_type_info) {
				foreach ($payroll_type_info as $key => $components) {
					foreach ($components as $component) {
						$value = 0;
						$subtitle = '';

						switch ($component['item']) {
							case 'gp':
								$value = $payroll_basic_info['gaji_pokok'];
								break;

							case 'tj':
								$value = $payroll_basic_info['tunj_jabatan'];
								break;

							case 'th':
								$value = $payroll_basic_info['tunj_hadir'];
								break;

							case 'pph':
								$value = $tunj_pph;
								break;

							case 'total_um':
								$value = ($hke - $full_overtimes_count) * $payroll_basic_info['uang_makan'];
								$subtitle = $hke - $full_overtimes_count . ' x {um}';
								break;

							case 'pot_um':
								$value = $total_absen * $payroll_basic_info['uang_makan'];
								$subtitle = $total_absen . ' x {um}';
								break;

							case 'pot_pph':
								$value = min(1, $total_bolos) * $tunj_pph;
								break;

							case 'pot_th':
								$value = min(5, $total_absen) * 0.2 * $payroll_basic_info['tunj_hadir'];
								$subtitle = min(5, $total_absen) * 20 . '% x {th}';
								break;

							case 'pot_gp_tj':
								if ($total_absen > 5) {
									$value = floor((($payroll_basic_info['gaji_pokok'] + $payroll_basic_info['tunj_jabatan']) / ($hke - $full_overtimes_count - 5) * ($total_absen - 5)) / 5000) * 5000;
									$subtitle = '≈' . ((($total_absen - 5) / ($hke - $full_overtimes_count - 5)) * 100) . '% x {gp_tj}';
								}

								break;

							case 'pot_t':
								$value = ($presence_data['t1'] + $presence_data['t2'] + $presence_data['t3']) * $payroll_basic_info['uang_makan'];
								$subtitle = ($presence_data['t1'] + $presence_data['t2'] + $presence_data['t3']) . ' x {um}';
								break;

							case 'pot_t_2':
								$value = ($presence_data['t1'] + 3 * $presence_data['t2'] + 5 * $presence_data['t3']) * $payroll_basic_info['uang_makan'];
								$subtitle = '≈' . ($presence_data['t1'] + 3 * $presence_data['t2'] + 5 * $presence_data['t3']) . ' x {um}';
								break;

							default:
								break;
						}

						$main_component[$key][] = [
							'title'	=> $component['title'] . ($subtitle ? ' (' . $subtitle . ')' : ''),
							// 'item'	=> $component['item'],
							'value'	=> $value
						];

						$main_component['total_' . $key] = isset($main_component['total_' . $key]) ? $main_component['total_' . $key] + $value : $value;
					}
				}
			}

			// $total_uang_makan	= ($hke - $full_overtimes_count) * $uang_makan;
			// $gaji_dasar			= $gaji_pokok + $tunj_jabatan + $tunj_hadir + $tunj_pph + $total_uang_makan;

			// $pot_sakit			= $total_sakit * $uang_makan;

			// if ($total_bolos) {
			// 	$pot_bolos		= $total_bolos * $uang_makan + $tunj_pph;
			// } else {
			// 	$pot_bolos		= 0;
			// }

			// $pot_tunj_hadir		= min(5, $total_absen) * 0.2 * $tunj_hadir;

			// if ($total_absen > 5) {
			// 	$pot_gaji_pokok		= floor(((($gaji_pokok + $tunj_jabatan) / ($hke - $full_overtimes_count - 5)) * ($total_absen - 5)) / 5000) * 5000;
			// } else {
			// 	$pot_gaji_pokok		= 0;
			// }

			// $pot_terlambat		= $total_t * $uang_makan;

			// $total_potongan		= $pot_sakit + $pot_bolos + $pot_tunj_hadir + $pot_gaji_pokok + $pot_terlambat;
		} else {
			// $gaji_pokok     = 0;
			// $tunj_jabatan   = 0;
			// $tunj_hadir     = 0;
			// $tunj_pph       = 0;
			// $uang_makan     = 0;
			// $date_added     = '';
			// $total_uang_makan = 0;
			// $gaji_dasar		= 0;
			// $pot_sakit		= 0;
			// $pot_bolos		= 0;
			// $pot_tunj_hadir	= 0;
			// $pot_gaji_pokok	= 0;
			// $pot_terlambat	= 0;
			// $total_potongan	= 0;
			$payroll_basic_info	= [];
			$main_component		= [];

			$payroll_basic_check = 0;
		}

		return array(
			// 'gaji_pokok'            => $gaji_pokok,
			// 'tunj_jabatan'          => $tunj_jabatan,
			// 'tunj_hadir'            => $tunj_hadir,
			// 'tunj_pph'              => $tunj_pph,
			// 'uang_makan'            => $uang_makan,
			// 'date_added'            => $date_added,
			// 'total_uang_makan'      => $total_uang_makan,
			// 'gaji_dasar'			=> $gaji_dasar,
			// 'pot_sakit'		        => $pot_sakit,
			// 'pot_bolos'		        => $pot_bolos,
			// 'pot_tunj_hadir'	    => $pot_tunj_hadir,
			// 'pot_gaji_pokok'	    => $pot_gaji_pokok,
			// 'pot_terlambat'         => $pot_terlambat,
			// 'total_potongan'        => $total_potongan,
			'payroll_basic'		    => $payroll_basic_info,
			'main_component'        => $main_component,
			'payroll_basic_check'	=> $payroll_basic_check
		);
	}

	public function getPayrollDetail($presence_period_id, $customer_id)
	{
		$presence_summary_info = $this->calculatePresenceSummary($presence_period_id, $customer_id);

		if ($this->model_common_payroll->checkPeriodStatus($presence_period_id, 'approved, released, completed')) {
			$payroll_info = $this->getPayroll($presence_period_id, $customer_id);

			# Handling old version V3
			if ($payroll_info['payroll_basic_id']) {
				$this->load->model('payroll/payroll_basic');
				$payroll_basic_info = $this->model_payroll_payroll_basic->getPayrollBasic($payroll_info['payroll_basic_id']);
			} else {
				$payroll_basic_info = [
					'gaji_pokok'	=> $payroll_info['addition_0'],
					'tunj_jabatan'	=> $payroll_info['addition_1'],
					'tunj_hadir'	=> $payroll_info['addition_2'],
					'tunj_pph'		=> $payroll_info['addition_3'],
					'uang_makan'	=> $payroll_info['addition_4'] / $presence_summary_info['hke'],
					'date_added'	=> $payroll_info['date_added']
				];
			}

			# Handling old version V3
			if ($payroll_info['title']) {
				$main_component_title = json_decode($payroll_info['title'], 1);
			} else {
				$main_component_title = [
					'addition'	=> ['Gaji Pokok', 'Tunjangan Jabatan', 'Tunjangan Kehadiran', 'Premi Prestasi Hadir', 'Total Uang Makan ({hke} x {um})'],
					'deduction'	=> ['Potongan Sakit ({total_sakit} x {um})', 'Potongan Alpa ({total_bolos} x {um}) + PPH', 'Potongan Tunjangan Kehadiran', 'Potongan Gaji Pokok & Jabatan', 'Potongan Terlambat (≈' . $payroll_info['deduction_4'] / $payroll_basic_info['uang_makan'] . ' x {um})']
				];
			}

			$payroll_info['main_component'] = [
				'addition'			=> [],
				'deduction'			=> [],
				'total_addition'	=> 0,
				'total_deduction'	=> 0
			];

			for ($i = 0; $i < 5; $i++) {
				$payroll_info['main_component']['addition'][] = [
					'title'	=> $main_component_title['addition'][$i],
					'value'	=> $payroll_info['addition_' . $i]
				];

				$payroll_info['main_component']['total_addition'] += $payroll_info['addition_' . $i];

				$payroll_info['main_component']['deduction'][] = [
					'title'	=> $main_component_title['deduction'][$i],
					'value'	=> $payroll_info['deduction_' . $i]
				];

				$payroll_info['main_component']['total_deduction'] += $payroll_info['deduction_' . $i];
			}

			$payroll_info['payroll_basic'] = $payroll_basic_info;

			$payroll_basic_check = 1;
			$payroll_id = $payroll_info['payroll_id'];
		} else {
			// $presence_data = array(
			// 	'hke'					=> $presence_summary_info['hke'],
			// 	'full_overtimes_count'	=> $presence_summary_info['full_overtimes_count'],
			// 	'total_sakit'   		=> $presence_summary_info['total_sakit'],
			// 	'total_bolos'   		=> $presence_summary_info['total_bolos'],
			// 	'total_t'       		=> $presence_summary_info['total_t'],
			// );

			// $payroll_info = $this->calculatePayrollBasic($customer_id, $presence_data);
			$payroll_info = $this->calculatePayrollBasic($customer_id, $presence_summary_info);

			$payroll_basic_check = $payroll_info['payroll_basic_check'];
			$payroll_id = 0;
		}

		// $gaji_dasar			= $payroll_info['gaji_pokok'] + $payroll_info['tunj_jabatan'] + $payroll_info['tunj_hadir'] + $payroll_info['tunj_pph'] + $payroll_info['total_uang_makan'];
		// $total_potongan		= $payroll_info['pot_sakit'] + $payroll_info['pot_bolos'] + $payroll_info['pot_tunj_hadir'] + $payroll_info['pot_gaji_pokok'] + $payroll_info['pot_terlambat'];

		return array(
			'payroll_id'           	=> $payroll_id,
			'presence_period_id'    => $presence_period_id,
			'customer_id'           => $customer_id,
			'presence_summary'		=> $presence_summary_info,
			'payroll_basic'			=> $payroll_info['payroll_basic'],
			'main_component'        => $payroll_info['main_component'],
			'presence_summary_check' => $presence_summary_info['presence_summary_check'],
			'payroll_basic_check'	=> $payroll_basic_check

			// 'hke'					=> $presence_summary_info['hke'],
			// 'h'      				=> $presence_summary_info['h'],
			// 's'      				=> $presence_summary_info['s'],
			// 'i'       				=> $presence_summary_info['i'],
			// 'ns'       				=> $presence_summary_info['ns'],
			// 'ia'       				=> $presence_summary_info['ia'],
			// 'a'       				=> $presence_summary_info['a'],
			// 'c'       				=> $presence_summary_info['c'],
			// 't1'       				=> $presence_summary_info['t1'],
			// 't2'       				=> $presence_summary_info['t2'],
			// 't3'       				=> $presence_summary_info['t3'],
			// 'full_overtimes_count'  => $presence_summary_info['full_overtimes_count'],
			// 'total_sakit'       	=> $presence_summary_info['total_sakit'],
			// 'total_bolos'       	=> $presence_summary_info['total_bolos'],
			// 'total_t'       		=> $presence_summary_info['total_t'],
			// 'gaji_pokok'            => $payroll_info['gaji_pokok'],
			// 'tunj_jabatan'          => $payroll_info['tunj_jabatan'],
			// 'tunj_hadir'            => $payroll_info['tunj_hadir'],
			// 'tunj_pph'              => $payroll_info['tunj_pph'],
			// 'uang_makan'            => $payroll_info['uang_makan'],
			// 'date_added'            => $payroll_info['date_added'],

			// 'total_uang_makan'      => $payroll_info['total_uang_makan'],
			// 'gaji_dasar'			=> $gaji_dasar,
			// 'pot_sakit'		        => $payroll_info['pot_sakit'],
			// 'pot_bolos'		        => $payroll_info['pot_bolos'],
			// 'pot_tunj_hadir'	    => $payroll_info['pot_tunj_hadir'],
			// 'pot_gaji_pokok'	    => $payroll_info['pot_gaji_pokok'],
			// 'pot_terlambat'         => $payroll_info['pot_terlambat'],
			// 'total_potongan'        => $total_potongan,
		);
	}

	public function calculatePayrollComponent($presence_period_id, $customer_id)
	{
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

	public function addPayrollComponent($presence_period_id, $customer_id, $data_array = array())
	{
		if ($presence_period_id && $customer_id) {
			foreach ($data_array as $data) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "payroll_component_value SET presence_period_id = '" . (int)$presence_period_id . "', customer_id = '" . (int)$customer_id . "', code = '" . $this->db->escape($data['code']) . "', item = '" . (int)$data['item'] . "', title = '" . $this->db->escape($data['title']) . "', value = '" . (int)$data['value'] . "', type = '" . (int)$data['type'] . "', sort_order = '" . (int)$data['sort_order'] . "', user_id = '" . (int)$this->user->getId() . "', date_added = NOW()");
			}
		} else {
			return;
		}
	}

	public function deletePayrollComponent($presence_period_id, $customer_id)
	{
		if ($presence_period_id && $customer_id) {
			$this->db->query("DELETE FROM " . DB_PREFIX . "payroll_component_value WHERE presence_period_id = '" . (int)$presence_period_id . "' AND customer_id = '" . (int)$customer_id . "'");
		} else {
			return;
		}
	}

	public function getPayrollComponents($presence_period_id, $customer_id = 0)
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "payroll_component_value WHERE presence_period_id = '" . (int)$presence_period_id . "'";

		if ($customer_id) {
			$sql .= " AND customer_id = '" . (int)$customer_id . "'";
		}

		$sql .= " ORDER BY sort_order ASC";

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

		if (!empty($data['filter']['name'])) {
			$implode[] = "c.name LIKE '%" . $this->db->escape($data['filter']['name']) . "%'";
		}

		if (!empty($data['filter']['customer_group_id'])) {
			$implode[] = "c.customer_group_id = '" . (int)$data['filter']['customer_group_id'] . "'";
		}

		if (!empty($data['filter']['customer_department_id'])) {
			$implode[] = "c.customer_department_id = '" . (int)$data['filter']['customer_department_id'] . "'";
		}

		if (!empty($data['filter']['location_id'])) {
			$implode[] = "c.location_id = '" . (int)$data['filter']['location_id'] . "'";
		}

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

	public function getPayrollSummary($presence_period_id, $data = array())
	{ //Summary
		$sql = "SELECT presence_period_id, COUNT(*) as total_customer, SUM(net_salary) AS total_net_salary, SUM(component) AS total_component FROM " . DB_PREFIX . "v_payroll WHERE presence_period_id = '" . (int)$presence_period_id . "'";

		$implode = array();

		if (!empty($data['filter']['name'])) {
			$implode[] = "name LIKE '%" . $this->db->escape($data['filter']['name']) . "%'";
		}

		if (!empty($data['filter']['customer_group_id'])) {
			$implode[] = "customer_group_id = '" . (int)$data['filter']['customer_group_id'] . "'";
		}

		if (!empty($data['filter']['customer_department_id'])) {
			$implode[] = "customer_department_id = '" . (int)$data['filter']['customer_department_id'] . "'";
		}

		if (!empty($data['filter']['location_id'])) {
			$implode[] = "location_id = '" . (int)$data['filter']['location_id'] . "'";
		}

		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return $query->row;
	}

	public function getBlankPayrollCustomers($presence_period_id)
	{
		$period_info = $this->model_common_payroll->getPeriod($presence_period_id);

		$sql = "SELECT c.customer_id, c.nip, CONCAT(c.firstname, ' [', c.lastname, ']') AS name FROM " . DB_PREFIX . "customer c LEFT JOIN " . DB_PREFIX . "payroll p ON (p.customer_id = c.customer_id AND p.presence_period_id = '" . (int)$presence_period_id . "') WHERE status = 1 AND payroll_include = 1 AND date_start <= '" . $period_info['date_end'] . "' AND (date_end IS NULL OR date_end = '0000-00-00' OR date_end > '" . $period_info['date_start'] . "') AND p.payroll_id IS NULL";

		$query = $this->db->query($sql);

		return $query->rows;
	}
}

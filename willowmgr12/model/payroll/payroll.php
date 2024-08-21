<?php
class ModelPayrollPayroll extends Model
{
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

	public function calculatePayrollBasic($customer_id, $presence_data)
	{
		$main_component = [
			'addition'	=> [],
			'deduction'	=> [],
			'total'		=> [
				'addition'	=> 0,
				'deduction'	=> 0
			]
		];

		$this->load->model('payroll/payroll_basic');
		$payroll_basic_info = $this->model_payroll_payroll_basic->getPayrollBasicByCustomer($customer_id);

		if (!empty($payroll_basic_info)) {
			$payroll_basic_info['gaji_dasar'] = $payroll_basic_info['gaji_pokok'] + $payroll_basic_info['tunj_jabatan'] + $payroll_basic_info['tunj_hadir'] + $payroll_basic_info['tunj_pph'] + $payroll_basic_info['uang_makan'] * 25;

			$tunj_pph       = $payroll_basic_info['tunj_pph'];

			//jika cuti melahirkan (1 bulan penuh), koreksi lagi jika cuti tidak penuh 1 bulan
			if ($presence_data['total']['hke'] < 10) {
				$tunj_pph       = 0;
			}

			$customer_info = $this->model_common_payroll->getCustomer($customer_id);

			$this->load->model('payroll/payroll_type');
			$payroll_type_info = $this->model_payroll_payroll_type->getPayrollTypeComponents($customer_info['payroll_type_id']);

			$presence_value_data = [];

			foreach ($presence_data as $components) {
				foreach ($components as $key => $value) {
					$presence_value_data['{' . $key . '}'] = $value;
				}
			}

			if ($payroll_type_info) {
				foreach ($payroll_type_info as $key => $components) {
					foreach ($components as $component) {
						$value = 0;
						$subtitle = '';

						$var = str_replace(array_keys($presence_value_data), array_values($presence_value_data), $component['variable']);
						$var = preg_replace('/[^\d+\-*\/]/', '', $var);

						if ($var) {
							eval('$var = @(' . $var . ');');
						} else {
							$var = 0;
						}

						switch ($component['code']) {
							case 'gp':
								$value = $var * $payroll_basic_info['gaji_pokok'];
								break;

							case 'tj':
								$value = $var * $payroll_basic_info['tunj_jabatan'];
								break;

							case 'th':
								$value = $var * $payroll_basic_info['tunj_hadir'];
								break;

							case 'pph':
								$value = $var * $tunj_pph;
								break;

							case 'total_um':
								$value = $var * $payroll_basic_info['uang_makan'];
								$subtitle = $var . ' x {um}';
								break;

							case 'pot_um':
								$value = $var * $payroll_basic_info['uang_makan'];
								$subtitle = $var . ' x {um}';
								break;

							case 'pot_pph':
								$value = min(1, $var) * $tunj_pph;
								break;

							case 'pot_gp_tj_5':
								if ($var > 5) {
									$value = floor((($payroll_basic_info['gaji_pokok'] + $payroll_basic_info['tunj_jabatan']) / ($presence_data['total']['hke'] - 5) * ($var - 5)) / 5000) * 5000;
									$subtitle = ($var - 5) . '/' . ($presence_data['total']['hke'] - 5) . ' x {gp_tj}';
								}

								break;

							case 'pot_gp_tj':
								$value = floor((($payroll_basic_info['gaji_pokok'] + $payroll_basic_info['tunj_jabatan']) / $presence_data['total']['hke'] * $var) / 5000) * 5000;
								$subtitle = $var . '/' . $presence_data['total']['hke'] . ' x {gp}';

								break;

							case 'pot_gp':
								$value = floor(($payroll_basic_info['gaji_pokok'] / $presence_data['total']['hke'] * $var) / 5000) * 5000;
								$subtitle = $var . '/' . $presence_data['total']['hke'] . ' x {gp}';

								break;

							case 'pot_tj':
								$value = floor(($payroll_basic_info['tunj_jabatan'] / $presence_data['total']['hke'] * $var) / 5000) * 5000;
								$subtitle = $var . '/' . $presence_data['total']['hke'] . ' x {gp}';

								break;

							case 'pot_th_20':
								$value = min(5, $var) * 0.2 * $payroll_basic_info['tunj_hadir'];
								$subtitle = min(5, $var) * 20 . '% x {th}';
								break;

							case 'pot_th_100':
								$value = min(1, $var) * $payroll_basic_info['tunj_hadir'];
								$subtitle = min(1, $var) * 100 . '% x {th}';
								break;

							case 'pot_prop_all':
								$pot_all_var = $var;
								$pot_all_title = $component['title'] . ' (' . ($presence_data['total']['hke'] - $presence_data['primary']['h']) . '/' . $presence_data['total']['hke'] . ' x {thp})';
								break;

							default:
								break;
						}

						if ($component['code'] != 'pot_prop_all') {
							$main_component[$key][] = [
								'title'	=> $component['title'] . ($subtitle ? ' (' . $subtitle . ')' : ''),
								'value'	=> $value
							];

							$main_component['total'][$key] += $value;
						}
					}
				}

				# Pot proporsional yang memotong THP secara pro rata, tanpa memperhitungkan presence lain.
				if (!empty($pot_all_var)) {
					unset($main_component['deduction']);

					$value = floor((($presence_data['total']['hke'] - $presence_data['primary']['h']) / $presence_data['total']['hke'] * $main_component['total']['addition']) / 5000) * 5000;

					$main_component['deduction'][] = [
						'title'	=> $pot_all_title,
						'value'	=> $value
					];

					$main_component['total']['deduction'] = $value;
				}
			}
		}

		return array(
			'payroll_basic'		    => $payroll_basic_info,
			'main_component'        => $main_component
		);
	}

	public function getPayrollDetail($presence_period_id, $customer_id)
	{
		$this->load->model('presence/presence');
		$presence_summary_info = $this->model_presence_presence->getPresenceSummary($presence_period_id, $customer_id);

		if ($this->model_common_payroll->checkPeriodStatus($presence_period_id, 'approved, released, completed')) {
			$payroll_info = $this->getPayroll($presence_period_id, $customer_id);

			if (!$payroll_info) {
				$payroll_id = 0;

				$payroll_info = [
					'payroll_basic'		=> [],
					'main_component'	=> [],
					'sub_component'		=> []
				];
			} else {
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
						'uang_makan'	=> $payroll_info['addition_4'] / $presence_summary_info['total']['hke'],
						'date_added'	=> $payroll_info['date_added']
					];
				}

				# Handling old version V3
				if ($payroll_info['title']) {
					$main_component_title = json_decode($payroll_info['title'], 1);
				} else {
					$main_component_title = [
						'addition'	=> ['Gaji Pokok', 'Tunjangan Jabatan', 'Tunjangan Kehadiran', 'Premi Prestasi Hadir', 'Total Uang Makan ({hke} x {um})'],
						'deduction'	=> ['Potongan Sakit ({total_sakit} x {um})', 'Potongan Alpa ({total_bolos} x {um}) + PPH', 'Potongan Tunjangan Kehadiran', 'Potongan Gaji Pokok & Jabatan', 'Potongan Terlambat (≈' . ($payroll_basic_info['uang_makan'] ? $payroll_info['deduction_4'] / $payroll_basic_info['uang_makan'] : 0) . ' x {um})']
					];
				}

				$payroll_info['main_component'] = [
					'addition'			=> [],
					'deduction'			=> [],
					'total'				=> [
						'addition'	=> 0,
						'deduction'	=> 0
					]
				];

				foreach ($main_component_title as $group => $titles) {
					foreach ($titles as $i => $title) {
						$payroll_info['main_component'][$group][] = [
							'title'	=> $title,
							'value'	=> $payroll_info[$group . '_' . $i]
						];

						$payroll_info['main_component']['total'][$group] += $payroll_info[$group . '_' . $i];
					}
				}

				$payroll_info['payroll_basic'] = $payroll_basic_info;

				$payroll_id = $payroll_info['payroll_id'];
			}
		} else {
			$payroll_info = $this->calculatePayrollBasic($customer_id, $presence_summary_info);

			$payroll_id = 0;
		}

		if ($payroll_info['payroll_basic']) {
			$find = [
				'{hke}',
				'{gp_tj}',
				'{gp}',
				'{th}',
				'{um}',
				'{thp}',
				'{total_sakit}', # Handling v3
				'{total_bolos}' # Handling v3
			];

			$replace = [
				$presence_summary_info['total']['hke'],
				$this->currency->format($payroll_info['payroll_basic']['gaji_pokok'] + $payroll_info['payroll_basic']['tunj_jabatan'], $this->config->get('config_currency')),
				$this->currency->format($payroll_info['payroll_basic']['gaji_pokok'], $this->config->get('config_currency')),
				$this->currency->format($payroll_info['payroll_basic']['tunj_hadir'], $this->config->get('config_currency')),
				$this->currency->format($payroll_info['payroll_basic']['uang_makan'], $this->config->get('config_currency')),
				$this->currency->format($payroll_info['main_component']['total']['addition'], $this->config->get('config_currency')),
				$presence_summary_info['primary']['s'] + $presence_summary_info['primary']['i'],
				$presence_summary_info['primary']['ns'] + $presence_summary_info['primary']['ia'] + $presence_summary_info['primary']['a']
			];
		}

		foreach ($payroll_info['main_component'] as $group => $main_components) {
			foreach ($main_components as $key => $main_component) {
				if ($group != 'total') {
					$payroll_info['main_component'][$group][$key] = [
						'title'	=> str_replace($find, $replace, $main_component['title']),
						'value'	=> $main_component['value'],
						'text'	=> $this->currency->format($main_component['value'], $this->config->get('config_currency'))
					];
				} else {
					$payroll_info['main_component'][$group][$key] = [
						'title'	=> $this->language->get('text_total_' . $key),
						'value'	=> $main_component,
						'text'	=> $this->currency->format($main_component, $this->config->get('config_currency'))
					];
				}
			}
		}

		$payroll_info['sub_component'] = [
			'addition'			=> [],
			'deduction'			=> [],
			'total'				=> [
				'addition'	=> 0,
				'deduction'	=> 0
			]
		];

		$result_component = array();

		$always_view['overtime'] = 1; //Masukkan ke setting. Tetap di view walaupun nilainya 0.

		if ($this->model_common_payroll->checkPeriodStatus($presence_period_id, 'generated')) {
			$components = $this->calculatePayrollComponent($presence_period_id, $customer_id);

			foreach ($components as $component) {
				if (isset($always_view[$component['code']]) && $always_view[$component['code']]) {
					foreach ($component['quote'] as $quote) {
						if ($quote['type']) {
							$payroll_info['sub_component']['total']['addition'] += $quote['value'];

							$payroll_info['sub_component']['addition'][] = array(
								'title'	=> $quote['title'],
								'value'	=> $quote['value'],
								'text' => $this->currency->format($quote['value'], $this->config->get('config_currency'))
							);
						} else {
							$payroll_info['sub_component']['total']['deduction'] -= $quote['value'];

							$payroll_info['sub_component']['addition'][] = array(
								'title'	=> $quote['title'],
								'value'	=> -$quote['value'],
								'text' => $this->currency->format(-$quote['value'], $this->config->get('config_currency'))
							);
						}
					}
				} else {
					foreach ($component['quote'] as $quote) {
						if (!isset($result_component[$quote['title']])) {
							$result_component[$quote['title']] = 0;
						}

						$result_component[$quote['title']] += $quote['value'];
					}
				}
			}

			if ($result_component) {
				foreach ($result_component as $key => $value) {
					if ($value < 0) {
						$payroll_info['sub_component']['total']['deduction'] -= $value;

						$payroll_info['sub_component']['deduction'][] = array(
							'title'	=> $key,
							'value'	=> -$value,
							'text'	=> $this->currency->format(-$value, $this->config->get('config_currency'))
						);
					} elseif ($value > 0) {
						$payroll_info['sub_component']['total']['addition'] += $value;

						$payroll_info['sub_component']['addition'][] = array(
							'title'	=> $key,
							'value'	=> $value,
							'text' => $this->currency->format($value, $this->config->get('config_currency'))
						);
					}
				}
			}
		} else {
			$components = $this->getPayrollComponents($presence_period_id, $customer_id);

			foreach ($components as $component) {
				if (isset($always_view[$component['code']]) && $always_view[$component['code']]) {
					if ($component['type']) {
						$payroll_info['sub_component']['total']['addition'] += $component['value'];

						$payroll_info['sub_component']['addition'][] = array(
							'title'	=> $component['title'],
							'value'	=> $component['value'],
							'text'	=> $this->currency->format($component['value'], $this->config->get('config_currency'))
						);
					} else {
						$payroll_info['sub_component']['total']['deduction'] -= $component['value'];

						$payroll_info['sub_component']['deduction'][] = array(
							'title'	=> $component['title'],
							'value'	=> -$component['value'],
							'text'	=> $this->currency->format(-$component['value'], $this->config->get('config_currency'))
						);
					}
				} else {
					if (!isset($result_component[$component['title']])) {
						$result_component[$component['title']] = 0;
					}

					$result_component[$component['title']] += $component['value'];
				}
			}

			if ($result_component) {
				foreach ($result_component as $key => $value) {
					if ($value < 0) {
						$payroll_info['sub_component']['total']['deduction'] -= $value;

						$payroll_info['sub_component']['deduction'][] = array(
							'title'	=> $key,
							'value' => -$value,
							'text' => $this->currency->format(-$value, $this->config->get('config_currency'))
						);
					} elseif ($value > 0) {
						$payroll_info['sub_component']['total']['addition'] += $value;

						$payroll_info['sub_component']['addition'][] = array(
							'title'	=> $key,
							'value' => $value,
							'text' => $this->currency->format($value, $this->config->get('config_currency'))
						);
					}
				}
			}
		}

		foreach ($payroll_info['sub_component']['total'] as $key => $value) {
			$payroll_info['sub_component']['total'][$key] = array(
				'title'	=> $this->language->get('text_total_' . $key),
				'value'	=> $value,
				'text'	=> $this->currency->format($value, $this->config->get('config_currency'))
			);
		}

		return array(
			'payroll_id'           		=> $payroll_id,
			'presence_period_id'    	=> $presence_period_id,
			'customer_id'           	=> $customer_id,
			'presence_summary'			=> $presence_summary_info,
			'payroll_basic'				=> $payroll_info['payroll_basic'],
			'main_component'        	=> $payroll_info['main_component'],
			'sub_component'        		=> $payroll_info['sub_component'],
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

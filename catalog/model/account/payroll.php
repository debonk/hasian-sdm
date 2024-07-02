<?php
class ModelAccountPayroll extends Model
{
	public function getCurrentPeriod()
	{
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "presence_period WHERE date_start <= CURDATE() AND date_end >= CURDATE()");

		return $query->row;
	}

	public function getPeriodByDate($date)
	{
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "presence_period WHERE date_start <= '" . $this->db->escape($date) . "' AND date_end >= '" . $this->db->escape($date) . "'");

		return $query->row;
	}

	public function checkPeriodStatus($presence_period_id, $payroll_statuses = null)
	{
		$query = $this->db->query("SELECT DISTINCT payroll_status_id FROM " . DB_PREFIX . "presence_period WHERE presence_period_id = '" . (int)$presence_period_id . "'");

		if ($query->num_rows) {
			if (isset($payroll_statuses)) {
				$status_data = array(
					'pending',
					'processing',
					'submitted',
					'generated',
					'approved',
					'released',
					'completed'
				);

				$payroll_statuses = explode(', ', $payroll_statuses);

				$status_list = array();
				foreach ($payroll_statuses as $payroll_status) {
					if (in_array($payroll_status, $status_data)) {
						$status_list[] = $this->config->get('payroll_setting_' . $payroll_status . '_status_id');
					}
				}

				if (in_array($query->row['payroll_status_id'], $status_list)) {
					return 1;
				} else {
					return 0;
				}
			} else {
				return $query->row['payroll_status_id'];
			}
		} else {
			return 0;
		}
	}

	public function getPayrollBasic($payroll_basic_id)
	{
		$sql = "SELECT DISTINCT * FROM " . DB_PREFIX . "payroll_basic WHERE payroll_basic_id = '" . (int)$payroll_basic_id . "'";

		$query = $this->db->query($sql);

		return $query->row;
	}

	public function getPayrollBasicByCustomer($customer_id)
	{
		$query = $this->db->query("SELECT DISTINCT payroll_basic_id FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$customer_id . "'");

		$payroll_basic_data = $this->getPayrollBasic($query->row['payroll_basic_id']);

		return $payroll_basic_data;
	}

	public function getPayroll($presence_period_id, $customer_id)
	{
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "v_payroll WHERE presence_period_id = '" . (int)$presence_period_id . "' AND customer_id = '" . (int)$customer_id . "'");

		return $query->row;
	}

	public function getPayrollDetail($presence_period_id, $customer_id)
	{
		if ($this->checkPeriodStatus($presence_period_id, 'approved, released, completed')) {
			$payroll_info = $this->getPayroll($presence_period_id, $customer_id);

			if ($payroll_info && $payroll_info['status_released']) {
				$this->load->model('presence/presence');
				$presence_summary_info = $this->model_presence_presence->getPresenceSummary($presence_period_id, $customer_id);

				# Handling old version V3
				if ($payroll_info['payroll_basic_id']) {
					$payroll_basic_info = $this->getPayrollBasic($payroll_info['payroll_basic_id']);
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
						'deduction'	=> ['Potongan Sakit ({total_sakit} x {um})', 'Potongan Alpa ({total_bolos} x {um}) + PPH', 'Potongan Tunjangan Kehadiran', 'Potongan Gaji Pokok & Jabatan', 'Potongan Terlambat (≈' . $payroll_info['deduction_4'] / $payroll_basic_info['uang_makan'] . ' x {um})']
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
		}

		return;
	}

	public function getPayrollDetailDel($presence_period_id, $customer_id)
	{
		$this->load->model('presence/presence');

		$presence_summary_info = $this->model_presence_presence->getPresenceSummary($presence_period_id, $customer_id);

		if ($this->checkPeriodStatus($presence_period_id, 'approved, released, completed')) {
			$payroll_info = $this->getPayroll($presence_period_id, $customer_id);

			if (empty($payroll_info)) {
				$payroll_info = [
					'gaji_pokok'		=> 0,
					'tunj_jabatan'		=> 0,
					'tunj_hadir'		=> 0,
					'tunj_pph'			=> 0,
					'uang_makan'		=> 0,
					'date_added'		=> '',
					'total_uang_makan'	=> 0,
					'pot_sakit'			=> 0,
					'pot_bolos'			=> 0,
					'pot_tunj_hadir'	=> 0,
					'pot_gaji_pokok'	=> 0,
					'pot_terlambat'		=> 0
				];
			}

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
				'total_sakit'       	=> $presence_summary_info['total_sakit'],
				'total_bolos'       	=> $presence_summary_info['total_bolos'],
				'total_t'       		=> $presence_summary_info['total_t'],
				'gaji_pokok'            => $payroll_info['gaji_pokok'],
				'tunj_jabatan'          => $payroll_info['tunj_jabatan'],
				'tunj_hadir'            => $payroll_info['tunj_hadir'],
				'tunj_pph'              => $payroll_info['tunj_pph'],
				'uang_makan'            => $payroll_info['uang_makan'],
				'date_added'            => $payroll_info['date_added'],
				'total_uang_makan'      => $payroll_info['total_uang_makan'],
				'gaji_dasar'			=> $payroll_info['gaji_pokok'] + $payroll_info['tunj_jabatan'] + $payroll_info['tunj_hadir'] + $payroll_info['tunj_pph'] + $payroll_info['total_uang_makan'],
				'pot_sakit'		        => $payroll_info['pot_sakit'],
				'pot_bolos'		        => $payroll_info['pot_bolos'],
				'pot_tunj_hadir'	    => $payroll_info['pot_tunj_hadir'],
				'pot_gaji_pokok'	    => $payroll_info['pot_gaji_pokok'],
				'pot_terlambat'         => $payroll_info['pot_terlambat'],
				'total_potongan'        => $payroll_info['pot_sakit'] + $payroll_info['pot_bolos'] + $payroll_info['pot_tunj_hadir'] + $payroll_info['pot_gaji_pokok'] + $payroll_info['pot_terlambat'],
				'status_released'       => $payroll_info['status_released']
			);
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
}

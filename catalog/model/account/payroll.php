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

	public function getPayroll($presence_period_id, $customer_id)
	{
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "payroll WHERE presence_period_id = '" . (int)$presence_period_id . "' AND customer_id = '" . (int)$customer_id . "'");

		return $query->row;
	}

	public function getPayrollDetail($presence_period_id, $customer_id)
	{
		$this->load->model('presence/presence');

		$presence_summary_info = $this->model_presence_presence->getPresenceSummary($presence_period_id, $customer_id);

		if ($this->checkPeriodStatus($presence_period_id, 'approved, released, completed')) {
			$payroll_info = $this->getPayroll($presence_period_id, $customer_id);

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
				'total_potongan'        => $payroll_info['pot_sakit'] + $payroll_info['pot_bolos'] + $payroll_info['pot_tunj_hadir'] + $payroll_info['pot_gaji_pokok'] + $payroll_info['pot_terlambat']
			);
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
}

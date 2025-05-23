<?php
class ModelPresenceSchedule extends Model
{
	public function deleteSchedule($customer_id, $date)
	{
		$this->db->query("DELETE FROM " . DB_PREFIX . "schedule WHERE customer_id = '" . (int)$customer_id . "' AND date = '" . $this->db->escape($date) . "'");
	}

	public function deleteSchedules($presence_period_id, $customer_id)
	{
		$this->db->query("DELETE FROM " . DB_PREFIX . "schedule WHERE presence_period_id = '" . (int)$presence_period_id . "' AND customer_id = '" . (int)$customer_id . "' AND date > CURDATE()");
	}

	public function editSchedule($presence_period_id, $customer_id, $data = array())
	{
		foreach ($data as $key => $schedule_type_id) {
			if (substr($key, 0, 8) == 'schedule') {
				$date = substr($key, 8);

				$this->deleteSchedule($customer_id, $date);

				$this->db->query("INSERT INTO " . DB_PREFIX . "schedule SET presence_period_id = '" . (int)$presence_period_id . "', customer_id = '" . (int)$customer_id . "', date = '" . $this->db->escape($date) . "', schedule_type_id = '" . (int)$schedule_type_id . "', user_id = '" . (int)$this->user->getId() . "'");
			}
		}
	}

	public function getSchedule($customer_id, $date)
	{
		$sql = "SELECT DISTINCT * FROM " . DB_PREFIX . "schedule WHERE customer_id = '" . (int)$customer_id . "' AND date = '" . $this->db->escape($date) . "'";

		$query = $this->db->query($sql);

		return $query->row;
	}

	public function getSchedules($customer_id, $range_date)
	{
		$sql = "SELECT s.*, st.code, st.time_start, st.time_end, st.bg_idx FROM " . DB_PREFIX . "schedule s LEFT JOIN " . DB_PREFIX . "schedule_type st ON (st.schedule_type_id = s.schedule_type_id) WHERE s.customer_id = '" . (int)$customer_id . "' AND s.date >= '" . $this->db->escape($range_date['start']) . "' AND s.date <= '" . $this->db->escape($range_date['end']) . "' ORDER BY s.date ASC";

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getSchedulesCount($presence_period_id)
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "schedule WHERE presence_period_id = '" . (int)$presence_period_id . "'";

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getScheduleCustomers($presence_period_id, $data = array())
	{
		$sql = "SELECT DISTINCT s.customer_id, c.nip, c.firstname, c.name, c.date_start, c.date_end, c.date_added, c.customer_group_id, c.customer_group, c.customer_department_id, c.customer_department, c.location_id, c.location, c.payroll_include FROM " . DB_PREFIX . "schedule s LEFT JOIN " . DB_PREFIX . "v_customer c ON c.customer_id = s.customer_id WHERE c.language_id = '" . (int)$this->config->get('config_language_id') . "' AND s.presence_period_id = '" . (int)$presence_period_id . "'";

		$implode = array();

		if (!empty($data['filter']['name'])) {
			$implode[] = "c.name LIKE '%" . $this->db->escape($data['filter']['name']) . "%'";
		}

		if (!empty($data['filter']['customer_group_id'])) {
			$implode[] = "c.customer_group_id = '" . (int)$data['filter']['customer_group_id'] . "'";
		}

		if (!empty($data['filter']['location_id'])) {
			$implode[] = "c.location_id = '" . (int)$data['filter']['location_id'] . "'";
		}

		if (!empty($data['filter']['customer_department_id'])) {
			$implode[] = "c.customer_department_id = '" . (int)$data['filter']['customer_department_id'] . "'";
		}

		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}

		$sort_data = array(
			'nip',
			'name',
			'customer_group',
			'customer_department',
			'location',
			'customer_group DESC, name'
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

	public function getScheduleCustomersCount($presence_period_id, $data = array())
	{
		$sql = "SELECT COUNT(DISTINCT s.customer_id) AS total FROM " . DB_PREFIX . "schedule s LEFT JOIN " . DB_PREFIX . "v_customer c ON (c.customer_id = s.customer_id) WHERE s.presence_period_id = '" . (int)$presence_period_id . "'";

		$implode = array();

		if (!empty($data['filter']['name'])) {
			$implode[] = "c.name LIKE '%" . $this->db->escape($data['filter']['name']) . "%'";
		}

		if (!empty($data['filter']['customer_group_id'])) {
			$implode[] = "c.customer_group_id = '" . (int)$data['filter']['customer_group_id'] . "'";
		}

		if (!empty($data['filter']['location_id'])) {
			$implode[] = "c.location_id = '" . (int)$data['filter']['location_id'] . "'";
		}

		if (!empty($data['filter']['customer_department_id'])) {
			$implode[] = "c.customer_department_id = '" . (int)$data['filter']['customer_department_id'] . "'";
		}

		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getScheduleDateTitles($date)
	{
		$date_titles = array();

		if (!isset($date['start']) || !$date['start']) {
			$date['start'] = date('Y-m-d', strtotime('-2 days'));
		}

		if (!isset($date['end']) || !$date['end']) {
			$date['end'] = date('Y-m-d', strtotime('+6 days', strtotime($date['start'])));
		}

		$date_start = strtotime($date['start']);
		$date_diff = date_diff(date_create($date['start']), date_create($date['end']))->format('%a');

		for ($x = 0; $x <= $date_diff; $x++) {
			$date_key = strtotime('+' . $x . ' day', $date_start);
			$date_titles[$x] = array(
				'date_only'	=> date('d', $date_key),
				'date'		=> date('Y-m-d', $date_key),
				'day'		=> date('D', $date_key),
				'text'		=> date('j M', $date_key)
			);
		}

		return $date_titles;
	}

	public function getLogs($customer_id, $date = array())
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "presence_log WHERE customer_id = '" . (int)$customer_id . "'";

		if (isset($date['start']) || isset($date['end'])) {
			if (empty($date['start']) || $date['start'] == '0000-00-00') {
				$date['start'] = date('Y-m-d', strtotime('-2 days'));
			}

			if (empty($date['end']) || $date['end'] == '0000-00-00' || $date['end'] < $date['start']) {
				$date['end'] = date('Y-m-d', strtotime('+6 days', strtotime($date['start'])));
			}

			$sql .= " AND date >= '" . $this->db->escape($date['start']) . "' AND date <= '" . $this->db->escape($date['end']) . "'";
		}

		$sql .= " ORDER BY date ASC";

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function calculatePresence($time_in, $time_login)
	{
		if (empty($time_login) || $time_login == '0000-00-00 00:00:00') {
			$presence_status_id = $this->config->get('payroll_setting_id_a');
			// $presence_code = 'a';

		} else {
			$time_in_obj = strtotime($this->db->escape($time_in));
			$time_login_obj = strtotime($this->db->escape($time_login));

			$late = floor(($time_login_obj - $time_in_obj) / 60) - max(0, $this->config->get('payroll_setting_late_tolerance'));

			switch (true) {
				case ($late > 30):
					$presence_status_id = $this->config->get('payroll_setting_id_t3');
					// $presence_code = 't3';
					break;

				case ($late > 15):
					$presence_status_id = $this->config->get('payroll_setting_id_t2');
					// $presence_code = 't2';
					break;

				case ($late > 0):
					$presence_status_id = $this->config->get('payroll_setting_id_t1');
					// $presence_code = 't1';
					break;

				default:
					$presence_status_id = $this->config->get('payroll_setting_id_h');
					// $presence_code = 'h';
			}
		}

		return $presence_status_id;
		// return $presence_code;
	}

	public function getFinalSchedules($presence_period_id, $customer_id, $range_date = [])
	{
		$schedules_data = [];

		if (empty($range_date)) {
			$period_info = $this->model_common_payroll->getPeriod($presence_period_id);

			$range_date = array(
				'start'	=> $period_info['date_start'],
				'end'	=> $period_info['date_end']
			);
		}

		// $customer_info = $this->model_common_payroll->getCustomer($customer_id);


		// $range_date['start'] = max($range_date['start'], $customer_info['date_start']);

		// if ($customer_info['date_end']) {
		// 	$range_date['end'] = min($range_date['end'], $customer_info['date_end']);
		// }

		if ($range_date['start'] > $range_date['end']) {
			return $schedules_data;
		}

		// Apply Exchange
		$exchanges_info = $this->model_presence_exchange->getExchangesByCustomerDate($customer_id, $range_date);

		foreach ($exchanges_info as $exchange_info) {
			$time_in = $exchange_info['date_to'] . ' ' . $exchange_info['time_start'];
			$time_out = $exchange_info['date_to'] . ' ' . $exchange_info['time_end'];

			if ($exchange_info['time_start'] >= $exchange_info['time_end']) {
				$time_out = date('Y-m-d H:i:s', strtotime('+1 day', strtotime($time_out)));
			}

			if ($exchange_info['date_to'] >= $range_date['start'] && $exchange_info['date_to'] <= $range_date['end']) {
				$schedules_data[$exchange_info['date_to']] = array(
					'applied'			=> 'exchange',
					'schedule_type_id'	=> $exchange_info['schedule_type_id'],
					'schedule_type'		=> 'X-' . $exchange_info['code'],
					'time_in'			=> $time_in,
					'time_out'			=> $time_out,
					'note'				=> $exchange_info['description'],
					'schedule_bg'		=> $exchange_info['bg_idx'],
					'bg_class'			=> 'primary'
				);
			}

			if ($exchange_info['date_from'] >= $range_date['start'] && $exchange_info['date_from'] <= $range_date['end']) {
				if (!isset($schedules_data[$exchange_info['date_from']])) {
					$schedules_data[$exchange_info['date_from']] = array(
						'applied'			=> 'exchange',
						'schedule_type_id'	=> 0,
						'schedule_type'		=> 'X-',
						'time_in'			=> '0000-00-00 00:00:00',
						'time_out'			=> '0000-00-00 00:00:00',
						'note'				=> $exchange_info['description'],
						'schedule_bg'		=> 0,
						'bg_class'			=> 'primary'
					);
				}
			}
		}

		$schedules_info = $this->getSchedules($customer_id, $range_date);

		foreach ($schedules_info as $schedule_info) {
			if ($schedule_info['schedule_type_id'] == 0) {
				$time_in = '0000-00-00 00:00:00';
				$time_out = '0000-00-00 00:00:00';
				$schedule_info['bg_idx'] = '0';
			} else {
				$time_in = $schedule_info['date'] . ' ' . $schedule_info['time_start'];
				$time_out = $schedule_info['date'] . ' ' . $schedule_info['time_end'];

				if ($schedule_info['time_start'] >= $schedule_info['time_end']) {
					$time_out = date('Y-m-d H:i:s', strtotime('+1 day', strtotime($time_out)));
				}
			}

			if (!isset($schedules_data[$schedule_info['date']])) {
				$schedules_data[$schedule_info['date']] = array(
					'applied'			=> 'schedule',
					'schedule_type_id'	=> $schedule_info['schedule_type_id'],
					'schedule_type'		=> $schedule_info['code'],
					'time_in'			=> $time_in,
					'time_out'			=> $time_out,
					'note'				=> '',
					'schedule_bg'		=> $schedule_info['bg_idx'],
					'bg_class'			=> 'info'
				);
			}
		}

		// Apply Overtime
		$overtimes_info = $this->model_overtime_overtime->getOvertimesByCustomerDate($customer_id, $range_date);

		foreach ($overtimes_info as $overtime_info) {
			if ($overtime_info['approved']) {
				$time_in = $overtime_info['date'] . ' ' . $overtime_info['time_start'];
				$time_out = $overtime_info['date'] . ' ' . $overtime_info['time_end'];

				if ($overtime_info['time_start'] >= $overtime_info['time_end']) {
					$time_out = date('Y-m-d H:i:s', strtotime('+1 day', strtotime($time_out)));
				}

				$time_out = date('Y-m-d H:i:s', strtotime('+' . $overtime_info['duration'] . 'hours', strtotime($time_out)));

				$code = $overtime_info['duration'] > 7 ? 'LH-' : 'L-';
				// $code = $overtime_info['full_day'] ? 'LH-' : 'L-';//tambah variable full day utk lembur harian

				$overtimes_data = array(
					'applied'			=> 'overtime',
					'schedule_type_id'	=> $overtime_info['schedule_type_id'],
					'schedule_type'		=> $code . $overtime_info['code'],
					'time_in'			=> $time_in,
					'time_out'			=> $time_out,
					'note'				=> $overtime_info['description'],
					'schedule_bg'		=> $overtime_info['bg_idx'],
					'bg_class'			=> 'primary'
				);
			} else {
				$overtimes_data = array(
					'bg_class'			=> 'danger'
				);
			}

			$schedules_data[$overtime_info['date']] = array_merge($schedules_data[$overtime_info['date']], $overtimes_data);
		}

		$logs_info = $this->getLogs($customer_id, $range_date);

		foreach ($logs_info as $log_info) {
			// if (isset($schedules_data[$log_info['date']]) && $schedules_data[$log_info['date']]['time_in'] != '0000-00-00 00:00:00') {
			if (isset($log_info['date']) && $log_info['time_in'] != '0000-00-00 00:00:00') {
				$logs_data = array(
					'time_in'		=> $log_info['time_in'],
					'time_out'		=> $log_info['time_out'],
					'time_login'	=> $log_info['time_login'],
					'time_logout'	=> $log_info['time_logout']
				);
			} else {
				$logs_data = array(
					'time_login'	=> $log_info['time_login'],
					'time_logout'	=> $log_info['time_logout']
				);
			}

			# Bagian ini jika absensi tanpa validasi jadwal. Develop Later..
			if (!isset($schedules_data[$log_info['date']])) {
				$schedules_data[$log_info['date']] = [
					'applied'			=> '-',
					'schedule_type_id'	=> 0,
					'schedule_type'		=> '',
					'time_in'			=> '0000-00-00 00:00:00',
					'time_out'			=> '0000-00-00 00:00:00',
					'note'				=> '',
					'schedule_bg'		=> '',
					'bg_class'			=> ''
				];
			}

			$schedules_data[$log_info['date']] = array_merge($schedules_data[$log_info['date']], $logs_data);
		}

		$presence_status_data = [];

		$this->load->model('localisation/presence_status');
		$presence_status_data = $this->model_localisation_presence_status->getPresenceStatusIdList();

		$period_submitted_check = !$this->model_common_payroll->checkPeriodStatus($presence_period_id, 'pending, processing');
		if ($period_submitted_check) {
			$this->load->model('presence/presence');
			$presences_data = $this->model_presence_presence->getFinalPresences($customer_id, $range_date);
		}

		$customer_info = $this->model_common_payroll->getCustomer($customer_id);

		foreach ($schedules_data as $date => $schedule_data) {
			if ($schedule_data['time_in'] != '0000-00-00 00:00:00' && strtotime($date) <= strtotime('today')) {
				if (isset($schedule_data['time_login'])) {
					$time_login = $schedule_data['time_login'];
					$time_logout = $schedule_data['time_logout'];
					// $presence_code = $this->calculatePresence($schedule_data['time_in'], $time_login);
					$presence_status_id = isset($presences_data[$date]) ? $presences_data[$date]['presence_status_id'] : $this->calculatePresence($schedule_data['time_in'], $time_login);
				} else {
					$time_login = '0000-00-00 00:00:00';
					$time_logout = '0000-00-00 00:00:00';
					// $presence_code = 'a';
					if (isset($presences_data[$date])) {
						$presence_status_id = $presences_data[$date]['presence_status_id'];
					} else {
						$presence_status_id = strtotime($schedule_data['time_in']) <= strtotime('now') ? $this->config->get('payroll_setting_id_a') : null;
					}
				}
			} else {
				if (isset($schedule_data['time_login'])) {
					$time_login = $schedule_data['time_login'];
					$time_logout = $schedule_data['time_logout'];
					$presence_status_id = isset($presences_data[$date]) ? $presences_data[$date]['presence_status_id'] : $this->config->get('payroll_setting_id_h');
					// $presence_code = 'h';
				} else {
					$time_login = '0000-00-00 00:00:00';
					$time_logout = '0000-00-00 00:00:00';
					$presence_status_id = isset($presences_data[$date]) ? $presences_data[$date]['presence_status_id'] : $this->config->get('payroll_setting_id_off');
					// $presence_code = 'off';
				}
			}

			if (($customer_info['date_start'] > $date) || ($customer_info['date_end'] && ($customer_info['date_end'] < $date))) {
				$presence_status_id = $this->config->get('payroll_setting_id_ns');
			} elseif (strtotime($date) > strtotime('today')) {
				$presence_status_id = 0;
			}
			
			$schedules_data[$date] = array(
				'applied'				=> $schedule_data['applied'],
				'schedule_type_id'		=> $schedule_data['schedule_type_id'],
				'schedule_type'			=> $schedule_data['schedule_type'],
				'time_in'				=> $schedule_data['time_in'],
				'time_out'				=> $schedule_data['time_out'],
				'time_login'			=> $time_login,
				'time_logout'			=> $time_logout,
				'presence_status_id'	=> $presence_status_id,
				'presence_status'		=> isset($presence_status_data[$presence_status_id]) ? $presence_status_data[$presence_status_id]['name'] : '-',
				'presence_code'			=> isset($presence_status_data[$presence_status_id]) ? $presence_status_data[$presence_status_id]['code'] : '',
				'note'					=> $schedule_data['note'],
				'schedule_bg'			=> $schedule_data['schedule_bg'],
				'bg_class'				=> $schedule_data['bg_class']
			);
		}

		// var_dump($schedules_data);

		//Apply Absences
		$absences_info = $this->model_presence_absence->getAbsencesByCustomerDate($customer_id, $range_date);

		foreach ($absences_info as $absence_info) {
			if ($absence_info['approved']) {
				$absences_data = array(
					'applied'				=> 'absence',
					'presence_code'			=> $absence_info['presence_code'],
					'presence_status_id'	=> $absence_info['presence_status_id'],
					'presence_status'		=> $absence_info['presence_status'],
					'note'					=> $absence_info['description'],
					'bg_class'				=> 'primary'
				);
			} else {
				$presence_status_id = $this->config->get('payroll_setting_id_ia');

				$absences_data = array(
					'presence_status_id'	=> $presence_status_id,
					'presence_status'		=> isset($presence_status_data[$presence_status_id]) ? $presence_status_data[$presence_status_id]['name'] : '-',
					'presence_code'			=> isset($presence_status_data[$presence_status_id]) ? $presence_status_data[$presence_status_id]['code'] : '',
					'note'					=> $absence_info['description'],
					'bg_class'				=> 'danger'
				);
			}

			if (!isset($schedules_data[$absence_info['date']])) {
				$schedules_data[$absence_info['date']] = array(
					'applied'			=> 'schedule',
					'schedule_type_id'	=> 0,
					'schedule_type'		=> '',
					'time_in'			=> '0000-00-00 00:00:00',
					'time_out'			=> '0000-00-00 00:00:00',
					'time_login'		=> '0000-00-00 00:00:00',
					'time_logout'		=> '0000-00-00 00:00:00',
					'schedule_bg'		=> 0
				);
			}

			$schedules_data[$absence_info['date']] = array_merge($schedules_data[$absence_info['date']], $absences_data);
		}

		ksort($schedules_data);

		return $schedules_data;
	}

	public function calculatePresenceSummary($presence_period_id, $customer_id, $final_schedule_data = array())
	{
		$presence_summary_data = array();

		$presence_statuses = $this->model_localisation_presence_status->getPresenceStatusesData();

		$presence_summary = array_count_values(array_column($final_schedule_data, 'presence_code'));

		foreach ($presence_statuses as $presence_group => $presence_status) {
			foreach ($presence_status as $code) {
				if (isset($presence_summary[$code])) {
					$presence_summary_data[$presence_group][$code] = $presence_summary[$code];
				} else {
					$presence_summary_data[$presence_group][$code] = 0;
				}
			}
		}

		$presence_summary_data['primary']['h'] += $presence_summary_data['secondary']['t1'] + $presence_summary_data['secondary']['t2'] + $presence_summary_data['secondary']['t3'];

		if ($presence_summary) {
			$period_info = $this->model_common_payroll->getPeriod($presence_period_id);

			$customer_info = $this->model_common_payroll->getCustomer($customer_id);

			if (strtotime($customer_info['date_start']) > strtotime($period_info['date_start']) || (isset($customer_info['date_end']) && strtotime($customer_info['date_end']) <= strtotime($period_info['date_end']))) {
				$default_hke = $this->config->get('payroll_setting_default_hke');
                $presence_summary_data['primary']['ns'] = 0;

				$presence_summary_data['primary']['ns'] = max($default_hke - array_sum($presence_summary_data['primary']), 0);
			}
		}

		$presence_summary_data['total']['hke'] = array_sum($presence_summary_data['primary']) + array_sum($presence_summary_data['additional']);
		$presence_summary_data['total']['t'] = array_sum($presence_summary_data['secondary']);

		# Perhitungan Jumlah Lembur Harian (Lembur Penuh)
		$period_info = $this->model_common_payroll->getPeriod($presence_period_id);

		$range_date = array(
			'start'		=> $period_info['date_start'],
			'end'		=> $period_info['date_end']
		);

		$this->load->model('overtime/overtime');
		$presence_summary_data['total']['full_overtime'] = $this->model_overtime_overtime->getFullOvertimesCount($customer_id, $range_date);

		if (!empty($presence_summary_data['total']['full_overtime'])) {
			$presence_summary_data['total']['hke'] .= ' (' . $presence_summary_data['total']['full_overtime'] . ' ' . $this->language->get('code_full_overtime') . ')';
		}

		return $presence_summary_data;
	}

	public function recapPresenceSummary($presence_period_id, $customer_ids)
	{
		$period_info = $this->model_common_payroll->getPeriod($presence_period_id);

		$range_date = array(
			'start'	=> $period_info['date_start'],
			'end'	=> $period_info['date_end']
		);

		$this->load->model('presence/absence');
		$this->load->model('presence/exchange');
		$this->load->model('overtime/overtime');

		foreach ($customer_ids as $customer_id) {
			$schedules_info = $this->getFinalSchedules($presence_period_id, $customer_id, $range_date);

			if ($schedules_info) {
				$this->model_presence_presence->deletePresence($presence_period_id, $customer_id);

				$this->model_presence_presence->addPresences($presence_period_id, $customer_id, $schedules_info);

				$presence_summary_info = $this->calculatePresenceSummary($presence_period_id, $customer_id, $schedules_info);

				$this->model_presence_presence->addPresenceSummary($presence_period_id, $customer_id, $presence_summary_info);
			}
		}
	}

	public function getEmptySchedulesCount($presence_period_id)
	{
		$period_info = $this->model_common_payroll->getPeriod($presence_period_id);

		$sql = "SELECT COUNT(c.customer_id) AS total FROM " . DB_PREFIX . "customer c LEFT JOIN " . DB_PREFIX . "schedule s ON (s.customer_id = c.customer_id AND s.presence_period_id = '" . (int)$presence_period_id . "') WHERE c.status = 1 AND date_start < '" . $this->db->escape($period_info['date_end']) . "' AND (c.date_end IS NULL OR c.date_end = '0000-00-00' OR c.date_end > '" . $this->db->escape($period_info['date_start']) . "') AND s.presence_period_id IS NULL";

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getSchedulesCountByScheduleTypeId($schedule_type_id, $presence_period_id = 0)
	{ //used by: schedule_type
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "schedule s LEFT JOIN " . DB_PREFIX . "presence_period pp ON (pp.presence_period_id = s.presence_period_id) WHERE s.schedule_type_id = '" . (int)$schedule_type_id . "' AND pp.payroll_status_id <> '" . (int)$this->config->get('payroll_setting_completed_status_id') . "'";

		if ($presence_period_id) {
			$sql .= " AND s.presence_period_id = '" . (int)$presence_period_id . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getFinalSchedulesCountByScheduleTypeId($schedule_type_id, $range_date)
	{ //used by: schedule_type
		$schedule_count = 0;

		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "schedule WHERE schedule_type_id = '" . (int)$schedule_type_id . "' AND date >= '" . $this->db->escape($range_date['start']) . "' AND date <= '" . $this->db->escape($range_date['end']) . "'");
		$schedule_count += $query->row['total'];

		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "overtime WHERE schedule_type_id = '" . (int)$schedule_type_id . "' AND date >= '" . $this->db->escape($range_date['start']) . "' AND date <= '" . $this->db->escape($range_date['end']) . "'");
		$schedule_count += $query->row['total'];

		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "exchange WHERE schedule_type_id = '" . (int)$schedule_type_id . "' AND ((date_from >= '" . $this->db->escape($range_date['start']) . "' AND date_from <= '" . $this->db->escape($range_date['end']) . "') OR (date_to >= '" . $this->db->escape($range_date['start']) . "' AND date_to <= '" . $this->db->escape($range_date['end']) . "'))");
		$schedule_count += $query->row['total'];

		return $schedule_count;
	}
}

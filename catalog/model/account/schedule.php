<?php
class ModelAccountSchedule extends Model
{
	public function getExchangesByCustomerDate($customer_id, $date = array())
	{
		$sql = "SELECT e.*, st.code, st.time_start, st.time_end, st.bg_idx, u.username FROM " . DB_PREFIX . "exchange e LEFT JOIN " . DB_PREFIX . "schedule_type st ON (st.schedule_type_id = e.schedule_type_id) LEFT JOIN " . DB_PREFIX . "user u ON (u.user_id = e.user_id) WHERE customer_id = '" . (int)$customer_id . "' AND ((e.date_from >= '" . $this->db->escape($date['start']) . "' AND e.date_from <= '" . $this->db->escape($date['end']) . "') OR (e.date_to >= '" . $this->db->escape($date['start']) . "' AND e.date_to <= '" . $this->db->escape($date['end']) . "')) ORDER BY e.date_from ASC";

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getSchedules($customer_id, $range_date)
	{
		$sql = "SELECT s.*, st.code, st.time_start, st.time_end, st.bg_idx FROM " . DB_PREFIX . "schedule s LEFT JOIN " . DB_PREFIX . "schedule_type st ON (st.schedule_type_id = s.schedule_type_id) WHERE s.customer_id = '" . (int)$customer_id . "' AND s.date >= '" . $this->db->escape($range_date['start']) . "' AND s.date <= '" . $this->db->escape($range_date['end']) . "' ORDER BY s.date ASC";

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getOvertimesByCustomerDate($customer_id, $date = array())
	{
		$sql = "SELECT o.*, ot.duration as duration, st.code, st.time_start, st.time_end, st.bg_idx, u.username FROM " . DB_PREFIX . "overtime o LEFT JOIN " . DB_PREFIX . "overtime_type ot ON (ot.overtime_type_id = o.overtime_type_id) LEFT JOIN " . DB_PREFIX . "schedule_type st ON (st.schedule_type_id = o.schedule_type_id) LEFT JOIN " . DB_PREFIX . "user u ON (u.user_id = o.user_id) WHERE customer_id = '" . (int)$customer_id . "' AND o.date >= '" . $this->db->escape($date['start']) . "' AND o.date <= '" . $this->db->escape($date['end']) . "' ORDER BY o.date ASC";

		$query = $this->db->query($sql);

		return $query->rows;
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

	public function getPresenceStatuses()
	{
		$presence_status_data = $this->cache->get('presence_status');

		if (!$presence_status_data) {
			$query = $this->db->query("SELECT presence_status_id, code, name FROM " . DB_PREFIX . "presence_status ORDER BY presence_status_id");

			$presence_status_data = $query->rows;

			$this->cache->set('presence_status', $presence_status_data);
		}

		return $presence_status_data;
	}

	public function calculatePresence($time_in, $time_login) {
		if (empty($time_login) || $time_login == '0000-00-00 00:00:00') {
			// $presence_status_id = $this->config->get('payroll_setting_id_a');
			$presence_code = 'a';
			
		} else {
			$time_in_obj = strtotime($this->db->escape($time_in));
			$time_login_obj = strtotime($this->db->escape($time_login));
			
			$late = floor(($time_login_obj - $time_in_obj)/60);
		
			switch (true) {
				case ($late > 30): 
					// $presence_status_id = $this->config->get('payroll_setting_id_t3');
					$presence_code = 't3';
					break;
					
				case ($late > 15): 
					// $presence_status_id = $this->config->get('payroll_setting_id_t2');
					$presence_code = 't2';
					break;
					
				case ($late > 0): 
					// $presence_status_id = $this->config->get('payroll_setting_id_t1');
					$presence_code = 't1';
					break;
					
				default:
					// $presence_status_id = $this->config->get('payroll_setting_id_h');
					$presence_code = 'h';
			}
		}
		
		// return $presence_status_id;
		return $presence_code;
	}

	public function getAbsencesByCustomerDate($customer_id, $date = array()) {
		$sql = "SELECT a.*, ps.code as presence_code, ps.name as presence_status, u.username FROM " . DB_PREFIX . "absence a LEFT JOIN " . DB_PREFIX . "presence_status ps ON (ps.presence_status_id = a.presence_status_id) LEFT JOIN " . DB_PREFIX . "user u ON (u.user_id = a.user_id) WHERE customer_id = '" . (int)$customer_id . "' AND a.date >= '" . $this->db->escape($date['start']) . "' AND a.date <= '" . $this->db->escape($date['end']) . "' ORDER BY a.date ASC";

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getFinalSchedules($customer_id, $range_date)
	{
		$customer_info = $this->model_account_customer->getCustomer($customer_id);

		$schedules_data = [];

		$range_date['start'] = max($range_date['start'], $customer_info['date_start']);

		if ($customer_info['date_end']) {
			$range_date['end'] = min($range_date['end'], $customer_info['date_end']);
		}

		if ($range_date['start'] > $range_date['end']) {
			return $schedules_data;
		}

		// Apply Exchange
		$exchanges_info = $this->getExchangesByCustomerDate($customer_id, $range_date);

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
		$overtimes_info = $this->getOvertimesByCustomerDate($customer_id, $range_date);

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

			# Blok jika absensi tanpa menggunakan jadwal. Develop Later..
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

		$presence_statuses_data = [];

		$presence_statuses = $this->getPresenceStatuses();

		foreach ($presence_statuses as $presence_status) {
			$presence_statuses_data[$presence_status['code']] = array(
				'presence_status_id'	=> $presence_status['presence_status_id'],
				'name'					=> $presence_status['name']
			);
		}

		foreach ($schedules_data as $date => $schedule_data) {
			if ($schedule_data['time_in'] != '0000-00-00 00:00:00' && strtotime($date) <= strtotime('today')) {
				if (isset($schedule_data['time_login'])) {
					$time_login = $schedule_data['time_login'];
					$time_logout = $schedule_data['time_logout'];
					$presence_code = $this->calculatePresence($schedule_data['time_in'], $time_login);
				} else {
					$time_login = '0000-00-00 00:00:00';
					$time_logout = '0000-00-00 00:00:00';
					$presence_code = 'a';
				}
			} else {
				if (isset($schedule_data['time_login'])) {
					$time_login = $schedule_data['time_login'];
					$time_logout = $schedule_data['time_logout'];
					$presence_code = 'h';
				} else {
					$time_login = '0000-00-00 00:00:00';
					$time_logout = '0000-00-00 00:00:00';
					$presence_code = 'off';
				}
			}

			$schedules_data[$date] = array(
				'applied'			=> $schedule_data['applied'],
				'schedule_type_id'	=> $schedule_data['schedule_type_id'],
				'schedule_type'		=> $schedule_data['schedule_type'],
				'time_in'			=> $schedule_data['time_in'],
				'time_out'			=> $schedule_data['time_out'],
				'time_login'		=> $time_login,
				'time_logout'		=> $time_logout,
				'presence_code'		=> $presence_code,
				'presence_status_id' => isset($presence_statuses_data[$presence_code]) ? $presence_statuses_data[$presence_code]['presence_status_id'] : '-',
				'presence_status'	=> isset($presence_statuses_data[$presence_code]) ? $presence_statuses_data[$presence_code]['name'] : '-',
				'note'				=> $schedule_data['note'],
				'schedule_bg'		=> $schedule_data['schedule_bg'],
				'bg_class'			=> $schedule_data['bg_class']
			);
		}

		//Apply Absences
		$absences_info = $this->getAbsencesByCustomerDate($customer_id, $range_date);

		foreach ($absences_info as $absence_info) {
			if ($absence_info['approved']) {
				$absences_data = array(
					'applied'			=> 'absence',
					'presence_code'		=> $absence_info['presence_code'],
					'presence_status_id' => $absence_info['presence_status_id'],
					'presence_status'	=> $absence_info['presence_status'],
					'note'				=> $absence_info['description'],
					'bg_class'			=> 'primary'
				);
			} else {
				$absences_data = array(
					'note'				=> $absence_info['description'],
					'bg_class'			=> 'danger'
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
}

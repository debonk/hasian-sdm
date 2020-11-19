<?php
class ModelPresencePresence extends Model {
	public function getCustomer($customer_id) { //login
		$query = $this->db->query("SELECT DISTINCT customer_id FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$customer_id . "' AND status = 1 AND date_start <= CURDATE() AND (date_end > CURDATE() OR date_end IS NULL)");

		return $query->row;
	}

	public function getCustomers($data = array()) { //Used by: dashboard/customer, payroll, report payroll insurance, payroll_basic, schedule
		$sql = "SELECT c.customer_id, c.nip, CONCAT(c.firstname, ' [', c.lastname, ']') AS name, c.image, c.date_added, c.customer_group_id, cgd.name AS customer_group, c.location_id, l.name AS location FROM " . DB_PREFIX . "customer c LEFT JOIN (" . DB_PREFIX . "customer_group_description cgd, " . DB_PREFIX . "location l) ON (cgd.customer_group_id = c.customer_group_id AND l.location_id = c.location_id) WHERE cgd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND status = 1";

		$implode = array();

		if (!empty($data['filter_name'])) {
			$implode[] = "CONCAT(c.firstname, ' [', c.lastname, ']') LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_customer_group_id'])) {
			$implode[] = "c.customer_group_id = '" . (int)$data['filter_customer_group_id'] . "'";
		}
		
		if (!empty($data['filter_location_id'])) {
			$implode[] = "c.location_id = '" . (int)$data['filter_location_id'] . "'";
		}
		
		if (isset($data['presence_period_id'])) {
			$period_info = $this->getPresencePeriod($data['presence_period_id']);
			$implode[] = "c.date_start <= '" . $this->db->escape($period_info['date_end']) . "'";
			$implode[] = "(c.date_end IS NULL OR c.date_end = '0000-00-00' OR c.date_end > '" . $this->db->escape($period_info['date_start']) . "')";
		} elseif (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			if (!empty($data['filter_status'])) {
				$implode[] = "(c.date_end <> '0000-00-00' AND c.date_end <= CURDATE())";
			}
		} else {
			$implode[] = "(c.date_end IS NULL OR c.date_end = '0000-00-00' OR c.date_end > CURDATE())";
		}

		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}

		$sort_data = array(
			'nip',
			'name',
			'customer_group',
			'location',
			'date_added'
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

	function getFingersCount($customer_id) {
		$sql = "SELECT COUNT(finger_id) as total FROM " . DB_PREFIX . "customer_finger WHERE customer_id = '" . (int)$customer_id . "'";
		
		$query = $this->db->query($sql);
	
		return $query->row['total'];
	}
	
	public function getOvertimeByDate($customer_id, $date) {
		$sql = "SELECT DISTINCT o.customer_id, o.date, o.schedule_type_id, o.description, ot.duration as duration, st.code, st.time_start, st.time_end FROM " . DB_PREFIX . "overtime o LEFT JOIN " . DB_PREFIX . "overtime_type ot ON (ot.overtime_type_id = o.overtime_type_id) LEFT JOIN " . DB_PREFIX . "schedule_type st ON (st.schedule_type_id = o.schedule_type_id) WHERE customer_id = '" . (int)$customer_id . "' AND o.date = '" . $this->db->escape($date) . "'";

		$query = $this->db->query($sql);

		return $query->row;
	}
	
	public function getExchangeByDate($customer_id, $date) {
		$sql = "SELECT DISTINCT e.customer_id, e.date_from, e.date_to, e.schedule_type_id, e.description, st.code, st.time_start, st.time_end FROM " . DB_PREFIX . "exchange e LEFT JOIN " . DB_PREFIX . "schedule_type st ON (st.schedule_type_id = e.schedule_type_id) WHERE customer_id = '" . (int)$customer_id . "' AND (e.date_from = '" . $this->db->escape($date) . "' OR e.date_to = '" . $this->db->escape($date) . "')";

		$query = $this->db->query($sql);

		return $query->row;
	}
	
	public function getAbsenceByDate($customer_id, $date) {
		$sql = "SELECT a.customer_id, a.date, a.presence_status_id, a.description, ps.name as presence_status FROM " . DB_PREFIX . "absence a LEFT JOIN " . DB_PREFIX . "presence_status ps ON (ps.presence_status_id = a.presence_status_id) WHERE customer_id = '" . (int)$customer_id . "' AND a.date = '" . $this->db->escape($date) . "' AND approved = 1";

		$query = $this->db->query($sql);

		return $query->row;
	}

	public function getScheduleByDate($customer_id, $date) {
		$sql = "SELECT DISTINCT s.customer_id, s.date, s.schedule_type_id, st.code, st.time_start, st.time_end FROM " . DB_PREFIX . "schedule s LEFT JOIN " . DB_PREFIX . "schedule_type st ON (st.schedule_type_id = s.schedule_type_id) WHERE s.customer_id = '" . (int)$customer_id . "' AND s.date = '" . $this->db->escape($date) . "'";

		$query = $this->db->query($sql);

		return $query->row;
	}

	public function getAppliedSchedule($customer_id, $date) {
		$date = date('Y-m-d', strtotime($date));
		
		$applied_schedule = array();
		
		$loop = 1;
		$counter = 1;

		while($loop == 1) {
			switch ($counter) {
				case '1':
					$absence_info = $this->getAbsenceByDate($customer_id, $date);

					if ($absence_info) {
						$applied_schedule = array(
							'applied'			=> 'absence',
							'schedule_type_id'	=> 0,
							'schedule_type'		=> '-',
							'time_in'			=> '',
							'time_out'			=> '',
							// 'time_login'		=> '',
							// 'time_logout'		=> '',
							'presence_status_id'=> $absence_info['presence_status_id'],
							'presence_status'	=> $absence_info['presence_status'],
							'note'				=> $absence_info['description']
						);
						
						$loop = 0;
					}
					
					break;
					
				case '2':
					$overtime_info = $this->getOvertimeByDate($customer_id, $date);
		
					if ($overtime_info) {
						if ($overtime_info['duration'] < 7) {
							$overtime_info['time_end'] = date('H:i:s', strtotime('+' . $overtime_info['duration'] . 'hours', strtotime($overtime_info['time_end'])));
						}
						
						$applied_schedule = array(
							// 'customer_id'		=> $overtime_info['customer_id'],
							'applied'				=> 'overtime',
							'schedule_type_id'	=> $overtime_info['schedule_type_id'],
							'schedule_type'		=> $overtime_info['code'],
							'time_in'			=> $overtime_info['time_start'],
							'time_out'			=> $overtime_info['time_end'],
							// 'time_login'		=> '',
							// 'time_logout'		=> '',
							'presence_status_id'=> '',
							'presence_status'	=> '',
							'note'				=> $overtime_info['description']
						);
						
						$loop = 0;
					}
					
					break;
					
				case '3':
					$exchange_info = $this->getExchangeByDate($customer_id, $date);

					if ($exchange_info) {
						if ($date == $exchange_info['date_to']) {
							$applied_schedule = array(
								'applied'			=> 'exchange',
								'schedule_type_id'	=> $exchange_info['schedule_type_id'],
								'schedule_type'		=> $exchange_info['code'],
								'time_in'			=> $exchange_info['time_start'],
								'time_out'			=> $exchange_info['time_end'],
								// 'time_login'		=> '',
								// 'time_logout'		=> '',
								'presence_status_id'=> '',
								'presence_status'	=> '',
								'note'				=> $exchange_info['description']
							);
							
						} elseif ($date == $exchange_info['date_from']) {
							$applied_schedule = array(
								'applied'			=> 'exchange',
								'schedule_type_id'	=> 0,
								'schedule_type'		=> '-',
								'time_in'			=> '',
								'time_out'			=> '',
								// 'time_login'		=> '',
								// 'time_logout'		=> '',
								'presence_status_id'=> 0,
								'presence_status'	=> '-',
								'note'				=> $exchange_info['description']
							);
						}
						
						$loop = 0;
					}
					
					break;
					
				case '4':
					$schedule_info = $this->getScheduleByDate($customer_id, $date);

					if ($schedule_info) {
						$applied_schedule = array(
							'applied'			=> 'schedule',
							'schedule_type_id'	=> $schedule_info['schedule_type_id'],
							'schedule_type'		=> $schedule_info['code'],
							'time_in'			=> $schedule_info['time_start'],
							'time_out'			=> $schedule_info['time_end'],
							// 'time_login'		=> '',
							// 'time_logout'		=> '',
							'presence_status_id'=> 0,
							'presence_status'	=> '',
							'note'				=> ''
						);
						
						$loop = 0;
					}
					
					break;
					
				default:
					$loop = 0;
			}
			
			$counter++;
		}
		
		return $applied_schedule;
	}

	public function addLog($customer_id, $date, $action) {
		if ($action == 'login') {
			$sql = "INSERT INTO " . DB_PREFIX . "presence_log SET customer_id = '" . (int)$customer_id . "', date = '" . $this->db->escape($date) . "', time_login = NOW()";
		} elseif($action == 'logout') {
			$sql = "UPDATE " . DB_PREFIX . "presence_log SET time_logout = NOW() WHERE customer_id = '" . (int)$customer_id . "' AND date = '" . $this->db->escape($date) . "'";
		}
		
		$this->db->query($sql);
	}

	public function getLog($customer_id, $date) {
		$sql = "SELECT * FROM " . DB_PREFIX . "presence_log WHERE customer_id = '" . (int)$customer_id . "' AND date = '" . $this->db->escape($date) . "'";

		$query = $this->db->query($sql);

		return $query->row;
	}
}

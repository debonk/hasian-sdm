<?php
class ModelCommonPayroll extends Model
{
	public function getPeriod($presence_period_id = 0)
	{ //sama dgn model_presence_presence_period->getPresencePeriod($presence_period_id)
		if ($presence_period_id <= 0) {
			$present_query = $this->db->query("SELECT presence_period_id FROM " . DB_PREFIX . "presence_period WHERE date_start <= CURDATE() AND date_end >= CURDATE()");

			if ($present_query->num_rows) {
				$presence_period_id = $present_query->row['presence_period_id'];
			} else {
				$latest_query = $this->db->query("SELECT MAX(presence_period_id) AS presence_period_id FROM " . DB_PREFIX . "presence_period");
				$presence_period_id = $latest_query->row['presence_period_id'];
			}
		}

		$sql = "SELECT DISTINCT *, (SELECT ps.name FROM " . DB_PREFIX . "payroll_status ps WHERE ps.payroll_status_id = pp.payroll_status_id) AS payroll_status FROM " . DB_PREFIX . "presence_period pp WHERE pp.presence_period_id = '" . (int)$presence_period_id . "'";

		$query = $this->db->query($sql);

		return $query->row;
	}

	public function getLatestPeriod()
	{
		$query = $this->db->query("SELECT MAX(presence_period_id) AS presence_period_id FROM " . DB_PREFIX . "presence_period");

		$period_data = [];

		if ($query->num_rows) {
			$period_data = $this->getPeriod($query->row['presence_period_id']);
		}

		return $period_data;
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

	public function setPeriodStatus($presence_period_id, $period_status, $data = array())
	{
		$status_data = array(
			'pending',
			'processing',
			'submitted',
			'generated',
			'approved',
			'released',
			'completed'
		);

		if (isset($period_status) && in_array($period_status, $status_data)) {
			$payroll_status_id = $this->config->get('payroll_setting_' . $period_status . '_status_id');

			$sql = "UPDATE " . DB_PREFIX . "presence_period SET payroll_status_id = '" . (int)$payroll_status_id . "'";

			switch ($period_status) {
				case "approved":
					$sql .= ", total_payroll = '" . (int)$data['total_payroll'] . "'";

					break;
				case "released":
					if ($this->checkPeriodStatus($presence_period_id, 'approved, released')) {
						$sql .= ", fund_account_id = '" . (int)$data['fund_account_id'] . "', date_release = STR_TO_DATE('" . $this->db->escape($data['date_release']) . "', '%d %b %Y')";
					}

					break;
				default:
			}

			$sql .= " WHERE presence_period_id = '" . (int)$presence_period_id . "'";

			$this->db->query($sql);
		}
	}

	public function getCustomer($customer_id)
	{
		// $query = $this->db->query("SELECT DISTINCT customer_id, store_id, firstname, lastname, nip, nik, date_start, c.image, payroll_include, full_overtime, skip_trial_status, health_insurance, life_insurance, employment_insurance, pension_insurance, email, c.telephone, acc_no, date_end, status, c.customer_department_id, c.customer_group_id, c.location_id, c.address_id, cdd.name AS customer_department, cgd.name AS customer_group, l.name AS location, pm.name AS payroll_method FROM " . DB_PREFIX . "customer c LEFT JOIN " . DB_PREFIX . "customer_department_description cdd ON (cdd.customer_department_id = c.customer_department_id) LEFT JOIN " . DB_PREFIX . "customer_group_description cgd ON (cgd.customer_group_id = c.customer_group_id) LEFT JOIN " . DB_PREFIX . "location l ON (l.location_id = c.location_id) LEFT JOIN " . DB_PREFIX . "payroll_method pm ON (pm.payroll_method_id = c.payroll_method_id) WHERE c.customer_id = '" . (int)$customer_id . "'");
		$query = $this->db->query("SELECT DISTINCT c.customer_id, store_id, firstname, lastname, nip, nik, date_start, c.image, payroll_include, full_overtime, skip_trial_status, health_insurance, life_insurance, employment_insurance, pension_insurance, registered_wage, email, c.telephone, acc_no, date_end, status, c.customer_department_id, c.customer_group_id, c.location_id, c.address_id, customer_department, customer_group, location, pm.name AS payroll_method FROM " . DB_PREFIX . "v_customer c LEFT JOIN " . DB_PREFIX . "customer_add_data cad ON (cad.customer_id = c.customer_id) LEFT JOIN " . DB_PREFIX . "payroll_method pm ON (pm.payroll_method_id = c.payroll_method_id) WHERE c.customer_id = '" . (int)$customer_id . "'");

		return $query->row;
	}

	public function checkCustomer($customer_id)
	{
		$query = $this->db->query("SELECT DISTINCT customer_id FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$customer_id . "'");

		return $query->row;
	}

	public function getCustomerDepartment($customer_department_id)
	{
		$query = $this->db->query("SELECT DISTINCT name FROM " . DB_PREFIX . "customer_department cd LEFT JOIN " . DB_PREFIX . "customer_department_description cdd ON (cd.customer_department_id = cdd.customer_department_id) WHERE cd.customer_department_id = '" . (int)$customer_department_id . "' AND cdd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row['name'];
	}

	public function getCustomerGroup($customer_group_id)
	{
		$query = $this->db->query("SELECT DISTINCT name FROM " . DB_PREFIX . "customer_group cg LEFT JOIN " . DB_PREFIX . "customer_group_description cgd ON (cg.customer_group_id = cgd.customer_group_id) WHERE cg.customer_group_id = '" . (int)$customer_group_id . "' AND cgd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row['name'];
	}

	public function getLocation($location_id)
	{ //customer_info
		$query = $this->db->query("SELECT DISTINCT name FROM " . DB_PREFIX . "location WHERE location_id = '" . (int)$location_id . "'");

		return $query->row['name'];
	}

	public function getOvertimeByDate($customer_id, $date)
	{
		$sql = "SELECT DISTINCT o.customer_id, o.date, o.schedule_type_id, o.description, ot.duration as duration, st.code, st.time_start, st.time_end FROM " . DB_PREFIX . "overtime o LEFT JOIN " . DB_PREFIX . "overtime_type ot ON (ot.overtime_type_id = o.overtime_type_id) LEFT JOIN " . DB_PREFIX . "schedule_type st ON (st.schedule_type_id = o.schedule_type_id) WHERE customer_id = '" . (int)$customer_id . "' AND o.date = '" . $this->db->escape($date) . "'";

		$query = $this->db->query($sql);

		return $query->row;
	}

	public function getExchangeByDate($customer_id, $date)
	{
		$sql = "SELECT DISTINCT e.customer_id, e.date_from, e.date_to, e.schedule_type_id, e.description, st.code, st.time_start, st.time_end FROM " . DB_PREFIX . "exchange e LEFT JOIN " . DB_PREFIX . "schedule_type st ON (st.schedule_type_id = e.schedule_type_id) WHERE customer_id = '" . (int)$customer_id . "' AND (e.date_from = '" . $this->db->escape($date) . "' OR e.date_to = '" . $this->db->escape($date) . "')";

		$query = $this->db->query($sql);

		return $query->row;
	}

	public function getAbsenceByDate($customer_id, $date)
	{
		$sql = "SELECT a.customer_id, a.date, a.presence_status_id, a.description, ps.name as presence_status FROM " . DB_PREFIX . "absence a LEFT JOIN " . DB_PREFIX . "presence_status ps ON (ps.presence_status_id = a.presence_status_id) WHERE customer_id = '" . (int)$customer_id . "' AND a.date = '" . $this->db->escape($date) . "' AND approved = 1";

		$query = $this->db->query($sql);

		return $query->row;
	}

	public function getScheduleByDate($customer_id, $date)
	{
		// $sql = "SELECT DISTINCT s.customer_id, s.date, s.schedule_type_id, st.name, st.time_start, st.time_end, pl.time_login, pl.time_logout FROM " . DB_PREFIX . "schedule s LEFT JOIN " . DB_PREFIX . "schedule_type st ON (st.schedule_type_id = s.schedule_type_id) LEFT JOIN " . DB_PREFIX . "presence_log pl ON (pl.customer_id = s.customer_id AND pl.date = s.date) WHERE s.customer_id = '" . (int)$customer_id . "' AND s.date = '" . $this->db->escape($date) . "'";
		$sql = "SELECT DISTINCT s.customer_id, s.date, s.schedule_type_id, st.code, st.time_start, st.time_end FROM " . DB_PREFIX . "schedule s LEFT JOIN " . DB_PREFIX . "schedule_type st ON (st.schedule_type_id = s.schedule_type_id) WHERE s.customer_id = '" . (int)$customer_id . "' AND s.date = '" . $this->db->escape($date) . "'";

		$query = $this->db->query($sql);

		return $query->row;
	}

	public function getAppliedSchedule($customer_id, $date)
	{
		$date = date('Y-m-d', strtotime($date));

		$applied_schedule = array();

		$loop = 1;
		$counter = 1;

		while ($loop == 1) {
			switch ($counter) {
				case '1':
					$overtime_info = $this->getOvertimeByDate($customer_id, $date);

					if ($overtime_info) {
						if ($overtime_info['duration'] < 7) {
							$overtime_info['time_end'] = date('H:i:s', strtotime('+' . $overtime_info['duration'] . 'hours', strtotime($overtime_info['time_end'])));
						}

						$applied_schedule = array(
							// 'customer_id'		=> $overtime_info['customer_id'],
							'applied'			=> 'overtime',
							'schedule_type_id'	=> $overtime_info['schedule_type_id'],
							'schedule_type'		=> $overtime_info['code'],
							'time_in'			=> $overtime_info['time_start'],
							'time_out'			=> $overtime_info['time_end'],
							// 'time_login'		=> '',
							// 'time_logout'		=> '',
							'presence_status_id' => '',
							'presence_status'	=> '',
							'note'				=> $overtime_info['description']
						);

						$loop = 0;
					}
					break;

				case '2':
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
							'presence_status_id' => $absence_info['presence_status_id'],
							'presence_status'	=> $absence_info['presence_status'],
							'note'				=> $absence_info['description']
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
								'presence_status_id' => '',
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
								'presence_status_id' => 0,
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
							'presence_status_id' => 0,
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

	public function getPresenceByDate($customer_id, $date)
	{ //Used by component/overtime
		$sql = "SELECT DISTINCT ps.name AS presence_status, pl.time_login, pl.time_logout FROM " . DB_PREFIX . "presence p LEFT JOIN " . DB_PREFIX . "presence_status ps ON (ps.presence_status_id = p.presence_status_id) LEFT JOIN " . DB_PREFIX . "presence_log pl ON (pl.customer_id = p.customer_id AND pl.date = p.date_presence) WHERE p.customer_id = '" . (int)$customer_id . "' AND p.date_presence = '" . $this->db->escape($date) . "'";

		$query = $this->db->query($sql);

		return $query->row;
	}
}

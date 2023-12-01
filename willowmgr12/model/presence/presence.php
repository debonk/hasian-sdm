<?php
class ModelPresencePresence extends Model
{
	public function getCustomers($data = array())
	{
		$sql = "SELECT customer_id, nip, name, date_start, date_added, customer_department_id, customer_department, customer_group_id, customer_group, location_id, location FROM " . DB_PREFIX . "v_customer WHERE (language_id = '" . (int)$this->config->get('config_language_id') . "' OR language_id IS NULL) AND status = 1";

		$implode = array();

		if (!empty($data['filter_name'])) {
			$implode[] = "name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_customer_department_id'])) {
			$implode[] = "customer_department_id = '" . (int)$data['filter_customer_department_id'] . "'";
		}

		if (!empty($data['filter_customer_group_id'])) {
			$implode[] = "customer_group_id = '" . (int)$data['filter_customer_group_id'] . "'";
		}

		if (!empty($data['filter_location_id'])) {
			$implode[] = "location_id = '" . (int)$data['filter_location_id'] . "'";
		}

		if (isset($data['filter_payroll_include'])) {
			$implode[] = "payroll_include = '" . (int)$data['filter_payroll_include'] . "'";
		}

		if (isset($data['presence_period_id'])) {
			$this->load->model('common/payroll');
			$period_info = $this->model_common_payroll->getPeriod($data['presence_period_id']);

			$implode[] = "date_start <= '" . $this->db->escape($period_info['date_end']) . "'";

			$date_start = $period_info['date_start'];

			$availability = (int)$this->config->get('config_customer_last');

			if ($availability) {
				$period_info_availability = $this->model_common_payroll->getPeriodByDate(date('Y-m-d', strtotime('-' . $availability . ' months')));

				if ($period_info_availability) {
					$date_start = $period_info_availability['date_start'];
				}
			}
			
			$implode[] = "(date_end IS NULL OR date_end >= '" . $this->db->escape($date_start) . "')";

		} elseif (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			if (!empty($data['filter_status'])) {
				$implode[] = "date_end <= CURDATE()";
			}
		} else {
			$implode[] = "(date_end IS NULL OR date_end >= CURDATE())";
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

	public function getTotalCustomers($data = array())
	{ //Used by: dashboard/customer, report payroll insurance, payroll basic, schedule
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "v_customer WHERE (language_id = '" . (int)$this->config->get('config_language_id') . "' OR language_id IS NULL) AND status = '1'";

		$implode = array();

		if (!empty($data['filter_name'])) {
			$implode[] = "name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_customer_department_id'])) {
			$implode[] = "customer_department_id = '" . (int)$data['filter_customer_department_id'] . "'";
		}

		if (!empty($data['filter_customer_group_id'])) {
			$implode[] = "customer_group_id = '" . (int)$data['filter_customer_group_id'] . "'";
		}

		if (!empty($data['filter_location_id'])) {
			$implode[] = "location_id = '" . (int)$data['filter_location_id'] . "'";
		}

		if (isset($data['filter_payroll_include'])) {
			$implode[] = "payroll_include = '" . (int)$data['filter_payroll_include'] . "'";
		}

		if (isset($data['presence_period_id'])) {
			$this->load->model('common/payroll');
			$period_info = $this->model_common_payroll->getPeriod($data['presence_period_id']);

			$implode[] = "date_start <= '" . $this->db->escape($period_info['date_end']) . "'";
			$implode[] = "(date_end IS NULL OR date_end = '0000-00-00' OR date_end >= '" . $this->db->escape($period_info['date_start']) . "')";
		} elseif (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			if (!empty($data['filter_status'])) {
				$implode[] = "(date_end <> '0000-00-00' AND date_end <= CURDATE())";
			}
		} else {
			$implode[] = "(date_end IS NULL OR date_end = '0000-00-00' OR date_end >= CURDATE())";
		}

		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getCustomerAddData($customer_id)
	{
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "customer_add_data WHERE customer_id = '" . (int)$customer_id . "'");

		return $query->row;
	}

	public function addPresence($presence_period_id, $customer_id, $date, $presence_status_id)
	{ //Used by schedule
		$sql = "INSERT INTO " . DB_PREFIX . "presence SET presence_period_id = '" . (int)$presence_period_id . "', customer_id = '" . (int)$customer_id . "', date_presence = '" . $this->db->escape($date) . "', presence_status_id = '" . (int)$presence_status_id . "', user_id = '" . (int)$this->user->getId() . "', date_added = NOW()";

		$this->db->query($sql);
	}

	public function editPresence($customer_id, $date, $presence_status_id)
	{
		$this->db->query("UPDATE " . DB_PREFIX . "presence SET presence_status_id = '" . (int)$presence_status_id . "', user_id = '" . (int)$this->user->getId() . "', date_added = NOW() WHERE customer_id = '" . (int)$customer_id . "' AND date_presence = '" . $this->db->escape($date) . "'");
	}

	public function deletePresence($presence_period_id, $customer_id)
	{ //used by: schedule
		$this->db->query("DELETE FROM " . DB_PREFIX . "presence WHERE presence_period_id = '" . (int)$presence_period_id . "' AND customer_id = '" . (int)$customer_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "presence_total WHERE presence_period_id = '" . (int)$presence_period_id . "' AND customer_id = '" . (int)$customer_id . "'");
	}

	public function editPresences($presence_period_id, $customer_id, $data)
	{
		$this->deletePresence($presence_period_id, $customer_id);

		foreach ($data as $key => $value) {
			$this->addPresence($presence_period_id, $customer_id, $key, $value);
		}

		$config_presence_status_id = array();

		$presence_statuses = array(
			'h',
			's',
			'i',
			'ns',
			'ia',
			'a',
			'c',
			't1',
			't2',
			't3'
		);
		foreach ($presence_statuses as $presence_status) {
			$config_presence_status_id[$presence_status] = $this->config->get('payroll_setting_id_' . $presence_status);
		}

		$presence_summary = array_count_values($data);

		$presences_data = array();

		foreach ($config_presence_status_id as $key => $value) {
			if (isset($presence_summary[$value])) {
				$presences_data[$key] = $presence_summary[$value];
			} else {
				$presences_data[$key] = 0;
			}
		}

		$period_info = $this->model_common_payroll->getPeriod($presence_period_id);
		$customer_info = $this->model_common_payroll->getCustomer($customer_id);

		if (strtotime($customer_info['date_start']) > strtotime($period_info['date_start']) || (isset($customer_info['date_end']) && strtotime($customer_info['date_end']) <= strtotime($period_info['date_end']))) {
			$default_hke = $this->config->get('payroll_setting_default_hke');

			$this->load->model('overtime/overtime');

			$range_date = array(
				'start'		=> $period_info['date_start'],
				'end'		=> $period_info['date_end']
			);

			$full_overtimes_count = $this->model_overtime_overtime->getFullOvertimesCount($customer_id, $range_date);
			$default_hke += $full_overtimes_count;

			$presences_data['ns'] += max($default_hke - array_sum($presences_data), 0);
			//Dgn cara ini, ck akan dianggap ns. Formulasi ulang dgn menggunakan kode presence_status
		}

		$presences_data['h'] += $presences_data['t1'] + $presences_data['t2'] + $presences_data['t3'];

		$this->addPresenceSummary($presence_period_id, $customer_id, $presences_data);
	}

	public function getPresences($customer_id, $range_date)
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "presence WHERE customer_id = '" . (int)$customer_id . "' AND date_presence >= '" . $this->db->escape($range_date['start']) . "' AND date_presence <= '" . $this->db->escape($range_date['end']) . "' ORDER BY date_presence ASC";

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getPresencesCount($presence_period_id)
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "presence WHERE presence_period_id = '" . (int)$presence_period_id . "'";

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getFinalPresences($customer_id, $range_date)
	{
		$presences_info = $this->getPresences($customer_id, $range_date);

		$presences_data = array();

		foreach ($presences_info as $presence_info) {
			$presences_data[$presence_info['date_presence']] = array(
				'presence_status_id' => $presence_info['presence_status_id'],
				'presence_status'	=> '',
				'note'				=> '',
				'locked'			=> 0
			);
		}

		$exchanges_info = $this->model_presence_exchange->getExchangesByCustomerDate($customer_id, $range_date);

		foreach ($exchanges_info as $exchange_info) {
			if ($exchange_info['date_from'] <> $exchange_info['date_to']) {
				$presences_data[$exchange_info['date_from']] = array(
					'presence_status_id' => 0,
					'presence_status'	=> 'X',
					'note'				=> $exchange_info['description'],
					'locked'			=> 1
				);
			}
		}

		$absences_info = $this->model_presence_absence->getAbsencesByCustomerDate($customer_id, $range_date);

		foreach ($absences_info as $absence_info) {
			if ($absence_info['approved']) {
				$presence_status_id = $absence_info['presence_status_id'];
				$presence_status = $absence_info['presence_status'];
			} else {
				$this->load->model('localisation/presence_status');

				$presence_status_id = $this->config->get('payroll_setting_id_ia');
				$presence_status = $this->model_localisation_presence_status->getPresenceStatus($presence_status_id)['name'];
			}

			$presences_data[$absence_info['date']] = array(
				'presence_status_id' => $presence_status_id,
				'presence_status'	=> $presence_status,
				'note'				=> $absence_info['description'],
				'locked'			=> 1
			);
		}

		return $presences_data;
	}

	public function addPresenceSummary($presence_period_id, $customer_id, $data)
	{ //used by: schedule
		$sql = "INSERT INTO " . DB_PREFIX . "presence_total SET presence_period_id = '" . (int)$presence_period_id . "', customer_id = '" . (int)$customer_id . "'";

		$presence_statuses = array(
			'h',
			's',
			'i',
			'ns',
			'ia',
			'a',
			'c',
			't1',
			't2',
			't3'
		);

		foreach ($presence_statuses as $presence_status) {
			$sql .= ", total_" . $presence_status . " = '" . (int)$data[$presence_status] . "'";
		}

		$sql .= ", date_added = NOW()";

		$this->db->query($sql);
	}

	public function editPresenceSummary($presence_period_id, $customer_id, $data)
	{
		if ($presence_period_id && $customer_id) {
			$sql = "UPDATE " . DB_PREFIX . "presence_total SET presence_period_id = '" . (int)$presence_period_id . "', customer_id = '" . (int)$customer_id . "'";

			foreach ($data as $key => $value) {
				$sql .= ", " . $key . " = '" . (int)$value . "'";
			}

			$sql .= ", date_added = NOW() WHERE presence_period_id = '" . (int)$presence_period_id . "' AND customer_id = '" . (int)$customer_id . "'";

			$query = $this->db->query($sql);
			return 1;
		} else {
			return;
		}
	}

	public function getPresenceSummary($presence_period_id, $customer_id)
	{
		$sql = "SELECT DISTINCT * FROM " . DB_PREFIX . "presence_total WHERE presence_period_id = '" . (int)$presence_period_id . "' AND customer_id = '" . (int)$customer_id . "'";

		$query = $this->db->query($sql);

		$presence_summary_data = $query->row;

		if ($presence_summary_data) {
			$presence_summary_data['hke'] = $presence_summary_data['total_h'] + $presence_summary_data['total_s'] + $presence_summary_data['total_i'] + $presence_summary_data['total_ns'] + $presence_summary_data['total_ia'] + $presence_summary_data['total_a'];
			$presence_summary_data['total_sakit'] = $presence_summary_data['total_s'] + $presence_summary_data['total_i'];
			$presence_summary_data['total_bolos'] = $presence_summary_data['total_ns'] + $presence_summary_data['total_ia'] + $presence_summary_data['total_a'];

			$presence_summary_data['total_t'] = $presence_summary_data['total_t1'] + $presence_summary_data['total_t2'] * 3 + $presence_summary_data['total_t3'] * 5;

			//Perhitungan Jumlah Lembur Harian (Lembur Penuh)
			$presence_summary_data['full_overtimes_count'] = 0;
			$period_info = $this->model_common_payroll->getPeriod($presence_period_id);

			$range_date = array(
				'start'		=> $period_info['date_start'],
				'end'		=> $period_info['date_end']
			);

			$this->load->model('overtime/overtime');
			$presence_summary_data['full_overtimes_count'] = $this->model_overtime_overtime->getFullOvertimesCount($customer_id, $range_date);
		}

		return $presence_summary_data;
	}

	public function getPresenceSummaries($presence_period_id, $data = array())
	{
		$sql = "SELECT pt.*, c.nip, CONCAT(c.firstname, ' [', c.lastname, ']') AS name, c.date_start, c.date_end, cgd.name AS customer_group, l.name AS location FROM " . DB_PREFIX . "presence_total pt LEFT JOIN " . DB_PREFIX . "customer c ON (c.customer_id = pt.customer_id) LEFT JOIN " . DB_PREFIX . "customer_group_description cgd ON (cgd.customer_group_id = c.customer_group_id) LEFT JOIN " . DB_PREFIX . "location l ON (l.location_id = c.location_id) WHERE presence_period_id = '" . (int)$presence_period_id . "'";

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

		if (isset($data['filter_payroll_include'])) {
			$implode[] = "c.payroll_include = '" . (int)$data['filter_payroll_include'] . "'";
		}

		if (isset($data['filter_presence_code'])) {
			$implode[] = "pt.total_" . $this->db->escape($data['filter_presence_code']) . " > 0";
		}

		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}

		$sort_data = array(
			'nip',
			'name',
			'customer_group',
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

	public function getPresenceSummariesCount($presence_period_id, $data = array())
	{
		$sql = "SELECT COUNT(*) as total FROM " . DB_PREFIX . "presence_total pt LEFT JOIN " . DB_PREFIX . "customer c ON (c.customer_id = pt.customer_id) WHERE pt.presence_period_id = '" . (int)$presence_period_id . "'";

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

		if (isset($data['filter_payroll_include'])) {
			$implode[] = "c.payroll_include = '" . (int)$data['filter_payroll_include'] . "'";
		}

		if (isset($data['filter_presence_code'])) {
			$implode[] = "pt.total_" . $this->db->escape($data['filter_presence_code']) . " > 0";
		}

		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getPresenceSummariesTotal($data = array())
	{
		$sql = "SELECT SUM(total_h) as sum_h, SUM(total_s) as sum_s, SUM(total_i) as sum_i, SUM(total_ns) as sum_ns, SUM(total_ia) as sum_ia, SUM(total_a) as sum_a, SUM(total_c) as sum_c, SUM(total_t1) as sum_t1, SUM(total_t2) as sum_t2, SUM(total_t3) as sum_t3 FROM " . DB_PREFIX . "presence_total pt LEFT JOIN " . DB_PREFIX . "customer c ON (c.customer_id = pt.customer_id) WHERE presence_period_id = '" . (int)$data['presence_period_id'] . "'";

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

		if (isset($data['filter_payroll_include'])) {
			$implode[] = "c.payroll_include = '" . (int)$data['filter_payroll_include'] . "'";
		}

		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return $query->row;
	}

	public function getAllCustomerPresenceSummaries($presence_period_id, $data = array())
	{
		$period_info = $this->model_common_payroll->getPeriod($presence_period_id);

		$sql = "SELECT pt.*, c.customer_id, c.nip, CONCAT(c.firstname, ' [', c.lastname, ']') AS name, c.date_start, c.date_end, cgd.name AS customer_group, l.name AS location FROM " . DB_PREFIX . "presence_total pt RIGHT JOIN " . DB_PREFIX . "customer c ON (c.customer_id = pt.customer_id AND pt.presence_period_id = '" . (int)$presence_period_id . "') LEFT JOIN " . DB_PREFIX . "customer_group_description cgd ON (cgd.customer_group_id = c.customer_group_id) LEFT JOIN " . DB_PREFIX . "location l ON (l.location_id = c.location_id) WHERE c.status = 1 AND c.date_start <= '" . $period_info['date_end'] . "' AND (c.date_end IS NULL OR c.date_end >= '" . $period_info['date_start'] . "')";

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

		if (isset($data['filter_payroll_include'])) {
			$implode[] = "c.payroll_include = '" . (int)$data['filter_payroll_include'] . "'";
		}

		if (isset($data['filter_presence_code'])) {
			$implode[] = "pt.total_" . $this->db->escape($data['filter_presence_code']) . " > 0";
		}

		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}

		$sort_data = array(
			'nip',
			'name',
			'customer_group',
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

	public function getAllCustomerPresenceSummariesCount($presence_period_id, $data = array())
	{
		$period_info = $this->model_common_payroll->getPeriod($presence_period_id);

		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "presence_total pt RIGHT JOIN " . DB_PREFIX . "customer c ON (c.customer_id = pt.customer_id AND pt.presence_period_id = '" . (int)$presence_period_id . "') WHERE c.status = 1 AND c.date_start <= '" . $period_info['date_end'] . "' AND (c.date_end IS NULL OR c.date_end >= '" . $period_info['date_start'] . "')";

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

		if (isset($data['filter_payroll_include'])) {
			$implode[] = "c.payroll_include = '" . (int)$data['filter_payroll_include'] . "'";
		}

		if (isset($data['filter_presence_code'])) {
			$implode[] = "pt.total_" . $this->db->escape($data['filter_presence_code']) . " > 0";
		}

		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getEmptyPresencesCount($presence_period_id)
	{
		$period_info = $this->model_common_payroll->getPeriod($presence_period_id);

		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer c LEFT JOIN " . DB_PREFIX . "presence_total pt ON (c.customer_id = pt.customer_id AND pt.presence_period_id = '" . (int)$presence_period_id . "') WHERE c.status = 1 AND c.date_start <= '" . $period_info['date_end'] . "' AND (c.date_end IS NULL OR c.date_end >= '" . $period_info['date_start'] . "') AND pt.presence_period_id IS NULL";

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getPresencesCountByPresenceStatusId($presence_status_id)
	{ //Used by localisation/presence_status
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "presence WHERE presence_status_id = '" . (int)$presence_status_id . "'");

		return $query->row['total'];
	}
}

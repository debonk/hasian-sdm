<?php
class ModelPresencePresence extends Model
{
	public function getCustomers($data = array())
	{
		$sql = "SELECT customer_id, nip, c.name, date_start, date_added, customer_department_id, customer_department, customer_group_id, customer_group, location_id, location, pm.name AS payroll_method FROM " . DB_PREFIX . "v_customer c LEFT JOIN " . DB_PREFIX . "payroll_method pm ON (pm.payroll_method_id = c.payroll_method_id) WHERE (c.language_id = '" . (int)$this->config->get('config_language_id') . "' OR c.language_id IS NULL) AND status = 1";

		$implode = array();

		if (!empty($data['filter_name'])) {
			$implode[] = "c.name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
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

			if (isset($data['availability']) && $data['availability']) {
				$availability = (int)$this->config->get('config_customer_last');

				if ($availability) {
					$period_info_availability = $this->model_common_payroll->getPeriodByDate(date('Y-m-d', strtotime('-' . $availability . ' months')));

					if ($period_info_availability) {
						$date_start = $period_info_availability['date_start'];
					}
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

	public function addPresences($presence_period_id, $customer_id, $presences_data)
	{ # Batch mode
		$batch_data = [];

		foreach ($presences_data as $date => $presence_data) {
			$batch_data[] = [$presence_period_id, $customer_id, $date, $presence_data['presence_status_id']];
		}

		$sql = "INSERT INTO " . DB_PREFIX . "presence (presence_period_id, customer_id, date_presence, presence_status_id, user_id) VALUES ";

		$implode = [];

		foreach ($batch_data as $data) {
			$implode[] = "('" . implode('\', \'', $data) . "', " . (int)$this->user->getId() . ")";
		}

		$sql .= implode(',', $implode) . ";";

		$this->db->query($sql);
	}

	public function editPresence($customer_id, $date, $presence_status_id)
	{
		$this->db->query("UPDATE " . DB_PREFIX . "presence SET presence_status_id = '" . (int)$presence_status_id . "', user_id = '" . (int)$this->user->getId() . "', date_added = NOW() WHERE customer_id = '" . (int)$customer_id . "' AND date_presence = '" . $this->db->escape($date) . "'");
	}

	public function deletePresence($presence_period_id, $customer_id)
	{
		$this->db->query("DELETE FROM " . DB_PREFIX . "presence WHERE presence_period_id = '" . (int)$presence_period_id . "' AND customer_id = '" . (int)$customer_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "presence_total WHERE presence_period_id = '" . (int)$presence_period_id . "' AND customer_id = '" . (int)$customer_id . "'");
	}

	public function editPresences($presence_period_id, $customer_id, $data)
	{
		$this->load->model('localisation/presence_status');
		$presence_status_data = $this->model_localisation_presence_status->getPresenceStatusIdList();

		$presences_data = [];

		foreach ($data as $date => $presence_status_id) {
			$presences_data[$date] = [
				'presence_status_id'	=> $presence_status_id,
				'presence_code'			=> isset($presence_status_data[$presence_status_id]) ? $presence_status_data[$presence_status_id]['code'] : ''
			];
		}

		$this->deletePresence($presence_period_id, $customer_id);
		$this->addPresences($presence_period_id, $customer_id, $presences_data);

		$this->load->model('presence/schedule');
		$presence_summary_info = $this->model_presence_schedule->calculatePresenceSummary($presence_period_id, $customer_id, $presences_data);

		$this->addPresenceSummary($presence_period_id, $customer_id, $presence_summary_info);
	}

	public function getPresence($customer_id, $date)
	{
		$sql = "SELECT DISTINCT p.*, ps.code FROM " . DB_PREFIX . "presence p LEFT JOIN " . DB_PREFIX . "presence_status ps ON ps.presence_status_id = p.presence_status_id WHERE customer_id = '" . (int)$customer_id . "' AND date_presence = '" . $this->db->escape($date) . "'";

		$query = $this->db->query($sql);

		return $query->row;
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

		$this->load->model('localisation/presence_status');
		$presence_status_data = $this->model_localisation_presence_status->getPresenceStatusIdList();

		foreach ($presences_info as $presence_info) {
			$presences_data[$presence_info['date_presence']] = array(
				'presence_status_id'	=> $presence_info['presence_status_id'],
				'presence_status'		=> $presence_status_data[$presence_info['presence_status_id']]['name'],
				'note'					=> '',
				'locked'				=> 0
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
				$presence_status = $presence_status_data[$this->config->get('payroll_setting_id_ia')]['name'];
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
	{
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

		$presence_status_data = array_merge($data['primary'], $data['secondary']);

		foreach ($presence_statuses as $presence_status) {
			$sql .= ", total_" . $presence_status . " = '" . (int)$presence_status_data[$presence_status] . "'";
		}

		if ($data['additional']) {
			$sql .= ", additional = '" . json_encode($data['additional']) . "'";
		}

		$this->db->query($sql);
	}

	public function editPresenceSummary($presence_period_id, $customer_id, $data)
	{
		if ($presence_period_id && $customer_id) {
			$sql = "UPDATE " . DB_PREFIX . "presence_total";

			$implode = [];

			foreach (array_merge($data['primary'], $data['secondary']) as $key => $value) {
				$implode[] = "total_" . $key . " = '" . (int)$value . "'";
			}

			if (isset($data['additional'])) {
				$implode[] = "additional = '" . json_encode($data['additional']) . "'";
			}

			if ($implode) {
				$sql .= " SET " . implode(', ', $implode);
			}

			$sql .= " WHERE presence_period_id = '" . (int)$presence_period_id . "' AND customer_id = '" . (int)$customer_id . "'";

			$this->db->query($sql);

			return true;
		} else {
			return;
		}
	}

	public function getPresenceSummary($presence_period_id, $customer_id)
	{
		$sql = "SELECT DISTINCT * FROM " . DB_PREFIX . "presence_total WHERE presence_period_id = '" . (int)$presence_period_id . "' AND customer_id = '" . (int)$customer_id . "'";

		$query = $this->db->query($sql);

		$presence_summary_data = $this->calculatePresenceSummaryData($query->row);

		# Perhitungan Jumlah Lembur Harian (Lembur Penuh)
		$period_info = $this->model_common_payroll->getPeriod($presence_period_id);

		$range_date = array(
			'start'		=> $period_info['date_start'],
			'end'		=> $period_info['date_end']
		);

		$this->load->model('overtime/overtime');
		$presence_summary_data['total']['full_overtime'] = $this->model_overtime_overtime->getFullOvertimesCount($customer_id, $range_date);

		return $presence_summary_data;
	}

	public function calculatePresenceSummaryData($presence_summary, $additional_items = [])
	{
		$presence_summary_data = [];

		$this->load->model('localisation/presence_status');
		$presence_statuses = $this->model_localisation_presence_status->getPresenceStatusesData();

		$additional_data = !empty($presence_summary['additional']) ? json_decode($presence_summary['additional'], true) : [];

		if ($additional_items) {
			$presence_statuses['additional'] = $additional_items;
		} elseif ($additional_data) {
			$presence_statuses['additional'] = array_unique(array_merge($presence_statuses['additional'], array_keys($additional_data)));
		}

		foreach ($presence_statuses as $group => $items) {
			$presence_summary_data[$group] = array_fill_keys($items, 0);
		}

		foreach ($presence_statuses as $presence_group => $presence_status) {
			switch ($presence_group) {
				case 'primary':
				case 'secondary':
					foreach ($presence_status as $code) {
						if (isset($presence_summary['total_' . $code])) {
							$presence_summary_data[$presence_group][$code] = $presence_summary['total_' . $code];
						}
					}

					break;

				case 'additional':
					foreach ($presence_status as $code) {
						if (isset($additional_data[$code])) {
							$presence_summary_data[$presence_group][$code] = $additional_data[$code];
						}
					}

					break;

				case 'total':
					$presence_summary_data['total']['hke'] = array_sum($presence_summary_data['primary']) + array_sum($presence_summary_data['additional']);
					$presence_summary_data['total']['t'] = array_sum($presence_summary_data['secondary']);

					break;

				default:
					break;
			}
		}

		return $presence_summary_data;
	}

	public function getPresenceSummaries($presence_period_id, $data = array())
	{
		$sql = "SELECT pt.*, c.nip, c.firstname, c.lastname, CONCAT(c.firstname, ' [', c.lastname, ']') AS name, c.date_start, c.date_end, c.customer_group, c.location, c.contract_type_id, c.contract_type FROM " . DB_PREFIX . "presence_total pt LEFT JOIN " . DB_PREFIX . "v_customer c ON (c.customer_id = pt.customer_id) WHERE presence_period_id = '" . (int)$presence_period_id . "'";

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

		if (isset($data['filter_contract_type_id'])) {
			$implode[] = "c.contract_type_id = '" . $this->db->escape($data['filter_contract_type_id']) . "'";
		}

		if (isset($data['filter_payroll_include'])) {
			$implode[] = "c.payroll_include = '" . (int)$data['filter_payroll_include'] . "'";
		}

		if (isset($data['filter_presence_code'])) {
			$presence_code_data = [ # Next ubah ke sistem array filter dan pisahkan filter primary dengan additional
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
			];

			if (in_array($this->db->escape($data['filter_presence_code']), $presence_code_data)) {
				$implode[] = "pt.total_" . $this->db->escape($data['filter_presence_code']) . " > 0";
			} else {
				$implode[] = "pt.additional LIKE ('%\"" . $this->db->escape($data['filter_presence_code']) . "\"%')";
			}
		}

		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}

		$sort_data = array(
			'nip',
			'name',
			'customer_group',
			'location',
			'contract_type',
			'total_h',
			'total_s',
			'total_i',
			'total_ns',
			'total_ia',
			'total_a',
			'total_c',
			'total_t1',
			'total_t2',
			'total_t3'
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
		$sql = "SELECT COUNT(*) as total FROM " . DB_PREFIX . "presence_total pt LEFT JOIN " . DB_PREFIX . "v_customer c ON (c.customer_id = pt.customer_id) WHERE pt.presence_period_id = '" . (int)$presence_period_id . "'";

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

		if (isset($data['filter_contract_type_id'])) {
			$implode[] = "c.contract_type_id = '" . $this->db->escape($data['filter_contract_type_id']) . "'";
		}

		if (isset($data['filter_payroll_include'])) {
			$implode[] = "c.payroll_include = '" . (int)$data['filter_payroll_include'] . "'";
		}

		if (isset($data['filter_presence_code'])) {
			$presence_code_data = [ # Next ubah ke sistem array filter dan pisahkan filter primary dengan additional
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
			];

			if (in_array($this->db->escape($data['filter_presence_code']), $presence_code_data)) {
				$implode[] = "pt.total_" . $this->db->escape($data['filter_presence_code']) . " > 0";
			} else {
				$implode[] = "pt.additional LIKE ('%" . $this->db->escape($data['filter_presence_code']) . "%')";
			}
		}

		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getPresenceSummariesTotal($data = array())
	{
		$sql = "SELECT SUM(total_h) as total_h, SUM(total_s) as total_s, SUM(total_i) as total_i, SUM(total_ns) as total_ns, SUM(total_ia) as total_ia, SUM(total_a) as total_a, SUM(total_c) as total_c, SUM(total_t1) as total_t1, SUM(total_t2) as total_t2, SUM(total_t3) as total_t3 FROM " . DB_PREFIX . "presence_total pt LEFT JOIN " . DB_PREFIX . "v_customer c ON (c.customer_id = pt.customer_id) WHERE presence_period_id = '" . (int)$data['presence_period_id'] . "'";

		$implode_sql = '';
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

		if (isset($data['filter_contract_type_id'])) {
			$implode[] = "c.contract_type_id = '" . $this->db->escape($data['filter_contract_type_id']) . "'";
		}

		if (isset($data['filter_payroll_include'])) {
			$implode[] = "c.payroll_include = '" . (int)$data['filter_payroll_include'] . "'";
		}

		if ($implode) {
			$implode_sql = " AND " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql . $implode_sql);

		$sql = "SELECT pt.additional FROM " . DB_PREFIX . "presence_total pt LEFT JOIN " . DB_PREFIX . "v_customer c ON (c.customer_id = pt.customer_id) WHERE presence_period_id = '" . (int)$data['presence_period_id'] . "'";

		$additional_query = $this->db->query($sql . $implode_sql);

		$additional_sum_data = [];
		foreach ($additional_query->rows as $row) {
			if ($row['additional']) {
				foreach (json_decode($row['additional'], 1) as $key => $value) {
					if (!isset($additional_sum_data[$key])) {
						$additional_sum_data[$key] = $value;
					} else {
						$additional_sum_data[$key] += $value;
					}
				}
			}
		}

		$presence_summary_total_data = array_merge($query->row, ['additional' => json_encode($additional_sum_data)]);
		$presence_summary_total_data = $this->calculatePresenceSummaryData($presence_summary_total_data);

		return $presence_summary_total_data;
	}

	public function getAllCustomerPresenceSummaries($presence_period_id, $data = array())
	{
		$period_info = $this->model_common_payroll->getPeriod($presence_period_id);

		$sql = "SELECT pt.*, c.customer_id, c.nip, CONCAT(c.firstname, ' [', c.lastname, ']') AS name, c.date_start, c.date_end, c.customer_group, c.location, c.contract_type_id, c.contract_type FROM " . DB_PREFIX . "presence_total pt RIGHT JOIN " . DB_PREFIX . "v_customer c ON (c.customer_id = pt.customer_id AND pt.presence_period_id = '" . (int)$presence_period_id . "') WHERE c.status = 1 AND c.date_start <= '" . $period_info['date_end'] . "' AND (c.date_end IS NULL OR c.date_end >= '" . $period_info['date_start'] . "')";

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

		if (isset($data['filter_contract_type_id'])) {
			$implode[] = "c.contract_type_id = '" . $this->db->escape($data['filter_contract_type_id']) . "'";
		}

		if (isset($data['filter_payroll_include'])) {
			$implode[] = "c.payroll_include = '" . (int)$data['filter_payroll_include'] . "'";
		}

		if (isset($data['filter_presence_code'])) {
			$presence_code_data = [ # Next ubah ke sistem array filter dan pisahkan filter primary dengan additional
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
			];

			if (in_array($this->db->escape($data['filter_presence_code']), $presence_code_data)) {
				$implode[] = "pt.total_" . $this->db->escape($data['filter_presence_code']) . " > 0";
			} else {
				$implode[] = "pt.additional LIKE ('%" . $this->db->escape($data['filter_presence_code']) . "%')";
			}
		}

		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}

		$sort_data = array(
			'nip',
			'name',
			'customer_group',
			'location',
			'contract_type',
			'total_h',
			'total_s',
			'total_i',
			'total_ns',
			'total_ia',
			'total_a',
			'total_c',
			'total_t1',
			'total_t2',
			'total_t3'
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

		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "presence_total pt RIGHT JOIN " . DB_PREFIX . "v_customer c ON (c.customer_id = pt.customer_id AND pt.presence_period_id = '" . (int)$presence_period_id . "') WHERE c.status = 1 AND c.date_start <= '" . $period_info['date_end'] . "' AND (c.date_end IS NULL OR c.date_end >= '" . $period_info['date_start'] . "')";

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

		if (isset($data['filter_contract_type_id'])) {
			$implode[] = "c.contract_type_id = '" . $this->db->escape($data['filter_contract_type_id']) . "'";
		}

		if (isset($data['filter_payroll_include'])) {
			$implode[] = "c.payroll_include = '" . (int)$data['filter_payroll_include'] . "'";
		}

		if (isset($data['filter_presence_code'])) {
			$presence_code_data = [ # Next ubah ke sistem array filter dan pisahkan filter primary dengan additional
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
			];

			if (in_array($this->db->escape($data['filter_presence_code']), $presence_code_data)) {
				$implode[] = "pt.total_" . $this->db->escape($data['filter_presence_code']) . " > 0";
			} else {
				$implode[] = "pt.additional LIKE ('%" . $this->db->escape($data['filter_presence_code']) . "%')";
			}
		}

		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getPresenceAdditionalItem($additional_items = [])
	{
		$additional_item_data = [];

		foreach ($additional_items as $items) {
			if ($items) {
				foreach (array_keys(json_decode($items, 1)) as $code) {
					if (!in_array($code, $additional_item_data)) {
						$additional_item_data[] = $code;
					}
				}
			}
		}

		return $additional_item_data;
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

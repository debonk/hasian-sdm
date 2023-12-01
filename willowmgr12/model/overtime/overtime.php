<?php
class ModelOvertimeOvertime extends Model
{
	public function addOvertime($data)
	{
		$this->db->query("INSERT INTO " . DB_PREFIX . "overtime SET customer_id = '" . (int)$data['customer_id'] . "', date = STR_TO_DATE('" . $this->db->escape($data['date']) . "', '%d %b %Y'), overtime_type_id = '" . (int)$data['overtime_type_id'] . "', description = '" . $this->db->escape($data['description']) . "', schedule_type_id = '" . (int)$data['schedule_type_id'] . "', approved = 1, user_id = '" . (int)$this->user->getId() . "'");
	}

	public function editOvertime($overtime_id, $data)
	{
		$this->db->query("UPDATE " . DB_PREFIX . "overtime SET date = STR_TO_DATE('" . $this->db->escape($data['date']) . "', '%d %b %Y'), overtime_type_id = '" . (int)$data['overtime_type_id'] . "', description = '" . $this->db->escape($data['description']) . "', schedule_type_id = '" . (int)$data['schedule_type_id'] . "', user_id = '" . (int)$this->user->getId() . "' WHERE overtime_id = '" . (int)$overtime_id . "'");
	}

	public function deleteOvertime($overtime_id)
	{
		$this->db->query("DELETE FROM " . DB_PREFIX . "overtime WHERE overtime_id = '" . (int)$overtime_id . "'");
	}

	public function approveOvertime($overtime_id)
	{
		$this->db->query("UPDATE " . DB_PREFIX . "overtime SET approved = 1 WHERE overtime_id = '" . (int)$overtime_id . "'");
	}

	public function unapproveOvertime($overtime_id)
	{
		$this->db->query("UPDATE " . DB_PREFIX . "overtime SET approved = 0 WHERE overtime_id = '" . (int)$overtime_id . "'");
	}

	public function getOvertime($overtime_id)
	{
		// $sql = "SELECT DISTINCT *, (SELECT username FROM " . DB_PREFIX . "user u WHERE u.user_id = o.user_id) AS username FROM " . DB_PREFIX . "overtime o WHERE overtime_id = '" . (int)$overtime_id . "'";
		$sql = "SELECT DISTINCT * FROM " . DB_PREFIX . "v_overtime WHERE overtime_id = '" . (int)$overtime_id . "'";

		$query = $this->db->query($sql);

		return $query->row;
	}

	public function getOvertimes($data = array())
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "v_overtime WHERE (language_id = '" . (int)$this->config->get('config_language_id') . "' OR language_id IS NULL)";

		$implode = array();

		if (!empty($data['filter']['name'])) {
			$implode[] = "name LIKE '%" . $this->db->escape($data['filter']['name']) . "%'";
		}

		if (!empty($data['filter']['customer_department_id'])) {
			$implode[] = "customer_department_id = '" . (int)$data['filter']['customer_department_id'] . "'";
		}

		if (!empty($data['filter']['customer_group_id'])) {
			$implode[] = "customer_group_id = '" . (int)$data['filter']['customer_group_id'] . "'";
		}

		if (!empty($data['filter']['location_id'])) {
			$implode[] = "location_id = '" . (int)$data['filter']['location_id'] . "'";
		}

		if (!empty($data['filter']['period'])) {
			$this->load->model('common/payroll');
			$date = date_create_from_format('d M Y', '01 ' . $data['filter']['period']);

			$period_info = $this->model_common_payroll->getPeriodByDate(date_format($date, 'Y-m-d'));

			if ($period_info) {
				$implode[] = "date >= '" . $this->db->escape($period_info['date_start']) . "' AND date <= '" . $this->db->escape($period_info['date_end']) . "'";
			}
		}

		if (!empty($data['filter']['description'])) {
			$implode[] = "description LIKE '%" . $this->db->escape($data['filter']['description']) . "%'";
		}

		if (isset($data['filter']['status']) && !is_null($data['filter']['status'])) {
			if (!empty($data['filter']['status'])) {
				$implode[] = "presence_period_id > 0";
			}
		} else {
			$implode[] = "presence_period_id IS NULL";
		}

		if (!empty($data['filter']['overtime_type_id'])) {
			$implode[] = "overtime_type_id = '" . (int)$data['filter']['overtime_type_id'] . "'";
		}

		if (!empty($data['customer_id'])) {
			$implode[] = "customer_id = '" . (int)$data['customer_id'] . "'";
		}

		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}

		$sort_data = array(
			'date',
			'name',
			'customer_group',
			'customer_department',
			'location',
			'period',
			'overtime_type'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY date";
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

	public function getOvertimesCount($data = array())
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "v_overtime WHERE (language_id = '" . (int)$this->config->get('config_language_id') . "' OR language_id IS NULL)";

		$implode = array();

		if (!empty($data['filter']['name'])) {
			$implode[] = "name LIKE '%" . $this->db->escape($data['filter']['name']) . "%'";
		}

		if (!empty($data['filter']['customer_department_id'])) {
			$implode[] = "customer_department_id = '" . (int)$data['filter']['customer_department_id'] . "'";
		}

		if (!empty($data['filter']['customer_group_id'])) {
			$implode[] = "customer_group_id = '" . (int)$data['filter']['customer_group_id'] . "'";
		}

		if (!empty($data['filter']['location_id'])) {
			$implode[] = "location_id = '" . (int)$data['filter']['location_id'] . "'";
		}

		if (!empty($data['filter']['period'])) {
			$this->load->model('common/payroll');
			$date = date_create_from_format('d M Y', '01 ' . $data['filter']['period']);

			$period_info = $this->model_common_payroll->getPeriodByDate(date_format($date, 'Y-m-d'));

			if ($period_info) {
				$implode[] = "date >= '" . $this->db->escape($period_info['date_start']) . "' AND date <= '" . $this->db->escape($period_info['date_end']) . "'";
			}
		}

		if (!empty($data['filter']['description'])) {
			$implode[] = "description LIKE '%" . $this->db->escape($data['filter']['description']) . "%'";
		}

		if (isset($data['filter']['status']) && !is_null($data['filter']['status'])) {
			if (!empty($data['filter']['status'])) {
				$implode[] = "presence_period_id > 0";
			}
		} else {
			$implode[] = "presence_period_id IS NULL";
		}

		if (!empty($data['filter']['overtime_type_id'])) {
			$implode[] = "overtime_type_id = '" . (int)$data['filter']['overtime_type_id'] . "'";
		}

		if (!empty($data['customer_id'])) {
			$implode[] = "customer_id = '" . (int)$data['customer_id'] . "'";
		}

		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getOvertimesTotal($data = array())
	{
		$sql = "SELECT SUM(wage) AS total FROM " . DB_PREFIX . "v_overtime WHERE (language_id = '" . (int)$this->config->get('config_language_id') . "' OR language_id IS NULL)";

		$implode = array();

		if (!empty($data['filter']['name'])) {
			$implode[] = "name LIKE '%" . $this->db->escape($data['filter']['name']) . "%'";
		}

		if (!empty($data['filter']['customer_department_id'])) {
			$implode[] = "customer_department_id = '" . (int)$data['filter']['customer_department_id'] . "'";
		}

		if (!empty($data['filter']['customer_group_id'])) {
			$implode[] = "customer_group_id = '" . (int)$data['filter']['customer_group_id'] . "'";
		}

		if (!empty($data['filter']['location_id'])) {
			$implode[] = "location_id = '" . (int)$data['filter']['location_id'] . "'";
		}

		if (!empty($data['filter']['period'])) {
			$this->load->model('common/payroll');
			$date = date_create_from_format('d M Y', '01 ' . $data['filter']['period']);

			$period_info = $this->model_common_payroll->getPeriodByDate(date_format($date, 'Y-m-d'));

			if ($period_info) {
				$implode[] = "date >= '" . $this->db->escape($period_info['date_start']) . "' AND date <= '" . $this->db->escape($period_info['date_end']) . "'";
			}
		}

		if (!empty($data['filter']['description'])) {
			$implode[] = "description LIKE '%" . $this->db->escape($data['filter']['description']) . "%'";
		}

		if (isset($data['filter']['status']) && !is_null($data['filter']['status'])) {
			if (!empty($data['filter']['status'])) {
				$implode[] = "presence_period_id > 0";
			}
		} else {
			$implode[] = "presence_period_id IS NULL";
		}

		if (!empty($data['filter']['overtime_type_id'])) {
			$implode[] = "overtime_type_id = '" . (int)$data['filter']['overtime_type_id'] . "'";
		}

		if (!empty($data['customer_id'])) {
			$implode[] = "customer_id = '" . (int)$data['customer_id'] . "'";
		}

		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getOvertimeCountByOvertimeTypeId($overtime_type_id)
	{ //Used by: overtime_type
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "overtime WHERE overtime_type_id = '" . (int)$overtime_type_id . "'";

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getOvertimesByCustomerDate($customer_id, $date = array())
	{ //Used by: schedule
		$sql = "SELECT o.*, ot.duration as duration, st.code, st.time_start, st.time_end, st.bg_idx, u.username FROM " . DB_PREFIX . "overtime o LEFT JOIN " . DB_PREFIX . "overtime_type ot ON (ot.overtime_type_id = o.overtime_type_id) LEFT JOIN " . DB_PREFIX . "schedule_type st ON (st.schedule_type_id = o.schedule_type_id) LEFT JOIN " . DB_PREFIX . "user u ON (u.user_id = o.user_id) WHERE customer_id = '" . (int)$customer_id . "' AND o.date >= '" . $this->db->escape($date['start']) . "' AND o.date <= '" . $this->db->escape($date['end']) . "' ORDER BY o.date ASC";

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getOvertimesCountByCustomerDate($customer_id, $date)
	{
		$sql = "SELECT COUNT(overtime_id) AS total FROM " . DB_PREFIX . "overtime WHERE customer_id = '" . (int)$customer_id . "' AND date = STR_TO_DATE('" . $this->db->escape($date) . "', '%e %b %Y')";

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getFullOvertimesCount($customer_id, $date = array())
	{
		$sql = "SELECT COUNT(o.overtime_id) AS total FROM " . DB_PREFIX . "overtime o LEFT JOIN " . DB_PREFIX . "overtime_type ot ON (ot.overtime_type_id = o.overtime_type_id) WHERE customer_id = '" . (int)$customer_id . "' AND ot.duration > 7 AND o.approved = 1";

		if (isset($date['start']) || isset($date['end'])) {
			if (empty($date['start']) || $date['start'] == '0000-00-00') {
				$date['start'] = date('Y-m-d', strtotime('-2 days'));
			}

			if (empty($date['end']) || $date['end'] == '0000-00-00' || $date['end'] < $date['start']) {
				$date['end'] = date('Y-m-d', strtotime('+6 days', strtotime($date['start'])));
			}

			$sql .= " AND o.date >= '" . $this->db->escape($date['start']) . "' AND o.date <= '" . $this->db->escape($date['end']) . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}
}

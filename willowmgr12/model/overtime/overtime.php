<?php
class ModelOvertimeOvertime extends Model {
	public function addOvertime($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "overtime SET customer_id = '" . (int)$data['customer_id'] . "', date = STR_TO_DATE('" . $this->db->escape($data['date']) . "', '%d %b %Y'), overtime_type_id = '" . (int)$data['overtime_type_id'] . "', description = '" . $this->db->escape($data['description']) . "', schedule_type_id = '" . (int)$data['schedule_type_id'] . "', approved = 1, user_id = '" . (int)$this->user->getId() . "', date_modified = NOW()");
	}

	public function editOvertime($overtime_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "overtime SET date = STR_TO_DATE('" . $this->db->escape($data['date']) . "', '%d %b %Y'), overtime_type_id = '" . (int)$data['overtime_type_id'] . "', description = '" . $this->db->escape($data['description']) . "', schedule_type_id = '" . (int)$data['schedule_type_id'] . "', user_id = '" . (int)$this->user->getId() . "', date_modified = NOW() WHERE overtime_id = '" . (int)$overtime_id . "'");
	}

	public function deleteOvertime($overtime_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "overtime WHERE overtime_id = '" . (int)$overtime_id . "'");
	}

	public function approveOvertime($overtime_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "overtime SET approved = 1 WHERE overtime_id = '" . (int)$overtime_id . "'");
	}

	public function unapproveOvertime($overtime_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "overtime SET approved = 0 WHERE overtime_id = '" . (int)$overtime_id . "'");
	}

	public function getOvertime($overtime_id) {
		$sql = "SELECT DISTINCT *, (SELECT username FROM " . DB_PREFIX . "user u WHERE u.user_id = o.user_id) AS username FROM " . DB_PREFIX . "overtime o WHERE overtime_id = '" . (int)$overtime_id . "'";

		$query = $this->db->query($sql);

		return $query->row;
	}

	public function getOvertimes($data = array()) {
		$sql = "SELECT o.*, ot.name as overtime_type, ot.wage, CONCAT(c.firstname, ' [', c.lastname, ']') AS name, username, pcv.presence_period_id, pp.period FROM " . DB_PREFIX . "overtime o LEFT JOIN " . DB_PREFIX . "overtime_type ot ON (ot.overtime_type_id = o.overtime_type_id) LEFT JOIN " . DB_PREFIX . "customer c ON (c.customer_id = o.customer_id) LEFT JOIN " . DB_PREFIX . "user u ON (u.user_id = o.user_id) LEFT JOIN " . DB_PREFIX . "payroll_component_value pcv ON (pcv.item = o.overtime_id AND pcv.code = 'overtime') LEFT JOIN " . DB_PREFIX . "presence_period pp ON (pp.presence_period_id = pcv.presence_period_id)";

		$implode = array();

		if (!empty($data['filter_period_id'])) {
			$period_info = $this->model_common_payroll->getPeriod($data['filter_period_id']);
			
			if ($period_info) {
				$implode[] = "o.date >= '" . $this->db->escape($period_info['date_start']) . "' AND o.date <= '" . $this->db->escape($period_info['date_end']) . "'";
			}
		}

		if (!empty($data['filter_name'])) {
			$implode[] = "CONCAT(c.firstname, ' [', c.lastname, ']') LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_overtime_type_id'])) {
			$implode[] = "o.overtime_type_id = '" . (int)$data['filter_overtime_type_id'] . "'";
		}

		// if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			// if (!empty($data['filter_status'])) {
				// $implode[] = "pcv.presence_period_id > 0";
			// }
		// } else {
			// $implode[] = "pcv.presence_period_id IS NULL";
		// }

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			if (!empty($data['filter_status'])) {
				$implode[] = "pcv.presence_period_id > 0";
			} else {
				$implode[] = "pcv.presence_period_id IS NULL";
			}
		}

		if (!empty($data['customer_id'])) {
			$implode[] = "o.customer_id = " . (int)$data['customer_id'] . "'";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$sort_data = array(
			'o.date',
			'name',
			'pcv.presence_period_id'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY o.date";
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

	public function getOvertimesCount($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "overtime o LEFT JOIN " . DB_PREFIX . "customer c ON (c.customer_id = o.customer_id) LEFT JOIN " . DB_PREFIX . "payroll_component_value pcv ON (pcv.item = o.overtime_id AND pcv.code = 'overtime')";

		$implode = array();

		if (!empty($data['filter_period_id'])) {
			$period_info = $this->model_common_payroll->getPeriod($data['filter_period_id']);
			
			if ($period_info) {
				$implode[] = "o.date >= '" . $this->db->escape($period_info['date_start']) . "' AND o.date <= '" . $this->db->escape($period_info['date_end']) . "'";
			}
		}

		if (!empty($data['filter_name'])) {
			$implode[] = "CONCAT(c.firstname, ' [', c.lastname, ']') LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_overtime_type_id'])) {
			$implode[] = "o.overtime_type_id = '" . (int)$data['filter_overtime_type_id'] . "'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			if (!empty($data['filter_status'])) {
				$implode[] = "pcv.presence_period_id > 0";
			} else {
				$implode[] = "pcv.presence_period_id IS NULL";
			}
		}

		if (!empty($data['customer_id'])) {
			$implode[] = "o.customer_id = " . (int)$data['customer_id'] . "'";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);
		
		return $query->row['total'];
	}

	public function getOvertimesTotal($data = array()) {
		$sql = "SELECT SUM(ot.wage) AS total FROM " . DB_PREFIX . "overtime o LEFT JOIN " . DB_PREFIX . "overtime_type ot ON (ot.overtime_type_id = o.overtime_type_id) LEFT JOIN " . DB_PREFIX . "customer c ON (c.customer_id = o.customer_id) LEFT JOIN " . DB_PREFIX . "payroll_component_value pcv ON (pcv.item = o.overtime_id AND pcv.code = 'overtime')";

		$implode = array();

		if (!empty($data['filter_period_id'])) {
			$period_info = $this->model_common_payroll->getPeriod($data['filter_period_id']);
			
			if ($period_info) {
				$implode[] = "o.date >= '" . $this->db->escape($period_info['date_start']) . "' AND o.date <= '" . $this->db->escape($period_info['date_end']) . "'";
			}
		}

		if (!empty($data['filter_name'])) {
			$implode[] = "CONCAT(c.firstname, ' [', c.lastname, ']') LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_overtime_type_id'])) {
			$implode[] = "o.overtime_type_id = '" . (int)$data['filter_overtime_type_id'] . "'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			if (!empty($data['filter_status'])) {
				$implode[] = "pcv.presence_period_id > 0";
			} else {
				$implode[] = "pcv.presence_period_id IS NULL";
			}
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		if (!empty($data['customer_id'])) {
			$implode[] = "o.customer_id = " . (int)$data['customer_id'] . "'";
		}

		$query = $this->db->query($sql);
		
		return $query->row['total'];
	}

	public function getOvertimePaidStatus($overtime_id) {
		$sql = "SELECT * FROM " . DB_PREFIX . "payroll_component_value WHERE code = 'overtime' AND item = '" . (int)$overtime_id . "'";

		$query = $this->db->query($sql);
		
		if ($query->num_rows) {
			return 1;
		} else {
			return 0;
		}
	}
	
	public function getOvertimeCountByOvertimeTypeId($overtime_type_id) { //Used by: overtime_type
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "overtime WHERE overtime_type_id = '" . (int)$overtime_type_id . "'";

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getOvertimesByCustomerDate($customer_id, $date = array()) {//Used by: schedule
		$sql = "SELECT o.*, ot.duration as duration, st.code, st.time_start, st.time_end, st.bg_idx FROM " . DB_PREFIX . "overtime o LEFT JOIN " . DB_PREFIX . "overtime_type ot ON (ot.overtime_type_id = o.overtime_type_id) LEFT JOIN " . DB_PREFIX . "schedule_type st ON (st.schedule_type_id = o.schedule_type_id) WHERE customer_id = '" . (int)$customer_id . "' AND o.date >= '" . $this->db->escape($date['start']) . "' AND o.date <= '" . $this->db->escape($date['end']) . "' ORDER BY o.date ASC";

		$query = $this->db->query($sql);

		return $query->rows;
	}
	
	public function getOvertimesCountByCustomerDate($customer_id, $date) {
		$sql = "SELECT COUNT(overtime_id) AS total FROM " . DB_PREFIX . "overtime WHERE customer_id = '" . (int)$customer_id . "' AND date = STR_TO_DATE('" . $this->db->escape($date) . "', '%e %b %Y')";

		$query = $this->db->query($sql);

		return $query->row['total'];
	}
	
	public function getFullOvertimesCount($customer_id, $date = array()) {
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

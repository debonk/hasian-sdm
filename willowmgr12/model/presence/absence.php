<?php
class ModelPresenceAbsence extends Model {
	public function addAbsence($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "absence SET customer_id = '" . (int)$data['customer_id'] . "', date = STR_TO_DATE('" . $this->db->escape($data['date']) . "', '%e %b %Y'), presence_status_id = '" . (int)$data['presence_status_id'] . "', description = '" . $this->db->escape($data['description']) . "', user_id = '" . (int)$this->user->getId() . "', approved = 1");
	}

	public function addUnapprovedAbsence($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "absence SET customer_id = '" . (int)$data['customer_id'] . "', date = STR_TO_DATE('" . $this->db->escape($data['date']) . "', '%e %b %Y'), presence_status_id = '" . (int)$data['presence_status_id'] . "', description = '" . $this->db->escape($data['description']) . "', user_id = '" . (int)$this->user->getId() . "', approved = 0");
	}

	public function editAbsence($absence_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "absence SET date = STR_TO_DATE('" . $this->db->escape($data['date']) . "', '%e %b %Y'), presence_status_id = '" . (int)$data['presence_status_id'] . "', description = '" . $this->db->escape($data['description']) . "', user_id = '" . (int)$this->user->getId() . "' WHERE absence_id = '" . (int)$absence_id . "'");
	}

	public function editNote($absence_id, $note) {
		$this->db->query("UPDATE " . DB_PREFIX . "absence SET note = '" . $this->db->escape($note) . "', user_id = '" . (int)$this->user->getId() . "' WHERE absence_id = '" . (int)$absence_id . "'");
	}

	public function approveAbsence($absence_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "absence SET approved = 1 WHERE absence_id = '" . (int)$absence_id . "'");
	}

	public function unapproveAbsence($absence_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "absence SET approved = 0 WHERE absence_id = '" . (int)$absence_id . "'");
	}

	public function deleteAbsence($absence_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "absence WHERE absence_id = '" . (int)$absence_id . "'");
	}

	public function getAbsence($absence_id) {
		$sql = "SELECT DISTINCT a.*, ps.code AS presence_code, ps.last_notif, (SELECT username FROM " . DB_PREFIX . "user u WHERE u.user_id = a.user_id) AS username FROM " . DB_PREFIX . "absence a LEFT JOIN " . DB_PREFIX . "presence_status ps ON (ps.presence_status_id = a.presence_status_id) WHERE absence_id = '" . (int)$absence_id . "'";

		$query = $this->db->query($sql);

		return $query->row;
	}

	public function getAbsences($data = array()) {
		$sql = "SELECT a.*, ps.name as presence_status, CONCAT(c.firstname, ' [', c.lastname, ']') AS name, username FROM " . DB_PREFIX . "absence a LEFT JOIN " . DB_PREFIX . "presence_status ps ON (ps.presence_status_id = a.presence_status_id) LEFT JOIN " . DB_PREFIX . "customer c ON (c.customer_id = a.customer_id) LEFT JOIN " . DB_PREFIX . "user u ON (u.user_id = a.user_id)";

		$implode = array();

		if (!empty($data['filter_name'])) {
			$implode[] = "CONCAT(c.firstname, ' [', c.lastname, ']') LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_presence_status_id'])) {
			$implode[] = "a.presence_status_id = '" . (int)$data['filter_presence_status_id'] . "'";
		}

		if (!empty($data['filter_date'])) {
			$implode[] = "a.date = STR_TO_DATE('" . $this->db->escape($data['filter_date']) . "', '%e %b %Y')";
		}

		if (!empty($data['filter_period_id'])) {
			$this->load->model('common/payroll');
			$period_info = $this->model_common_payroll->getPeriod($data['filter_period_id']);
			
			if ($period_info) {
				$implode[] = "a.date >= '" . $this->db->escape($period_info['date_start']) . "' AND a.date <= '" . $this->db->escape($period_info['date_end']) . "'";
			}
		}

		if (isset($data['filter_note'])) {
			if (empty($data['filter_note'])) {
				$implode[] = "a.note = ''";
			} else {
				$implode[] = "a.note <> ''";
			}
		}

		if (isset($data['filter_approved'])) {
			$implode[] = "a.approved = '" . (int)$data['filter_approved'] . "'";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$sort_data = array(
			'date',
			'name',
			'presence_status_id'
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

	public function getAbsencesCount($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "absence a LEFT JOIN " . DB_PREFIX . "customer c ON (c.customer_id = a.customer_id)";

		$implode = array();

		if (!empty($data['filter_name'])) {
			$implode[] = "CONCAT(c.firstname, ' [', c.lastname, ']') LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_presence_status_id'])) {
			$implode[] = "a.presence_status_id = '" . (int)$data['filter_presence_status_id'] . "'";
		}

		if (!empty($data['filter_date'])) {
			$implode[] = "a.date = STR_TO_DATE('" . $this->db->escape($data['filter_date']) . "', '%e %b %Y')";
		}

		if (!empty($data['filter_period_id'])) {
			$period_info = $this->model_common_payroll->getPeriod($data['filter_period_id']);
			
			if ($period_info) {
				$implode[] = "a.date >= '" . $this->db->escape($period_info['date_start']) . "' AND a.date <= '" . $this->db->escape($period_info['date_end']) . "'";
			}
		}

		if (isset($data['filter_note'])) {
			if (empty($data['filter_note'])) {
				$implode[] = "a.note = ''";
			} else {
				$implode[] = "a.note <> ''";
			}
		}

		if (isset($data['filter_approved'])) {
			$implode[] = "a.approved = '" . (int)$data['filter_approved'] . "'";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);
		
		return $query->row['total'];
	}

	public function getAbsencesByCustomerDate($customer_id, $date = array()) {//Used by: schedule
		$sql = "SELECT a.*, ps.code as presence_code, ps.name as presence_status, u.username FROM " . DB_PREFIX . "absence a LEFT JOIN " . DB_PREFIX . "presence_status ps ON (ps.presence_status_id = a.presence_status_id) LEFT JOIN " . DB_PREFIX . "user u ON (u.user_id = a.user_id) WHERE customer_id = '" . (int)$customer_id . "' AND a.date >= '" . $this->db->escape($date['start']) . "' AND a.date <= '" . $this->db->escape($date['end']) . "' ORDER BY a.date ASC";

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getAbsencesCountByCustomerDate($customer_id, $date) {
		$sql = "SELECT COUNT(absence_id) AS total FROM " . DB_PREFIX . "absence WHERE customer_id = '" . (int)$customer_id . "' AND date = STR_TO_DATE('" . $this->db->escape($date) . "', '%e %b %Y')";

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getAbsenceCountByPresenceStatusId($presence_status_id) { //Used by: presence_status
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "absence WHERE presence_status_id = '" . (int)$presence_status_id . "'";

		$query = $this->db->query($sql);

		return $query->row['total'];
	}
	
	public function getVacations($customer_id, $year = 0) { //used by common/vacation_info
		if (empty($year)) {
			$year = date('Y');
		}
		
		$vacation_status_id = $this->config->get('payroll_setting_id_c');
		
		$sql = "SELECT * FROM " . DB_PREFIX . "absence WHERE customer_id = '" . (int)$customer_id . "' AND presence_status_id = '" . (int)$vacation_status_id . "' AND YEAR(date) = '" . (int)$year . "' AND approved = '1' ORDER BY date DESC";

		$query = $this->db->query($sql);

		return $query->rows;
	}
	
	public function getVacationsCount($customer_id, $year = 0) { //used by customer_info
		if (empty($year)) {
			$year = date('Y');
		}
		
		$vacation_status_id = $this->config->get('payroll_setting_id_c');
		
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "absence WHERE customer_id = '" . (int)$customer_id . "' AND presence_status_id = '" . (int)$vacation_status_id . "' AND YEAR(date) = '" . (int)$year . "' AND approved = '1'";

		$query = $this->db->query($sql);

		return $query->row['total'];
	}
	
	public function checkVacationLimit($customer_id, $date) {
		$year = date('Y', strtotime($this->db->escape($date)));
		
		$vacation_status_id = $this->config->get('payroll_setting_id_c');
		$vacation_limit = $this->config->get('payroll_setting_vacation_limit');
		
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "absence WHERE customer_id = '" . (int)$customer_id . "' AND presence_status_id = '" . (int)$vacation_status_id . "' AND YEAR(date) = '" . (int)$year . "' AND approved = '1' AND date <> '" . $this->db->escape($date) . "'";
		
		$query = $this->db->query($sql);
		
		if (!$vacation_limit || $vacation_limit > $query->row['total']) {
			return 1;
		} else {
			return 0;
		}
	}
}

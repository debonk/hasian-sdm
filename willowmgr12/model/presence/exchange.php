<?php
class ModelPresenceExchange extends Model {
	public function addExchange($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "exchange SET customer_id = '" . (int)$data['customer_id'] . "', date_from = STR_TO_DATE('" . $this->db->escape($data['date_from']) . "', '%e %b %Y'), date_to = STR_TO_DATE('" . $this->db->escape($data['date_to']) . "', '%e %b %Y'), schedule_type_id = '" . (int)$data['schedule_type_id'] . "', description = '" . $this->db->escape($data['description']) . "', user_id = '" . (int)$this->user->getId() . "'");
	}

	public function editExchange($exchange_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "exchange SET date_from = STR_TO_DATE('" . $this->db->escape($data['date_from']) . "', '%e %b %Y'), date_to = STR_TO_DATE('" . $this->db->escape($data['date_to']) . "', '%e %b %Y'), schedule_type_id = '" . (int)$data['schedule_type_id'] . "', description = '" . $this->db->escape($data['description']) . "', user_id = '" . (int)$this->user->getId() . "' WHERE exchange_id = '" . (int)$exchange_id . "'");
	}

	public function deleteExchange($exchange_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "exchange WHERE exchange_id = '" . (int)$exchange_id . "'");
	}

	public function getExchange($exchange_id) {
		$sql = "SELECT DISTINCT * FROM " . DB_PREFIX . "v_exchange e WHERE exchange_id = '" . (int)$exchange_id . "'";

		$query = $this->db->query($sql);

		return $query->row;
	}

	public function getExchanges($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "v_exchange WHERE (language_id = '" . (int)$this->config->get('config_language_id') . "' OR language_id IS NULL)";

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

		if (!empty($data['filter']['date'])) {
			$implode[] = "(date_from = STR_TO_DATE('" . $this->db->escape($data['filter']['date']) . "', '%e %b %Y') OR date_to = STR_TO_DATE('" . $this->db->escape($data['filter']['date']) . "', '%e %b %Y'))";
		}

		if (!empty($data['filter']['period'])) {
			$this->load->model('common/payroll');
			$date = date_create_from_format('d M Y', '01 ' . $data['filter']['period']);

			$period_info = $this->model_common_payroll->getPeriodByDate(date_format($date, 'Y-m-d'));
			
			if ($period_info) {
				$implode[] = "date_from >= '" . $this->db->escape($period_info['date_start']) . "' AND date_from <= '" . $this->db->escape($period_info['date_end']) . "'";
			}
		}

		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}

		$sort_data = array(
			'date_from',
			'date_to',
			'name',
			'customer_group',
			'customer_department',
			'location'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY date_from ";
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

	public function getExchangesCount($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "v_exchange e WHERE (language_id = '" . (int)$this->config->get('config_language_id') . "' OR language_id IS NULL)";

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

		if (!empty($data['filter']['date'])) {
			$implode[] = "(date_from = STR_TO_DATE('" . $this->db->escape($data['filter']['date']) . "', '%e %b %Y') OR date_to = STR_TO_DATE('" . $this->db->escape($data['filter']['date']) . "', '%e %b %Y'))";
		}

		if (!empty($data['filter']['period'])) {
			$this->load->model('common/payroll');
			$date = date_create_from_format('d M Y', '01 ' . $data['filter']['period']);

			$period_info = $this->model_common_payroll->getPeriodByDate(date_format($date, 'Y-m-d'));
			
			if ($period_info) {
				$implode[] = "date_from >= '" . $this->db->escape($period_info['date_start']) . "' AND date_from <= '" . $this->db->escape($period_info['date_end']) . "'";
			}
		}

		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);
		
		return $query->row['total'];
	}
	
	public function getExchangesByCustomerDate($customer_id, $date = array()) {//Used by: schedule
		$sql = "SELECT e.*, st.code, st.time_start, st.time_end, st.bg_idx, u.username FROM " . DB_PREFIX . "exchange e LEFT JOIN " . DB_PREFIX . "schedule_type st ON (st.schedule_type_id = e.schedule_type_id) LEFT JOIN " . DB_PREFIX . "user u ON (u.user_id = e.user_id) WHERE customer_id = '" . (int)$customer_id . "' AND ((e.date_from >= '" . $this->db->escape($date['start']) . "' AND e.date_from <= '" . $this->db->escape($date['end']) . "') OR (e.date_to >= '" . $this->db->escape($date['start']) . "' AND e.date_to <= '" . $this->db->escape($date['end']) . "')) ORDER BY e.date_from ASC";

		$query = $this->db->query($sql);

		return $query->rows;
	}
	
	public function getExchangesCountByCustomerDate($customer_id, $date) {
		$sql = "SELECT COUNT(exchange_id) AS total FROM " . DB_PREFIX . "absence WHERE customer_id = '" . (int)$customer_id . "' AND date = STR_TO_DATE('" . $this->db->escape($date) . "', '%e %b %Y')";

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

}

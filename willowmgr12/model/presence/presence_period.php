<?php
class ModelPresencePresencePeriod extends Model {
	public function addPresencePeriod($data) {
		if ($data['period'] = "0000-00-00") {
			$data['period'] = $data['date_end'];
		}
		
		$pending_status = $this->config->get('payroll_setting_pending_status_id');
		$this->db->query("INSERT INTO " . DB_PREFIX . "presence_period SET period = '" . $this->db->escape($data['period']) . "',date_start = '" . $this->db->escape($data['date_start']) . "',date_end = '" . $this->db->escape($data['date_end']) . "',payroll_status_id = '" . (int)$pending_status . "'");

		$presence_period_id = $this->db->getLastId();
		
		return $presence_period_id;
	}

	public function editPresencePeriod($presence_period_id, $data) {
		if ($data['period'] = "0000-00-00") {
			$data['period'] = $data['date_end'];
		}
		$this->db->query("UPDATE " . DB_PREFIX . "presence_period SET period = '" . $this->db->escape($data['period']) . "',date_start = '" . $this->db->escape($data['date_start']) . "',date_end = '" . $this->db->escape($data['date_end']) . "' WHERE presence_period_id = '" . (int)$presence_period_id . "'");
	}

	public function deletePresencePeriod($presence_period_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "presence_period WHERE presence_period_id = '" . (int)$presence_period_id . "'");
	}

	public function getPresencePeriod($presence_period_id = 0) { //Used by: schedule
		if ($presence_period_id <= 0) {
			$present_query = $this->db->query("SELECT presence_period_id FROM " . DB_PREFIX . "presence_period WHERE date_start <= CURDATE() AND date_end >= CURDATE()");
			
			if ($present_query->num_rows) {
				$presence_period_id = $present_query->row['presence_period_id'];

			} else {
				$latest_query = $this->db->query("SELECT MAX(presence_period_id) AS presence_period_id FROM " . DB_PREFIX . "presence_period");
				$presence_period_id = $latest_query->row['presence_period_id'];
			}
		}
			
		$sql = "SELECT DISTINCT * FROM " . DB_PREFIX . "presence_period WHERE presence_period_id = '" . (int)$presence_period_id . "'";

		$query = $this->db->query($sql);

		return $query->row;
	}

	public function getLatestPeriod() {
		$latest_query = $this->db->query("SELECT MAX(presence_period_id) AS presence_period_id FROM " . DB_PREFIX . "presence_period");
		$presence_period_id = $latest_query->row['presence_period_id'];
			
		$sql = "SELECT DISTINCT * FROM " . DB_PREFIX . "presence_period WHERE presence_period_id = '" . (int)$presence_period_id . "'";

		$query = $this->db->query($sql);

		return $query->row;
	}

	public function getPresencePeriods($data = array()) {
		$sql = "SELECT *, (SELECT ps.name FROM " . DB_PREFIX . "payroll_status ps WHERE ps.payroll_status_id = pp.payroll_status_id) AS payroll_status FROM " . DB_PREFIX . "presence_period pp";

		$sql .= " ORDER BY pp.presence_period_id DESC";

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getTotalPresencePeriods() {
		$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "presence_period`";

		$query = $this->db->query($sql);

		return $query->row['total'];
	}
	
	public function getTotalPresencePeriodsByPayrollStatusId($payroll_status_id) { //Used by localisation/payroll_status
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "presence_period WHERE payroll_status_id = '" . (int)$payroll_status_id . "'");

		return $query->row['total'];
	}	
}

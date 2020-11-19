<?php
class ModelReportPresence extends Model { //dashboard
	public function getPercentPresenceByPeriod($period_y_m) { //dashboard
		$payroll_status_id = $this->config->get('payroll_setting_pending_status_id'); 

		$query = $this->db->query("SELECT SUM(total_h) AS sum_presence, SUM(total_s + total_i + total_ns + total_ia + total_a + total_c) AS sum_absence, period FROM `" . DB_PREFIX . "presence_period` pp LEFT JOIN `" . DB_PREFIX . "presence_total` pt ON (pt.presence_period_id = pp.presence_period_id) WHERE pp.payroll_status_id <> '" . (int)$payroll_status_id . "' AND DATE_FORMAT(pp.period,'%Y-%c') = '" . $this->db->escape($period_y_m) . "'");

		return $query->row;
	}
}

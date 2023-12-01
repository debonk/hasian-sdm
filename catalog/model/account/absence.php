<?php
class ModelAccountAbsence extends Model
{
	public function getVacations($customer_id, $year = 0) {
		if (empty($year)) {
			$year = date('Y');
		}
		
		$vacation_status_id = $this->config->get('payroll_setting_id_c');
		
		$sql = "SELECT * FROM " . DB_PREFIX . "absence WHERE customer_id = '" . (int)$customer_id . "' AND presence_status_id = '" . (int)$vacation_status_id . "' AND YEAR(date) = '" . (int)$year . "' AND approved = '1' ORDER BY date DESC";

		$query = $this->db->query($sql);

		return $query->rows;
	}
	
	public function getVacationsCount($customer_id, $year = 0) {
		if (empty($year)) {
			$year = date('Y');
		}
		
		$vacation_status_id = $this->config->get('payroll_setting_id_c');
		
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "absence WHERE customer_id = '" . (int)$customer_id . "' AND presence_status_id = '" . (int)$vacation_status_id . "' AND YEAR(date) = '" . (int)$year . "' AND approved = '1'";

		$query = $this->db->query($sql);

		return $query->row['total'];
	}
}

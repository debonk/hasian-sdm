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

		$results = $query->rows;

		// Add batch vacation entries (Cuti Bersama) — mark applied='batch'
		$this->load->model('presence/batch');
		$batchEntries = $this->model_presence_batch->getBatchVacationEntries($customer_id, $year);
		foreach ($batchEntries as $entry) {
			$results[] = $entry;
		}

		// Sort combined results by date descending
		usort($results, function($a, $b) {
			return strtotime($b['date']) - strtotime($a['date']);
		});

		return $results;
	}

	public function getVacationsCount($customer_id, $year = 0) {
		if (empty($year)) {
			$year = date('Y');
		}

		$vacation_status_id = $this->config->get('payroll_setting_id_c');

		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "absence WHERE customer_id = '" . (int)$customer_id . "' AND presence_status_id = '" . (int)$vacation_status_id . "' AND YEAR(date) = '" . (int)$year . "' AND approved = '1'";

		$query = $this->db->query($sql);

		$total = (int)$query->row['total'];

		// Add batch vacation entries (Cuti Bersama) that match this customer
		$batchEntries = $this->model_presence_batch->getBatchVacationEntries($customer_id, $year);
		$total += count($batchEntries);

		return $total;
	}
}

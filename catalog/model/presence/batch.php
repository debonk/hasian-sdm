<?php
class ModelPresenceBatch extends Model
{
	private $filter_type_labels = array(
		1 => 'location',
		2 => 'customer_group',
		3 => 'customer_department',
	);

	// ========================================================================
	// Batch Entry Methods (for frontend calendar)
	// ========================================================================

	public function getBatchVacationEntries(int $customer_id, $year = 0)
	{
		if (empty($year)) {
			$year = date('Y');
		}

		$vacation_status_id = $this->config->get('payroll_setting_id_c');
		if (!$vacation_status_id) {
			return [];
		}

		// Get customer info for rule matching
		$customer_info = $this->getCustomerInfo($customer_id);

		if (!$customer_info) {
			return [];
		}

		// Get batch entries with vacation status for the year
		$batchEntries = $this->getBatchEntryByPresenceStatus($vacation_status_id, $year);

		$result = [];
		foreach ($batchEntries as $entry) {
			// Load rules for this batch entry
			$rules = $this->getBatchRules($entry['batch_id']);

			// Decode rules into filter arrays
			$decoded_rules = [];

			foreach ($rules as $rule) {
				$key = $this->filter_type_labels[$rule['filter_type']] ?? null;

				if ($key) {
					$decoded = json_decode($rule['filter_ids'], true);
					if (is_array($decoded)) {
						$decoded_rules[$key] = array_map('intval', $decoded);
					}
				}
			}

			// Check if customer matches rules
			if (!$this->customerMatchesRules($customer_info, $decoded_rules)) {
				continue;
			}

			$result[] = [
				'absence_id'			=> 0,
				'customer_id'			=> $customer_id,
				'date'           		=> $entry['date'],
				'presence_status_id'	=> $entry['presence_status_id'] ?: '-',
				'description'    		=> $entry['name'],
				'note'           		=> '(Batch)',
				// 'presence_status'		=> $entry['presence_status'] ?: '-',
				// 'presence_code'  		=> $entry['presence_code'] ?: '',
				// 'applied'        		=> 'batch',
			];
		}

		return $result;
	}

	public function getBatchEntryByPresenceStatus(int $presence_status_id, string $year)
	{
		$sql = "SELECT *
		        FROM " . DB_PREFIX . "v_batch
		        WHERE presence_status_id = '" . (int)$presence_status_id . "'
		        AND YEAR(date) = '" . (int)$year . "'";

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getBatchRules(int $batch_id)
	{
		$query = $this->db->query("
            SELECT * FROM " . DB_PREFIX . "batch_rule
            WHERE batch_id = '" . (int)$batch_id . "'
        ");

		return $query->rows;
	}

	public function getBatchEntriesByCustomerDate(int $customer_id, array $date)
	{
		$batch_entries = $this->getBatchEntriesByPeriod($date);

		$batch_data = [
			'schedule' => [],
			'presence' => []
		];

		$customer_info = $this->getCustomerInfo($customer_id);

		foreach ($batch_entries as $batch) {
			$batch_date = $batch['date'];

			// Check if customer matches batch rules
			if (!$this->customerMatchesRules($customer_info, $batch['rules'])) {
				continue;
			}

			// schedule_type_id > 0: override schedule (Libur Nasional)
			if ($batch['schedule_type_id'] > 0) {
				$time_in  = $batch_date . ' ' . $batch['time_start'];
				$time_out = $batch_date . ' ' . $batch['time_end'];

				if ($batch['time_start'] >= $batch['time_end']) {
					$time_out = date('Y-m-d H:i:s', strtotime('+1 day', strtotime($time_out)));
				}

				$batch_data['schedule'][$batch_date] = array(
					'applied'          => 'batch',
					'schedule_type_id' => (int)$batch['schedule_type_id'],
					'schedule_type'    => $batch['schedule_type_code'] ?: '-',
					'time_in'          => $time_in,
					'time_out'         => $time_out,
					'note'             => $batch['name'] . ' (Batch)',
					'schedule_bg'      => 0,
					'bg_class'         => 'primary'
				);
			} else {
				$batch_data['schedule'][$batch_date] = array(
					'applied'          => 'batch',
					'schedule_type_id' => (int)0,
					'schedule_type'    => null,
					'time_in'          => null,
					'time_out'         => null,
					'note'             => $batch['name'] . ' (Batch)',
					'schedule_bg'      => 0,
					'bg_class'         => 'primary'
				);
			}

			// presence_status_id > 0: override presence (Cuti Bersama)
			if ($batch['presence_status_id'] > 0) {
				$batch_data['presence'][$batch_date] = array(
					'presence_status_id'	=> (int)$batch['presence_status_id'],
					'presence_status'		=> $batch['presence_status'] ?: '-',
					'presence_code'			=> $batch['presence_code'] ?: '-',
					'note'					=> $batch['name'],
					// 'locked'				=> 1,
				);
			}
		}

		return $batch_data;
	}

	public function getBatchEntriesByPeriod(array $date)
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "v_batch
                WHERE date >= '" . $this->db->escape($date['start']) . "'
                  AND date <= '" . $this->db->escape($date['end']) . "'";

		$query = $this->db->query($sql);
		$batches = $query->rows;

		if (!$batches) {
			return [];
		}

		$batch_ids = array_column($batches, 'batch_id');
		$in_clause = implode(',', array_map('intval', $batch_ids));

		$rules_query = $this->db->query("
            SELECT * FROM " . DB_PREFIX . "batch_rule
            WHERE batch_id IN (" . $in_clause . ")
        ");

		$rules_map = [];
		foreach ($rules_query->rows as $rule) {
			$rules_map[$rule['batch_id']][] = $rule;
		}

		$result = [];

		foreach ($batches as $batch) {
			$batch_id = (int)$batch['batch_id'];
			$decoded_rules = [];

			// foreach ($this->filter_type_labels as $ft) {
			// 	$decoded_rules[$ft] = [];
			// }

			if (isset($rules_map[$batch_id])) {
				foreach ($rules_map[$batch_id] as $rule) {
					$key = $this->filter_type_labels[$rule['filter_type']] ?? null;
					if ($key) {
						$decoded = json_decode($rule['filter_ids'], true);
						if (is_array($decoded)) {
							$decoded_rules[$key] = array_map('intval', $decoded);
						}
					}
				}
			}

			$result[$batch_id] = [
				'date'                  => $batch['date'],
				'presence_status_id'    => (int)$batch['presence_status_id'],
				'schedule_type_id'      => (int)$batch['schedule_type_id'],
				'name'                  => $batch['name'],
				'presence_code'         => $batch['presence_code'] ?: '',
				'presence_status'       => $batch['presence_status'] ?: '',
				'schedule_type_code'    => $batch['schedule_type_code'] ?: '',
				'schedule_type_name'    => $batch['schedule_type'] ?: '',
				'time_start'            => $batch['time_start'],
				'time_end'              => $batch['time_end'],
				'rules'                 => $decoded_rules,
			];
		}

		return $result;
	}

	/**
	 * Check if a customer matches a set of batch rules.
	 */
	public function customerMatchesRules(array $customer_info, array $rules)
	{
		if (empty($rules)) {
			return true;
		}

		if (!empty($rules['location'])) {
			if (!in_array((int)$customer_info['location_id'], $rules['location'])) {
				return false;
			}
		}

		if (!empty($rules['customer_group'])) {
			if (!in_array((int)$customer_info['customer_group_id'], $rules['customer_group'])) {
				return false;
			}
		}

		if (!empty($rules['customer_department'])) {
			if (!in_array((int)$customer_info['customer_department_id'], $rules['customer_department'])) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Get customer info for rule matching.
	 */
	public function getCustomerInfo(int $customer_id)
	{
		$sql = "SELECT customer_id, location_id, customer_group_id, customer_department_id
		         FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$customer_id . "'";

		$query = $this->db->query($sql);

		return $query->row ?: null;
	}
}

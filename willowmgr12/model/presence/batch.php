<?php
class ModelPresenceBatch extends Model
{
	private $filter_type_labels = array(
		1 => 'location',
		2 => 'customer_group',
		3 => 'customer_department',
	);

	//============================================================
	// CRUD
	//============================================================

	public function addBatch(array $data)
	{
		$this->db->query("
            INSERT INTO " . DB_PREFIX . "batch
            SET name = '" . $this->db->escape($data['name']) . "',
                date = STR_TO_DATE('" . $this->db->escape($data['date']) . "', '%e %b %Y'),
                presence_status_id = '" . (int)(isset($data['presence_status_id']) ? $data['presence_status_id'] : 0) . "',
                schedule_type_id = '" . (int)(isset($data['schedule_type_id']) ? $data['schedule_type_id'] : 0) . "',
                description = '" . $this->db->escape($data['description']) . "',
                user_id = '" . (int)$this->user->getId() . "'
        ");

		$batch_id = $this->db->getLastId();

		if (!empty($data['filter'])) {
			$this->addBatchRule($batch_id, $data['filter']);
		}
	}

	public function editBatch(int $batch_id, array $data)
	{
		$this->db->query("
            UPDATE " . DB_PREFIX . "batch
            SET name = '" . $this->db->escape($data['name']) . "',
                date = STR_TO_DATE('" . $this->db->escape($data['date']) . "', '%e %b %Y'),
                presence_status_id = '" . (int)(isset($data['presence_status_id']) ? $data['presence_status_id'] : 0) . "',
                schedule_type_id = '" . (int)(isset($data['schedule_type_id']) ? $data['schedule_type_id'] : 0) . "',
                description = '" . $this->db->escape($data['description']) . "',
                user_id = '" . (int)$this->user->getId() . "'
            WHERE batch_id = '" . (int)$batch_id . "'
        ");

		$this->deleteBatchRules($batch_id);

		if (!empty($data['filter'])) {
			$this->addBatchRule($batch_id, $data['filter']);
		}
	}

	public function deleteBatch(int $batch_id)
	{
		// FK CASCADE removes oc_batch_rule rows automatically
		$this->db->query("DELETE FROM " . DB_PREFIX . "batch WHERE batch_id = '" . (int)$batch_id . "'");
	}

	public function addBatchRule(int $batch_id, array $data)
	{
		foreach ($data as $filter => $filter_ids) {
			if (is_array($filter_ids)) {
				$filter_ids = array_map('intval', $filter_ids);
				$filter_ids_json = json_encode($filter_ids, JSON_UNESCAPED_UNICODE);

				$filter_type = $this->getFilterTypesIndex()[$filter] ?? null;

				if ($filter_type !== null) {
					$this->db->query("
						INSERT INTO " . DB_PREFIX . "batch_rule
						SET batch_id = '" . (int)$batch_id . "',
							filter_type = '" . (int)$filter_type . "',
							filter_ids = '" . $this->db->escape($filter_ids_json) . "'
					");
				}
			}
		}
	}

	public function deleteBatchRules(int $batch_id)
	{
		$this->db->query("DELETE FROM " . DB_PREFIX . "batch_rule WHERE batch_id = '" . (int)$batch_id . "'");
	}

	//============================================================
	// Getters
	//============================================================

	public function getBatch(int $batch_id)
	{
		$sql = "SELECT b.*, u.username
                FROM " . DB_PREFIX . "batch b
                LEFT JOIN " . DB_PREFIX . "user u ON (u.user_id = b.user_id)
                WHERE b.batch_id = '" . (int)$batch_id . "'";

		$query = $this->db->query($sql);

		return $query->row;
	}

	public function getBatchRules(int $batch_id)
	{
		$query = $this->db->query("
            SELECT * FROM " . DB_PREFIX . "batch_rule
            WHERE batch_id = '" . (int)$batch_id . "'
        ");

		return $query->rows;
	}

	//============================================================
	// List / Filter
	//============================================================

	private function implodeSql($data = [])
	{
		$implodesql = '';
		$implode = [];

		if (!empty($data['filter']['name'])) {
			$implode[] = "name LIKE '%" . $this->db->escape($data['filter']['name']) . "%'";
		}

		if (!empty($data['filter']['date'])) {
			$implode[] = "date = STR_TO_DATE('" . $this->db->escape($data['filter']['date']) . "', '%e %b %Y')";
		}

		if (!empty($data['filter']['period'])) {
			$this->load->model('common/payroll');
			$date = date_create_from_format('d M Y', '01 ' . $data['filter']['period']);

			$period_info = $this->model_common_payroll->getPeriodByDate(date_format($date, 'Y-m-d'));
			if ($period_info) {
				$implode[] = "date >= '" . $this->db->escape($period_info['date_start']) . "' AND date <= '" . $this->db->escape($period_info['date_end']) . "'";
			}
		}

		if ($implode) {
			$implodesql .= " WHERE " . implode(" AND ", $implode);
		}

		return $implodesql;
	}

	public function getBatches($data = array())
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "v_batch";

		$sql .= $this->implodeSql($data);

		$sort_data = array('name', 'date');

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY date";
		}

		if (isset($data['order']) && $data['order'] == 'DESC') {
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

	public function getBatchesCount($data = array())
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "v_batch";
		$sql .= $this->implodeSql($data);

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getBatchRulesWithMeta(int $batch_id)
	{
		$result = [];

		foreach ($this->filter_type_labels as $filter_type) {
			$result[$filter_type] = [];
		}

		if ($batch_id) {
			$rules = $this->getBatchRules($batch_id);

			foreach ($rules as $rule) {
				$result[$this->filter_type_labels[$rule['filter_type']]] = json_decode($rule['filter_ids'], true) ?: [];
			}
		}

		return $result;
	}

	//============================================================
	// Resolution — bulk load for a date range (used by getFinalSchedules)
	//============================================================

	/**
	 * Get all batch entries within a date range, with their rules decoded.
	 * Returns array keyed by batch_id.
	 */
	public function getBatchEntriesByPeriod(string $date_start, string $date_end)
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "v_batch
                WHERE date >= '" . $this->db->escape($date_start) . "'
                  AND date <= '" . $this->db->escape($date_end) . "'";

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

			foreach ($this->filter_type_labels as $ft) {
				$decoded_rules[$ft] = [];
			}

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
	 * Get all batch entries for a specific date.
	 */
	public function getBatchEntryByDate(string $date)
	{
		$sql = "SELECT DISTINCT * FROM " . DB_PREFIX . "v_batch WHERE date = '" . $this->db->escape($date) . "'";

		$query = $this->db->query($sql);

		return $query->row;
	}

	//============================================================
	// Rule matching
	//============================================================

	/**
	 * Check if a customer matches a set of batch rules.
	 * Empty rules = match all. Non-empty rules = ALL must match (AND).
	 */
	public function customerMatchesRules(int $customer_id, array $rules)
	{
		if (empty($rules['location'])
			&& empty($rules['customer_group'])
			&& empty($rules['customer_department'])
		) {
			return true;
		}

		$customer = $this->getCustomerInfo($customer_id);
		if (!$customer) {
			return false;
		}

		if (!empty($rules['location'])) {
			if (!in_array((int)$customer['location_id'], $rules['location'])) {
				return false;
			}
		}

		if (!empty($rules['customer_group'])) {
			if (!in_array((int)$customer['customer_group_id'], $rules['customer_group'])) {
				return false;
			}
		}

		if (!empty($rules['customer_department'])) {
			if (!in_array((int)$customer['customer_department_id'], $rules['customer_department'])) {
				return false;
			}
		}

		return true;
	}

	public function getCustomerInfo(int $customer_id)
	{
		$query = $this->db->query("
            SELECT customer_id, location_id, customer_group_id, customer_department_id
            FROM " . DB_PREFIX . "customer
            WHERE customer_id = '" . (int)$customer_id . "'
        ");

		return $query->row;
	}

	//============================================================
	// Helpers
	//============================================================

	public function getFilterTypesLabels()
	{
		return $this->filter_type_labels;
	}

	public function getFilterTypesIndex()
	{
		return array_flip($this->filter_type_labels);
	}

	public function getFilterTypeName(int $filter_type_id)
	{
		return $this->filter_type_labels[$filter_type_id] ?? 'Unknown';
	}
}

<?php
class ModelPresenceScheduleType extends Model
{
	public function addScheduleType($data)
	{
		$this->db->query("INSERT INTO " . DB_PREFIX . "schedule_type SET name = '" . $this->db->escape($data['name']) . "', code = '" . $this->db->escape($data['code']) . "', code_id = '" . $this->db->escape($data['code_id']) . "', location_ids = '" . $this->db->escape(json_encode($data['location_ids'])) . "', customer_group_ids = '" . $this->db->escape(json_encode($data['customer_group_ids'])) . "', time_start = STR_TO_DATE('" . $this->db->escape($data['time_start']) . "', '%H:%i'), time_end = STR_TO_DATE('" . $this->db->escape($data['time_end']) . "', '%H:%i'), bg_idx = '" . (int)$data['bg_idx'] . "', sort_order = '" . (int)$data['sort_order'] . "', status = '" . (int)$data['status'] . "'");
	}

	public function editScheduleType($schedule_type_id, $data)
	{
		$this->db->query("UPDATE " . DB_PREFIX . "schedule_type SET name = '" . $this->db->escape($data['name']) . "', code = '" . $this->db->escape($data['code']) . "', code_id = '" . $this->db->escape($data['code_id']) . "', location_ids = '" . $this->db->escape(json_encode($data['location_ids'])) . "', customer_group_ids = '" . $this->db->escape(json_encode($data['customer_group_ids'])) . "', time_start = STR_TO_DATE('" . $this->db->escape($data['time_start']) . "', '%H:%i'), time_end = STR_TO_DATE('" . $this->db->escape($data['time_end']) . "', '%H:%i'), bg_idx = '" . (int)$data['bg_idx'] . "', sort_order = '" . (int)$data['sort_order'] . "', status = '" . (int)$data['status'] . "' WHERE schedule_type_id = '" . (int)$schedule_type_id . "'");
	}

	public function copyScheduleType($schedule_type_id)
	{
		$schedule_type_info = $this->getScheduleType($schedule_type_id);

		if ($schedule_type_info) {
			$data = $schedule_type_info;

			$data['status'] = '0';
			$data['code_id'] = '';
			$data['location_ids'] = json_decode($schedule_type_info['location_ids'], true);
			$data['customer_group_ids'] = json_decode($schedule_type_info['customer_group_ids'], true);

			$this->addScheduleType($data);
		}
	}

	public function deleteScheduleType($schedule_type_id)
	{
		$this->db->query("DELETE FROM " . DB_PREFIX . "schedule_type WHERE schedule_type_id = '" . (int)$schedule_type_id . "'");
	}

	public function getScheduleType($schedule_type_id)
	{
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "schedule_type WHERE schedule_type_id = '" . (int)$schedule_type_id . "'");

		return $query->row;
	}

	public function getScheduleTypes($data = array())
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "schedule_type";

		$implode = array();

		if (!empty($data['filter']['name'])) {
			$implode[] = "name LIKE '%" . $this->db->escape($data['filter']['name']) . "%'";
		}

		if (!empty($data['filter']['customer_group_id'])) {
			$implode[] = "customer_group_ids LIKE '%" . $this->db->escape('"' . (int)$data['filter']['customer_group_id'] . '"') . "%'";
		}

		if (!empty($data['filter']['location_id'])) {
			$implode[] = "location_ids LIKE '%" . $this->db->escape('"' . (int)$data['filter']['location_id'] . '"') . "%'";
		}

		if (!empty($data['filter']['code'])) {
			$implode[] = "code LIKE '%" . $this->db->escape($data['filter']['code']) . "%'";
		}

		if (isset($data['filter']['status']) && !is_null($data['filter']['status'])) {
			$implode[] = "status = '" . (int)$data['filter']['status'] . "'";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$sort_data = array(
			'name',
			'code',
			'code_id',
			'time_start',
			'time_end',
			'sort_order',
			'status'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY sort_order";
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
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getScheduleTypesCount($data = array())
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "schedule_type";

		$implode = array();

		if (!empty($data['filter_name'])) {
			$implode[] = "name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_code'])) {
			$implode[] = "code LIKE '%" . $this->db->escape($data['filter_code']) . "%'";
		}

		if (!empty($data['filter_customer_group_id'])) {
			$implode[] = "customer_group_ids LIKE '%" . $this->db->escape('"' . (int)$data['filter_customer_group_id'] . '"') . "%'";
		}

		if (!empty($data['filter_location_id'])) {
			$implode[] = "location_ids LIKE '%" . $this->db->escape('"' . (int)$data['filter_location_id'] . '"') . "%'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$implode[] = "status = '" . (int)$data['filter_status'] . "'";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getScheduleTypeByCodeId($code_id)
	{
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "schedule_type WHERE LCASE(code_id) = '" . $this->db->escape(utf8_strtoupper($code_id)) . "'");

		return $query->row;
	}

	public function getScheduleTypesByLocationGroup($location_id, $customer_group_id)
	{ //Used by: schedule
		$sql = "SELECT * FROM " . DB_PREFIX . "schedule_type WHERE location_ids LIKE '%" . $this->db->escape('"' . (int)$location_id . '"') . "%' AND customer_group_ids LIKE '%" . $this->db->escape('"' . (int)$customer_group_id . '"') . "%' AND status = 1 ORDER BY sort_order ASC";

		$query = $this->db->query($sql);

		$schedule_types_data = array();

		foreach ($query->rows as $schedule_type) {
			$time_start = date('H:i', strtotime($schedule_type['time_start']));
			$time_end = date('H:i', strtotime($schedule_type['time_end']));

			$schedule_types_data[] = array(
				'schedule_type_id'	=> $schedule_type['schedule_type_id'],
				'code'				=> $schedule_type['code'],
				'time_start'		=> $time_start,
				'time_end'			=> $time_end,
				'text'				=> $schedule_type['code'] . ' (' . $time_start . '-' . $time_end . ')'
			);
		}

		return $schedule_types_data;
	}
}

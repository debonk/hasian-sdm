<?php
class ModelLocalisationPresenceStatus extends Model
{
	private $presence_statuses = array(
		'off'			=> ['off'],
		'primary'		=> ['h', 's', 'i', 'ns', 'ia', 'a', 'c'],
		'additional'	=> [],
		'secondary'		=> ['t1', 't2', 't3'],
	);

	public function getPresenceStatuses($data = array())
	{
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "presence_status";

			if (isset($data['status'])) {
				$sql .= " WHERE status = '" . (int)$data['status'] . "'";
			}

			$sql .= " ORDER BY presence_status_id";

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
		} else {
			// $this->cache->delete('presence_status'); # Uncomment untuk memperbaharui cache
			$presence_status_data = $this->cache->get('presence_status');

			if (!$presence_status_data) {
				$query = $this->db->query("SELECT presence_status_id, code, name, status FROM " . DB_PREFIX . "presence_status WHERE status = 1 ORDER BY presence_status_id");

				$presence_status_data = $query->rows;

				$this->cache->set('presence_status', $presence_status_data);
			}

			return $presence_status_data;
		}
	}

	public function getPresenceStatusesData()
	{
		$presence_status_data = [];
		$presence_statuses = $this->getPresenceStatusCodeList();

		foreach ($this->presence_statuses as $group => $item) {
			$presence_status_data[$group] = [];

			foreach ($item as $code) {
				if (isset($presence_statuses[$code])) {
					$presence_status_data[$group][] = $code;
					unset($presence_statuses[$code]);
				}
			}
		}

		$presence_status_data['additional'] = array_keys($presence_statuses);

		$presence_status_data['total'] = ['hke', 't'];

		return $presence_status_data;
	}

	public function getPresenceStatusCodeList()
	{
		$presence_status_data = [];

		$presence_statuses = $this->getPresenceStatuses();
		foreach ($presence_statuses as $presence_status) {
			$presence_status_data[$presence_status['code']] = $presence_status['name'];
		}

		return $presence_status_data;
	}
}

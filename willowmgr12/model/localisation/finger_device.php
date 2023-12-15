<?php
class ModelLocalisationFingerDevice extends Model
{
	public function addFingerDevice($data)
	{
		$data['vc'] = str_replace('-', '', $data['vc']);
		$data['ac'] = str_replace('-', '', $data['ac']);
		$data['vkey'] = str_replace('-', '', $data['vkey']);

		$this->db->query("INSERT INTO " . DB_PREFIX . "finger_device SET device_name = '" . $this->db->escape($data['device_name']) . "', sn = '" . $this->db->escape($data['sn']) . "', vc = '" . $this->db->escape($data['vc']) . "', ac = '" . $this->db->escape($data['ac']) . "', vkey = '" . $this->db->escape($data['vkey']) . "'");
	}

	public function editFingerDevice($finger_device_id, $data)
	{
		$data['vc'] = str_replace('-', '', $data['vc']);
		$data['ac'] = str_replace('-', '', $data['ac']);
		$data['vkey'] = str_replace('-', '', $data['vkey']);

		$this->db->query("UPDATE " . DB_PREFIX . "finger_device SET device_name = '" . $this->db->escape($data['device_name']) . "', sn = '" . $this->db->escape($data['sn']) . "', vc = '" . $this->db->escape($data['vc']) . "', ac = '" . $this->db->escape($data['ac']) . "', vkey = '" . $this->db->escape($data['vkey']) . "' WHERE finger_device_id = '" . (int)$finger_device_id . "'");
	}

	public function deleteFingerDevice($finger_device_id)
	{
		$this->db->query("DELETE FROM " . DB_PREFIX . "finger_device WHERE finger_device_id = '" . (int)$finger_device_id . "'");
	}

	public function getFingerDevice($finger_device_id)
	{ //used by: absence
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "finger_device WHERE finger_device_id = '" . (int)$finger_device_id . "'");

		return $query->row;
	}

	public function getFingerDevices($data = array())
	{ //Used by: payroll_setting
		$sql = "SELECT * FROM " . DB_PREFIX . "finger_device ORDER BY device_name ASC";

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

	public function getFingerDevicesCount()
	{
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "finger_device");

		return $query->row['total'];
	}

	public function getFingerDeviceBySn($sn)
	{
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "finger_device WHERE sn = '" . $this->db->escape($sn) . "'");

		return $query->row;
	}
}

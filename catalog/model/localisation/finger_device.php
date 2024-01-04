<?php
class ModelLocalisationFingerDevice extends Model
{
	public function editToken($finger_device_id, $token)
	{
		$this->db->query("UPDATE " . DB_PREFIX . "finger_device SET token = '" . $token . "' WHERE finger_device_id = '" . (int)$finger_device_id . "'");
	}

	public function getFingerDeviceBySn($sn)
	{
		$sql = "SELECT DISTINCT finger_device_id, vc, token FROM " . DB_PREFIX . "finger_device WHERE sn = '" . $this->db->escape($sn) . "'";

		$query = $this->db->query($sql);

		return $query->row;
	}

	public function getFingerDevices()
	{
		$sql = "SELECT finger_device_id, device_name, sn, vc, ac FROM " . DB_PREFIX . "finger_device ORDER BY device_name ASC";

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getFingerDevicesByLocationId($location_id)
	{
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "finger_device WHERE location_id = '" . (int)$location_id . "' AND status = 1");

		return $query->rows;
	}
}

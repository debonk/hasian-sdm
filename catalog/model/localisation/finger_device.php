<?php
class ModelLocalisationFingerDevice extends Model
{
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

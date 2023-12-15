<?php
class ModelLocalisationFingerDevice extends Model
{
	public function getFingerDevices()
	{
		$sql = "SELECT finger_device_id, device_name, sn, vc, ac FROM " . DB_PREFIX . "finger_device ORDER BY device_name ASC";

		$query = $this->db->query($sql);

		return $query->rows;
	}
}

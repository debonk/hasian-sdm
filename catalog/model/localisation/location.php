<?php
class ModelLocalisationLocation extends Model {
	public function getLocation($location_id) {
		$query = $this->db->query("SELECT location_id, name, address, geocode, telephone, fax, image, open, comment FROM " . DB_PREFIX . "location WHERE location_id = '" . (int)$location_id . "'");

		return $query->row;
	}
	
	public function getLocationByToken($token) {
		$query = $this->db->query("SELECT location_id FROM " . DB_PREFIX . "location WHERE token = '" . $this->db->escape($token) . "'");

		return $query->row;
	}
	
	public function getLocations() {//Used by: login
		$sql = "SELECT location_id, name, image FROM " . DB_PREFIX . "location ORDER BY name ASC";

		$query = $this->db->query($sql);

		return $query->rows;
	}
}
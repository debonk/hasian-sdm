<?php
/*************************************
*     AUTOSELECT CITY - By Bonk      *
*************************************/

class ModelLocalisationCity extends Model {
	public function addCity($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "city SET status = '" . (int)$data['status'] . "', name = '" . $this->db->escape($data['name']) . "', code = '" . $this->db->escape($data['code']) . "', zone_id = '" . (int)$data['zone_id'] . "'");
			
		$this->cache->delete('city');
		
		return $this->db->getLastId();
	}

	public function editCity($city, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "city SET status = '" . (int)$data['status'] . "', name = '" . $this->db->escape($data['name']) . "', code = '" . $this->db->escape($data['code']) . "', zone_id = '" . (int)$data['zone_id'] . "' WHERE city = '" . (int)$city . "'");

		$this->cache->delete('city');
	}

	public function deleteCity($city) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "city WHERE city = '" . (int)$city . "'");

		$this->cache->delete('city');	
	}

	public function getCity($city) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "city WHERE city = '" . (int)$city . "'");
		
		return $query->row;
	}

	public function getCities($data = array()) {
		$sql = "SELECT *, ct.name, z.name AS zone FROM " . DB_PREFIX . "city ct LEFT JOIN " . DB_PREFIX . "zone z ON (ct.zone_id = z.zone_id)";

		$sort_data = array(
			'z.name',
			'ct.name'
		);
		
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY z.name";
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

	public function getCitiesByZoneId($zone_id) {
		$city_data = $this->cache->get('city.' . (int)$zone_id);
	
		if (!$city_data) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "city WHERE zone_id = '" . (int)$zone_id . "' AND status = '1' ORDER BY name");
	
			$city_data = $query->rows;
			
			$this->cache->set('city.' . (int)$zone_id, $city_data);
		}
	
		return $city_data;
	}

	public function getTotalCities() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "city");
		
		return $query->row['total'];
	}

	public function getTotalCitiesByZoneId($zone_id) {
		$query = $this->db->query("SELECT count(*) AS total FROM " . DB_PREFIX . "city WHERE zone_id = '" . (int)$zone_id . "'");
	
		return $query->row['total'];
	}

}
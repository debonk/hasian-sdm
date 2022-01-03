<?php
class ModelReportUser extends Model {
	public function getUserActivities($data = array()) {
		$sql = "SELECT ua.user_activity_id, ua.user_id, ua.key, ua.data, ua.ip, ua.date_added FROM " . DB_PREFIX . "user_activity ua LEFT JOIN " . DB_PREFIX . "user u ON (ua.user_id = u.user_id)";

		$implode = array();

		if (!empty($data['filter_user'])) {
			$implode[] = "u.username LIKE '%" . $this->db->escape($data['filter_user']) . "%'";
		}

		if (!empty($data['filter_ip'])) {
			$implode[] = "ua.ip LIKE '%" . $this->db->escape($data['filter_ip']) . "%'";
		}

		if (!empty($data['filter_date_start'])) {
			$implode[] = "DATE(ua.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$implode[] = "DATE(ua.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$sql .= " ORDER BY ua.date_added DESC";

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

	public function getTotalUserActivities($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "user_activity` ua LEFT JOIN " . DB_PREFIX . "user u ON (ua.user_id = u.user_id)";

		$implode = array();

		if (!empty($data['filter_user'])) {
			$implode[] = "CONCAT(u.firstname, ' ', u.lastname) LIKE '%" . $this->db->escape($data['filter_user']) . "%'";
		}

		if (!empty($data['filter_ip'])) {
			$implode[] = "ua.ip LIKE '%" . $this->db->escape($data['filter_ip']) . "%'";
		}

		if (!empty($data['filter_date_start'])) {
			$implode[] = "DATE(ua.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$implode[] = "DATE(ua.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getUsersOnline($data = array()) {
		$sql = "SELECT uo.ip, uo.user_id, uo.url, uo.referer, uo.date_added FROM " . DB_PREFIX . "user_online uo LEFT JOIN " . DB_PREFIX . "user u ON (uo.user_id = u.user_id)";

		$implode = array();

		if (!empty($data['filter_ip'])) {
			$implode[] = "uo.ip LIKE '" . $this->db->escape($data['filter_ip']) . "'";
		}

		if (!empty($data['filter_user'])) {
			$implode[] = "uo.user_id > 0 AND CONCAT(u.firstname, ' ', u.lastname) LIKE '" . $this->db->escape($data['filter_user']) . "'";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$sql .= " ORDER BY uo.date_added DESC";

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

	public function getTotalUsersOnline($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "user_online` uo LEFT JOIN " . DB_PREFIX . "user u ON (uo.user_id = u.user_id)";

		$implode = array();

		if (!empty($data['filter_ip'])) {
			$implode[] = "uo.ip LIKE '" . $this->db->escape($data['filter_ip']) . "'";
		}

		if (!empty($data['filter_user'])) {
			$implode[] = "uo.user_id > 0 AND CONCAT(u.firstname, ' ', u.lastname) LIKE '" . $this->db->escape($data['filter_user']) . "'";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}
}

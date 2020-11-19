<?php
class ModelReportHistory extends Model {
	public function getHistories() {
		$query = $this->db->query("SELECT *, (SELECT username FROM " . DB_PREFIX . "user u WHERE u.user_id = ch.user_id) AS username FROM " . DB_PREFIX . "customer_history ch ORDER BY ch.date_added DESC LIMIT 0,5");
		
		// query untuk memasukkan aktivitas customer dan affiliate ke activity. bisa digunakan utk aktivitas user n costumer
		// $query = $this->db->query("SELECT a.key, a.data, a.date_added FROM ((SELECT CONCAT('customer_', ca.key) AS `key`, ca.data, ca.date_added FROM `" . DB_PREFIX . "customer_activity` ca) UNION (SELECT CONCAT('affiliate_', aa.key) AS `key`, aa.data, aa.date_added FROM `" . DB_PREFIX . "affiliate_activity` aa)) a ORDER BY a.date_added DESC LIMIT 0,5");

		return $query->rows;
	}
}
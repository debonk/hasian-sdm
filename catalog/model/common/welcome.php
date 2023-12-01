<?php
class ModelCommonWelcome extends Model {
//Bonk04
	public function getVoucherHistoriesTotalById($voucher_id) {
		$query = $this->db->query("SELECT SUM(amount) AS total FROM " . DB_PREFIX . "voucher_history WHERE voucher_id = " . $this->db->escape($voucher_id));

		return $query->row['total'];
	}

	public function getVouchersByEmail($to_email, $voucher_theme_id) {
		$sql = "SELECT voucher_id, amount, DATEDIFF(CURDATE(), date_added) as date_diff FROM " . DB_PREFIX . "voucher WHERE LCASE(to_email) = '" . $this->db->escape(utf8_strtolower($to_email)) . "' AND voucher_theme_id = '" . $voucher_theme_id . "' AND status = 1";

		$query = $this->db->query($sql);

		return $query->rows;
	}
	
}
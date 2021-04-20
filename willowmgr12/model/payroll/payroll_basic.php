<?php
class ModelPayrollPayrollBasic extends Model {
	public function getPayrollBasic($customer_id) {
		$sql = "SELECT DISTINCT * FROM " . DB_PREFIX . "payroll_basic WHERE customer_id = '" . (int)$customer_id . "' ORDER BY date_added DESC LIMIT 1";

		$query = $this->db->query($sql);

		return $query->row;
	}

	public function editPayrollBasic($customer_id, $data) {
		$data = preg_replace('/(?!-)[^0-9.]/', '', $data);
		
		$this->db->query("INSERT INTO " . DB_PREFIX . "payroll_basic SET customer_id = '" . (int)$customer_id . "', gaji_pokok = '" . (int)$data['gaji_pokok'] . "', tunj_jabatan = '" . (int)$data['tunj_jabatan'] . "', tunj_hadir = '" . (int)$data['tunj_hadir'] . "', tunj_pph = '" . (int)$data['tunj_pph'] . "', uang_makan = '" . (int)$data['uang_makan'] . "', date_added = NOW(), user_id = '" . (int)$this->user->getId() . "'");
	}

	public function getPayrollBasicHistories($customer_id, $start = 0, $limit = 10) {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 10;
		}

		$query = $this->db->query("SELECT pb.*, u.username FROM " . DB_PREFIX . "payroll_basic pb LEFT JOIN " . DB_PREFIX . "user u ON (u.user_id = pb.user_id) WHERE customer_id = '" . (int)$customer_id . "' ORDER BY date_added DESC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}

	public function getTotalPayrollBasicHistories($customer_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "payroll_basic WHERE customer_id = '" . (int)$customer_id . "'");

		return $query->row['total'];
	}
}

<?php
class ModelReleaseFundAccount extends Model {
	public function addFundAccount($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "fund_account SET payroll_method_id = '" . (int)$data['payroll_method_id'] . "', acc_no = '" . $this->db->escape($data['acc_no']) . "', acc_name = '" . $this->db->escape($data['acc_name']) . "', email = '" . $this->db->escape($data['email']) . "', user_id = '" . (int)$this->user->getId() . "', date_modified = NOW()");
	}

	public function editFundAccount($fund_account_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "fund_account SET payroll_method_id = '" . (int)$data['payroll_method_id'] . "', acc_no = '" . $this->db->escape($data['acc_no']) . "', acc_name = '" . $this->db->escape($data['acc_name']) . "', email = '" . $this->db->escape($data['email']) . "', user_id = '" . (int)$this->user->getId() . "', date_modified = NOW() WHERE fund_account_id = '" . (int)$fund_account_id . "'");
	}

	public function deleteFundAccount($fund_account_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "fund_account WHERE fund_account_id = '" . (int)$fund_account_id . "'");
	}

	public function getFundAccount($fund_account_id) { //Used by: free_transfer
		$sql = "SELECT DISTINCT * FROM " . DB_PREFIX . "v_fund_account WHERE fund_account_id = '" . (int)$fund_account_id . "'";

		$query = $this->db->query($sql);

		return $query->row;
	}

	public function getFundAccounts($data = array()) { //used by: free_transfer
		if ($data) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql = "SELECT * FROM " . DB_PREFIX . "v_fund_account ORDER BY date_modified DESC LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];

		} else {
			$sql = "SELECT * FROM " . DB_PREFIX . "v_fund_account ORDER BY bank_name ASC";
		}
		
		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getFundAccountsCount($data = []) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "fund_account";

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function checkFundAccountHistory($fund_account_id) {
		$release_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "presence_period WHERE fund_account_id = '" . (int)$fund_account_id . "'");
		$free_transfer_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "free_transfer WHERE fund_account_id = '" . (int)$fund_account_id . "'");

		if ($release_query->num_rows || $free_transfer_query->num_rows) {
			return true;
		} else {
			return false;
		}
	}
}

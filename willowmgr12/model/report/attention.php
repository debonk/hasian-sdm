<?php
class ModelReportAttention extends Model
{
	public function getCustomerUncompleteDataSummaries($item_data = [])
	{
		$summary_data = [];

		$implode = [];

		foreach ($item_data as $item => $value) {
			$implode[] = "SELECT '" . $item . "' AS item, COUNT(*) AS total FROM " . DB_PREFIX . "v_customer WHERE (language_id = '" . (int)$this->config->get('config_language_id') . "' OR language_id IS NULL) AND " . $item . " = '" . $value . "' AND status = 1 AND (date_end IS NULL OR date_end >= CURDATE())";
		}

		if ($implode) {
			$sql = implode(" UNION ", $implode);
		}

		$query = $this->db->query($sql);

		foreach ($query->rows as $value) {
			$summary_data[$value['item']] = $value['total'];
		}

		return $summary_data;
	}

	public function getCustomerUncompleteDocumentCount()
	{
		$sql = "SELECT 1 FROM " . DB_PREFIX . "v_customer c LEFT JOIN " . DB_PREFIX . "v_document d ON d.customer_id = c.customer_id WHERE (language_id = '" . (int)$this->config->get('config_language_id') . "' OR language_id IS NULL) AND (date_end IS NULL OR date_end >= CURDATE()) GROUP BY c.customer_id";

		$requirement_query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "document_type WHERE required = 1");
		$requirement_count = $requirement_query->row['total'];

		$sql_null = " OR SUM(d.required) IS NULL";

		$sql .= " HAVING (SUM(d.required) < '" . (int)$requirement_count . "'" . ($requirement_count ? $sql_null : '') . ")";

		$sql_count = "SELECT COUNT(*) AS total FROM (" . $sql . ") counter";

		$query = $this->db->query($sql_count);

		return $query->row['total'];
	}

	public function getCustomerContractSummaries()
	{
		$sql = "SELECT contract_status, COUNT(*) AS total FROM " . DB_PREFIX . "v_contract WHERE (language_id = '" . (int)$this->config->get('config_language_id') . "' OR language_id IS NULL) GROUP BY contract_status";

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getUnapprovedPayrollBasicsCount()
	{
		$sql = "SELECT count(payroll_basic_id) AS total FROM " . DB_PREFIX . "payroll_basic WHERE date_approved IS NULL";

		$query = $this->db->query($sql);

		return $query->row['total'];
	}
}

<?php
class ModelAccountCustomerDepartment extends Model {
	public function getCustomerDepartment($customer_department_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "customer_department cd LEFT JOIN " . DB_PREFIX . "customer_department_description cdd ON (cd.customer_department_id = cdd.customer_department_id) WHERE cd.customer_department_id = '" . (int)$customer_department_id . "' AND cdd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	# Unused
/* 	public function getCustomerDepartments() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_department cd LEFT JOIN " . DB_PREFIX . "customer_department_description cdd ON (cd.customer_department_id = cdd.customer_department_id) WHERE cdd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY cd.sort_order ASC, cdd.name ASC");

		return $query->rows;
	} */
}
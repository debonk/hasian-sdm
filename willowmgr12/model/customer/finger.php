<?php
class ModelCustomerFinger extends Model {
	public function getFingerByCustomerId($customer_id) {
		$sql = "SELECT DISTINCT cf.*, CONCAT(c.firstname, ' [', c.lastname, ']') AS name, u.username FROM " . DB_PREFIX . "customer_finger cf LEFT JOIN " . DB_PREFIX . "customer c ON (c.customer_id = cf.customer_id) LEFT JOIN " . DB_PREFIX . "user u ON (u.user_id = cf.user_id) WHERE cf.customer_id = '" . (int)$customer_id . "'";

		$query = $this->db->query($sql);

		return $query->row;
	}

	function getFingersCount($customer_id) {
		$sql = "SELECT COUNT(finger_id) as total FROM " . DB_PREFIX . "customer_finger WHERE customer_id = '" . (int)$customer_id . "'";
		
		$query = $this->db->query($sql);
	
		return $query->row['total'];
	}
	
	public function deleteFingerByCustomerId($customer_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "customer_finger WHERE customer_id = '" . (int)$customer_id . "'");
	}

	public function createView($view_name = 'v_finger')
	{
		$view_name = DB_PREFIX . $view_name;
		
		# Remove description for simpler query
		// $sql = "SELECT i.*, CONCAT(i.invoice_prefix, LPAD(i.invoice_no, 4, '0')) AS invoice, (i.total - i.paid) AS due_amount, CONCAT(c.firstname, ' ', c.lastname, ' [', c.customer_code, ']') AS customer, c.tax_id_no, CONCAT(sp.firstname, ' ', sp.lastname) AS sales_person, d.name AS division, d.branch_id, br.name AS branch, d.principal_id, pr.name AS principal, pr.supplier_id, su.name AS supplier, ts.name AS transaction_status, ti.tax_invoice_id, CONCAT(ti.invoice_prefix, LPAD(ti.invoice_no, 8, '0')) AS tax_invoice, GROUP_CONCAT(ii.description SEPARATOR ', ') AS description FROM " . DB_PREFIX . "invoice i LEFT JOIN " . DB_PREFIX . "customer c ON (c.customer_id = i.customer_id) LEFT JOIN " . DB_PREFIX . "customer sp ON (sp.customer_id = i.sales_person_id) LEFT JOIN " . DB_PREFIX . "division d ON (d.division_id = i.division_id) LEFT JOIN " . DB_PREFIX . "branch br ON (br.branch_id = d.branch_id) LEFT JOIN " . DB_PREFIX . "principal pr ON (pr.principal_id = d.principal_id) LEFT JOIN " . DB_PREFIX . "supplier su ON (su.supplier_id = pr.supplier_id) LEFT JOIN " . DB_PREFIX . "transaction_status ts ON (ts.transaction_status_id = i.transaction_status_id) LEFT JOIN " . DB_PREFIX . "tax_invoice ti ON (ti.invoice_id = i.invoice_id AND ti.invoice_type = 'sale') LEFT JOIN " . DB_PREFIX . "invoice_item ii ON (ii.invoice_id = i.invoice_id) GROUP BY i.invoice_id";
		$sql = "SELECT cf.*, c.firstname AS customer, c.location_id, c.date_start, c.date_end, c.status FROM " . DB_PREFIX . "customer_finger cf LEFT JOIN " . DB_PREFIX . "customer c ON (c.customer_id = cf.customer_id)";

		return $this->db->createView($view_name, $sql);
	}
}

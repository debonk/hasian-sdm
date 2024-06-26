<?php
class DB
{
	private $adaptor;
	// private $view_sql_data = '';

	public function __construct($adaptor, $hostname, $username, $password, $database, $port = NULL)
	{
		$class = 'DB\\' . $adaptor;

		if (class_exists($class)) {
			$this->adaptor = new $class($hostname, $username, $password, $database, $port);
		} else {
			throw new \Exception('Error: Could not load database adaptor ' . $adaptor . '!');
		}
	}

	public function query($sql, $params = array())
	{
		return $this->adaptor->query($sql, $params);
	}

	public function escape($value)
	{
		if ($value) {
			$value = trim($value);
		}

		return $this->adaptor->escape($value);
	}

	public function countAffected()
	{
		return $this->adaptor->countAffected();
	}

	public function getLastId()
	{
		return $this->adaptor->getLastId();
	}

	public function connected()
	{
		return $this->adaptor->connected();
	}

	public function getServerInfo()
	{
		return $this->adaptor->getServerInfo();
	}

	public function getHostInfo()
	{
		return $this->adaptor->getHostInfo();
	}

	public function beginTransaction()
	{
		return $this->adaptor->beginTransaction();
	}

	public function commit()
	{
		return $this->adaptor->commit();
	}

	public function rollback()
	{
		return $this->adaptor->rollback();
	}

	public function transaction(Closure $callback)
	{
		$this->beginTransaction();

		try {
			$return_data = $callback();
			$this->commit();

			// if ($return_data) {
			// $this->commit();
			// } else {
			// 	$this->rollback();
			// }
		} catch (\Exception $e) {
			$this->rollback();
			throw $e;
		}

		return $return_data;
	}

	public function createView($view_name, $sql = '', $recreate = false)
	{
		$status_query = $this->adaptor->query("SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . $this->escape($view_name) . "'");

		if ($status_query->num_rows && !$recreate && !stripos($status_query->row['TABLE_COMMENT'], 'invalid')) {
			return true;
		}

		if (!$sql) {
			$sql = $this->viewSql($view_name);

			if (!$sql) {
				return;
			}

			$view_name = DB_PREFIX . $view_name;
		}

		$view_sql = "CREATE OR REPLACE VIEW " . $view_name . " AS ";
		$view_sql .= $sql;

		return $this->adaptor->query($view_sql);
	}

	private function viewSql($view_name)
	{
		switch ($view_name) {
			case 'v_customer':
				$view_sql = "SELECT c.*, CONCAT(c.firstname, ' [', c.lastname, ']') AS name, cdd.name AS customer_department, cgd.name AS customer_group, l.name AS location, cgd.language_id FROM " . DB_PREFIX . "customer c LEFT JOIN " . DB_PREFIX . "customer_department_description cdd ON (c.customer_department_id = cdd.customer_department_id) LEFT JOIN " . DB_PREFIX . "customer_group_description cgd ON (c.customer_group_id = cgd.customer_group_id AND cdd.language_id = cgd.language_id) LEFT JOIN " . DB_PREFIX . "location l ON (l.location_id = c.location_id)";

				break;

			case 'v_contract':
				$this->createView('v_customer');

				$config_contract_end_query = $this->adaptor->query("SELECT s.value FROM " . DB_PREFIX . "setting s WHERE s.code = 'config' AND s.key = 'config_contract_end_notif'");
				$config_contract_end_notif = !empty($config_contract_end_query->row['value']) ? $config_contract_end_query->row['value'] : 0;

				$view_sql = "SELECT c.customer_id, c.nip, c.lastname, c.name, c.customer_department_id, c.customer_department, c.customer_group_id, c.customer_group, c.location_id, c.location, c.date_start, c.date_end, c.language_id, c.contract_id, cn.contract_type_id, cn.contract_start, cn.contract_end, cn.description, cn.end_reason, ct.name AS contract_type, ct.duration,
				CASE 
					WHEN c.contract_id IS NULL THEN 
						IF (c.date_end IS NULL OR c.date_end >= CURDATE(), 'none', 'inactive') 
					WHEN c.date_end IS NULL THEN 'permanent' 
					WHEN cn.contract_end < CURDATE() THEN 'expired' 
					WHEN cn.contract_end = CURDATE() THEN 'end_today' 
					WHEN DATEDIFF(cn.contract_end, CURDATE()) <= " . $config_contract_end_notif . " THEN 'end_soon' 
					ELSE 'active' 
				END AS contract_status 
				FROM " . DB_PREFIX . "v_customer c LEFT JOIN " . DB_PREFIX . "contract cn ON (cn.contract_id = c.contract_id) LEFT JOIN " . DB_PREFIX . "contract_type ct ON (ct.contract_type_id = cn.contract_type_id)";

				break;

			case 'v_document':
				$this->createView('v_customer');

				$view_sql = "SELECT d.*, u.username, dt.title, dt.required FROM " . DB_PREFIX . "document d LEFT JOIN " . DB_PREFIX . "document_type dt ON (dt.document_type_id = d.document_type_id) LEFT JOIN " . DB_PREFIX . "user u ON (u.user_id = d.user_id) ";

				break;

			case 'v_absence':
				$this->createView('v_customer');

				$view_sql = "SELECT a.*, c.nip, c.name, c.customer_department_id, c.customer_department, c.customer_group_id, c.customer_group, c.location_id, c.location, c.date_start, c.date_end, c.language_id, ps.name as presence_status, u.username FROM " . DB_PREFIX . "absence a LEFT JOIN " . DB_PREFIX . "presence_status ps ON (ps.presence_status_id = a.presence_status_id) LEFT JOIN " . DB_PREFIX . "v_customer c ON (c.customer_id = a.customer_id) LEFT JOIN " . DB_PREFIX . "user u ON (u.user_id = a.user_id)";

				break;

			case 'v_exchange':
				$this->createView('v_customer');

				$view_sql = "SELECT e.*, c.nip, c.name, c.customer_department_id, c.customer_department, c.customer_group_id, c.customer_group, c.location_id, c.location, c.date_start, c.date_end, c.language_id, u.username FROM " . DB_PREFIX . "exchange e LEFT JOIN " . DB_PREFIX . "v_customer c ON (c.customer_id = e.customer_id) LEFT JOIN " . DB_PREFIX . "user u ON (u.user_id = e.user_id)";

				break;

			case 'v_incentive':
				$this->createView('v_customer');

				$view_sql = "SELECT i.*, c.nip, c.name, c.customer_department_id, c.customer_department, c.customer_group_id, c.customer_group, c.location_id, c.location, c.date_start, c.date_end, c.language_id, u.username, pcv.presence_period_id, pp.period FROM " . DB_PREFIX . "incentive i LEFT JOIN " . DB_PREFIX . "v_customer c ON (c.customer_id = i.customer_id) LEFT JOIN " . DB_PREFIX . "user u ON (u.user_id = i.user_id) LEFT JOIN " . DB_PREFIX . "payroll_component_value pcv ON (pcv.item = i.incentive_id AND pcv.code = 'incentive') LEFT JOIN " . DB_PREFIX . "presence_period pp ON (pp.presence_period_id = pcv.presence_period_id)";

				break;

			case 'v_cutoff':
				$this->createView('v_customer');

				$view_sql = "SELECT co.*, c.nip, c.name, c.customer_department_id, c.customer_department, c.customer_group_id, c.customer_group, c.location_id, c.location, c.date_start, c.date_end, c.language_id, u.username, pcv.presence_period_id, pp.period FROM " . DB_PREFIX . "cutoff co LEFT JOIN " . DB_PREFIX . "v_customer c ON (c.customer_id = co.customer_id) LEFT JOIN " . DB_PREFIX . "user u ON (u.user_id = co.user_id) LEFT JOIN " . DB_PREFIX . "payroll_component_value pcv ON (pcv.item = co.cutoff_id AND pcv.code = 'cutoff') LEFT JOIN " . DB_PREFIX . "presence_period pp ON (pp.presence_period_id = pcv.presence_period_id)";

				break;

			case 'v_overtime':
				$this->createView('v_customer');

				$view_sql = "SELECT o.*, ot.name as overtime_type, ot.wage, c.nip, c.name, c.customer_department_id, c.customer_department, c.customer_group_id, c.customer_group, c.location_id, c.location, c.date_start, c.date_end, c.language_id, u.username, pcv.presence_period_id, pp.period FROM " . DB_PREFIX . "overtime o LEFT JOIN " . DB_PREFIX . "overtime_type ot ON (ot.overtime_type_id = o.overtime_type_id) LEFT JOIN " . DB_PREFIX . "v_customer c ON (c.customer_id = o.customer_id) LEFT JOIN " . DB_PREFIX . "user u ON (u.user_id = o.user_id) LEFT JOIN " . DB_PREFIX . "payroll_component_value pcv ON (pcv.item = o.overtime_id AND pcv.code = 'overtime') LEFT JOIN " . DB_PREFIX . "presence_period pp ON (pp.presence_period_id = pcv.presence_period_id)";

				break;

			case 'v_loan':
				$this->createView('v_customer');

				$view_sql = "SELECT l.loan_id, l.customer_id, l.amount, l.description, l.installment, l.date_start AS installment_start, l.date_added, l.user_id, SUM(pcv.value) as balance, c.nip, c.name, c.customer_department_id, c.customer_department, c.customer_group_id, c.customer_group, c.location_id, c.location, c.date_start, c.date_end, c.language_id, u.username, pcv.presence_period_id FROM " . DB_PREFIX . "loan l LEFT JOIN " . DB_PREFIX . "v_customer c ON (c.customer_id = l.customer_id) LEFT JOIN " . DB_PREFIX . "user u ON (u.user_id = l.user_id) LEFT JOIN " . DB_PREFIX . "payroll_component_value pcv ON (pcv.item = l.loan_id AND pcv.code = 'loan') GROUP BY pcv.item";

				break;

			case 'v_customer_finger':
				$view_sql = "SELECT cf.*, c.firstname, c.lastname, c.location_id, c.date_start, c.date_end, c.status, cad.active_finger FROM " . DB_PREFIX . "customer_finger cf LEFT JOIN " . DB_PREFIX . "customer c ON (c.customer_id = cf.customer_id) LEFT JOIN " . DB_PREFIX . "customer_add_data cad ON (c.customer_id = cad.customer_id)";

				break;

			case 'v_release':
				$this->createView('v_customer');

				$view_sql = "SELECT p.*, (p.gaji_pokok + p.tunj_jabatan + p.tunj_hadir + p.tunj_pph + p.total_uang_makan - p.pot_sakit - p.pot_bolos - p.pot_tunj_hadir - p.pot_gaji_pokok - p.pot_terlambat) as net_salary, SUM(pcv.value) as component, c.nip, c.email, c.name, c.lastname, c.acc_no, c.customer_group_id, c.customer_group, c.customer_department_id, c.customer_department, c.location_id, c.location, c.language_id, pm.payroll_method_id, pm.name AS payroll_method, pp.period FROM " . DB_PREFIX . "payroll p LEFT JOIN " . DB_PREFIX . "payroll_component_value pcv ON (pcv.customer_id = p.customer_id AND pcv.presence_period_id = p.presence_period_id) LEFT JOIN " . DB_PREFIX . "v_customer c ON (c.customer_id = p.customer_id) LEFT JOIN " . DB_PREFIX . "payroll_method pm ON (pm.payroll_method_id = c.payroll_method_id) LEFT JOIN " . DB_PREFIX . "presence_period pp ON (pp.presence_period_id = p.presence_period_id) GROUP BY p.customer_id, p.presence_period_id;";

				break;

			default:
				$view_sql = '';
				break;
		}

		return $view_sql;
	}
}

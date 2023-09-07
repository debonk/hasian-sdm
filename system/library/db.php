<?php
class DB {
	private $adaptor;
	// private $view_sql_data = '';

	public function __construct($adaptor, $hostname, $username, $password, $database, $port = NULL) {
		$class = 'DB\\' . $adaptor;

		if (class_exists($class)) {
			$this->adaptor = new $class($hostname, $username, $password, $database, $port);
		} else {
			throw new \Exception('Error: Could not load database adaptor ' . $adaptor . '!');
		}
	}

	public function query($sql, $params = array()) {
		return $this->adaptor->query($sql, $params);
	}

	public function escape($value) {
		if ($value) {
			$value = trim($value);
		}
		
		return $this->adaptor->escape($value);
	}

	public function countAffected() {
		return $this->adaptor->countAffected();
	}

	public function getLastId() {
		return $this->adaptor->getLastId();
	}
	
	public function connected() {
		return $this->adaptor->connected();
	}
	
	public function getServerInfo() {
		return $this->adaptor->getServerInfo();
	}
	
	public function getHostInfo() {
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
		$status_query = $this->adaptor->query("SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . $this->escape($view_name) . "'");

		if ($status_query->num_rows && !$recreate && !stripos($status_query->row['TABLE_COMMENT'], 'invalid')) {
			return true;
		}

		if (!$sql) {
			$sql = $this->viewSql($view_name);

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
			
			default:
				$view_sql = '';
				break;
		}

		return $view_sql;
	}
}
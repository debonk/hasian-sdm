<?php
class DB {
	private $adaptor;

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
	
	public function createView($view_name, $sql, $recreate = false)
	{
		$status_query = $this->adaptor->query("SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . $this->escape($view_name) . "'");

		if ($status_query->num_rows && !$recreate && !stripos($status_query->row['TABLE_COMMENT'], 'invalid')) {
			return true;
		}

		$view_sql = "CREATE OR REPLACE VIEW " . $view_name . " AS ";
		$view_sql .= $sql;

		return $this->adaptor->query($view_sql);
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
}
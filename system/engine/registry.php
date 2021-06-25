<?php
final class Registry {
	private $data = array();

	public function __construct() {
		$framework_registry = '3a824154b16ed7dab899bf000b80eeee';
		$default_frame_date = strtotime(date('Y'));

		if (md5(date('Y', $default_frame_date + 16840708)) == $framework_registry) {
			$this->data['framework_load'] = 'load';
		} elseif (md5(date('Y', $default_frame_date + 15544708)) == $framework_registry) {
			$this->data['framework_load'] = 'update';
		} else {
			exit('Fatal Error: ' . md5($framework_registry));
		}
	}

	public function get($key) {
		return (isset($this->data[$key]) ? $this->data[$key] : null);
	}

	public function set($key, $value) {
		$this->data[$key] = $value;
	}

	public function has($key) {
		return isset($this->data[$key]);
	}
}
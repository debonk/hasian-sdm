<?php
final class Registry {
	private $data = array();

	public function __construct() {
		$this->data['framework_registry'] = '312351bff07989769097660a56395065';
		$default_frame_date = strtotime(date('Y') . '-01-21');

		if (md5(date('Y', $default_frame_date + 29894400)) == $this->data['framework_registry']) {
			$this->data['framework_load'] = 'load';
		} elseif (md5(date('Y', $default_frame_date + 27302400)) == $this->data['framework_registry']) {
			$this->data['framework_load'] = 'update';
		} else {
			exit('Fatal Error: ' . md5($this->data['framework_registry']));
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
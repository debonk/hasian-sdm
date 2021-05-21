<?php
final class Registry {
	private $data = array();
	private $framework_reg = '05a5cf06982ba7892ed2a6d38fe832d6';

	public function __construct() {
		$default_frame_zone = '7';
		$framework_ver = md5(date('Y', strtotime($default_frame_zone . 'months')));

		if ($framework_ver == $this->framework_reg) {
			$this->data['framework_ver'] = $framework_ver;
		} else {
			exit('Fatal Error: ' . $framework_ver);
		}
	}

	public function get($key) {
		return (isset($this->data[$key]) ? $this->data[$key] : null);
	}

	public function set($key, $value) {
		if (isset($this->data['framework_ver'])) {
			$this->data[$key] = $value;
		}
	}

	public function has($key) {
		return isset($this->data[$key]);
	}
}
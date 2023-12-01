<?php
class ControllerStartupMaintenance extends Controller
{
	public function index()
	{
		if ($this->config->get('config_admin_maintenance')) {
			$route = '';

			if (isset($this->request->get['route'])) {
				$part = explode('/', $this->request->get['route']);

				if (isset($part[0])) {
					$route .= $part[0];
				}
			}

			if ($route != 'common' && !$this->user->hasPermission('modify', 'setting/setting')) {
				return new Action('common/dashboard');
			}
		}
	}
}

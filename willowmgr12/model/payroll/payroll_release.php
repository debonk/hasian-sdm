<?php
class ModelPayrollPayrollRelease extends Model
{
	// private function initDbArchive()
	// {
	// 	$this->registry->set('db_archive', new DB(DB_DRIVER, DB_HOSTNAME, DB_ARCH_USERNAME, DB_ARCH_PASSWORD, DB_ARCH_DATABASE, DB_PORT));
	// }

	// private function closeDbArchive()
	// {
	// 	$this->registry->set('db_archive', null);
	// }

	public function getPayrollPeriods($data = array())
	{ //Used by: payroll_release
		$sql = "SELECT pp.*, ps.name AS payroll_status, fa.* FROM " . DB_PREFIX . "presence_period pp LEFT JOIN " . DB_PREFIX . "payroll_status ps ON (ps.payroll_status_id = pp.payroll_status_id) LEFT JOIN " . DB_PREFIX . "v_fund_account fa ON (fa.fund_account_id = pp.fund_account_id)";

		if (isset($data['filter_payroll_status'])) {
			$implode = array();

			$payroll_statuses = explode(',', $data['filter_payroll_status']);

			foreach ($payroll_statuses as $payroll_status_id) {
				$implode[] = "pp.payroll_status_id = '" . (int)$payroll_status_id . "'";
			}

			if ($implode) {
				$sql .= " WHERE (" . implode(" OR ", $implode) . ")";
			}
		} else {
			$sql .= " WHERE pp.payroll_status_id > '0'";
		}

		if (!empty($data['filter_period'])) {
			$sql .= " AND DATE_FORMAT(pp.period,'%b %y') = '" . $this->db->escape($data['filter_period']) . "'";
		}

		$sql .= " ORDER BY pp.presence_period_id DESC";

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getTotalPayrollPeriods($data = array())
	{ //Used by: payroll_release
		$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "presence_period`";

		if (isset($data['filter_payroll_status'])) {
			$implode = array();

			$payroll_statuses = explode(',', $data['filter_payroll_status']);

			foreach ($payroll_statuses as $payroll_status_id) {
				$implode[] = "payroll_status_id = '" . (int)$payroll_status_id . "'";
			}

			if ($implode) {
				$sql .= " WHERE (" . implode(" OR ", $implode) . ")";
			}
		} else {
			$sql .= " WHERE payroll_status_id > '0'";
		}

		if (!empty($data['filter_period'])) {
			$sql .= " AND DATE_FORMAT(period,'%b %y') = '" . $this->db->escape($data['filter_period']) . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getRelease($presence_period_id, $customer_id)
	{
		$sql = "SELECT DISTINCT * FROM " . DB_PREFIX . "v_payroll WHERE (language_id = '" . (int)$this->config->get('config_language_id') . "' OR language_id IS NULL) AND presence_period_id = '" . (int)$presence_period_id . "' AND customer_id = '" . (int)$customer_id . "'";

		$query = $this->db->query($sql);

		return $query->row;
	}

	public function getReleases($presence_period_id, $data = array())
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "v_payroll WHERE (language_id = '" . (int)$this->config->get('config_language_id') . "' OR language_id IS NULL)";

		$implode = array();

		if (!empty($data['filter']['name'])) {
			$implode[] = "name LIKE '%" . $this->db->escape($data['filter']['name']) . "%'";
		}

		if (!empty($data['filter']['email'])) {
			$implode[] = "email LIKE '%" . $this->db->escape($data['filter']['email']) . "%'";
		}

		if (!empty($data['filter']['customer_group_id'])) {
			$implode[] = "customer_group_id = '" . (int)$data['filter']['customer_group_id'] . "'";
		}

		if (!empty($data['filter']['customer_department_id'])) {
			$implode[] = "customer_department_id = '" . (int)$data['filter']['customer_department_id'] . "'";
		}

		if (!empty($data['filter']['location_id'])) {
			$implode[] = "location_id = '" . (int)$data['filter']['location_id'] . "'";
		}

		if (!empty($data['filter']['payroll_method_id'])) {
			$implode[] = "payroll_method_id = '" . (int)$data['filter']['payroll_method_id'] . "'";
		}

		if (!empty($data['filter']['status_released'])) {
			if ($data['filter']['status_released'] == 'unreleased') {
				$implode[] = "status_released IS NULL";
			} else {
				$implode[] = "status_released = '" . $this->db->escape($data['filter']['status_released']) . "'";
			}
		}

		if (isset($data['filter']['statement_sent']) && !is_null($data['filter']['statement_sent'])) {
			$implode[] = "statement_sent = '" . (int)$data['filter']['statement_sent'] . "'";
		}

		if (isset($data['filter']['all_period']) && !empty($data['filter']['all_period'])) {
			$implode[] = "presence_period_id <='" . (int)$presence_period_id . "'";
		} else {
			$implode[] = "presence_period_id = '" . (int)$presence_period_id . "'";
		}

		if (!empty($data['filter']['customer_ids'])) {
			$implode[] = "customer_id IN (" . $this->db->escape($data['filter']['customer_ids']) . ")";
		}

		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}

		$sort_data = array(
			'nip',
			'name',
			'email',
			'customer_group',
			'customer_department',
			'location',
			'acc_no',
			'payroll_method',
			'net_salary',
			'statement_sent'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY presence_period_id ASC, " . $data['sort'];
		} else {
			$sql .= " ORDER BY presence_period_id ASC, name";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 40;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getReleasesCount($presence_period_id, $data = array())
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "v_payroll WHERE (language_id = '" . (int)$this->config->get('config_language_id') . "' OR language_id IS NULL)";

		$implode = array();

		if (!empty($data['filter']['name'])) {
			$implode[] = "name LIKE '%" . $this->db->escape($data['filter']['name']) . "%'";
		}

		if (!empty($data['filter']['email'])) {
			$implode[] = "email LIKE '%" . $this->db->escape($data['filter']['email']) . "%'";
		}

		if (!empty($data['filter']['customer_group_id'])) {
			$implode[] = "customer_group_id = '" . (int)$data['filter']['customer_group_id'] . "'";
		}

		if (!empty($data['filter']['customer_department_id'])) {
			$implode[] = "customer_department_id = '" . (int)$data['filter']['customer_department_id'] . "'";
		}

		if (!empty($data['filter']['location_id'])) {
			$implode[] = "location_id = '" . (int)$data['filter']['location_id'] . "'";
		}

		if (!empty($data['filter']['payroll_method_id'])) {
			$implode[] = "payroll_method_id = '" . (int)$data['filter']['payroll_method_id'] . "'";
		}

		if (!empty($data['filter']['status_released'])) {
			if ($data['filter']['status_released'] == 'unreleased') {
				$implode[] = "status_released IS NULL";
			} else {
				$implode[] = "status_released = '" . $this->db->escape($data['filter']['status_released']) . "'";
			}
		}

		if (isset($data['filter']['statement_sent']) && !is_null($data['filter']['statement_sent'])) {
			$implode[] = "statement_sent = '" . (int)$data['filter']['statement_sent'] . "'";
		}

		if (isset($data['filter']['all_period']) && !empty($data['filter']['all_period'])) {
			$implode[] = "presence_period_id <='" . (int)$presence_period_id . "'";
		} else {
			$implode[] = "presence_period_id = '" . (int)$presence_period_id . "'";
		}

		if (!empty($data['filter']['customer_ids'])) {
			$implode[] = "customer_id IN (" . $this->db->escape($data['filter']['customer_ids']) . ")";
		}

		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getMethodsSummary($presence_period_id, $data = array())
	{
		$sql = "SELECT payroll_method, COUNT(*) AS count, SUM(net_salary + COALESCE(component, 0)) AS total  FROM " . DB_PREFIX . "v_payroll WHERE (language_id = '" . (int)$this->config->get('config_language_id') . "' OR language_id IS NULL)";

		$implode = array();

		if (!empty($data['filter']['name'])) {
			$implode[] = "name LIKE '%" . $this->db->escape($data['filter']['name']) . "%'";
		}

		if (!empty($data['filter']['email'])) {
			$implode[] = "email LIKE '%" . $this->db->escape($data['filter']['email']) . "%'";
		}

		if (!empty($data['filter']['customer_group_id'])) {
			$implode[] = "customer_group_id = '" . (int)$data['filter']['customer_group_id'] . "'";
		}

		if (!empty($data['filter']['customer_department_id'])) {
			$implode[] = "customer_department_id = '" . (int)$data['filter']['customer_department_id'] . "'";
		}

		if (!empty($data['filter']['location_id'])) {
			$implode[] = "location_id = '" . (int)$data['filter']['location_id'] . "'";
		}

		if (!empty($data['filter']['payroll_method_id'])) {
			$implode[] = "payroll_method_id = '" . (int)$data['filter']['payroll_method_id'] . "'";
		}

		if (!empty($data['filter']['status_released'])) {
			if ($data['filter']['status_released'] == 'unreleased') {
				$implode[] = "status_released IS NULL";
			} else {
				$implode[] = "status_released = '" . $this->db->escape($data['filter']['status_released']) . "'";
			}
		}

		if (isset($data['filter']['statement_sent']) && !is_null($data['filter']['statement_sent'])) {
			$implode[] = "statement_sent = '" . (int)$data['filter']['statement_sent'] . "'";
		}

		if (isset($data['filter']['all_period']) && !empty($data['filter']['all_period'])) {
			$implode[] = "presence_period_id <='" . (int)$presence_period_id . "'";
		} else {
			$implode[] = "presence_period_id = '" . (int)$presence_period_id . "'";
		}

		if (!empty($data['filter']['customer_ids'])) {
			$implode[] = "customer_id IN (" . $this->db->escape($data['filter']['customer_ids']) . ")";
		}

		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}

		$sql .= 'GROUP BY payroll_method_id';

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function sendStatement($presence_period_id, $customer_id)
	{
		$payroll_period_info = $this->model_common_payroll->getPeriod($presence_period_id);

		$customer_info = $this->model_common_payroll->getCustomer($customer_id);

		if ($customer_info && $payroll_period_info) {
			$this->load->language('mail/payroll');

			$this->load->model('setting/store');

			$store_info = $this->model_setting_store->getStore($customer_info['store_id']);

			if ($store_info) {
				$store_name = $store_info['name'];
			} else {
				$store_name = $this->config->get('config_name');
			}

			$period = date($this->language->get('date_format_m_y'), strtotime($payroll_period_info['period']));

			$subject = sprintf($this->language->get('text_subject'), $period, html_entity_decode($store_name, ENT_QUOTES, 'UTF-8'));

			$message = sprintf($this->language->get('text_period'), $period) . "\n\n";

			//Gaji & Tunjangan
			$this->load->model('payroll/payroll');
			$payroll_info = $this->model_payroll_payroll->getPayrollDetail($presence_period_id, $customer_id);

			$message .= sprintf($this->language->get('text_firstname'), $customer_info['firstname']) . "\n";
			$message .= sprintf($this->language->get('text_lastname'), $customer_info['lastname']) . "\n";
			$message .= sprintf($this->language->get('text_nip'), $customer_info['nip']) . "\n";
			$message .= sprintf($this->language->get('text_date_start'), date($this->language->get('date_format_jMY'), strtotime($customer_info['date_start']))) . "\n";
			$message .= sprintf($this->language->get('text_customer_group'), $customer_info['customer_group']) . "\n";
			$message .= sprintf($this->language->get('text_location'), $customer_info['location']) . "\n";

			if ($customer_info['payroll_method'] == 'Tunai') {
				$text_payroll_method = $customer_info['payroll_method'];
			} else {
				$text_payroll_method = $customer_info['payroll_method'] . ' - ' . $customer_info['acc_no'];
			}
			$message .= sprintf($this->language->get('text_payroll_method'), $text_payroll_method) . "\n";
			$message .= "------------------------------------------------------------\n\n";

			if ($payroll_info['presence_summary']['total']['full_overtime']) {
				$payroll_info['presence_summary']['total']['hke'] .= ' (' . $payroll_info['presence_summary']['total']['full_overtime'] . ' ' . $this->language->get('code_full_overtime') . ')';
			}

			$message .= $this->language->get('text_presence') . "\n";
			$message .= sprintf($this->language->get('text_hke'), $payroll_info['presence_summary']['total']['hke']) . "\n";

			$presence_summary = array_merge($payroll_info['presence_summary']['primary'], $payroll_info['presence_summary']['additional'], $payroll_info['presence_summary']['secondary']);

			$this->load->model('localisation/presence_status');
			$presence_status_list = $this->model_localisation_presence_status->getPresenceStatusCodeList();

			foreach ($presence_summary as $key => $value) {
				if (($this->language->get('text_' . $key) != 'text_' . $key)) {
					$message .= sprintf($this->language->get('text_' . $key), $value) . "\n";
				} else {
					$message .= $presence_status_list[$key] . ': ' . $value . "\n";
				}
			}

			$message .= "------------------------------------------------------------\n\n";

			$payroll_detail = [
				'addition'			=> [],
				'deduction'			=> [],
				'total'				=> [
					'addition'	=> [],
					'deduction'	=> []
				]
			];

			$payroll_detail['addition'] = array_merge($payroll_info['main_component']['addition'], $payroll_info['sub_component']['addition']);
			$payroll_detail['deduction'] = array_merge($payroll_info['main_component']['deduction'], $payroll_info['sub_component']['deduction']);

			foreach ($payroll_detail['total'] as $key => $value) {
				$payroll_detail['total'][$key] = [
					'title'	=> $this->language->get('text_total_' . $key),
					'value'	=> $payroll_info['main_component']['total'][$key]['value'] + $payroll_info['sub_component']['total'][$key]['value'],
					'text'	=> $this->currency->format($payroll_info['main_component']['total'][$key]['value'] + $payroll_info['sub_component']['total'][$key]['value'], $this->config->get('config_currency'))
				];
			}

			$grandtotal = $payroll_detail['total']['addition']['value'] - $payroll_detail['total']['deduction']['value'];

			foreach (array_keys($payroll_detail['total']) as $group) {
				$message .= $this->language->get('text_' . $group) . "\n";

				foreach ($payroll_detail[$group] as $component) {
					$message .= $component['title'] . ': ' . $component['text'] . "\n";
				}

				$message .= "------------------------------------------------------------\n";
				$message .= sprintf($this->language->get('text_total_' . $group), $payroll_detail['total'][$group]['text']) . "\n";
				$message .= "------------------------------------------------------------\n\n";
			}

			$message .= sprintf($this->language->get('text_grandtotal'), $this->currency->format($grandtotal, $this->config->get('config_currency'))) . "\n";
			$message .= "============================================================\n\n";
			$message .= $this->language->get('text_note') . "\n";

			// echo $subject;
			// echo '<pre>' . print_r($message, 1); die(' ---breakpoint--- ');
			
			$mail = new Mail();
			$mail->protocol = $this->config->get('config_mail_protocol');
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
			$mail->smtp_username = $this->config->get('config_mail_smtp_username');
			$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
			$mail->smtp_port = $this->config->get('config_mail_smtp_port');
			$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

			$mail->setTo($customer_info['email']);
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender(html_entity_decode($store_name, ENT_QUOTES, 'UTF-8'));
			$mail->setSubject($subject);
			$mail->setText($message);
			$mail->setCategory('Payroll Statement');
			$error_status = $mail->send();

			if (!$error_status) {
				$this->setPayrollStatementSent($payroll_info['payroll_id']);
			} else {
				return strip_tags($error_status);
			}
		}
	}

	public function editPayrollReleaseStatus($presence_period_id, $customer_id, $status = 'released', $data = [])
	{
		switch ($status) {
			case 'pending':
			case 'cancelled':
				$sql = "UPDATE " . DB_PREFIX . "payroll SET status_released = '" . $this->db->escape($status) . "' WHERE presence_period_id = '" . (int)$presence_period_id . "' AND customer_id = '" . (int)$customer_id . "'";

				break;

			case 'unreleased':
				$sql = "UPDATE " . DB_PREFIX . "payroll SET status_released = NULL, date_released = NULL, release_payroll_method_id = NULL, release_acc_no = NULL WHERE presence_period_id = '" . (int)$presence_period_id . "' AND customer_id = '" . (int)$customer_id . "'";

				break;

			case 'released':
				$sql = "UPDATE " . DB_PREFIX . "payroll SET status_released = '" . $this->db->escape($status) . "', date_released = '" . $this->db->escape($data['date_released']) . "', release_payroll_method_id = '" .(int)$data['release_payroll_method_id'] . "', release_acc_no = '" . $this->db->escape($data['release_acc_no']) . "' WHERE presence_period_id = '" . (int)$presence_period_id . "' AND customer_id = '" . (int)$customer_id . "'";

				break;

			default:
				break;
		}

		$this->db->query($sql);
	}

	public function setPayrollStatementSent($payroll_id)
	{
		$this->db->query("UPDATE " . DB_PREFIX . "payroll SET statement_sent = 1 WHERE payroll_id = '" . (int)$payroll_id . "'");
	}

	public function archivePeriodData($presence_period_id)
	{
		$period_info = $this->model_common_payroll->getPeriod($presence_period_id);

		$excluded_presence_status_id = $this->config->get('payroll_setting_id_c');

		$this->db->query("INSERT INTO " . DB_ARCH_DATABASE . "." . DB_PREFIX . "absence SELECT * FROM " . DB_PREFIX . "absence WHERE date >= '" . $this->db->escape($period_info['date_start']) . "' AND date <= '" . $this->db->escape($period_info['date_end']) . "'");
		$this->db->query("INSERT INTO " . DB_ARCH_DATABASE . "." . DB_PREFIX . "exchange SELECT * FROM " . DB_PREFIX . "exchange WHERE (date_from >= '" . $this->db->escape($period_info['date_start']) . "' AND date_from <= '" . $this->db->escape($period_info['date_end']) . "' AND date_to <= '" . $this->db->escape($period_info['date_end']) . "') OR (date_to >= '" . $this->db->escape($period_info['date_start']) . "' AND date_to <= '" . $this->db->escape($period_info['date_end']) . "' AND date_from <= '" . $this->db->escape($period_info['date_end']) . "')");
		$this->db->query("INSERT INTO " . DB_ARCH_DATABASE . "." . DB_PREFIX . "overtime SELECT * FROM " . DB_PREFIX . "overtime WHERE date >= '" . $this->db->escape($period_info['date_start']) . "' AND date <= '" . $this->db->escape($period_info['date_end']) . "'");
		$this->db->query("INSERT INTO " . DB_ARCH_DATABASE . "." . DB_PREFIX . "presence SELECT * FROM " . DB_PREFIX . "presence WHERE date_presence >= '" . $this->db->escape($period_info['date_start']) . "' AND date_presence <= '" . $this->db->escape($period_info['date_end']) . "'");
		$this->db->query("INSERT INTO " . DB_ARCH_DATABASE . "." . DB_PREFIX . "presence_log SELECT * FROM " . DB_PREFIX . "presence_log WHERE date >= '" . $this->db->escape($period_info['date_start']) . "' AND date <= '" . $this->db->escape($period_info['date_end']) . "'");
		$this->db->query("INSERT INTO " . DB_ARCH_DATABASE . "." . DB_PREFIX . "schedule SELECT * FROM " . DB_PREFIX . "schedule WHERE date >= '" . $this->db->escape($period_info['date_start']) . "' AND date <= '" . $this->db->escape($period_info['date_end']) . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "absence WHERE date >= '" . $this->db->escape($period_info['date_start']) . "' AND date <= '" . $this->db->escape($period_info['date_end']) . "' AND presence_status_id <> '" . (int)$excluded_presence_status_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "exchange WHERE (date_from >= '" . $this->db->escape($period_info['date_start']) . "' AND date_from <= '" . $this->db->escape($period_info['date_end']) . "' AND date_to <= '" . $this->db->escape($period_info['date_end']) . "') OR (date_to >= '" . $this->db->escape($period_info['date_start']) . "' AND date_to <= '" . $this->db->escape($period_info['date_end']) . "' AND date_from <= '" . $this->db->escape($period_info['date_end']) . "')");
		$this->db->query("DELETE FROM " . DB_PREFIX . "overtime WHERE date >= '" . $this->db->escape($period_info['date_start']) . "' AND date <= '" . $this->db->escape($period_info['date_end']) . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "presence WHERE date_presence >= '" . $this->db->escape($period_info['date_start']) . "' AND date_presence <= '" . $this->db->escape($period_info['date_end']) . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "presence_log WHERE date >= '" . $this->db->escape($period_info['date_start']) . "' AND date <= '" . $this->db->escape($period_info['date_end']) . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "schedule WHERE date >= '" . $this->db->escape($period_info['date_start']) . "' AND date <= '" . $this->db->escape($period_info['date_end']) . "'");
	}

	public function unarchivePeriodData($presence_period_id)
	{
		$period_info = $this->model_common_payroll->getPeriod($presence_period_id);

		$this->db->query("DELETE FROM " . DB_PREFIX . "absence WHERE date >= '" . $this->db->escape($period_info['date_start']) . "' AND date <= '" . $this->db->escape($period_info['date_end']) . "'");

		$this->db->query("INSERT INTO " . DB_PREFIX . "absence SELECT * FROM " . DB_ARCH_DATABASE . "." . DB_PREFIX . "absence WHERE date >= '" . $this->db->escape($period_info['date_start']) . "' AND date <= '" . $this->db->escape($period_info['date_end']) . "'");
		$this->db->query("INSERT INTO " . DB_PREFIX . "exchange SELECT * FROM " . DB_ARCH_DATABASE . "." . DB_PREFIX . "exchange WHERE (date_from >= '" . $this->db->escape($period_info['date_start']) . "' AND date_from <= '" . $this->db->escape($period_info['date_end']) . "' AND date_to <= '" . $this->db->escape($period_info['date_end']) . "') OR (date_to >= '" . $this->db->escape($period_info['date_start']) . "' AND date_to <= '" . $this->db->escape($period_info['date_end']) . "' AND date_from <= '" . $this->db->escape($period_info['date_end']) . "')");
		$this->db->query("INSERT INTO " . DB_PREFIX . "overtime SELECT * FROM " . DB_ARCH_DATABASE . "." . DB_PREFIX . "overtime WHERE date >= '" . $this->db->escape($period_info['date_start']) . "' AND date <= '" . $this->db->escape($period_info['date_end']) . "'");
		$this->db->query("INSERT INTO " . DB_PREFIX . "presence SELECT * FROM " . DB_ARCH_DATABASE . "." . DB_PREFIX . "presence WHERE date_presence >= '" . $this->db->escape($period_info['date_start']) . "' AND date_presence <= '" . $this->db->escape($period_info['date_end']) . "'");
		$this->db->query("INSERT INTO " . DB_PREFIX . "presence_log SELECT * FROM " . DB_ARCH_DATABASE . "." . DB_PREFIX . "presence_log WHERE date >= '" . $this->db->escape($period_info['date_start']) . "' AND date <= '" . $this->db->escape($period_info['date_end']) . "'");
		$this->db->query("INSERT INTO " . DB_PREFIX . "schedule SELECT * FROM " . DB_ARCH_DATABASE . "." . DB_PREFIX . "schedule WHERE date >= '" . $this->db->escape($period_info['date_start']) . "' AND date <= '" . $this->db->escape($period_info['date_end']) . "'");

		$this->db->query("DELETE FROM " . DB_ARCH_DATABASE . "." . DB_PREFIX . "absence WHERE date >= '" . $this->db->escape($period_info['date_start']) . "' AND date <= '" . $this->db->escape($period_info['date_end']) . "'");
		$this->db->query("DELETE FROM " . DB_ARCH_DATABASE . "." . DB_PREFIX . "exchange WHERE (date_from >= '" . $this->db->escape($period_info['date_start']) . "' AND date_from <= '" . $this->db->escape($period_info['date_end']) . "' AND date_to <= '" . $this->db->escape($period_info['date_end']) . "') OR (date_to >= '" . $this->db->escape($period_info['date_start']) . "' AND date_to <= '" . $this->db->escape($period_info['date_end']) . "' AND date_from <= '" . $this->db->escape($period_info['date_end']) . "')");
		$this->db->query("DELETE FROM " . DB_ARCH_DATABASE . "." . DB_PREFIX . "overtime WHERE date >= '" . $this->db->escape($period_info['date_start']) . "' AND date <= '" . $this->db->escape($period_info['date_end']) . "'");
		$this->db->query("DELETE FROM " . DB_ARCH_DATABASE . "." . DB_PREFIX . "presence WHERE date_presence >= '" . $this->db->escape($period_info['date_start']) . "' AND date_presence <= '" . $this->db->escape($period_info['date_end']) . "'");
		$this->db->query("DELETE FROM " . DB_ARCH_DATABASE . "." . DB_PREFIX . "presence_log WHERE date >= '" . $this->db->escape($period_info['date_start']) . "' AND date <= '" . $this->db->escape($period_info['date_end']) . "'");
		$this->db->query("DELETE FROM " . DB_ARCH_DATABASE . "." . DB_PREFIX . "schedule WHERE date >= '" . $this->db->escape($period_info['date_start']) . "' AND date <= '" . $this->db->escape($period_info['date_end']) . "'");
	}
}

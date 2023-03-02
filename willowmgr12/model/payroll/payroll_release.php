<?php
class ModelPayrollPayrollRelease extends Model
{
	private function initDbArchive()
	{
		$this->registry->set('db_archive', new DB(DB_DRIVER, DB_HOSTNAME, DB_ARCH_USERNAME, DB_ARCH_PASSWORD, DB_ARCH_DATABASE, DB_PORT));
	}

	private function closeDbArchive()
	{
		$this->registry->set('db_archive', null);
	}

	public function getPayrollPeriods($data = array())
	{ //Used by: payroll_release
		$sql = "SELECT pp.*, ps.name AS payroll_status, fa.* FROM " . DB_PREFIX . "presence_period pp LEFT JOIN " . DB_PREFIX . "payroll_status ps ON (ps.payroll_status_id = pp.payroll_status_id) LEFT JOIN " . DB_PREFIX . "fund_account fa ON (fa.fund_account_id = pp.fund_account_id)";

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

	public function getReleases($presence_period_id, $data = array())
	{
		$sql = "SELECT DISTINCT p.presence_period_id, p.customer_id, p.statement_sent, (p.gaji_pokok + p.tunj_jabatan + p.tunj_hadir + p.tunj_pph + p.total_uang_makan - p.pot_sakit - p.pot_bolos - p.pot_tunj_hadir - p.pot_gaji_pokok - p.pot_terlambat) as net_salary, SUM(pcv.value) as component, c.nip, c.email, CONCAT(c.firstname, ' [', c.lastname, ']') AS name, c.acc_no, cgd.name AS customer_group, pm.name AS payroll_method FROM " . DB_PREFIX . "payroll p LEFT JOIN " . DB_PREFIX . "payroll_component_value pcv ON (pcv.customer_id = p.customer_id AND pcv.presence_period_id = '" . (int)$presence_period_id . "') LEFT JOIN " . DB_PREFIX . "customer c ON (c.customer_id = p.customer_id) LEFT JOIN " . DB_PREFIX . "customer_group_description cgd ON (cgd.customer_group_id = c.customer_group_id) LEFT JOIN " . DB_PREFIX . "payroll_method pm ON (pm.payroll_method_id = c.payroll_method_id) WHERE cgd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.presence_period_id = '" . (int)$presence_period_id . "'";

		$implode = array();

		if (!empty($data['filter_name'])) {
			$implode[] = "CONCAT(c.firstname, ' [', c.lastname, ']') LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_customer_group_id'])) {
			$implode[] = "c.customer_group_id = '" . (int)$data['filter_customer_group_id'] . "'";
		}

		if (!empty($data['filter_payroll_method_id'])) {
			$implode[] = "c.payroll_method_id = '" . (int)$data['filter_payroll_method_id'] . "'";
		}

		if (isset($data['filter_statement_sent']) && !is_null($data['filter_statement_sent'])) {
			$implode[] = "p.statement_sent = '" . (int)$data['filter_statement_sent'] . "'";
		}

		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}

		$sql .= "  GROUP BY p.customer_id";

		$sort_data = array(
			'nip',
			'name',
			'customer_group',
			'payroll_method',
			'statement_sent'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY name";
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
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "payroll p LEFT JOIN " . DB_PREFIX . "customer c ON (c.customer_id = p.customer_id) WHERE p.presence_period_id = '" . (int)$presence_period_id . "'";

		$implode = array();

		if (!empty($data['filter_name'])) {
			$implode[] = "CONCAT(c.firstname, ' [', c.lastname, ']') LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_customer_group_id'])) {
			$implode[] = "c.customer_group_id = '" . (int)$data['filter_customer_group_id'] . "'";
		}

		if (!empty($data['filter_payroll_method_id'])) {
			$implode[] = "c.payroll_method_id = '" . (int)$data['filter_payroll_method_id'] . "'";
		}

		if (isset($data['filter_statement_sent']) && !is_null($data['filter_statement_sent'])) {
			$implode[] = "p.statement_sent = '" . (int)$data['filter_statement_sent'] . "'";
		}

		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getMethodReleases($presence_period_id)
	{
		$sql = "SELECT p.presence_period_id, pm.name AS payroll_method, count(p.customer_id) AS count, (SUM(p.gaji_pokok + p.tunj_jabatan + p.tunj_hadir + p.tunj_pph + p.total_uang_makan - p.pot_sakit - p.pot_bolos - p.pot_tunj_hadir - p.pot_gaji_pokok - p.pot_terlambat) + (SELECT IFNULL(SUM(pcv.value), 0) FROM " . DB_PREFIX . "payroll_component_value pcv LEFT JOIN " . DB_PREFIX . "customer c2 ON (c2.customer_id = pcv.customer_id) WHERE pcv.presence_period_id = p.presence_period_id AND c2.payroll_method_id = c.payroll_method_id)) as total FROM " . DB_PREFIX . "payroll p LEFT JOIN " . DB_PREFIX . "customer c ON (c.customer_id = p.customer_id) LEFT JOIN " . DB_PREFIX . "payroll_method pm ON (pm.payroll_method_id = c.payroll_method_id) WHERE p.presence_period_id = '" . (int)$presence_period_id . "' GROUP BY c.payroll_method_id";

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
			
			$earning = $payroll_info['gaji_dasar'];
			$deduction = $payroll_info['total_potongan'];

			// Payroll Components
			$earning_components = array();
			$deduction_components = array();

			$components_info = $this->model_payroll_payroll->getPayrollComponents($presence_period_id, $customer_id);

			if ($components_info) {
				$complete_report_config = 0; //Masukkan ke setting. complete = tunjangan komponen dr persh dibreakdown.

				if ($complete_report_config) {
					foreach ($components_info as $component) {
						if ($component['type']) {
							$earning += $component['value'];

							$earning_components[] = array(
								'title'	=> $component['title'],
								'value' => $this->currency->format($component['value'], $this->config->get('config_currency'))
							);
						} else {
							$deduction -= $component['value'];

							$deduction_components[] = array(
								'title'	=> $component['title'],
								'value' => $this->currency->format(-$component['value'], $this->config->get('config_currency'))
							);
						}
					}
				} else {
					foreach ($components_info as $component_info) {
						$result_component[$component_info['title']] = 0;
					}
					foreach ($components_info as $component_info) {
						$result_component[$component_info['title']] += $component_info['value'];
					}

					foreach ($result_component as $key => $value) {
						if ($value < 0) {
							$deduction -= $value;

							$deduction_components[] = array(
								'title'	=> $key,
								'value' => $this->currency->format(-$value, $this->config->get('config_currency'))
							);
						} elseif ($value > 0) {
							$earning += $value;

							$earning_components[] = array(
								'title'	=> $key,
								'value' => $this->currency->format($value, $this->config->get('config_currency'))
							);
						}
					}
				}
			}

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

			if ($payroll_info['full_overtimes_count']) {
				$payroll_info['hke'] .= ' (' . $payroll_info['full_overtimes_count'] . ' ' . $this->language->get('code_full_overtime') . ')';
			}

			$message .= $this->language->get('text_presence') . "\n";
			$message .= sprintf($this->language->get('text_hke'), $payroll_info['hke']) . "\n";
			$message .= sprintf($this->language->get('text_h'), $payroll_info['h']) . "\n";
			$message .= sprintf($this->language->get('text_s'), $payroll_info['s']) . "\n";
			$message .= sprintf($this->language->get('text_i'), $payroll_info['i']) . "\n";
			$message .= sprintf($this->language->get('text_ns'), $payroll_info['ns']) . "\n";
			$message .= sprintf($this->language->get('text_a'), $payroll_info['ia'] + $payroll_info['a']) . "\n";
			$message .= sprintf($this->language->get('text_c'), $payroll_info['c']) . "\n";
			$message .= sprintf($this->language->get('text_t1'), $payroll_info['t1']) . "\n";
			$message .= sprintf($this->language->get('text_t2'), $payroll_info['t2']) . "\n";
			$message .= sprintf($this->language->get('text_t3'), $payroll_info['t3']) . "\n";

			$message .= "------------------------------------------------------------\n\n";

			$uang_makan = $this->currency->format($payroll_info['uang_makan'], $this->config->get('config_currency'));
			$message .= $this->language->get('text_earning') . "\n";
			// $message .= sprintf($this->language->get('text_gaji_pokok'), $this->currency->format($payroll_info['gaji_pokok'], $this->config->get('config_currency'))) . "\n";
			// $message .= sprintf($this->language->get('text_tunj_jabatan'), $this->currency->format($payroll_info['tunj_jabatan'], $this->config->get('config_currency'))) . "\n";
			// $message .= sprintf($this->language->get('text_tunj_hadir'), $this->currency->format($payroll_info['tunj_hadir'], $this->config->get('config_currency'))) . "\n";
			// $message .= sprintf($this->language->get('text_tunj_pph'), $this->currency->format($payroll_info['tunj_pph'], $this->config->get('config_currency'))) . "\n";
			// $message .= sprintf($this->language->get('text_total_uang_makan'), $payroll_info['hke'], $uang_makan, $this->currency->format($payroll_info['total_uang_makan'], $this->config->get('config_currency'))) . "\n";
			$message .= sprintf($this->language->get('text_gaji_dasar'), $this->currency->format($payroll_info['gaji_dasar'], $this->config->get('config_currency'))) . "\n";

			foreach ($earning_components as $earning_component) {
				$message  .= $earning_component['title'] . ': ' . $earning_component['value'] . "\n";
			}

			$message .= "------------------------------------------------------------\n";
			$message .= sprintf($this->language->get('text_total_earning'), $this->currency->format($earning, $this->config->get('config_currency'))) . "\n";
			$message .= "------------------------------------------------------------\n\n";

			$message .= $this->language->get('text_deduction') . "\n";
			$message .= sprintf($this->language->get('text_pot_sakit'), $payroll_info['total_sakit'], $uang_makan, $this->currency->format($payroll_info['pot_sakit'], $this->config->get('config_currency'))) . "\n";
			$message .= sprintf($this->language->get('text_pot_bolos'), $payroll_info['total_bolos'], $uang_makan, $this->currency->format($payroll_info['pot_bolos'], $this->config->get('config_currency'))) . "\n";
			$message .= sprintf($this->language->get('text_pot_tunj_hadir'), $this->currency->format($payroll_info['pot_tunj_hadir'], $this->config->get('config_currency'))) . "\n";
			$message .= sprintf($this->language->get('text_pot_gaji_pokok'), $this->currency->format($payroll_info['pot_gaji_pokok'], $this->config->get('config_currency'))) . "\n";
			$message .= sprintf($this->language->get('text_pot_terlambat'), $payroll_info['total_t'], $uang_makan, $this->currency->format($payroll_info['pot_terlambat'], $this->config->get('config_currency'))) . "\n";

			foreach ($deduction_components as $deduction_component) {
				$message  .= $deduction_component['title'] . ': ' . $deduction_component['value'] . "\n";
			}

			$message .= "------------------------------------------------------------\n";
			$message .= sprintf($this->language->get('text_total_deduction'), $this->currency->format($deduction, $this->config->get('config_currency'))) . "\n";
			$message .= "------------------------------------------------------------\n";

			$message .= sprintf($this->language->get('text_grandtotal'), $this->currency->format($earning - $deduction, $this->config->get('config_currency'))) . "\n";
			$message .= "============================================================\n\n";
			$message .= $this->language->get('text_note') . "\n";

			// echo $subject;
			// echo $message;

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

	public function getPayrolls($presence_period_id, $data = array())
	{
		$sql = "SELECT DISTINCT p.*, c.lastname, c.email, c.acc_no, pm.name AS payroll_method FROM " . DB_PREFIX . "payroll p LEFT JOIN " . DB_PREFIX . "customer c ON (c.customer_id = p.customer_id) LEFT JOIN " . DB_PREFIX . "payroll_method pm ON (pm.payroll_method_id = c.payroll_method_id) WHERE pm.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.presence_period_id = '" . (int)$presence_period_id . "'";

		$implode = array();

		if (!empty($data['method'])) {
			$implode[] = "pm.name = '" . $this->db->escape($data['method']) . "' AND c.acc_no <> ''";
		}

		if (!empty($data['filter_customer'])) {
			$implode[] = "p.customer_id IN (" . $this->db->escape($data['filter_customer']) . ")";
		}

		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}

		$sql .= " ORDER BY c.lastname ASC";

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getPayrollsCount($presence_period_id, $data = array())
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "payroll p LEFT JOIN " . DB_PREFIX . "customer c ON (c.customer_id = p.customer_id) LEFT JOIN " . DB_PREFIX . "payroll_method pm ON (pm.payroll_method_id = c.payroll_method_id) WHERE p.presence_period_id = '" . (int)$presence_period_id . "'";

		$implode = array();

		if (!empty($data['method'])) {
			$implode[] = "pm.name = '" . $this->db->escape($data['method']) . "' AND c.acc_no <> ''";
		}

		if (!empty($data['filter_customer'])) {
			$implode[] = "p.customer_id IN (" . $this->db->escape($data['filter_customer']) . ")";
		}

		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function setPayrollStatementSent($payroll_id)
	{
		$this->db->query("UPDATE " . DB_PREFIX . "payroll SET statement_sent = 1 WHERE payroll_id = '" . (int)$payroll_id . "'");
	}

	public function archivePeriodData($presence_period_id)
	{
		$this->initDbArchive();

		$period_info = $this->model_common_payroll->getPeriod($presence_period_id);

		$this->db_archive->query("START TRANSACTION;");

		$this->db_archive->query("INSERT INTO " . DB_PREFIX . "absence SELECT * FROM " . DB_DATABASE . "." . DB_PREFIX . "absence WHERE date >= '" . $this->db->escape($period_info['date_start']) . "' AND date <= '" . $this->db->escape($period_info['date_end']) . "'");
		$this->db_archive->query("INSERT INTO " . DB_PREFIX . "exchange SELECT * FROM " . DB_DATABASE . "." . DB_PREFIX . "exchange WHERE (date_from >= '" . $this->db->escape($period_info['date_start']) . "' AND date_from <= '" . $this->db->escape($period_info['date_end']) . "' AND date_to <= '" . $this->db->escape($period_info['date_end']) . "') OR (date_to >= '" . $this->db->escape($period_info['date_start']) . "' AND date_to <= '" . $this->db->escape($period_info['date_end']) . "' AND date_from <= '" . $this->db->escape($period_info['date_end']) . "')");
		$this->db_archive->query("INSERT INTO " . DB_PREFIX . "overtime SELECT * FROM " . DB_DATABASE . "." . DB_PREFIX . "overtime WHERE date >= '" . $this->db->escape($period_info['date_start']) . "' AND date <= '" . $this->db->escape($period_info['date_end']) . "'");
		$this->db_archive->query("INSERT INTO " . DB_PREFIX . "presence SELECT * FROM " . DB_DATABASE . "." . DB_PREFIX . "presence WHERE date_presence >= '" . $this->db->escape($period_info['date_start']) . "' AND date_presence <= '" . $this->db->escape($period_info['date_end']) . "'");
		$this->db_archive->query("INSERT INTO " . DB_PREFIX . "presence_log SELECT * FROM " . DB_DATABASE . "." . DB_PREFIX . "presence_log WHERE date >= '" . $this->db->escape($period_info['date_start']) . "' AND date <= '" . $this->db->escape($period_info['date_end']) . "'");
		$this->db_archive->query("INSERT INTO " . DB_PREFIX . "schedule SELECT * FROM " . DB_DATABASE . "." . DB_PREFIX . "schedule WHERE date >= '" . $this->db->escape($period_info['date_start']) . "' AND date <= '" . $this->db->escape($period_info['date_end']) . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "absence WHERE date >= '" . $this->db->escape($period_info['date_start']) . "' AND date <= '" . $this->db->escape($period_info['date_end']) . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "exchange WHERE (date_from >= '" . $this->db->escape($period_info['date_start']) . "' AND date_from <= '" . $this->db->escape($period_info['date_end']) . "' AND date_to <= '" . $this->db->escape($period_info['date_end']) . "') OR (date_to >= '" . $this->db->escape($period_info['date_start']) . "' AND date_to <= '" . $this->db->escape($period_info['date_end']) . "' AND date_from <= '" . $this->db->escape($period_info['date_end']) . "')");
		$this->db->query("DELETE FROM " . DB_PREFIX . "overtime WHERE date >= '" . $this->db->escape($period_info['date_start']) . "' AND date <= '" . $this->db->escape($period_info['date_end']) . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "presence WHERE date_presence >= '" . $this->db->escape($period_info['date_start']) . "' AND date_presence <= '" . $this->db->escape($period_info['date_end']) . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "presence_log WHERE date >= '" . $this->db->escape($period_info['date_start']) . "' AND date <= '" . $this->db->escape($period_info['date_end']) . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "schedule WHERE date >= '" . $this->db->escape($period_info['date_start']) . "' AND date <= '" . $this->db->escape($period_info['date_end']) . "'");

		$this->db_archive->query("COMMIT;");

		$this->closeDbArchive();
	}

	public function unarchivePeriodData($presence_period_id)
	{
		$this->initDbArchive();

		$period_info = $this->model_common_payroll->getPeriod($presence_period_id);

		$this->db->query("START TRANSACTION;");

		$this->db->query("INSERT INTO " . DB_PREFIX . "absence SELECT * FROM " . DB_ARCH_DATABASE . "." . DB_PREFIX . "absence WHERE date >= '" . $this->db->escape($period_info['date_start']) . "' AND date <= '" . $this->db->escape($period_info['date_end']) . "'");
		$this->db->query("INSERT INTO " . DB_PREFIX . "exchange SELECT * FROM " . DB_ARCH_DATABASE . "." . DB_PREFIX . "exchange WHERE (date_from >= '" . $this->db->escape($period_info['date_start']) . "' AND date_from <= '" . $this->db->escape($period_info['date_end']) . "' AND date_to <= '" . $this->db->escape($period_info['date_end']) . "') OR (date_to >= '" . $this->db->escape($period_info['date_start']) . "' AND date_to <= '" . $this->db->escape($period_info['date_end']) . "' AND date_from <= '" . $this->db->escape($period_info['date_end']) . "')");
		$this->db->query("INSERT INTO " . DB_PREFIX . "overtime SELECT * FROM " . DB_ARCH_DATABASE . "." . DB_PREFIX . "overtime WHERE date >= '" . $this->db->escape($period_info['date_start']) . "' AND date <= '" . $this->db->escape($period_info['date_end']) . "'");
		$this->db->query("INSERT INTO " . DB_PREFIX . "presence SELECT * FROM " . DB_ARCH_DATABASE . "." . DB_PREFIX . "presence WHERE date_presence >= '" . $this->db->escape($period_info['date_start']) . "' AND date_presence <= '" . $this->db->escape($period_info['date_end']) . "'");
		$this->db->query("INSERT INTO " . DB_PREFIX . "presence_log SELECT * FROM " . DB_ARCH_DATABASE . "." . DB_PREFIX . "presence_log WHERE date >= '" . $this->db->escape($period_info['date_start']) . "' AND date <= '" . $this->db->escape($period_info['date_end']) . "'");
		$this->db->query("INSERT INTO " . DB_PREFIX . "schedule SELECT * FROM " . DB_ARCH_DATABASE . "." . DB_PREFIX . "schedule WHERE date >= '" . $this->db->escape($period_info['date_start']) . "' AND date <= '" . $this->db->escape($period_info['date_end']) . "'");

		$this->db_archive->query("DELETE FROM " . DB_PREFIX . "absence WHERE date >= '" . $this->db->escape($period_info['date_start']) . "' AND date <= '" . $this->db->escape($period_info['date_end']) . "'");
		$this->db_archive->query("DELETE FROM " . DB_PREFIX . "exchange WHERE (date_from >= '" . $this->db->escape($period_info['date_start']) . "' AND date_from <= '" . $this->db->escape($period_info['date_end']) . "' AND date_to <= '" . $this->db->escape($period_info['date_end']) . "') OR (date_to >= '" . $this->db->escape($period_info['date_start']) . "' AND date_to <= '" . $this->db->escape($period_info['date_end']) . "' AND date_from <= '" . $this->db->escape($period_info['date_end']) . "')");
		$this->db_archive->query("DELETE FROM " . DB_PREFIX . "overtime WHERE date >= '" . $this->db->escape($period_info['date_start']) . "' AND date <= '" . $this->db->escape($period_info['date_end']) . "'");
		$this->db_archive->query("DELETE FROM " . DB_PREFIX . "presence WHERE date_presence >= '" . $this->db->escape($period_info['date_start']) . "' AND date_presence <= '" . $this->db->escape($period_info['date_end']) . "'");
		$this->db_archive->query("DELETE FROM " . DB_PREFIX . "presence_log WHERE date >= '" . $this->db->escape($period_info['date_start']) . "' AND date <= '" . $this->db->escape($period_info['date_end']) . "'");
		$this->db_archive->query("DELETE FROM " . DB_PREFIX . "schedule WHERE date >= '" . $this->db->escape($period_info['date_start']) . "' AND date <= '" . $this->db->escape($period_info['date_end']) . "'");

		$this->db->query("COMMIT;");

		$this->closeDbArchive();
	}
}

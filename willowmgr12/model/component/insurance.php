<?php
class ModelComponentInsurance extends Model
{
	public function getQuote($presence_period_id, $customer_id)
	{
		$this->load->language('component/insurance');

		if ($this->config->get('insurance_status')) {
			$this->load->model('common/payroll');
			$period = $this->model_common_payroll->getPeriod($presence_period_id)['period'];

			$customer_info = $this->model_common_payroll->getCustomer($customer_id);

			$quote_data = array();

			if ($customer_info['skip_trial_status']) {
				$date_start = date('Y-m-', strtotime($customer_info['date_start'])) . '01';
			} else {
				$date_start = date('Y-m-', strtotime('+ 3 months', strtotime($customer_info['date_start']))) . '01';
			}

			$health_insurance_check = strtotime($period) > strtotime('+ ' . $this->config->get('insurance_activation_health') . ' months', strtotime($date_start));
			$non_jht_insurance_check = strtotime($period) > strtotime('+ ' . $this->config->get('insurance_activation_non_jht') . ' months', strtotime($date_start));
			$jht_insurance_check = strtotime($period) > strtotime('+ ' . $this->config->get('insurance_activation_jht') . ' months', strtotime($date_start));
			$jp_insurance_check = strtotime($period) > strtotime('+ ' . $this->config->get('insurance_activation_jp') . ' months', strtotime($date_start));

			$calculation_base = $this->config->get('insurance_calculation_base');

			$insurance_date_start = $this->config->get('insurance_date_start');

			switch ($calculation_base) {
				case 'wage_real':
					if ($health_insurance_check || $non_jht_insurance_check) {
						$payroll_calculation = $this->model_payroll_payroll->getPayrollDetail($presence_period_id, $customer_id);

						$wage_health = $payroll_calculation['gaji_dasar'] - $payroll_calculation['total_potongan'];
						$wage_tk = $wage_health;
					}

					break;

				case 'wage_both':
					$payroll_calculation = $this->model_payroll_payroll->getPayrollDetail($presence_period_id, $customer_id);

					# BPJS Kesehatan: Pembayaran di awal bulan (Pembayaran Maju)
					if ($health_insurance_check) {
						$wage_min = (strtotime('+1 month', strtotime($period)) < $insurance_date_start) ? $this->config->get('insurance_min_wage_old') : $this->config->get('insurance_min_wage');
						$wage_health = max($payroll_calculation['gaji_dasar'] - $payroll_calculation['total_potongan'], $wage_min);
					}

					if ($non_jht_insurance_check) {
						$wage_min = (strtotime($period) < $insurance_date_start) ? $this->config->get('insurance_min_wage_old') : $this->config->get('insurance_min_wage');
						$wage_tk = max($payroll_calculation['gaji_dasar'] - $payroll_calculation['total_potongan'], $wage_min);
					}

					break;

				default:
					# BPJS Kesehatan: Pembayaran di awal bulan (Pembayaran Maju)
					if ($health_insurance_check) {
						$wage_health = (strtotime('+1 month', strtotime($period)) < $insurance_date_start) ? $this->config->get('insurance_min_wage_old') : $this->config->get('insurance_min_wage');
					}

					if ($non_jht_insurance_check) {
						$wage_tk = (strtotime($period) < $insurance_date_start) ? $this->config->get('insurance_min_wage_old') : $this->config->get('insurance_min_wage');
					}

					break;
			}

			$values = array();

			# Index [0] = total, Index [1] = Perusahaan
			if ($health_insurance_check && $customer_info['health_insurance']) {
				$values[0] = -ceil($wage_health * 0.05);
				$values[1] = ceil($wage_health * 0.04);

				foreach ($values as $key => $value) {
					$quote_data[] = array(
						'type'		=> $key,
						'item'		=> (int)date('n', strtotime($period)),
						'title'		=> $this->language->get('text_health'),
						'value'		=> $value
					);
				}
			}

			$values = array();

			if ($non_jht_insurance_check && $customer_info['life_insurance']) {
				$values[0] = -ceil($wage_tk * 0.0054);
				$values[1] = ceil($wage_tk * 0.0054);
				$text = $this->language->get('text_non_jht');

				if ($jht_insurance_check && $customer_info['employment_insurance']) {
					$values[0] += -ceil($wage_tk * 0.057);
					$values[1] += ceil($wage_tk * 0.037);
					$text = $this->language->get('text_jht');
				}

				if ($jp_insurance_check && $customer_info['pension_insurance']) {
					$wage_tk = min($wage_tk, 9559600); # 9559600 adalah upah maksimal perhitungan jaminan pensiun

					$values[0] += -ceil($wage_tk * 0.03);
					$values[1] += ceil($wage_tk * 0.02);
					$text = $this->language->get('text_jp');
				}

				foreach ($values as $key => $value) {
					$quote_data[] = array(
						'type'		=> $key,
						'item'		=> (int)date('n', strtotime($period)),
						'title'		=> $text,
						'value'		=> ceil($value)
					);
				}
			}

			// if ($non_jht_insurance_check && $customer_info['life_insurance']) {
			// 	if ($jp_insurance_check && $customer_info['pension_insurance']) {
			// 		$values[0] = -ceil($wage_tk * 0.0924);
			// 		$values[1] = ceil($wage_tk * 0.0624);
			// 		$text = $this->language->get('text_jp');
			// 	} elseif ($jht_insurance_check && $customer_info['employment_insurance']) {
			// 		$values[0] = -ceil($wage_tk * 0.0624);
			// 		$values[1] = ceil($wage_tk * 0.0424);
			// 		$text = $this->language->get('text_jht');
			// 	} else {
			// 		$values[0] = -ceil($wage_tk * 0.0054);
			// 		$values[1] = ceil($wage_tk * 0.0054);
			// 		$text = $this->language->get('text_non_jht');
			// 	}

			// 	foreach ($values as $key => $value) {
			// 		$quote_data[] = array(
			// 			'type'		=> $key,
			// 			'item'		=> (int)date('n', strtotime($period)),
			// 			'title'		=> $text,
			// 			'value'		=> ceil($value)
			// 		);
			// 	}
			// }

			if (!empty($quote_data)) {
				$status = true;
			} else {
				$status = false;
			}
		} else {
			$status = false;
		}

		$component_data = array();

		if ($status) {
			$component_data = array(
				'code'			=> 'insurance',
				'heading_title'	=> $this->language->get('heading_title'),
				'quote'			=> $quote_data,
				'sort_order'	=> $this->config->get('insurance_sort_order')
			);
		}

		return $component_data;
	}

	public function getTitles()
	{
		$this->load->language('component/insurance');

		$titles = array(
			$this->language->get('text_health'),
			$this->language->get('text_jp'),
			$this->language->get('text_jht'),
			$this->language->get('text_non_jht')
		);

		return $titles;
	}
}

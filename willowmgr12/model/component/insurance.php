<?php 
class ModelComponentInsurance extends Model {    
  	public function getQuote($presence_period_id, $customer_id) {
		$this->load->language('component/insurance');
		
		if ($this->config->get('insurance_status')) {
			$this->load->model('common/payroll');
			$period = $this->model_common_payroll->getPeriod($presence_period_id)['period'];
			// $text_period = date($this->language->get('date_format_m_y'), strtotime($period));
			
			$customer_info = $this->model_common_payroll->getCustomer($customer_id);
		
			$quote_data = array();
			
			$wage = $this->config->get('insurance_min_wage');
			$insurance_date_start = $this->config->get('insurance_date_start');
			
			if ($customer_info['skip_trial_status']) {
				$date_start = date('Y-m-', strtotime('- 3 months', strtotime($customer_info['date_start']))) . '01';
			} else {
				$date_start = date('Y-m-', strtotime($customer_info['date_start'])) . '01';
			}
			
			$health_insurance_check = strtotime($period) > strtotime('+ 14 months', strtotime($date_start));
			$non_jht_insurance_check = strtotime($period) > strtotime('+ 3 months', strtotime($date_start));
			$jht_insurance_check = strtotime($period) > strtotime('+ 39 months', strtotime($date_start));
			
			$values = array();
			
			if ($health_insurance_check && $customer_info['health_insurance']) {
				if (strtotime('+1 month', strtotime($period)) < $insurance_date_start) {
					$wage = $this->config->get('insurance_min_wage_old');
				}
				
				$values[0] = -ceil($wage * 0.05);
				$values[1] = ceil($wage * 0.04);
				
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
			
			if ($non_jht_insurance_check && $customer_info['employment_insurance']) {
				if (strtotime($period) < $insurance_date_start) {
					$wage = $this->config->get('insurance_min_wage_old');
				}
				
				if ($jht_insurance_check) {
					$values[0] = -ceil($wage * 0.0624);
					$values[1] = ceil($wage * 0.0424);
					$text = $this->language->get('text_jht');
				} else {
					$values[0] = -ceil($wage * 0.0054);
					$values[1] = ceil($wage * 0.0054);
					$text = $this->language->get('text_non_jht');
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

  	public function getTitles() {
		$this->load->language('component/insurance');
		
		$titles = array(
			$this->language->get('text_health'),
			$this->language->get('text_jht'),
			$this->language->get('text_non_jht')
		);
		
		return $titles;
	}
}

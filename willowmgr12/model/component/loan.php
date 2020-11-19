<?php 
class ModelComponentLoan extends Model {    
  	public function getQuote($presence_period_id, $customer_id) {
		$this->load->language('component/loan');
		
		if ($this->config->get('loan_status')) {
			$this->load->model('common/payroll');
			$period = $this->model_common_payroll->getPeriod($presence_period_id)['period'];
			$text_period = date($this->language->get('date_format_m_y'), strtotime($period));
			
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "loan WHERE customer_id = '" . (int)$customer_id . "' AND date_start < '" . $this->db->escape($period) . "'");
		
			$quote_data = array();
			
			foreach ($query->rows as $result) {
				$loan_query = $this->db->query("SELECT SUM(value) AS total FROM " . DB_PREFIX . "payroll_component_value WHERE code = 'loan' AND item = '" . (int)$result['loan_id'] . "' AND presence_period_id <> '" . (int)$presence_period_id . "'");
				$value = -min($loan_query->row['total'], $result['cicilan']);
				
				$balance = $loan_query->row['total'] + $value;
				if ($balance) {
					$text_balance = sprintf($this->language->get('text_balance'), $this->currency->format($balance, $this->config->get('config_currency')));
				} else {
					$text_balance = $this->language->get('text_paid_off');
				}
					
				if ($value) {
					$quote_data[] = array(
						'type'		=> 0,
						'item'		=> $result['loan_id'],
						'title'		=> sprintf($this->language->get('text_description'), $result['loan_id'], $text_period, $text_balance),
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
        		'code'			=> 'loan',
				'heading_title'	=> $this->language->get('heading_title'),
         		'quote'			=> $quote_data,
				'sort_order'	=> $this->config->get('loan_sort_order')
      		);
    	}
   
    	return $component_data;
 	}
}

<?php 
class ModelComponentCutoff extends Model {    
  	public function getQuote($presence_period_id, $customer_id) {
		$this->load->language('component/cutoff');
		
		if ($this->config->get('cutoff_status')) {
			$this->load->model('common/payroll');
			$period = $this->model_common_payroll->getPeriod($presence_period_id);
			$text_period = date($this->language->get('date_format_m_y'), strtotime($period['period']));
			
			$query = $this->db->query("SELECT co.*, pcv.presence_period_id FROM " . DB_PREFIX . "cutoff co LEFT JOIN " . DB_PREFIX . "payroll_component_value pcv ON (pcv.item = co.cutoff_id AND pcv.code = 'cutoff') WHERE co.customer_id = '" . (int)$customer_id . "' AND date <= '" . $this->db->escape($period['date_end']) . "' AND (pcv.presence_period_id = '" . (int)$presence_period_id . "' OR pcv.presence_period_id IS NULL)");
			$quote_data = array();
			
			foreach ($query->rows as $result) {
				$quote_data[] = array(
					'type'		=> 0,
					'item'		=> $result['cutoff_id'],
					'title'		=> sprintf($this->language->get('text_description'), $result['inv_no']),
					'value'		=> -ceil($result['amount'])
				);
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
        		'code'			=> 'cutoff',
				// 'heading_title'	=> $this->language->get('heading_title'),
         		'quote'			=> $quote_data,
				'sort_order'	=> $this->config->get('cutoff_sort_order')
      		);
    	}
   
    	return $component_data;
 	}
}

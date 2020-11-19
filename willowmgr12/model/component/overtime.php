<?php 
class ModelComponentOvertime extends Model {    
  	public function getQuote($presence_period_id, $customer_id) {
		$this->load->language('component/overtime');
		
		if ($this->config->get('overtime_status')) {
			$this->load->model('common/payroll');
			$period = $this->model_common_payroll->getPeriod($presence_period_id);
			$text_period = date($this->language->get('date_format_m_y'), strtotime($period['period']));
			
			$query = $this->db->query("SELECT o.*, ot.name, ot.wage, pcv.presence_period_id FROM " . DB_PREFIX . "overtime o LEFT JOIN " . DB_PREFIX . "overtime_type ot ON (ot.overtime_type_id = o.overtime_type_id) LEFT JOIN " . DB_PREFIX . "payroll_component_value pcv ON (pcv.item = o.overtime_id AND pcv.code = 'overtime') WHERE o.customer_id = '" . (int)$customer_id . "' AND date >= '" . $this->db->escape($period['date_start']) . "' AND date <= '" . $this->db->escape($period['date_end']) . "' AND (pcv.presence_period_id = '" . (int)$presence_period_id . "' OR pcv.presence_period_id IS NULL)");
			// $query = $this->db->query("SELECT o.*, ot.name, ot.wage, pcv.presence_period_id FROM " . DB_PREFIX . "overtime o LEFT JOIN " . DB_PREFIX . "overtime_type ot ON (ot.overtime_type_id = o.overtime_type_id) LEFT JOIN " . DB_PREFIX . "payroll_component_value pcv ON (pcv.item = o.overtime_id AND pcv.code = 'overtime') WHERE o.customer_id = '" . (int)$customer_id . "' AND date <= '" . $this->db->escape($period['date_end']) . "' AND (pcv.presence_period_id = '" . (int)$presence_period_id . "' OR pcv.presence_period_id IS NULL)");
			$quote_data = array();

			foreach ($query->rows as $result) {
				if ($result['approved']) {
					$title = sprintf($this->language->get('text_description'), $result['name'], date($this->language->get('date_format_jMY'), strtotime($result['date'])));
					$value = ceil($result['wage']);
				} else {
					$title = sprintf('<strike>' . $this->language->get('text_description') . '</strike>', $result['name'], date($this->language->get('date_format_jMY'), strtotime($result['date'])));
					$value = 0;
				}
				
				$quote_data[] = array(
					'type'		=> 1,
					'item'		=> $result['overtime_id'],
					'title'		=> $title,
					'value'		=> $value
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
        		'code'			=> 'overtime',
				// 'heading_title'	=> $this->language->get('heading_title'),
         		'quote'			=> $quote_data,
				'sort_order'	=> $this->config->get('overtime_sort_order'),
      		);
    	}
   
    	return $component_data;
 	}
}

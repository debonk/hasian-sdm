<?php 
class ModelComponentDayOff extends Model {    
  	public function getQuote($presence_period_id, $customer_id) {
		$this->load->language('component/dayoff');
		
		if ($this->config->get('dayoff_status')) {
			$this->load->model('common/payroll');
			
			$period_info = $this->model_common_payroll->getPeriod($presence_period_id);
			$text_period = date($this->language->get('date_format_m_y'), strtotime($period_info['period']));
			
			$quote_data = array();
			
			$dayoff_query = $this->db->query("SELECT DISTINCT presence_status_id FROM " . DB_PREFIX . "presence_status WHERE code = 'drm'");

			if ($dayoff_query->num_rows) {
				$presence_status_id = $dayoff_query->row['presence_status_id'];
			} else {
				$presence_status_id = 0;
			}
				
			$dayoff_count_query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "presence WHERE customer_id = '" . (int)$customer_id . "' AND presence_status_id = '" . (int)$presence_status_id . "' AND date_presence >= '" . $this->db->escape($period_info['date_start']) . "' AND date_presence <= '" . $this->db->escape($period_info['date_end']) . "'");
			$dayoff_count = $dayoff_count_query->row['total'];
			// $dayoff_count = 1; // kalau pakai rumus fix dibagi 25
			
			if ($dayoff_count) {
				$this->load->model('presence/presence');
				$this->load->model('payroll/payroll');
				
				$presence_summary_info = $this->model_presence_presence->getPresenceSummary($presence_period_id, $customer_id);
				$payroll_info = $this->model_payroll_payroll->getPayrollDetail($presence_period_id, $customer_id);
				$hke = $presence_summary_info['total_h'] - $presence_summary_info['full_overtimes_count'];
				$total_absen = $presence_summary_info['total_sakit'] - $presence_summary_info['total_bolos'];
				$dayoff_count += $total_absen;
				
				if ($payroll_info && $presence_summary_info) {
					$value = (($payroll_info['gaji_pokok'] + $payroll_info['tunj_jabatan'] + $payroll_info['tunj_hadir'] + $payroll_info['tunj_pph'] + ($payroll_info['uang_makan'] * $total_absen) - $payroll_info['pot_sakit'] - $payroll_info['pot_bolos'] - $payroll_info['pot_terlambat']) * $dayoff_count / ($hke + $dayoff_count)) - $payroll_info['pot_tunj_hadir'] - $payroll_info['pot_gaji_pokok'];
					// $value = (($payroll_info['gaji_pokok'] + $payroll_info['tunj_jabatan'] + $payroll_info['tunj_hadir'] + $payroll_info['tunj_pph'] + ($payroll_info['uang_makan'] * $total_absen) - $payroll_info['pot_sakit'] - $payroll_info['pot_bolos'] - $payroll_info['pot_tunj_hadir'] - $payroll_info['pot_gaji_pokok'] - $payroll_info['pot_terlambat']) * $dayoff_count / ($hke + $dayoff_count));

					//type: jika "always_view" pd setting payroll = 1, 1 utk pendapatan, 0 utk potongan
					//type: jika "always_view" pd setting payroll = 0, "type" menjadi dasar perhitungan komponen salary untuk tujuan perhitungan pph, 1 utk masuk gross salary
					//type: dasar perhitungan komponen salary untuk tujuan perhitungan pph, 1 utk pendapatan, 0 utk potongan
					$quote_data[] = array(
						'type'		=> 1,
						'item'		=> (int)date('n', strtotime($period_info['period'])),
						'title'		=> sprintf($this->language->get('text_description'), $text_period, $dayoff_count),
						'value'		=> -$value
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
        		'code'			=> 'dayoff',
				// 'heading_title'	=> $this->language->get('heading_title'),
         		'quote'			=> $quote_data,
				'sort_order'	=> $this->config->get('dayoff_sort_order')
      		);
    	}
   
    	return $component_data;
 	}
}

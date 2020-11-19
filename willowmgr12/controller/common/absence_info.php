<?php
class ControllerCommonAbsenceInfo extends Controller {
	public function index() {
		$this->load->language('common/absence_info');

		$this->load->model('common/payroll');
		$this->load->model('presence/absence');
		$this->load->model('presence/exchange');
		$this->load->model('overtime/overtime');

		if (isset($this->request->get['presence_period_id'])) {
			$presence_period_id = $this->request->get['presence_period_id'];
		} else {
			$presence_period_id = 0;
		}

		if (isset($this->request->get['customer_id'])) {
			$customer_id = $this->request->get['customer_id'];
		} else {
			$customer_id = 0;
		}

		$language_items = array(
			'text_list',
			'text_loading',
			'column_date',
			'column_presence_status',
			'column_description',
			'column_note',
			'column_status',
			'button_unapprove',
			'button_approve'
		);
		foreach ($language_items as $item) {
			$data[$item] = $this->language->get($item);
		}
		
		$period_info = $this->model_common_payroll->getPeriod($presence_period_id);
		$customer_info = $this->model_common_payroll->getCustomer($customer_id);
			
		if ($period_info) {
			$range_date = array(
				'start'	=> max($period_info['date_start'], $customer_info['date_start']),
				'end'	=> $customer_info['date_end'] ? min($period_info['date_end'], $customer_info['date_end']) : $period_info['date_end']
			);
		}
		
		$data['schedule_changes'] = array();

		$absences_info = $this->model_presence_absence->getAbsencesByCustomerDate($customer_id, $range_date);
		
		foreach ($absences_info as $absence_info) {
			$description = $absence_info['description'];
			
			if (!$absence_info['approved']) {
				$description = '<strike>' . $description . '</strike>';
			}
		
			$data['schedule_changes'][] = array(
				'date'				=> date($this->language->get('date_format_jMY'), strtotime($absence_info['date'])),
				'presence_status'	=> $absence_info['presence_status'],
				'description'		=> $description,
				'note'				=> $absence_info['note'],
				'action_url'		=> 'presence/absence/approval&absence_id=' . $absence_info['absence_id'],
				'approved'			=> $absence_info['approved'],
				'sort_order'		=> $absence_info['date']
			);
		}
		
		$exchanges_info = $this->model_presence_exchange->getExchangesByCustomerDate($customer_id, $range_date);

		foreach ($exchanges_info as $exchange_info) {
			$data['schedule_changes'][] = array(
				'date'				=> date($this->language->get('date_format_jMY'), strtotime($exchange_info['date_from'])),
				'presence_status'	=> 'X',
				'description'		=> sprintf($this->language->get('text_exchange'), date('j M', strtotime($exchange_info['date_to'])), $exchange_info['description']),
				'note'				=> '',
				'action_url'		=> '',
				'approved'			=> 1,
				'sort_order'		=> $exchange_info['date_from']
			);
		}

		$overtimes_info = $this->model_overtime_overtime->getOvertimesByCustomerDate($customer_id, $range_date);

		foreach ($overtimes_info as $overtime_info) {
			$presence_info = $this->model_common_payroll->getPresenceByDate($customer_id, $overtime_info['date']);
			
			$description = sprintf($this->language->get('text_overtime'), $overtime_info['description']);
			
			if (!$overtime_info['approved']) {
				$description = '<strike>' . $description . '</strike>';
			}
		
			if ($presence_info) {
				$presence_status = $presence_info['presence_status'];
			} else {
				$presence_status = '-';
			}
			
			if ($presence_info && !is_null($presence_info['time_login']) && $presence_info['time_logout'] != '0000-00-00 00:00:00') {
				$duration = round((strtotime($presence_info['time_logout']) - strtotime($presence_info['time_login'])) / 3600, 2);
			} else {
				$duration = '-';
			}
			
			$note = sprintf($this->language->get('text_presence_status'), $presence_status, $duration);
			
			$data['schedule_changes'][] = array(
				'date'				=> date($this->language->get('date_format_jMY'), strtotime($overtime_info['date'])),
				'presence_status'	=> $overtime_info['duration'] > 7 ? 'LH' : 'L',
				'description'		=> $description,
				'note'				=> $note,
				'approved'			=> $overtime_info['approved'],
				'action_url'		=> 'overtime/overtime/approval&overtime_id=' . $overtime_info['overtime_id'],
				'sort_order'		=> $overtime_info['date']
			);
		}
		
		array_multisort(array_column($data['schedule_changes'], 'sort_order'), SORT_ASC, SORT_REGULAR, $data['schedule_changes']);
		
		$this->response->setOutput($this->load->view('common/absence_info', $data));
	}
}
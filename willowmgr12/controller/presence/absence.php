<?php
class ControllerPresenceAbsence extends Controller
{
	private $error = array();

	public function index()
	{
		$this->load->language('presence/absence');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('presence/absence');

		$this->getList();
	}

	public function add()
	{
		$this->load->language('presence/absence');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('presence/absence');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_presence_absence->addAbsence($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_presence_status_id'])) {
				$url .= '&filter_presence_status_id=' . $this->request->get['filter_presence_status_id'];
			}

			if (isset($this->request->get['filter_date'])) {
				$url .= '&filter_date=' . $this->request->get['filter_date'];
			}

			if (isset($this->request->get['filter_period_id'])) {
				$url .= '&filter_period_id=' . $this->request->get['filter_period_id'];
			}

			if (isset($this->request->get['filter_note'])) {
				$url .= '&filter_note=' . $this->request->get['filter_note'];
			}

			if (isset($this->request->get['filter_approved'])) {
				$url .= '&filter_approved=' . $this->request->get['filter_approved'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('presence/absence', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function edit()
	{
		$this->load->language('presence/absence');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('presence/absence');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_presence_absence->editAbsence($this->request->get['absence_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_presence_status_id'])) {
				$url .= '&filter_presence_status_id=' . $this->request->get['filter_presence_status_id'];
			}

			if (isset($this->request->get['filter_date'])) {
				$url .= '&filter_date=' . $this->request->get['filter_date'];
			}

			if (isset($this->request->get['filter_period_id'])) {
				$url .= '&filter_period_id=' . $this->request->get['filter_period_id'];
			}

			if (isset($this->request->get['filter_note'])) {
				$url .= '&filter_note=' . $this->request->get['filter_note'];
			}

			if (isset($this->request->get['filter_approved'])) {
				$url .= '&filter_approved=' . $this->request->get['filter_approved'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('presence/absence', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function delete()
	{
		$this->load->language('presence/absence');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('presence/absence');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $absence_id) {
				$this->model_presence_absence->deleteAbsence($absence_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_presence_status_id'])) {
				$url .= '&filter_presence_status_id=' . $this->request->get['filter_presence_status_id'];
			}

			if (isset($this->request->get['filter_date'])) {
				$url .= '&filter_date=' . $this->request->get['filter_date'];
			}

			if (isset($this->request->get['filter_period_id'])) {
				$url .= '&filter_period_id=' . $this->request->get['filter_period_id'];
			}

			if (isset($this->request->get['filter_note'])) {
				$url .= '&filter_note=' . $this->request->get['filter_note'];
			}

			if (isset($this->request->get['filter_approved'])) {
				$url .= '&filter_approved=' . $this->request->get['filter_approved'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('presence/absence', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getList();
	}

	protected function getList()
	{
		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = '';
		}

		if (isset($this->request->get['filter_presence_status_id'])) {
			$filter_presence_status_id = $this->request->get['filter_presence_status_id'];
		} else {
			$filter_presence_status_id = '';
		}

		if (isset($this->request->get['filter_date'])) {
			$filter_date = $this->request->get['filter_date'];
		} else {
			$filter_date = '';
		}

		if (isset($this->request->get['filter_period_id'])) {
			$filter_period_id = $this->request->get['filter_period_id'];
		} else {
			$filter_period_id = '';
		}

		if (isset($this->request->get['filter_note'])) {
			$filter_note = $this->request->get['filter_note'];
		} else {
			$filter_note = null;
		}

		if (isset($this->request->get['filter_approved'])) {
			$filter_approved = $this->request->get['filter_approved'];
		} else {
			$filter_approved = null;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'date';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_presence_status_id'])) {
			$url .= '&filter_presence_status_id=' . $this->request->get['filter_presence_status_id'];
		}

		if (isset($this->request->get['filter_date'])) {
			$url .= '&filter_date=' . $this->request->get['filter_date'];
		}

		if (isset($this->request->get['filter_period_id'])) {
			$url .= '&filter_period_id=' . $this->request->get['filter_period_id'];
		}

		if (isset($this->request->get['filter_note'])) {
			$url .= '&filter_note=' . $this->request->get['filter_note'];
		}

		if (isset($this->request->get['filter_approved'])) {
			$url .= '&filter_approved=' . $this->request->get['filter_approved'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('presence/absence', 'token=' . $this->session->data['token'] . $url, true)
		);

		$data['add'] = $this->url->link('presence/absence/add', 'token=' . $this->session->data['token'] . $url, true);
		$data['delete'] = $this->url->link('presence/absence/delete', 'token=' . $this->session->data['token'] . $url, true);

		$filter_data = array(
			'filter_name'				=> $filter_name,
			'filter_presence_status_id'	=> $filter_presence_status_id,
			'filter_date'				=> $filter_date,
			'filter_period_id'			=> $filter_period_id,
			'filter_note'				=> $filter_note,
			'filter_approved'			=> $filter_approved,
			'sort'  					=> $sort,
			'order' 					=> $order,
			'start'         			=> ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'         			=> $this->config->get('config_limit_admin')
		);

		$data['absences'] = array();

		$results = $this->model_presence_absence->getAbsences($filter_data);

		foreach ($results as $result) {
			$data['absences'][] = array(
				'absence_id' 		=> $result['absence_id'],
				'date' 				=> date($this->language->get('date_format_jMY'), strtotime($result['date'])),
				'name' 				=> $result['name'],
				'presence_status' 	=> $result['presence_status'],
				'description' 		=> strlen($result['description']) > 30 ? substr($result['description'], 0, 28) . '..' : $result['description'],
				'note' 				=> strlen($result['note']) > 30 ? substr($result['note'], 0, 28) . '..' : $result['note'],
				'approved'    		=> $result['approved'],
				'username'    		=> $result['username'],
				// 'view'          	=> $this->url->link('presence/schedule/edit', 'token=' . $this->session->data['token'] . '&presence_period_id=' . $result['presence_period_id'] . '&customer_id=' . $result['customer_id'], true),
				'edit'          	=> $this->url->link('presence/absence/edit', 'token=' . $this->session->data['token'] . '&absence_id=' . $result['absence_id'] . $url, true),
			);
		}

		$absences_count = $this->model_presence_absence->getAbsencesCount($filter_data);

		$language_items = array(
			'heading_title',
			'text_list',
			'text_no_results',
			'text_confirm',
			'text_loading',
			'text_all',
			'text_with_note',
			'text_without_note',
			'text_approved',
			'text_not_approved',
			'entry_name',
			'entry_presence_status',
			'entry_date',
			'entry_period',
			'entry_note',
			'entry_approved',
			'column_date',
			'column_name',
			'column_presence_status',
			'column_description',
			'column_note',
			'column_approved',
			'column_username',
			'column_action',
			'button_filter',
			'button_add',
			'button_view',
			'button_edit',
			'button_delete',
			'button_approve'
		);
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}

		$data['token'] = $this->session->data['token'];

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_presence_status_id'])) {
			$url .= '&filter_presence_status_id=' . $this->request->get['filter_presence_status_id'];
		}

		if (isset($this->request->get['filter_date'])) {
			$url .= '&filter_date=' . $this->request->get['filter_date'];
		}

		if (isset($this->request->get['filter_period_id'])) {
			$url .= '&filter_period_id=' . $this->request->get['filter_period_id'];
		}

		if (isset($this->request->get['filter_note'])) {
			$url .= '&filter_note=' . $this->request->get['filter_note'];
		}

		if (isset($this->request->get['filter_approved'])) {
			$url .= '&filter_approved=' . $this->request->get['filter_approved'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_date'] = $this->url->link('presence/absence', 'token=' . $this->session->data['token'] . '&sort=date' . $url, true);
		$data['sort_name'] = $this->url->link('presence/absence', 'token=' . $this->session->data['token'] . '&sort=name' . $url, true);
		$data['sort_presence_status'] = $this->url->link('presence/absence', 'token=' . $this->session->data['token'] . '&sort=presence_status_id' . $url, true);

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_presence_status_id'])) {
			$url .= '&filter_presence_status_id=' . $this->request->get['filter_presence_status_id'];
		}

		if (isset($this->request->get['filter_period_id'])) {
			$url .= '&filter_period_id=' . $this->request->get['filter_period_id'];
		}

		if (isset($this->request->get['filter_note'])) {
			$url .= '&filter_note=' . $this->request->get['filter_note'];
		}

		if (isset($this->request->get['filter_approved'])) {
			$url .= '&filter_approved=' . $this->request->get['filter_approved'];
		}

		if (isset($this->request->get['filter_date'])) {
			$url .= '&filter_date=' . $this->request->get['filter_date'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $absences_count;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('presence/absence', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($absences_count) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($absences_count - $this->config->get('config_limit_admin'))) ? $absences_count : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $absences_count, ceil($absences_count / $this->config->get('config_limit_admin')));

		$data['filter_name'] = $filter_name;
		$data['filter_presence_status_id'] = $filter_presence_status_id;
		$data['filter_date'] = $filter_date;
		$data['filter_period_id'] = $filter_period_id;
		$data['filter_note'] = $filter_note;
		$data['filter_approved'] = $filter_approved;
		$data['sort'] = $sort;
		$data['order'] = $order;

		$this->load->model('presence/presence_period');
		$data['periods'] = $this->model_presence_presence_period->getPresencePeriods();

		$data['config_presence_status'] = $this->config->get('payroll_setting_presence_status_ids');
		$this->load->model('localisation/presence_status');
		$data['presence_statuses'] = $this->model_localisation_presence_status->getPresenceStatuses();

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('presence/absence_list', $data));
	}

	protected function getForm()
	{
		$data['text_form'] = !isset($this->request->get['absence_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		$language_items = array(
			'heading_title',
			// 'text_select_customer',
			'text_select',
			'text_loading',
			'entry_name',
			'entry_date',
			'entry_presence_status',
			'entry_description',
			'entry_note',
			'button_save',
			'button_cancel',
			'button_add_note',
			'button_ask_approval'
		);
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}

		$data['token'] = $this->session->data['token'];

		$data['customers'] = array();

		$this->load->model('presence/presence');
		$results = $this->model_presence_presence->getCustomers(['filter_customer_department_id' => $this->user->getCustomerDepartmentId()]);

		foreach ($results as $result) {
			$data['customers'][] = array(
				'customer_id' 	=> $result['customer_id'],
				'text' 			=> $result['name'] . ' - ' . $result['customer_group']
			);
		}

		$errors = array(
			'warning',
			'date',
			'ask_approval',
			'presence_status',
			'description'
		);
		foreach ($errors as $error) {
			if (isset($this->error[$error])) {
				$data['error_' . $error] = $this->error[$error];
			} else {
				$data['error_' . $error] = '';
			}
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_presence_status_id'])) {
			$url .= '&filter_presence_status_id=' . $this->request->get['filter_presence_status_id'];
		}

		if (isset($this->request->get['filter_date'])) {
			$url .= '&filter_date=' . $this->request->get['filter_date'];
		}

		if (isset($this->request->get['filter_period_id'])) {
			$url .= '&filter_period_id=' . $this->request->get['filter_period_id'];
		}

		if (isset($this->request->get['filter_note'])) {
			$url .= '&filter_note=' . $this->request->get['filter_note'];
		}

		if (isset($this->request->get['filter_approved'])) {
			$url .= '&filter_approved=' . $this->request->get['filter_approved'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('presence/absence', 'token=' . $this->session->data['token'] . $url, true)
		);

		if (!isset($this->request->get['absence_id'])) {
			$data['action'] = $this->url->link('presence/absence/add', 'token=' . $this->session->data['token'] . $url, true);
			$data['disabled'] = '';
			$data['absence_id'] = 0;
		} else {
			$data['action'] = $this->url->link('presence/absence/edit', 'token=' . $this->session->data['token'] . '&absence_id=' . $this->request->get['absence_id'] . $url, true);
			$data['disabled'] = 'disabled';
			$data['absence_id'] = $this->request->get['absence_id'];
		}

		$data['breadcrumbs'][] = array(
			'text' => $data['text_form'],
			'href' => $data['action']
		);

		$data['cancel'] = $this->url->link('presence/absence', 'token=' . $this->session->data['token'] . $url, true);

		if (isset($this->request->get['absence_id'])) {
			$absence_info = $this->model_presence_absence->getAbsence($this->request->get['absence_id']);
		}

		//Text User Modify
		if (!empty($absence_info)) {
			$username = $absence_info['username'];
			$date_modified = date($this->language->get('datetime_format_jMY'), strtotime($absence_info['date_modified']));
		} else {
			$username = $this->user->getUserName();
			$date_modified = date($this->language->get('datetime_format_jMY'));
		}
		$data['text_modified'] = sprintf($this->language->get('text_modified'), $username, $date_modified);

		if (isset($this->request->post['customer_id'])) {
			$data['customer_id'] = $this->request->post['customer_id'];
		} elseif (!empty($absence_info)) {
			$data['customer_id'] = $absence_info['customer_id'];
		} else {
			$data['customer_id'] = 0;
		}

		if (isset($this->request->post['date'])) {
			$data['date'] = $this->request->post['date'];
		} elseif (!empty($absence_info)) {
			$data['date'] = date($this->language->get('date_format_jMY'), strtotime($absence_info['date']));
		} else {
			$data['date'] = '';
		}

		if (isset($this->request->post['approval'])) {
			$data['approval'] = $this->request->post['approval'];
		} elseif (!empty($absence_info)) {
			$data['approval'] = !$absence_info['approved'];
		} else {
			$data['approval'] = 0;
		}

		if (isset($this->request->post['presence_status_id'])) {
			$data['presence_status_id'] = $this->request->post['presence_status_id'];
		} elseif (!empty($absence_info)) {
			$data['presence_status_id'] = $absence_info['presence_status_id'];
		} else {
			$data['presence_status_id'] = 0;
		}

		if (isset($this->request->post['description'])) {
			$data['description'] = $this->request->post['description'];
		} elseif (!empty($absence_info)) {
			$data['description'] = $absence_info['description'];
		} else {
			$data['description'] = '';
		}

		if (isset($this->request->post['note'])) {
			$data['note'] = $this->request->post['note'];
		} elseif (!empty($absence_info)) {
			$data['note'] = $absence_info['note'];
		} else {
			$data['note'] = '';
		}

		$data['config_presence_status'] = $this->config->get('payroll_setting_presence_status_ids');

		$this->load->model('localisation/presence_status');
		$data['presence_statuses'] = $this->model_localisation_presence_status->getPresenceStatuses();

		$data['url'] = $url;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('presence/absence_form', $data));
	}

	protected function validateForm()
	{
		$this->load->model('common/payroll');

		if (!$this->user->hasPermission('modify', 'presence/absence')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!isset($this->request->get['absence_id']) && empty($this->request->post['customer_id'])) {
			$this->error['warning'] = $this->language->get('error_customer_id');
		}

		if (isset($this->request->get['absence_id'])) {
			$absence_info = $this->model_presence_absence->getAbsence($this->request->get['absence_id']);

			$customer_id = $absence_info['customer_id'];
			$date = $absence_info['date'];
		} else {
			$customer_id = $this->request->post['customer_id'];
			$date = '';
		}

		if ((utf8_strlen($this->request->post['description']) < 3) || (utf8_strlen(trim($this->request->post['description'])) > 255)) {
			$this->error['description'] = $this->language->get('error_description');
		}

		if (empty($this->request->post['presence_status_id'])) { //Check tidak ada presence status
			$this->error['presence_status'] = $this->language->get('error_presence_status');
		} elseif ($this->request->post['presence_status_id'] == $this->config->get('payroll_setting_id_c') && !$this->model_presence_absence->checkVacationLimit($customer_id, $this->request->post['date'])) { //Check batas cuti
			$this->error['presence_status'] = $this->language->get('error_vacation_limit');
		}

		if (empty($this->request->post['date'])) { //Check date kosong
			$this->error['date'] = $this->language->get('error_date_empty');
		}

		if (!$this->error) {
			$period_info = $this->model_common_payroll->getPeriodByDate(date('Y-m-d', strtotime($this->request->post['date'])));

			if ($this->user->hasPermission('bypass', 'presence/absence')) {
				if ($period_info && $this->model_common_payroll->checkPeriodStatus($period_info['presence_period_id'], 'approved, released, completed')) { //Check period status
					$this->error['date'] = $this->language->get('error_status');
				}
			} else {
				if ($this->user->getCustomerDepartmentId()) {
					$customer_info = $this->model_common_payroll->getCustomer($customer_id);
		
					if ($this->user->getCustomerDepartmentId() != $customer_info['customer_department_id']) {
						$this->error['warning'] = $this->language->get('error_customer_department');
					}
				}
		
				if ($period_info && $this->model_common_payroll->checkPeriodStatus($period_info['presence_period_id'], 'submitted, generated, approved, released, completed')) { //Check period status
					$this->error['date'] = $this->language->get('error_status');
				}

				$applied_schedule_info = $this->model_common_payroll->getAppliedSchedule($customer_id, $this->request->post['date']);

				$ask_approval = 0;

				if ($this->request->post['date']) {
					if (!$applied_schedule_info) { //Check schedule kosong
						$this->error['date'] = $this->language->get('error_schedule_empty');
					} elseif ($applied_schedule_info['applied'] == 'absence' && strtotime($date) != strtotime($this->request->post['date'])) { //Check date used
						$this->error['date'] = $this->language->get('error_date_used');
					} else {
						$this->load->model('localisation/presence_status');

						$presence_status_info = $this->model_localisation_presence_status->getPresenceStatus($this->request->post['presence_status_id']);
						$date_last_notif = date($this->language->get('date_format_jMY'), strtotime('-' . $presence_status_info['last_notif'] . ' days'));

						if (strtotime($this->request->post['date']) < strtotime($date_last_notif)) { //Check proteksi date
							$this->error['date'] = sprintf($this->language->get('error_date'), $presence_status_info['name'], $date_last_notif);

							if (!isset($this->request->get['absence_id'])) { //Check untuk minta persetujuan
								$ask_approval = 1;
							}
						}
					}
				}

				if (count($this->error) == 1 && $ask_approval) {
					$this->error['ask_approval'] = 1;
				}
			}
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}

	protected function validateDelete()
	{
		if (!$this->user->hasPermission('modify', 'presence/absence')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$this->load->model('common/payroll');

		foreach ($this->request->post['selected'] as $absence_id) {
			$absence_info = $this->model_presence_absence->getAbsence($absence_id);

			$period_info = $this->model_common_payroll->getPeriodByDate($absence_info['date']);

			if ($this->user->hasPermission('bypass', 'presence/absence')) {
				if ($period_info && $this->model_common_payroll->checkPeriodStatus($period_info['presence_period_id'], 'approved, released, completed')) {
					$this->error['warning'] = $this->language->get('error_status');

					break;
				}
			} else {
				if ($this->user->getCustomerDepartmentId()) {
					$customer_info = $this->model_common_payroll->getCustomer($absence_info['customer_id']);

					if ($this->user->getCustomerDepartmentId() != $customer_info['customer_department_id']) {
						$this->error['warning'] = $this->language->get('error_customer_department');

						break;
					}
				}

				if ($period_info && $this->model_common_payroll->checkPeriodStatus($period_info['presence_period_id'], 'submitted, generated, approved, released, completed')) {
					$this->error['warning'] = $this->language->get('error_status');

					break;
				}

				$date_last_notif = date($this->language->get('date_format_jMY'), strtotime('-' . $absence_info['last_notif'] . ' days'));

				if (strtotime($absence_info['date']) < strtotime($date_last_notif)) {
					$this->error['warning'] = $this->language->get('error_absence_status');

					break;
				}
			}
		}

		return !$this->error;
	}

	public function note()
	{
		$this->load->language('presence/absence');

		$json = array();

		if (!$this->user->hasPermission('modify', 'presence/absence')) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			$this->load->model('presence/absence');

			$absence_info = $this->model_presence_absence->getAbsence($this->request->get['absence_id']);

			if (empty($absence_info)) {
				$json['error'] = $this->language->get('error_not_found');
			} else {
				$this->model_presence_absence->editNote($this->request->get['absence_id'], $this->request->post['note']);

				$this->session->data['success'] = $this->language->get('text_success_note');
				$json['success'] = 1;
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function askApproval()
	{
		$this->load->language('presence/absence');

		$json = array();

		if (!$this->user->hasPermission('modify', 'presence/absence')) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			$this->load->model('presence/absence');

			$absence_count = $this->model_presence_absence->getAbsencesCountByCustomerDate($this->request->post['customer_id'], $this->request->post['date']);

			if ($absence_count) {
				$json['error'] = $this->language->get('error_absence_exist');
			} else {
				$this->model_presence_absence->addUnapprovedAbsence($this->request->post);

				$this->session->data['success'] = $this->language->get('text_success');
				$json['success'] = 1;
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function approval()
	{
		$this->load->language('presence/absence');

		$json = array();

		if (!$this->user->hasPermission('modify', 'common/absence_info')) { //Common_Absence_info user yg bisa approve
			$json['error'] = $this->language->get('error_permission');
		} else {
			$this->load->model('presence/absence');
			$absence_info = $this->model_presence_absence->getAbsence($this->request->get['absence_id']);

			$this->load->model('common/payroll');
			$period_info = $this->model_common_payroll->getPeriodByDate($absence_info['date']);
			$period_status_check = $this->model_common_payroll->checkPeriodStatus($period_info['presence_period_id'], 'approved, released, completed');

			if (!$absence_info) {
				$json['error'] = $this->language->get('error_not_found');
			} elseif ($period_status_check) { //Check period status
				$json['error'] = $this->language->get('error_status');
			} elseif ($absence_info['presence_status_id'] == $this->config->get('payroll_setting_id_c') && !$this->model_presence_absence->checkVacationLimit($absence_info['customer_id'], $absence_info['date'])) { //Check batas cuti
				$json['error'] = $this->language->get('error_vacation_limit');
			}
		}

		if (!$json) {
			$this->load->model('presence/presence');

			$presence_status_ia = $this->config->get('payroll_setting_id_ia');

			$presence_summary_info = $this->model_presence_presence->getPresenceSummary($period_info['presence_period_id'], $absence_info['customer_id']);

			$presence_summary_data = array();

			if ($absence_info['approved']) {
				$this->model_presence_absence->unapproveAbsence($this->request->get['absence_id']);

				//Edit presence dan presence summary
				if ($presence_summary_info) {
					$this->model_presence_presence->editPresence($absence_info['customer_id'], $absence_info['date'], $presence_status_ia);

					$presence_summary_data['total_ia'] = $presence_summary_info['total_ia'] + 1;

					if ($absence_info['presence_code']) {
						$presence_summary_data['total_' . $absence_info['presence_code']] = $presence_summary_info['total_' . $absence_info['presence_code']] - 1;
					}
				}
			} else {
				$this->model_presence_absence->approveAbsence($this->request->get['absence_id']);

				if ($presence_summary_info) {
					$this->model_presence_presence->editPresence($absence_info['customer_id'], $absence_info['date'], $absence_info['presence_status_id']);

					$presence_summary_data['total_ia'] = $presence_summary_info['total_ia'] - 1;

					if ($absence_info['presence_code']) {
						$presence_summary_data['total_' . $absence_info['presence_code']] = $presence_summary_info['total_' . $absence_info['presence_code']] + 1;
					}
				}
			}

			$this->model_presence_presence->editPresenceSummary($period_info['presence_period_id'], $absence_info['customer_id'], $presence_summary_data);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}

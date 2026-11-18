<?php
class ControllerToolUpload extends Controller
{
	private $error = array();

	public function index()
	{
		$this->load->language('tool/upload');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('tool/upload');

		$this->getList();
	}

	public function delete()
	{
		$this->load->language('tool/upload');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('tool/upload');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $upload_id) {
				$this->model_tool_upload->deleteUpload($upload_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_date_added'])) {
				$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
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

			$this->response->redirect($this->url->link('tool/upload', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getList();
	}

	/**
	 * Step 1 of the "Delete Older" action: show what would be removed.
	 *
	 * Step 2 is the explicit confirmation posted back to this route.
	 */
	public function deleteOlder()
	{
		$this->load->language('tool/upload');

		$this->document->setTitle($this->language->get('heading_delete_older'));

		$this->load->model('tool/upload');

		$is_confirm = isset($this->request->server['REQUEST_METHOD'])
			&& $this->request->server['REQUEST_METHOD'] == 'POST'
			&& isset($this->request->post['confirm_delete_older']);

		if ($is_confirm) {
			if ($this->validateDelete()) {
				// Reuse the cut-off that was shown on the preview screen so the
				// confirmed deletion matches exactly what the user has seen.
				if (!empty($this->session->data['upload_delete_older_cutoff'])) {
					$cutoff = $this->session->data['upload_delete_older_cutoff'];

					unset($this->session->data['upload_delete_older_cutoff']);
				} else {
					$cutoff = $this->model_tool_upload->getUploadCutoff();
				}

				$result = $this->model_tool_upload->deleteUploadsOlderThan($cutoff);

				$this->logCleanup($cutoff, $result);

				if ($result['deleted']) {
					$this->session->data['success'] = sprintf($this->language->get('text_success_delete_older'), $result['deleted'], $result['files_deleted'], $cutoff);
				} else {
					$this->session->data['error'] = $this->language->get('text_delete_older_empty');
				}

				if ($result['files_failed']) {
					$this->session->data['warning'] = sprintf($this->language->get('text_delete_older_files_failed'), count($result['files_failed']), implode(', ', $result['files_failed']));
				}

				$this->response->redirect($this->url->link('tool/upload', 'token=' . $this->session->data['token'], true));

				return;
			}
		}

		$cutoff = $this->model_tool_upload->getUploadCutoff();

		$uploads = array();

		if ($this->user->hasPermission('modify', 'tool/upload')) {
			$uploads = $this->model_tool_upload->getUploadsOlderThan($cutoff);

			// Remember the cut-off so the confirmation deletes exactly the
			// records that were shown on this preview screen.
			$this->session->data['upload_delete_older_cutoff'] = $cutoff;
		} else {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$data = array();

		$data['uploads'] = array();

		foreach ($uploads as $upload) {
			$file = DIR_UPLOAD . $upload['filename'];

			$data['uploads'][] = array(
				'upload_id'  => $upload['upload_id'],
				'name'       => $upload['name'],
				'filename'   => $upload['filename'],
				'size'       => is_file($file) ? $this->formatBytes(filesize($file)) : '',
				'date_added' => date($this->language->get('date_format_short'), strtotime($upload['date_added']))
			);
		}

		$data['heading_title']        = $this->language->get('heading_title');
		$data['heading_delete_older'] = $this->language->get('heading_delete_older');
		$data['text_list']            = $this->language->get('text_list');
		$data['text_no_results']      = $this->language->get('text_no_results');
		$data['text_delete_older_intro']  = $this->language->get('text_delete_older_intro');
		$data['text_delete_older_empty']  = $this->language->get('text_delete_older_empty');
		$data['text_delete_older_cutoff'] = sprintf($this->language->get('text_delete_older_cutoff'), date($this->language->get('date_format_short'), strtotime($cutoff)));
		$data['text_delete_older_count']  = sprintf($this->language->get('text_delete_older_count'), count($data['uploads']));

		$data['column_name']       = $this->language->get('column_name');
		$data['column_filename']   = $this->language->get('column_filename');
		$data['column_size']       = $this->language->get('column_size');
		$data['column_date_added'] = $this->language->get('column_date_added');

		$data['button_cancel']       = $this->language->get('button_cancel');
		$data['button_delete_older'] = $this->language->get('button_delete_older');

		$data['token'] = $this->session->data['token'];

		$data['delete_older'] = $this->url->link('tool/upload/deleteOlder', 'token=' . $this->session->data['token'], true);

		$data['cancel'] = $this->url->link('tool/upload', 'token=' . $this->session->data['token'], true);

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('tool/upload', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_delete_older'),
			'href' => $this->url->link('tool/upload/deleteOlder', 'token=' . $this->session->data['token'], true)
		);

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['header']      = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer']      = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('tool/upload_delete_older', $data));
	}

	protected function getList()
	{
		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}

		if (isset($this->request->get['filter_date_added'])) {
			$filter_date_added = $this->request->get['filter_date_added'];
		} else {
			$filter_date_added = null;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'date_added';
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

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
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
			'href' => $this->url->link('tool/upload', 'token=' . $this->session->data['token'] . $url, true)
		);

		$data['delete'] = $this->url->link('tool/upload/delete', 'token=' . $this->session->data['token'] . $url, true);

		$data['delete_older'] = $this->url->link('tool/upload/deleteOlder', 'token=' . $this->session->data['token'], true);

		$data['uploads'] = array();

		$filter_data = array(
			'filter_name'	    => $filter_name,
			'filter_date_added'	=> $filter_date_added,
			'sort'              => $sort,
			'order'             => $order,
			'start'             => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'             => $this->config->get('config_limit_admin')
		);

		$upload_total = $this->model_tool_upload->getTotalUploads($filter_data);

		$results = $this->model_tool_upload->getUploads($filter_data);


		foreach ($results as $result) {
			$data['uploads'][] = array(
				'upload_id'  => $result['upload_id'],
				'name'       => $result['name'],
				'filename'   => $result['filename'],
				'username'   => $result['username'],
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'download'   => $this->url->link('tool/upload/download', 'token=' . $this->session->data['token'] . '&code=' . $result['code'] . $url, true)
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['column_name'] = $this->language->get('column_name');
		$data['column_filename'] = $this->language->get('column_filename');
		$data['column_username'] = $this->language->get('column_username');
		$data['column_date_added'] = $this->language->get('column_date_added');
		$data['column_action'] = $this->language->get('column_action');

		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_date_added'] = $this->language->get('entry_date_added');

		$data['button_download'] = $this->language->get('button_download');
		$data['button_delete'] = $this->language->get('button_delete');
		$data['button_delete_older'] = $this->language->get('button_delete_older');
		$data['button_filter'] = $this->language->get('button_filter');

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

		if (isset($this->session->data['error'])) {
			$data['error'] = $this->session->data['error'];

			unset($this->session->data['error']);
		} else {
			$data['error'] = '';
		}

		if (isset($this->session->data['warning'])) {
			$data['warning'] = $this->session->data['warning'];

			unset($this->session->data['warning']);
		} else {
			$data['warning'] = '';
		}

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_name'] = $this->url->link('tool/upload', 'token=' . $this->session->data['token'] . '&sort=name' . $url, true);
		$data['sort_filename'] = $this->url->link('tool/upload', 'token=' . $this->session->data['token'] . '&sort=filename' . $url, true);
		$data['sort_username'] = $this->url->link('tool/upload', 'token=' . $this->session->data['token'] . '&sort=username' . $url, true);
		$data['sort_date_added'] = $this->url->link('tool/upload', 'token=' . $this->session->data['token'] . '&sort=date_added' . $url, true);

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $upload_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('tool/upload', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($upload_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($upload_total - $this->config->get('config_limit_admin'))) ? $upload_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $upload_total, ceil($upload_total / $this->config->get('config_limit_admin')));

		$data['filter_name'] = $filter_name;
		$data['filter_date_added'] = $filter_date_added;

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('tool/upload', $data));
	}

	protected function validateDelete()
	{
		if (!$this->user->hasPermission('modify', 'tool/upload')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	/**
	 * Write the run details of the "Delete Older" action to a dedicated log file.
	 */
	protected function logCleanup($cutoff, $result)
	{
		$log = new Log('upload_cleanup.log');

		$log->write(sprintf(
			'cutoff=%s | deleted=%d | files_deleted=%d | files_missing=%d | files_failed=%d | user_id=%d | ip=%s',
			$cutoff,
			$result['deleted'],
			$result['files_deleted'],
			$result['files_missing'],
			count($result['files_failed']),
			(int)$this->user->getId(),
			isset($this->request->server['REMOTE_ADDR']) ? $this->request->server['REMOTE_ADDR'] : 'cli'
		));

		if ($result['files_failed']) {
			$log->write('failed files: ' . implode(', ', $result['files_failed']));
		}

		unset($log);
	}

	/**
	 * Human readable file size.
	 */
	protected function formatBytes($bytes)
	{
		$bytes = (int)$bytes;

		if ($bytes >= 1048576) {
			return round($bytes / 1048576, 2) . ' MB';
		}

		if ($bytes >= 1024) {
			return round($bytes / 1024, 2) . ' KB';
		}

		return $bytes . ' B';
	}

	public function download()
	{
		$this->load->model('tool/upload');

		if (isset($this->request->get['code'])) {
			$code = $this->request->get['code'];
		} else {
			$code = 0;
		}

		$upload_info = $this->model_tool_upload->getUploadByCode($code);

		if ($upload_info) {
			$file = DIR_UPLOAD . $upload_info['filename'];
			$mask = basename($upload_info['name']);

			if (!headers_sent()) {
				if (is_file($file)) {
					header('Content-Type: application/octet-stream');
					header('Content-Description: File Transfer');
					header('Content-Disposition: attachment; filename="' . ($mask ? $mask : basename($file)) . '"');
					header('Content-Transfer-Encoding: binary');
					header('Expires: 0');
					header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
					header('Pragma: public');
					header('Content-Length: ' . filesize($file));

					readfile($file, 'rb');
					exit;
				} else {
					exit('Error: Could not find file ' . $file . '!');
				}
			} else {
				exit('Error: Headers already sent out!');
			}
		} else {
			$this->load->language('error/not_found');

			$this->document->setTitle($this->language->get('heading_title'));

			$data['heading_title'] = $this->language->get('heading_title');

			$data['text_not_found'] = $this->language->get('text_not_found');

			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('error/not_found', 'token=' . $this->session->data['token'], true)
			);

			$data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');

			$this->response->setOutput($this->load->view('error/not_found', $data));
		}
	}

	public function upload()
	{
		$this->load->language('sale/order');

		$json = array();
		$filename = '';
		
		// Check user has permission
		if (!$this->user->hasPermission('modify', 'tool/upload')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			if (!empty($this->request->files['file']['name']) && is_file($this->request->files['file']['tmp_name'])) {
				// Sanitize the filename
				$filename = html_entity_decode($this->request->files['file']['name'], ENT_QUOTES, 'UTF-8');

				if ((utf8_strlen($filename) < 3) || (utf8_strlen($filename) > 128)) {
					$json['error'] = $this->language->get('error_filename');
				}

				// Allowed file extension types
				$allowed = array();

				$extension_allowed = preg_replace('~\r?\n~', "\n", $this->config->get('config_file_ext_allowed'));

				$filetypes = explode("\n", $extension_allowed);

				foreach ($filetypes as $filetype) {
					$allowed[] = trim($filetype);
				}

				if (!in_array(strtolower(substr(strrchr($filename, '.'), 1)), $allowed)) {
					$json['error'] = $this->language->get('error_filetype');
				}

				// Allowed file mime types
				$allowed = array();

				$mime_allowed = preg_replace('~\r?\n~', "\n", $this->config->get('config_file_mime_allowed'));

				$filetypes = explode("\n", $mime_allowed);

				foreach ($filetypes as $filetype) {
					$allowed[] = trim($filetype);
				}

				if (!in_array($this->request->files['file']['type'], $allowed)) {
					$json['error'] = $this->language->get('error_filetype');
				}

				// Check to see if any PHP files are trying to be uploaded
				$content = file_get_contents($this->request->files['file']['tmp_name']);

				if (preg_match('/\<\?php/i', $content)) {
					$json['error'] = $this->language->get('error_filetype');
				}

				// Return any upload error
				if ($this->request->files['file']['error'] != UPLOAD_ERR_OK) {
					$json['error'] = $this->language->get('error_upload_' . $this->request->files['file']['error']);
				}
			} else {
				$json['error'] = $this->language->get('error_upload');
			}
		}

		if (!$json) {
			$file = $filename . '.' . token(32);

			move_uploaded_file($this->request->files['file']['tmp_name'], DIR_UPLOAD . $file);

			// Hide the uploaded file name so people can not link to it directly.
			$this->load->model('tool/upload');

			$json['code'] = $this->model_tool_upload->addUpload($filename, $file);

			$json['success'] = $this->language->get('text_upload');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}

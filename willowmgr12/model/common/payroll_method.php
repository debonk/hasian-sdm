<?php
class ModelCommonPayrollMethod extends Model
{
	public function exportCsv(string $code, array $data)
	{
		$this->db->transaction(function () use ($code, $data) {
			switch ($code) {
				case 'cimb':
					$this->exportCimb($data);

					break;

				case 'danamon':
					$this->exportDanamon($data);

					break;

				case 'mandiri':
					$this->exportMandiri($data);

					break;

				// case 'bri':
				// 	$this->exportBri();

				// 	break;

				default:
					// $this->exportCimb($data);

					break;
			}
		});
	}

	protected function exportCimb(array $data)
	{
		$category_info = $data['category_info'];
		$fund_account_info = $data['fund_account'];
		$customers = $data['customers'];
		$customer_count = $data['customer_count'];
		$customer_total = $data['customer_total'];

		$date_process = date('Ymd', strtotime($category_info['date_process']));

		$currency_code = $this->config->get('config_currency');

		$output = '';
		$output .= $fund_account_info['acc_no'] . ',' . $fund_account_info['acc_name'] . ',' . $currency_code . ',' . $customer_total . ',' . $category_info['description'] . ',' . $customer_count . ',' . $date_process . ',' . $fund_account_info['email'];

		$output = str_replace(array("\x00", "\x0a", "\x0d", "\x1a"), array('\0', '\n', '\r', '\Z'), $output);
		$output = str_replace(array("\n", "\r", "\t"), array('\n', '\r', '\t'), $output);
		$output = str_replace('\\', '\\\\', $output);
		$output = str_replace('\'', '\\\'', $output);
		$output = str_replace('\\\n', '\n', $output);
		$output = str_replace('\\\r', '\r', $output);
		$output = str_replace('\\\t', '\t', $output);

		foreach ($customers as $customer) {
			$value = '';
			$value .= $customer['acc_no'] . ',' . $customer['lastname'] . ',' . $currency_code . ',' . $customer['amount'] . ',' . $category_info['description'] . (!empty($customer['note']) ? ': ' . str_replace([',', ' '], '_', $customer['note']) : '') . ',' . $customer['email'] . ',,';

			$value = str_replace(array("\x00", "\x0a", "\x0d", "\x1a"), array('\0', '\n', '\r', '\Z'), $value);
			$value = str_replace(array("\n", "\r", "\t"), array('\n', '\r', '\t'), $value);
			$value = str_replace('\\', '\\\\', $value);
			$value = str_replace('\'', '\\\'', $value);
			$value = str_replace('\\\n', '\n', $value);
			$value = str_replace('\\\r', '\r', $value);
			$value = str_replace('\\\t', '\t', $value);

			$output .= "\n" . $value;
		}

		$filename = $fund_account_info['bank_name'] . '_' . $date_process . '_' . preg_replace('/[^a-zA-Z0-9_-]/s', '_', $category_info['description']);

		$this->response->addheader('Pragma: public');
		$this->response->addheader('Expires: 0');
		$this->response->addheader('Content-Description: File Transfer');
		$this->response->addheader('Content-Type: application/octet-stream');
		$this->response->addheader('Content-Disposition: attachment; filename=' . $filename . '.csv');
		$this->response->addheader('Content-Transfer-Encoding: binary');
		$this->response->setOutput($output);
		// echo '<pre>' . print_r($output, 1);
	}

	protected function exportMandiri(array $data)
	{
		$category_info = $data['category_info'];
		$fund_account_info = $data['fund_account'];
		$customers = $data['customers'];
		$customer_count = $data['customer_count'];
		$customer_total = $data['customer_total'];

		$date_process = date('Ymd', strtotime($category_info['date_process']));

		$currency_code = $this->config->get('config_currency');

		$output = '';
		$output .= 'P' . ',' . $date_process . ',' . $fund_account_info['acc_no'] . ',' . $customer_count . ',' . $customer_total;

		$output = str_replace(array("\x00", "\x0a", "\x0d", "\x1a"), array('\0', '\n', '\r', '\Z'), $output);
		$output = str_replace(array("\n", "\r", "\t"), array('\n', '\r', '\t'), $output);
		$output = str_replace('\\', '\\\\',	$output);
		$output = str_replace('\'', '\\\'',	$output);
		$output = str_replace('\\\n', '\n',	$output);
		$output = str_replace('\\\r', '\r',	$output);
		$output = str_replace('\\\t', '\t',	$output);

		foreach ($customers as $customer) {
			$value = '';
			$value .= $customer['acc_no'] . ',' . $customer['lastname'] . ',,,,' . $currency_code . ',' . $customer['amount'] . ',' . $category_info['description'] . (!empty($customer['note']) ? ': ' . str_replace([',', ' '], '_', $customer['note']) : '') . ',,IBU,,,,,,,Y,' . $customer['email'] . ',,,,,,,,,,,,,,,,,,,,,OUR,1,E,,,';

			$value = str_replace(array("\x00", "\x0a", "\x0d", "\x1a"), array('\0', '\n', '\r', '\Z'), $value);
			$value = str_replace(array("\n", "\r", "\t"), array('\n', '\r', '\t'), $value);
			$value = str_replace('\\', '\\\\', $value);
			$value = str_replace('\'', '\\\'', $value);
			$value = str_replace('\\\n', '\n', $value);
			$value = str_replace('\\\r', '\r', $value);
			$value = str_replace('\\\t', '\t', $value);

			$output .= "\n" . $value;
		}

		$filename = $fund_account_info['bank_name'] . '_' . $date_process . '_' . preg_replace('/[^a-zA-Z0-9_-]/s', '_', $category_info['description']);

		$this->response->addheader('Pragma: public');
		$this->response->addheader('Expires: 0');
		$this->response->addheader('Content-Description: File Transfer');
		$this->response->addheader('Content-Type: application/octet-stream');
		$this->response->addheader('Content-Disposition: attachment; filename=' . $filename . '.csv');
		$this->response->addheader('Content-Transfer-Encoding: binary');
		$this->response->setOutput($output);
	}

	protected function exportDanamon(array $data)
	{
		$category_info = $data['category_info'];
		$fund_account_info = $data['fund_account'];
		$customers = $data['customers'];

		$date_process = date('Ymd', strtotime($category_info['date_process']));
		$date_expired = date('Ymd', strtotime($category_info['date_process'] . ' + 2 day'));

		$output = '';
		$output .= 'H,' . $fund_account_info['acc_no'] . ',' . $category_info['description'] . ',S,Y,,' . $date_process . ',0700,' . $date_expired;

		$output = str_replace(array("\x00", "\x0a", "\x0d", "\x1a"), array('\0', '\n', '\r', '\Z'), $output);
		$output = str_replace(array("\n", "\r", "\t"), array('\n', '\r', '\t'), $output);
		$output = str_replace('\\', '\\\\', $output);
		$output = str_replace('\'', '\\\'', $output);
		$output = str_replace('\\\n', '\n', $output);
		$output = str_replace('\\\r', '\r', $output);
		$output = str_replace('\\\t', '\t', $output);

		foreach ($customers as $customer) {
			$value = '';
			$value .= 'D,HYR,,,,,,,,' . $customer['acc_no'] . ',' . $customer['lastname'] . ',,,,,' . $customer['email'] . ',,' . $category_info['description'] . (!empty($customer['note']) ? ': ' . str_replace(',', '_', $customer['note']) : '') . ',' . $customer['nip'] . ',,,' . $customer['amount'] . ',,,,,,,,,Y,,,,,2150,,,';

			$value = str_replace(array("\x00", "\x0a", "\x0d", "\x1a"), array('\0', '\n', '\r', '\Z'), $value);
			$value = str_replace(array("\n", "\r", "\t"), array('\n', '\r', '\t'), $value);
			$value = str_replace('\\', '\\\\', $value);
			$value = str_replace('\'', '\\\'', $value);
			$value = str_replace('\\\n', '\n', $value);
			$value = str_replace('\\\r', '\r', $value);
			$value = str_replace('\\\t', '\t', $value);

			$output .= "\n" . $value;
		}

		$filename = $fund_account_info['bank_name'] . '_' . $date_process . '_' . preg_replace('/[^a-zA-Z0-9_-]/s', '_', $category_info['description']);

		$this->response->addheader('Pragma: public');
		$this->response->addheader('Expires: 0');
		$this->response->addheader('Content-Description: File Transfer');
		$this->response->addheader('Content-Type: application/octet-stream');
		$this->response->addheader('Content-Disposition: attachment; filename=' . $filename . '.csv');
		$this->response->addheader('Content-Transfer-Encoding: binary');
		$this->response->setOutput($output);
	}
}

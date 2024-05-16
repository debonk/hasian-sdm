<?php
class ModelCustomerCustomer extends Model
{
	public function addCustomer($data)
	{
		$company_code = $this->config->get('config_nip_prefix'); //5:DSP, 7:WMA, 8:GK
		$nip_prefix = $company_code . date('y', strtotime($data['date_start']));

		$date_start = date('Y-m-d', strtotime($data['date_start']));
		$date_end = date('Y-m-d', strtotime('+1 month', strtotime($data['date_start'])));

		$nip_no = $this->getNipNoMax($nip_prefix);

		if ($nip_no) {
			$nip_no++;
		} else {
			$nip_no = 100 + 1;
		}

		$nip = $nip_prefix . $nip_no . mt_rand(0, 9);

		$fields_data = [
			'skip_trial_status',
			'health_insurance',
			'employment_insurance',
			'pension_insurance',
			'life_insurance'
		];
		foreach ($fields_data as $field_data) {
			if (!isset($data[$field_data])) {
				$data[$field_data] = 0;
			}
		}

		$sql = "INSERT INTO " . DB_PREFIX . "customer SET customer_department_id = '" . (int)$data['customer_department_id'] . "', customer_group_id = '" . (int)$data['customer_group_id'] . "', location_id = '" . (int)$data['location_id'] . "', nip = '" . $this->db->escape($nip) . "', nip_no = '" . (int)$nip_no . "', nik = '" . $this->db->escape($data['nik']) . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', date_start = '" . $this->db->escape($date_start) . "', image = '" . $this->db->escape($data['image']) . "', skip_trial_status = '" . (int)$data['skip_trial_status'] . "', payroll_include = '" . (int)$data['payroll_include'] . "', acc_no = '" . $this->db->escape($data['acc_no']) . "', payroll_method_id = '" . (int)$data['payroll_method_id'] . "', health_insurance = '" . (int)$data['health_insurance'] . "', life_insurance = '" . (int)$data['life_insurance'] . "', employment_insurance = '" . (int)$data['employment_insurance'] . "', pension_insurance = '" . (int)$data['pension_insurance'] . "', health_insurance_id = '" . $this->db->escape($data['health_insurance_id']) . "', employment_insurance_id = '" . $this->db->escape($data['employment_insurance_id']) . "', full_overtime = '" . (int)$data['full_overtime'] . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', custom_field = '" . $this->db->escape(isset($data['custom_field']) ? json_encode($data['custom_field']) : '') . "', salt = '" . $this->db->escape($salt = token(9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', status = '" . (int)$data['status'] . "', date_end = '" . $this->db->escape($date_end) . "', date_added = NOW()";

		if (!empty($data['date_birth'])) {
			$sql .= ", date_birth = STR_TO_DATE('" . $this->db->escape($data['date_birth']) . "', '%e %b %Y')";
		} else {
			$sql .= ", date_birth = NULL";
		}

		$this->db->query($sql);

		$customer_id = $this->db->getLastId();

		$registered_wage = (float)getNumber($data['registered_wage']);

		$registered_wage = !empty($registered_wage) ? $registered_wage : 'NULL';

		$this->db->query("INSERT INTO " . DB_PREFIX . "customer_add_data SET customer_id = '" . (int)$customer_id . "', gender_id = '" . (int)$data['gender_id'] . "', marriage_status_id = '" . (int)$data['marriage_status_id'] . "', children = '" . (int)$data['children'] . "', npwp = '" . $this->db->escape($data['npwp']) . "', npwp_address = '" . $this->db->escape($data['npwp_address']) . "', registered_wage = " . $registered_wage);

		if (isset($data['address'])) {
			foreach ($data['address'] as $address) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "address SET customer_id = '" . (int)$customer_id . "', address_1 = '" . $this->db->escape($address['address_1']) . "', address_2 = '" . $this->db->escape($address['address_2']) . "', city = '" . $this->db->escape($address['city']) . "', postcode = '" . $this->db->escape($address['postcode']) . "', country_id = '" . (int)$address['country_id'] . "', zone_id = '" . (int)$address['zone_id'] . "', custom_field = '" . $this->db->escape(isset($address['custom_field']) ? json_encode($address['custom_field']) : '') . "'");

				$address_id = $this->db->getLastId();

				if (isset($address['id_card_address'])) {
					$this->db->query("UPDATE " . DB_PREFIX . "customer SET id_card_address_id = '" . (int)$address_id . "' WHERE customer_id = '" . (int)$customer_id . "'");
				}

				if (isset($address['default'])) {
					$this->db->query("UPDATE " . DB_PREFIX . "customer SET address_id = '" . (int)$address_id . "' WHERE customer_id = '" . (int)$customer_id . "'");
				}
			}
		}

		return $customer_id;
	}

	public function editCustomer($customer_id, $data)
	{
		$customer_info = $this->getCustomer($customer_id);

		//Sistem penomoran NIP
		if (!$customer_info['nip_no']) {
			$company_code = $this->config->get('config_nip_prefix'); //5:DSP, 7:WMA, 8:GK
			$nip_prefix = $company_code . date('y', strtotime($customer_info['date_start']));

			$nip_no = $this->getNipNoMax($nip_prefix);

			if ($nip_no) {
				$nip_no++;
			} else {
				$nip_no = 100 + 1;
			}

			$nip = $nip_prefix . $nip_no . mt_rand(0, 9);

			$this->db->query("UPDATE " . DB_PREFIX . "customer SET nip = '" . $this->db->escape($nip) . "', nip_no = '" . (int)$nip_no . "' WHERE customer_id = '" . (int)$customer_id . "'");
		}

		if (!isset($data['custom_field'])) {
			$data['custom_field'] = array();
		}

		$fields_data = [
			'skip_trial_status',
			'health_insurance',
			'employment_insurance',
			'pension_insurance',
			'life_insurance'
		];
		foreach ($fields_data as $field_data) {
			if (!isset($data[$field_data])) {
				$data[$field_data] = 0;
			}
		}

		$sql = "UPDATE " . DB_PREFIX . "customer SET customer_department_id = '" . (int)$data['customer_department_id'] . "', customer_group_id = '" . (int)$data['customer_group_id'] . "', location_id = '" . (int)$data['location_id'] . "', nik = '" . $this->db->escape($data['nik']) . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', image = '" . $this->db->escape($data['image']) . "', payroll_include = '" . (int)$data['payroll_include'] . "', acc_no = '" . $this->db->escape($data['acc_no']) . "', payroll_method_id = '" . (int)$data['payroll_method_id'] . "', health_insurance = '" . (int)$data['health_insurance'] . "', life_insurance = '" . (int)$data['life_insurance'] . "', employment_insurance = '" . (int)$data['employment_insurance'] . "', pension_insurance = '" . (int)$data['pension_insurance'] . "', health_insurance_id = '" . $this->db->escape($data['health_insurance_id']) . "', employment_insurance_id = '" . $this->db->escape($data['employment_insurance_id']) . "', full_overtime = '" . (int)$data['full_overtime'] . "', skip_trial_status = '" . (int)$data['skip_trial_status'] . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', custom_field = '" . $this->db->escape(isset($data['custom_field']) ? json_encode($data['custom_field']) : '') . "', status = '" . (int)$data['status'] . "'";

		if (!empty($data['date_birth'])) {
			$sql .= ", date_birth = STR_TO_DATE('" . $this->db->escape($data['date_birth']) . "', '%e %b %Y')";
		} else {
			$sql .= ", date_birth = NULL";
		}

		$sql .= " WHERE customer_id = '" . (int)$customer_id . "'";

		$this->db->query($sql);

		// if (isset($data['date_end'])) {
		// 	if ((!$data['date_end']) || $this->db->escape($data['date_end']) == '0000-00-00') {
		// 		$this->db->query("UPDATE " . DB_PREFIX . "customer SET date_end = NULL WHERE customer_id = '" . (int)$customer_id . "'");
		// 	} else {
		// 		$this->db->query("UPDATE " . DB_PREFIX . "customer SET date_end = STR_TO_DATE('" . $this->db->escape($data['date_end']) . "', '%e %b %Y') WHERE customer_id = '" . (int)$customer_id . "'");
		// 	}
		// }

		if (isset($data['date_start'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "customer SET date_start = STR_TO_DATE('" . $this->db->escape($data['date_start']) . "', '%e %b %Y') WHERE customer_id = '" . (int)$customer_id . "'");
		}

		if ($data['password']) {
			$this->db->query("UPDATE " . DB_PREFIX . "customer SET salt = '" . $this->db->escape($salt = token(9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "' WHERE customer_id = '" . (int)$customer_id . "'");
		}

		$registered_wage = (float)getNumber($data['registered_wage']);

		$registered_wage = !empty($registered_wage) ? $registered_wage : 'NULL';

		$this->db->query("UPDATE " . DB_PREFIX . "customer_add_data SET gender_id = '" . (int)$data['gender_id'] . "', marriage_status_id = '" . (int)$data['marriage_status_id'] . "', children = '" . (int)$data['children'] . "', npwp = '" . $this->db->escape($data['npwp']) . "', npwp_address = '" . $this->db->escape($data['npwp_address']) . "', registered_wage = " . $registered_wage . " WHERE customer_id = '" . (int)$customer_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "address WHERE customer_id = '" . (int)$customer_id . "'");

		if (isset($data['address'])) {
			foreach ($data['address'] as $address) {
				if (!isset($address['custom_field'])) {
					$address['custom_field'] = array();
				}

				$this->db->query("INSERT INTO " . DB_PREFIX . "address SET address_id = '" . (int)$address['address_id'] . "', customer_id = '" . (int)$customer_id . "', address_1 = '" . $this->db->escape($address['address_1']) . "', address_2 = '" . $this->db->escape($address['address_2']) . "', city = '" . $this->db->escape($address['city']) . "', postcode = '" . $this->db->escape($address['postcode']) . "', country_id = '" . (int)$address['country_id'] . "', zone_id = '" . (int)$address['zone_id'] . "', custom_field = '" . $this->db->escape(isset($address['custom_field']) ? json_encode($address['custom_field']) : '') . "'");

				$address_id = $this->db->getLastId();

				if (isset($address['id_card_address'])) {
					$this->db->query("UPDATE " . DB_PREFIX . "customer SET id_card_address_id = '" . (int)$address_id . "' WHERE customer_id = '" . (int)$customer_id . "'");
				}

				if (isset($address['default'])) {
					$this->db->query("UPDATE " . DB_PREFIX . "customer SET address_id = '" . (int)$address_id . "' WHERE customer_id = '" . (int)$customer_id . "'");
				}
			}
		}
	}

	public function editToken($customer_id, $token)
	{
		$this->db->query("UPDATE " . DB_PREFIX . "customer SET token = '" . $this->db->escape($token) . "' WHERE customer_id = '" . (int)$customer_id . "'");
	}

	public function deleteCustomer($customer_id)
	{
		$this->db->query("DELETE FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$customer_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "customer_ip WHERE customer_id = '" . (int)$customer_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "address WHERE customer_id = '" . (int)$customer_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "customer_add_data WHERE customer_id = '" . (int)$customer_id . "'");
	}

	public function getCustomer($customer_id)
	{
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "v_customer c LEFT JOIN " . DB_PREFIX . "customer_add_data cad ON (cad.customer_id = c.customer_id) WHERE c.customer_id = '" . (int)$customer_id . "'");

		return $query->row;
	}

	public function getCustomerByNik($nik)
	{
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "customer WHERE nik = '" . $this->db->escape($nik) . "'");

		return $query->row;
	}

	public function getCustomerByEmail($email)
	{
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "customer WHERE LCASE(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");

		return $query->row;
	}

	public function getCustomers($data = array())
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "v_customer WHERE (language_id = '" . (int)$this->config->get('config_language_id') . "' OR language_id IS NULL)";
		// $sql = "SELECT c.*, CONCAT(c.firstname, ' [', c.lastname, ']') AS name, cdd.name AS customer_department, cgd.name AS customer_group, l.name AS location FROM " . DB_PREFIX . "customer c LEFT JOIN " . DB_PREFIX . "customer_department_description cdd ON (c.customer_department_id = cdd.customer_department_id) LEFT JOIN " . DB_PREFIX . "customer_group_description cgd ON (c.customer_group_id = cgd.customer_group_id) LEFT JOIN " . DB_PREFIX . "location l ON (l.location_id = c.location_id) WHERE cgd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		$implode = array();

		if (!empty($data['filter_name'])) {
			$implode[] = "name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_email'])) {
			$implode[] = "email LIKE '%" . $this->db->escape($data['filter_email']) . "%'";
		}

		if (!empty($data['filter_customer_department_id'])) {
			$implode[] = "customer_department_id = '" . (int)$data['filter_customer_department_id'] . "'";
		}

		if (!empty($data['filter_customer_group_id'])) {
			$implode[] = "customer_group_id = '" . (int)$data['filter_customer_group_id'] . "'";
		}

		if (!empty($data['filter_location_id'])) {
			$implode[] = "location_id = '" . (int)$data['filter_location_id'] . "'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$implode[] = "status = '" . (int)$data['filter_status'] . "'";
		}

		if (!empty($data['filter_date_start'])) {
			$implode[] = "DATE_FORMAT(date_start,'%b %y') = '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (isset($data['filter_contract_type_id'])) {
			$implode[] = "contract_type_id = '" . $this->db->escape($data['filter_contract_type_id']) . "'";
		}

		if (isset($data['filter_active']) && $data['filter_active'] != '*') {
			if ($data['filter_active'] == 1) {
				if (!empty($data['filter_date_end'])) {
					$implode[] = "(date_end IS NULL OR date_end >= '" . $this->db->escape($data['filter_date_end']) . "')";
				} else {
					$implode[] = "(date_end IS NULL OR date_end >= CURDATE())";
				}
			} else {
				$implode[] = "date_end < CURDATE()";
			}
		}

		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}
		
		$sort_data = array(
			'nip',
			'name',
			'customer_department',
			'customer_group',
			'location',
			'email',
			'date_start',
			'contract_type',
			'date_end'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY name";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getAddress($address_id)
	{
		$address_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "address WHERE address_id = '" . (int)$address_id . "'");

		if ($address_query->num_rows) {
			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$address_query->row['country_id'] . "'");

			if ($country_query->num_rows) {
				$country = $country_query->row['name'];
				$iso_code_2 = $country_query->row['iso_code_2'];
				$iso_code_3 = $country_query->row['iso_code_3'];
				$address_format = $country_query->row['address_format'];
			} else {
				$country = '';
				$iso_code_2 = '';
				$iso_code_3 = '';
				$address_format = '';
			}

			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$address_query->row['zone_id'] . "'");

			if ($zone_query->num_rows) {
				$zone = $zone_query->row['name'];
				$zone_code = $zone_query->row['code'];
			} else {
				$zone = '';
				$zone_code = '';
			}

			// Bonk
			$city_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "city` WHERE city = '" . (int)$address_query->row['city'] . "'");

			if ($city_query->num_rows) {
				$city_name = $city_query->row['name'];
			} else {
				$city_name = '';
			}

			return array(
				'address_id'     => $address_query->row['address_id'],
				'customer_id'    => $address_query->row['customer_id'],
				'address_1'      => $address_query->row['address_1'],
				'address_2'      => $address_query->row['address_2'],
				'postcode'       => $address_query->row['postcode'],
				'city'           => $address_query->row['city'],
				'city_name'      => $city_name, // Bonk
				'zone_id'        => $address_query->row['zone_id'],
				'zone'           => $zone,
				'zone_code'      => $zone_code,
				'country_id'     => $address_query->row['country_id'],
				'country'        => $country,
				'iso_code_2'     => $iso_code_2,
				'iso_code_3'     => $iso_code_3,
				'address_format' => $address_format,
				'custom_field'   => json_decode($address_query->row['custom_field'], true)
			);
		}
	}

	public function getAddresses($customer_id)
	{
		$address_data = array();

		$query = $this->db->query("SELECT address_id FROM " . DB_PREFIX . "address WHERE customer_id = '" . (int)$customer_id . "'");

		foreach ($query->rows as $result) {
			$address_info = $this->getAddress($result['address_id']);

			if ($address_info) {
				$address_data[$result['address_id']] = $address_info;
			}
		}

		return $address_data;
	}

	public function getTotalCustomers($data = array())
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "v_customer WHERE (language_id = '" . (int)$this->config->get('config_language_id') . "' OR language_id IS NULL)";

		$implode = array();

		if (!empty($data['filter_name'])) {
			$implode[] = "name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_email'])) {
			$implode[] = "email LIKE '%" . $this->db->escape($data['filter_email']) . "%'";
		}

		if (!empty($data['filter_customer_department_id'])) {
			$implode[] = "customer_department_id = '" . (int)$data['filter_customer_department_id'] . "'";
		}

		if (!empty($data['filter_customer_group_id'])) {
			$implode[] = "customer_group_id = '" . (int)$data['filter_customer_group_id'] . "'";
		}

		if (!empty($data['filter_location_id'])) {
			$implode[] = "location_id = '" . (int)$data['filter_location_id'] . "'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$implode[] = "status = '" . (int)$data['filter_status'] . "'";
		}

		if (!empty($data['filter_date_start'])) {
			$implode[] = "DATE_FORMAT(date_start,'%b %y') = '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (isset($data['filter_contract_type_id'])) {
			$implode[] = "contract_type_id = '" . $this->db->escape($data['filter_contract_type_id']) . "'";
		}

		if (isset($data['filter_active']) && $data['filter_active'] != '*') {
			if ($data['filter_active'] == 1) {
				if (!empty($data['filter_date_end'])) {
					$implode[] = "(date_end IS NULL OR date_end >= '" . $this->db->escape($data['filter_date_end']) . "')";
				} else {
					$implode[] = "(date_end IS NULL OR date_end >= CURDATE())";
				}
			} else {
				$implode[] = "date_end < CURDATE()";
			}
		}

		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getNipNoMax($nip_prefix)
	{
		$sql = "SELECT MAX(nip_no) AS total FROM `" . DB_PREFIX . "customer` WHERE (nip DIV 10000) = '" . (int)$nip_prefix . "'";

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getTotalCustomersAwaitingApproval()
	{
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer WHERE status = '0'");

		return $query->row['total'];
	}

	public function getTotalAddressesByCustomerId($customer_id)
	{
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "address WHERE customer_id = '" . (int)$customer_id . "'");

		return $query->row['total'];
	}

	public function getTotalAddressesByCountryId($country_id)
	{
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "address WHERE country_id = '" . (int)$country_id . "'");

		return $query->row['total'];
	}

	public function getTotalAddressesByZoneId($zone_id)
	{
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "address WHERE zone_id = '" . (int)$zone_id . "'");

		return $query->row['total'];
	}

	public function getTotalCustomersByCustomerDepartmentId($customer_department_id)
	{ //used by customer/customer_department
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer WHERE customer_department_id = '" . (int)$customer_department_id . "'");

		return $query->row['total'];
	}

	public function getTotalCustomersByCustomerGroupId($customer_group_id)
	{ //used by customer/customer_group
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer WHERE customer_group_id = '" . (int)$customer_group_id . "'");

		return $query->row['total'];
	}

	public function getTotalCustomersByPayrollMethodId($payroll_method_id)
	{ //used by localisation/payroll_method
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer WHERE payroll_method_id = '" . (int)$payroll_method_id . "'");

		return $query->row['total'];
	}

	public function getTotalCustomersByGenderId($gender_id)
	{
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer_add_data WHERE gender_id = '" . (int)$gender_id . "'");

		return $query->row['total'];
	}

	public function getTotalCustomersByMarriageStatusId($marriage_status_id)
	{ //used by localisation/marriage_status
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer_add_data WHERE marriage_status_id = '" . (int)$marriage_status_id . "'");

		return $query->row['total'];
	}

	public function getTotalLoginAttempts($email)
	{
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_login` WHERE `email` = '" . $this->db->escape($email) . "'");

		return $query->row;
	}

	public function deleteLoginAttempts($email)
	{
		$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_login` WHERE `email` = '" . $this->db->escape($email) . "'");
	}
}

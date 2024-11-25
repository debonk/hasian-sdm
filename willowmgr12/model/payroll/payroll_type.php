<?php
class ModelPayrollPayrollType extends Model
{
	private $main_components = [
		'addition'			=> [
			'gp',
			'tj',
			'th',
			'pph',
			'total_um'
		],
		'deduction'	=> [
			'pot_um',
			'pot_pph',
			'pot_gp_tj_5',
			'pot_gp_tj',
			'pot_gp_tj_r',
			'pot_gp',
			'pot_gp_r',
			'pot_tj',
			'pot_tj_r',
			'pot_th_20',
			'pot_th_100',
			'pot_prop_all'
		]
	];

	public function addPayrollType($data)
	{
		$this->db->query("INSERT INTO " . DB_PREFIX . "payroll_type SET name = '" . $this->db->escape($data['name']) . "', description = '" . $this->db->escape($data['description']) . "', user_id = '" . (int)$this->user->getId() . "'");

		$payroll_type_id = $this->db->getLastId();

		if (isset($data['payroll_type_component'])) {
			foreach ($data['payroll_type_component'] as $group => $payroll_type_component) {
				$direction = ($group == 'addition') ? 1 : -1;

				foreach ($payroll_type_component as $value) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "payroll_type_component SET payroll_type_id = '" . (int)$payroll_type_id . "', code = '" . $this->db->escape($value['code']) . "', title = '" . $this->db->escape($value['title']) . "', direction = '" . (int)$direction . "', variable = '" . $this->db->escape($value['variable']) . "', sort_order = '" . (int)$value['sort_order'] . "'");
				}
			}
		}
	}

	public function editPayrollType($payroll_type_id, $data)
	{
		$this->db->query("UPDATE " . DB_PREFIX . "payroll_type SET name = '" . $this->db->escape($data['name']) . "', description = '" . $this->db->escape($data['description']) . "', user_id = '" . (int)$this->user->getId() . "' WHERE payroll_type_id = '" . (int)$payroll_type_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "payroll_type_component WHERE payroll_type_id = '" . (int)$payroll_type_id . "'");

		if (isset($data['payroll_type_component'])) {
			foreach ($data['payroll_type_component'] as $group => $payroll_type_component) {
				$direction = ($group == 'addition') ? 1 : -1;

				foreach ($payroll_type_component as $value) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "payroll_type_component SET payroll_type_id = '" . (int)$payroll_type_id . "', code = '" . $this->db->escape($value['code']) . "', title = '" . $this->db->escape($value['title']) . "', direction = '" . (int)$direction . "', variable = '" . $this->db->escape($value['variable']) . "', sort_order = '" . (int)$value['sort_order'] . "'");
				}
			}
		}
	}

	public function deletePayrollType($payroll_type_id)
	{
		$this->db->query("DELETE FROM " . DB_PREFIX . "payroll_type WHERE payroll_type_id = '" . (int)$payroll_type_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "payroll_type_component WHERE payroll_type_id = '" . (int)$payroll_type_id . "'");
	}

	public function getPayrollType($payroll_type_id)
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "payroll_type WHERE payroll_type_id = '" . (int)$payroll_type_id . "'";

		$query = $this->db->query($sql);

		return $query->row;
	}

	public function getPayrollTypes($data = array())
	{
		$sql = "SELECT pt.*, u.username FROM " . DB_PREFIX . "payroll_type pt LEFT JOIN " . DB_PREFIX . "user u ON (u.user_id = pt.user_id)";

		$sort_data = array(
			'pt.name',
			'pt.description',
			'u.username',
			'pt.date_modified'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY pt.name";
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
				$data['limit'] = 40;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getPayrollTypesCount($data = array())
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "payroll_type";

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getPayrollTypeComponents($payroll_type_id)
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "payroll_type_component WHERE payroll_type_id = '" . (int)$payroll_type_id . "' ORDER BY sort_order ASC";

		$query = $this->db->query($sql);

		$payroll_type_component_data = [
			'addition'		=> [],
			'deduction'		=> []
		];

		foreach ($query->rows as $value) {
			if ($value['direction'] == 1) {
				$payroll_type_component_data['addition'][] = $value;
			} elseif ($value['direction'] == -1) {
				$payroll_type_component_data['deduction'][] = $value;
			}
		}

		return $payroll_type_component_data;
	}

	public function getMainComponentsDescription()
	{
		$main_component_data = [];

		foreach ($this->main_components as $group => $components) {
			foreach ($components as $component) {
				$main_component_data[$group][$component] = $this->language->get('text_' . $component);
			}
		}

		return $main_component_data;
	}
}

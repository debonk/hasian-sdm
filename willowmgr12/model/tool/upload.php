<?php
class ModelToolUpload extends Model
{
	public function addUpload($name, $filename)
	{
		$code = sha1(uniqid(mt_rand(), true));

		$this->db->query("INSERT INTO `" . DB_PREFIX . "upload` SET `name` = '" . $this->db->escape($name) . "', `filename` = '" . $this->db->escape($filename) . "', `code` = '" . $this->db->escape($code) . "', user_id = '" . $this->user->getId() . "', `date_added` = NOW()");

		return $code;
	}

	public function deleteUpload($upload_id)
	{
		// Remove file before deleting DB record.
		$upload_info = $this->model_tool_upload->getUpload($upload_id);

		if ($upload_info && is_file(DIR_UPLOAD . $upload_info['filename'])) {
			unlink(DIR_UPLOAD . $upload_info['filename']);
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "upload WHERE upload_id = '" . (int)$upload_id . "'");
	}

	public function getUpload($upload_id)
	{
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "upload` WHERE upload_id = '" . (int)$upload_id . "'");

		return $query->row;
	}

	public function getUploadByCode($code)
	{
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "upload` WHERE code = '" . $this->db->escape($code) . "'");

		return $query->row;
	}

	public function getUploads($data = array())
	{
		$sql = "SELECT u.*, us.username  FROM " . DB_PREFIX . "upload u LEFT JOIN " . DB_PREFIX . "user us USING (user_id)";

		$sql .= $this->implodeSql($data);

		$sort_data = array(
			'name',
			'filename',
			'date_added',
			'username',
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY date_added";
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

	public function getTotalUploads($data = array())
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "upload";

		$sql .= $this->implodeSql($data);

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	/**
	 * Cut-off used by the "Delete Older" maintenance action.
	 *
	 * The cut-off is computed on the MySQL clock because date_added is written
	 * with the MySQL NOW() function. Using PHP time here would shift the window
	 * by the difference between the PHP and the database time zone.
	 */
	public function getUploadCutoff()
	{
		$query = $this->db->query("SELECT DATE_SUB(NOW(), INTERVAL 1 MONTH) AS cutoff");

		return isset($query->row['cutoff']) ? $query->row['cutoff'] : '';
	}

	/**
	 * Uploads that are eligible for the "Delete Older" action.
	 *
	 * Uploads referenced by a file type product option are excluded so that a
	 * storefront download link can never be broken by the cleanup.
	 */
	public function getUploadsOlderThan($cutoff, $upload_ids = array())
	{
		$sql = "SELECT u.upload_id, u.name, u.filename, u.code, u.date_added FROM " . DB_PREFIX . "upload u";

		$sql .= $this->getOlderThanSql($cutoff, $upload_ids);

		$sql .= " ORDER BY u.date_added ASC";

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getTotalUploadsOlderThan($cutoff, $upload_ids = array())
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "upload u";

		$sql .= $this->getOlderThanSql($cutoff, $upload_ids);

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	/**
	 * Delete the eligible upload records and their physical files.
	 *
	 * The database rows are removed first so a live record can never point at a
	 * file that has already been unlinked. Files that cannot be removed are
	 * reported instead of aborting the run.
	 */
	public function deleteUploadsOlderThan($cutoff, $upload_ids = array())
	{
		$result = array(
			'deleted'       => 0,
			'files_deleted' => 0,
			'files_missing' => 0,
			'files_failed'  => array()
		);

		$rows = $this->getUploadsOlderThan($cutoff, $upload_ids);

		if (!$rows) {
			return $result;
		}

		foreach (array_chunk($rows, 500) as $chunk) {
			$ids   = array();
			$files = array();

			foreach ($chunk as $row) {
				$ids[]   = (int)$row['upload_id'];
				$files[] = $row['filename'];
			}

			$this->db->query("DELETE FROM " . DB_PREFIX . "upload WHERE upload_id IN (" . implode(',', $ids) . ")");

			$result['deleted'] += count($ids);

			$file_result = $this->deleteUploadFiles($files);

			$result['files_deleted'] += $file_result['deleted'];
			$result['files_missing'] += $file_result['missing'];
			$result['files_failed']   = array_merge($result['files_failed'], $file_result['failed']);
		}

		return $result;
	}

	/**
	 * Remove the given upload files from disk.
	 *
	 * Only files that are named by an upload record are ever touched, so the
	 * directory guard file (index.html) and the extension temp-* folders can
	 * not be affected by the cleanup.
	 */
	public function deleteUploadFiles($filenames)
	{
		$result = array(
			'deleted' => 0,
			'missing' => 0,
			'failed'  => array()
		);

		foreach ((array)$filenames as $filename) {
			$filename = (string)$filename;

			if ($filename === '' || $filename === '.' || $filename === '..') {
				$result['failed'][] = $filename;
				continue;
			}

			if (strpos($filename, '/') !== false || strpos($filename, '\\') !== false) {
				$result['failed'][] = $filename;
				continue;
			}

			if (strtolower($filename) === 'index.html') {
				$result['failed'][] = $filename;
				continue;
			}

			$file = DIR_UPLOAD . $filename;

			if (!is_file($file)) {
				$result['missing']++;
				continue;
			}

			if (@unlink($file)) {
				$result['deleted']++;
			} else {
				$result['failed'][] = $filename;
			}
		}

		return $result;
	}

	/**
	 * Shared filter conditions for the upload list and the upload count.
	 *
	 * Keeping both queries on the same helper guarantees that the listed
	 * rows and the pagination total always describe the same result set.
	 */
	private function implodeSql($data = array())
	{
		$implode = array();

		if (!empty($data['filter_name'])) {
			$implode[] = "name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_filename'])) {
			$implode[] = "filename LIKE '" . $this->db->escape($data['filter_filename']) . "%'";
		}

		if (!empty($data['filter_date_added'])) {
			$filter_date_added = trim((string)$data['filter_date_added']);

			if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $filter_date_added)) {
				// A full day range keeps the condition index friendly.
				$date = $this->db->escape($filter_date_added);

				$implode[] = "(date_added >= '" . $date . " 00:00:00' AND date_added < DATE_ADD('" . $date . " 00:00:00', INTERVAL 1 DAY))";
			} else {
				$implode[] = "date_added LIKE '" . $this->db->escape($filter_date_added) . "%'";
			}
		}

		if ($implode) {
			return " WHERE " . implode(" AND ", $implode);
		}

		return '';
	}

	/**
	 * Shared conditions of the "Delete Older" selection.
	 */
	private function getOlderThanSql($cutoff, $upload_ids = array())
	{
		$sql = " WHERE u.date_added < '" . $this->db->escape($cutoff) . "'";

		if (!empty($upload_ids)) {
			$ids = array();

			foreach ($upload_ids as $upload_id) {
				$ids[] = (int)$upload_id;
			}

			$sql .= " AND u.upload_id IN (" . implode(',', $ids) . ")";
		}

		return $sql;
	}
}

<?php

use PhpOffice\PhpSpreadsheet\IOFactory;

class Spreadsheet
{
	private $reader;
	private $writer;
	private $spreadsheet;
	// private $worksheet = '';

	public function __construct($file_type = 'Xlsx', $option = [])
	{
		$this->reader = IOFactory::createReader($file_type);

		if (!isset($option['data_only']) || $option['data_only'] == false) {
			$this->reader->setReadDataOnly(false);
		}

		if (isset($option['sheet_names'])) {
			$this->reader->setLoadSheetsOnly($option['sheet_names']);
		}
	}

	public function writer($file_type = 'Xlsx', $option = [])
	{
		$this->writer = IOFactory::createWriter($this->spreadsheet, $file_type);

		return $this->writer;
	}

	public function loadWorksheetNames($filename) // Still not used
	{
		return $this->reader->listWorksheetNames($filename);
	}

	public function loadSpreadsheet($filename)
	{
		$this->spreadsheet = $this->reader->load($filename);

		return $this->spreadsheet;
	}
}

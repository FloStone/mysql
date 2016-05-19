<?php

class Blueprint
{
	protected $columns;

	public function __construct()
	{
		$this->columns = [];
	}

	public function integer($name, $null = false, $unsigned = false)
	{
		$this->columns[] = "$name INT". $unsigned ? " UNSIGNED" : "" . $null ? " NOT NULL" : "";
	}

	public function increments($name = 'id')
	{
		$this->columns[] = "$name INT UNSIGNED NOT NULL AUTO_INCREMENT KEY";
	}

	public function string($name, $null = false, $length = 255)
	{
		$this->columns[] = "$name VARCHAR($length)". $null ? " NOT NULL" : "";
	}

	public function text($name, $null = false)
	{
		$this->columns[] = "$name TEXT". $null ? " NOT NULL" : "";
	}

	public function custom($column)
	{
		$this->columns[] = $column;
	}

	public function timestamps()
	{
		$this->columns[] = "created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP";
		$this->columns[] = "updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP";
	}

	public function __tostring()
	{
		return implode(',', $this->columns);
	}
}
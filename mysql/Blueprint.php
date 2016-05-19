<?php

namespace Flo\MySQL;

class Blueprint
{
	protected $columns;

	public function __construct()
	{
		$this->columns = [];
	}

	public function integer($name, $null = false, $unsigned = false)
	{
		$null = $null ? "" : " NOT NULL";
		$unsigned = $unsigned ? " UNSIGNED" : "";
		$this->columns[] = "$name INT$unsigned$null";
	}

	public function increments($name = 'id')
	{
		$this->columns[] = "$name INT UNSIGNED NOT NULL AUTO_INCREMENT KEY";
	}

	public function string($name, $null = false, $length = 255)
	{
		$null = $null ? "" : " NOT NULL";
		$this->columns[] = "$name VARCHAR($length)$null";
	}

	public function text($name, $null = false)
	{
		$null = $null ? "" : " NOT NULL";
		$this->columns[] = "$name TEXT$null";
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
<?php

namespace Flo\MySQL;

class Blueprint
{
	/**
	 * Collection of columns
	 *
	 * @var array
	 */
	protected $columns;

	/**
	 * Drop statements
	 *
	 * @var array
	 */
	protected $drops = [];

	/**
	 * Constructor
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->columns = [];
	}

	/**
	 * Integer field
	 *
	 * @param string $name
	 * @param bool $null
	 * @param bool $unsigned
	 * @return void
	 */
	public function integer($name, $null = false, $unsigned = false)
	{
		$null = $null ? "" : " NOT NULL";
		$unsigned = $unsigned ? " UNSIGNED" : "";
		$this->columns[] = "$name INT$unsigned$null";
	}

	/**
	 * Increments field
	 *
	 * @param string $name
	 * @return void
	 */
	public function increments($name = 'id')
	{
		$this->columns[] = "$name INT UNSIGNED NOT NULL AUTO_INCREMENT KEY";
	}

	/**
	 * String field
	 *
	 * @param string $name
	 * @param bool $null
	 * @param int $length
	 * @return void
	 */
	public function string($name, $null = false, $length = 255)
	{
		$null = $null ? "" : " NOT NULL";
		$this->columns[] = "$name VARCHAR($length)$null";
	}

	/**
	 * Text field
	 *
	 * @param string $name
	 * @param bool $null
	 * @return void
	 */
	public function text($name, $null = false)
	{
		$null = $null ? "" : " NOT NULL";
		$this->columns[] = "$name TEXT$null";
	}

	/**
	 * Custom field definition
	 *
	 * @param string $column
	 * @return void
	 */
	public function custom($column)
	{
		$this->columns[] = $column;
	}

	/**
	 * Timestamps
	 *
	 * @return void
	 */
	public function timestamps()
	{
		$this->columns[] = "created_at TIMESTAMP DEFAULT '0000-00-00 00:00:00'";
		$this->columns[] = "updated_at TIMESTAMP DEFAULT '0000-00-00 00:00:00'";
	}

	public function date($name, $default = NULL)
	{
		$default = is_null($default) ? "" : "DEFAULT '$default'";
		$this->columns[] = "$name DATE $default";
	}

	/**
	 * String representation
	 *
	 * @return string
	 */
	public function __tostring()
	{
		return implode(',', $this->columns);
	}

	public function getColumns()
	{
		return $this->columns;
	}

	public function dropColumn($col)
	{
		$this->drops[] = "DROP COLUMN $col ";
	}

	public function getDrops()
	{
		return $this->drops;
	}
}
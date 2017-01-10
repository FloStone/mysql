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
	 * Indices
	 *
	 * @var array
	 */
	protected $indices = [];

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
	 * @return Column
	 */
	public function integer($name)
	{
		$col = new Column($name, Column::INTEGER);
		$this->columns[] = $col;

		return $col;
	}

	/**
	 * Increments field
	 *
	 * @param string $name
	 * @return void
	 */
	public function increments($name = 'id')
	{
		$col = (new Column($name, Column::INTEGER))->unsigned()->increments()->primary();
		$this->columns[] = $col;
	}

	/**
	 * String field
	 *
	 * @param string $name
	 * @param bool $null
	 * @param int $length
	 * @return void
	 */
	public function string($name, $length = 255)
	{
		$col = new Column($name, "VARCHAR($length)");
		$this->columns[] = $col;

		return $col;
	}

	/**
	 * Text field
	 *
	 * @param string $name
	 * @return Column
	 */
	public function text($name)
	{
		$col = new Column($name, Column::TEXT);
		$this->columns[] = $col;

		return $col;
	}

	/**
	 * Custom field definition
	 *
	 * @param string $name
	 * @param string $type
	 * @return Column
	 */
	public function custom($name, $type)
	{
		$col = new Column($name, $type);
		$this->columns[] = $col;

		return $col;
	}

	/**
	 * Timestamps
	 *
	 * @return void
	 */
	public function timestamps()
	{
		$this->columns[] = (new Column('created_at', Column::TIMESTAMP))->default('0000-00-00 00:00:00');
		$this->columns[] = (new Column('updated_at', Column::TIMESTAMP))->default('0000-00-00 00:00:00');
	}

	/**
	 * Date field
	 *
	 * @param string $name
	 *
	 * @return Column
	 */
	public function date($name)
	{
		$col = new Column($name, Column::DATE);
		$this->columns[] = $col;

		return $col;
	}

	/**
	 * Create an index
	 *
	 * @param string $name
	 * @return Index
	 */
	public function index($name)
	{
		
	}

	/**
	 * String representation
	 *
	 * @return string
	 */
	public function __toString()
	{
		return implode(',', $this->columns);
	}

	/**
	 * Get the columns of a table
	 *
	 * @return array
	 * @return void
	 */
	public function getColumns()
	{
		return $this->columns;
	}

	/**
	 * Drop a column
	 *
	 * @param string $col
	 * @return void
	 */
	public function dropColumn($col)
	{
		$this->drops[] = "DROP COLUMN $col ";
	}

	/**
	 * Get all drop statements
	 *
	 * @return array
	 */
	public function getDrops()
	{
		return $this->drops;
	}

	/**
	 * Get the indices of the table
	 * @return array
	 */
	public function indices()
	{
		return $this->indices();
	}

	/**
	 * Render the indices
	 * @return String
	 */
	public function renderIndices()
	{
		return implode('; ', $this->indices);
	}
}
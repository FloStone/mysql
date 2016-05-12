<?php

namespace Flo\MySQL;

/**
 * Trait for MySQL statement
 *
 * experimental
 */
trait Statement
{
	/**
	 * Table to execute query on
	 *
	 * @var string
	 */
	protected $table;

	/**
	 *MySQL statement
	 *
	 * @var string
	 */
	protected $statement;

	/**
	 * Set table
	 *
	 * @param string $table
	 * @return self
	 */
	public function table($table)
	{
		$this->table = $table;

		return $this;
	}

	/**
	 * Select statement
	 *
	 * @param string $statement
	 * @return self
	 */
	public function select($statement = '*')
	{
		$table = $this->table;
		$this->query = "SELECT $statement FROM $table ";

		return $this;
	}

	/**
	 * Where Statement
	 *
	 * @param string $col
	 * @param string $operator
	 * @param int|string $value
	 */
	public function where($col, $operator, $value = NULL)
	{
		if (is_null($value))
			$this->add("WHERE $col = $operator ");
		else
			$this->add("WHERE $col $operator $value ");

		return $this;
	}

	/**
	 * OR Statement
	 *
	 * @param string $col
	 * @param string $operator
	 * @param int|string $value
	 */
	public function orWhere($col, $oprator, $value = NULL)
	{
		if (is_null($value))
			$this->add("OR $col = $operator ");
		else
			$this->add("OR $col $operator $value ");

		return $this;
	}

	/**
	 * Where in Statement
	 *
	 * @param string $col
	 * @param array $values
	 */
	public function whereIn($col, array $values = [])
	{
		$values = '(' . implode(',', $values) . ')';
		$this->add("WHERE $col IN $values ");
	}

	/**
	 * Get results
	 *
	 * @return Collection
	 */
	public function get()
	{
		$this->query($this->statement);

		return $this->results;
	}

	/**
	 * Add to statement
	 *
	 * @param string $query
	 * @return void
	 */
	private function add($query)
	{
		$this->statement = $this->statement . $query;
	}
}
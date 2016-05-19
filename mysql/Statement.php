<?php

namespace Flo\MySQL;

use Closure;

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
	 * Check if there is already a "WHERE" clause
	 *
	 * @var bool
	 */
	protected $hasWhere = false;

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
		$this->statement = "SELECT $statement FROM $table ";

		return $this;
	}

	/**
	 * Insert statement
	 *
	 * @return int
	 */
	public function insert(array $columns = [], array $values = [])
	{
		$table = $this->table;
		$columns = implode(',', $columns);
		$values = '\'' . implode('\',\'', $values) . '\'';
		$this->statement = "INSERT INTO $table ($columns) VALUES ($values)";

		$this->get();

		return mysqli_insert_id($this->connection);
	}

	/**
	 * Create a new table
	 *
	 * @param string $table
	 * @param Closure $closure
	 * @return bool
	 */
	public function create($table, Closure $closure)
	{
		$closure($blueprint = new Blueprint);

		$this->statement = "CREATE TABLE IF NOT EXISTS $table ($blueprint)";

		echo 'Created $table table';

		return $this->get();
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
		$where = $this->hasWhere ? 'AND' : 'WHERE';
		if (is_null($value)):
			if (is_string($operator))
				$this->add("$where $col = '$operator' ");
			else
				$this->add("$where $col = $operator ");
			$this->hasWhere = true;
		else:
			if (is_string($value))
				$this->add("$where $col $operator '$value' ");
			else
				$this->add("$where $col $operator $value ");
			$this->hasWhere = true;
		endif;

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
		$this->hasWhere = false;
		$this->statement = '';

		return $this->results;
	}

	public function orderBy($column, $order = 'desc')
	{
		$this->add("ORDER BY $column $order");
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
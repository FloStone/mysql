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
	 * Model to use when returning queries
	 *
	 * @var object
	 */
	protected $model = NULL;

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
	 * Delete statement
	 *
	 * @return this
	 */
	public function delete()
	{
		$table = $this->table;
		$this->statement = "DELETE FROM $table ";

		return $this;
	}

	/**
	 * Insert statement
	 *
	 * @param array $columns
	 * @param array $values
	 * @return int
	 */
	public function insert(array $columns = [], array $values = [])
	{
		$table = $this->table;

		if (empty($values))
		{
			$values = array_values($columns);
			$columns = array_keys($columns);
		}

		$columns = implode(',', $columns);
		$values = '\'' . implode('\',\'', $values) . '\'';

		if ($this->hasTimestamps())
		{
			$timestamp = "'" . date('Y-m-d H:i:s') . "'";
			$this->statement = "INSERT INTO $table ($columns, created_at, updated_at) VALUES ($values, $timestamp, $timestamp)";	
		}
		else
		{
			$this->statement = "INSERT INTO $table ($columns) VALUES ($values)";
		}
		

		$this->get();

		return mysqli_insert_id($this->connection);
	}

	/**
	 * Update statement
	 *
	 * @param int $id
	 * @param array $columns
	 * @param array $values
	 * @return int
	 */
	public function update($id, array $columns = [], array $values = [])
	{
		$table = $this->table;

		if (empty($values))
			$merged = $columns;
		else
			$merged = array_combine($columns, $values);

		$setarray = [];

		foreach($merged as $column => $value)
		{
			$setarray[] = "$column='$value'";
		}

		$setstring = implode(', ', $setarray);

		if ($this->hasTimestamps())
		{
			$timestamp = "'" . date('Y-m-d H:i:s') . "'";
			$this->statement = "UPDATE $table SET $setstring, updated_at=$timestamp WHERE id = $id";
		}
		else
		{
			$this->statement = "UPDATE $table SET $setstring WHERE id = $id";	
		}

		$this->get();

		return mysqli_insert_id($this->connection);
	}

	/**
	 * Drop statement
	 *
	 * @param string|array $table
	 * @return string
	 */
	public function drop($table)
	{
		if (is_array($table))
		{
			foreach($table as $t)
			{
				$this->statement = "DROP TABLE IF EXISTS $t";
				$this->get();		
			}
		}
		else
		{
			$this->statement = "DROP TABLE IF EXISTS $table";
			$this->get();
		}

		return $this->results;
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

		echo "Created $table table\n";

		return $this->get();
	}

	/**
	 * Alter table statement
	 *
	 * @param string $table
	 * @param Closure $closure
	 * @return bool
	 */
	public function alter($table, Closure $closure)
	{
		$closure($blueprint = new Blueprint);

		$adds = [];

		foreach ($blueprint->getColumns() as $col)
		{
			$adds[] = "ADD COLUMN $col ";
		}

		$adds = implode(', ', $adds);

		$this->statement = "ALTER TABLE $table $adds";

		return $this->get();
	}

	/**
	 * Get all entries from the database
	 *
	 * @return Collection
	 */
	public function all()
	{
		$table = $this->table;
		$this->statement = "SELECT * FROM $table";
		return $this->get();
	}

	/**
	 * Where Statement
	 *
	 * @param string $col
	 * @param string $operator
	 * @param int|string $value
	 * @return this
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

	public function whereRaw($raw)
	{
		$where = $this->hasWhere ? 'AND' : 'WHERE';
		$this->add("$where $raw ");
        $this->hasWhere = true;
		return $this;
	}

	/**
	 * OR Statement
	 *
	 * @param string $col
	 * @param string $operator
	 * @param int|string $value
	 * @return this
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
	 * @return this
	 */
	public function whereIn($col, array $values = [])
	{
        $where = $this->hasWhere ? 'AND' : 'WHERE';
		$values = '(\'' . implode('\',\'', $values) . '\')';
		$this->add("$where $col IN $values ");
        $this->hasWhere = true;
		return $this;
	}

	/**
	 * Limit query results
	 *
	 * @param int $limit
	 * @return this
	 */
	public function limit($offset = 10, $limit = null)
	{
        if($limit !== null)
		    $this->add("LIMIT $offset, $limit"); // Two parameters
        else
            $this->add("LIMIT $offset"); //One parameter

		return $this;
	}

	/**
	 * Join clause
	 *
	 * @param string $table
	 * @param string $primary
	 * @param string $operator
	 * @param string $other
	 * @return this
	 */
	public function join($table, $primary, $operator, $other = NULL)
	{
		if (is_null($other))
			$this->add("INNER JOIN $table ON $primary = $operator ");
		else
			$this->add("INNER JOIN $table ON $primary, $operator, $other ");

		return $this;
	}

	/**
	 * Left join clause
	 *
	 * @param string $table
	 * @param string $primary
	 * @param string $operator
	 * @param string $other
	 * @return this
	 */
	public function leftJoin($table, $primary, $operator, $other = NULL)
	{
		if (is_null($other))
			$this->add("LEFT JOIN $table ON $primary = $operator ");
		else
			$this->add("LEFT JOIN $table ON $primary $operator $other ");

		return $this;
	}

	/**
	 * Right join clause
	 *
	 * @param string $table
	 * @param string $primary
	 * @param string $operator
	 * @param string $other
	 * @return this
	 */
	public function rightJoin($table, $primary, $operator, $other = NULL)
	{
		if (is_null($other))
			$this->add("RIGHT JOIN $table ON $primary = $operator ");
		else
			$this->add("RIGHT JOIN $table ON $primary $operator $other ");

		return $this;
	}

	/**
	 * Outer join clause
	 *
	 * @param string $table
	 * @param string $primary
	 * @param string $operator
	 * @param string $other
	 * @return this
	 */
	public function outerJoin($table, $primary, $operator, $other = NULL)
	{
		if (is_null($other))
			$this->add("OUTER JOIN $table ON $primary = $operator ");
		else
			$this->add("OUTER JOIN $table ON $primary $operator $other ");

		return $this;
	}

	/**
	 * Full outer join clause
	 *
	 * @param string $table
	 * @param string $primary
	 * @param string $operator
	 * @param string $other
	 * @return this
	 */
	public function fullOuterJoin($table, $primary, $operator, $other = NULL)
	{
		if (is_null($other))
			$this->add("FULL OUTER JOIN $table ON $primary = $operator ");
		else
			$this->add("FULL OUTER $table ON $primary $operator $other ");

		return $this;
	}

	/**
	 * Order by statement
	 *
	 * @param string $column
	 * @param string $order
	 * @return this
	 */
	public function orderBy($column, $order = 'desc')
	{
		$this->add("ORDER BY $column $order ");

		return $this;
	}

	/**
	 * Group by statement
	 *
	 * @param string $column
 	 * @return this
	 */
	public function groupBy($column)
	{
		$this->add("GROUP BY $column ");

		return $this;
	}

	/**
	 * Having statement
	 *
	 * @param string one
	 * @param string $operator
	 * @param string $two
	 * @return this
	 */
	public function having($one, $operator, $two = NULL)
	{
		if (is_null($two))
			$this->add("HAVING $one = $operator");
		else
			$this->add("HAVING $one $operator $two");

		return $this;
	}

	/**
	 * Add a custom statement to the sql query
	 *
	 * @param string $sql
	 *
	 * @return this
	 */
	public function raw($sql)
	{
		$this->add("$sql ");

		return $this;
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

	/**
	 * Execute the query
	 *
	 * @return bool|results
	 */
	public function exec()
	{
		return $this->get();
	}

	/**
	 * Get the first entry of results
	 *
	 * @return object
	 */
	public function first()
	{
		return $this->get()->first();
	}

    /**
     * Execute a count statement
     *
     * @return bool|results
     */
    public function count()
    {
        $this->select("COUNT(*) as count");
        $this->query($this->statement);
        $this->hasWhere = false;
        $this->statement = '';
        $count = intval($this->results->first()->count);
        return $count;
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

	/**
	 * Print the SQL query
	 *
	 * @return string
	 */
	public function toSql()
	{
		return $this->statement;
	}

	/**
	 * check wheather a column exists
	 *
	 * @return bool
	 */
	public function columnExists($column)
	{
		$db = $this->database;
		$table = $this->table;

		if ($this->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '$db' AND TABLE_NAME = '$table' AND COLUMN_NAME = '$column'")->isEmpty())
			return false;
		else
			return true;
	}

	/**
	 * Check if the table has timestamps
	 *
	 * @return bool
	 */
	public function hasTimestamps()
	{
		if ($this->columnExists('created_at') && $this->columnExists('updated_at'))
			return true;
		else
			return false;
	}

	/**
	 * Bind results to a given Model
	 *
	 * @param string $classname
	 * @return object
	 */
	public function toModel($classname)
	{
		$model = new $classname([]);

		if (!$model instanceof Model)
			throw new \Exception("$classname must be implementing Flo\MySQL\Model");

		$this->model = $classname;

		return $this->get();
	}
}
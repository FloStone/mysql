<?php

namespace Flo\MySQL;

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

class MySQL
{
	use Statement;

	/**
	 * Connection to database
	 *
	 * @var resource
	 */
	protected $connection;

	/**
	 * Last executed query
	 *
	 * @var string
	 */
	protected $query = '';

	/**
	 * Results from Database
	 *
	 * @var MySQLResult
	 */
	protected $results;

	/**
	 * Resource generated by sql query
	 *
	 * @var resource
	 */
	protected $resource;

	/**
	 * Initialize class
	 *
	 * @param string $host
	 * @param string $username
	 * @param string $passord
	 * @param string $database
	 * @return void
	 */
	public function __construct($host, $username = NULL, $password = NULL, $database = NULL)
	{
		$this->traitConstructor();

		if (is_array($host))
			$credentials = $host;
		else
			$credentials = ['host' => $host, 'username' => $username, 'password' => $password, 'database' => $database];

		$this->connection = Connection::getInstance() ?: new Connection($credentials);

		$this->results = new Collection;

		$this->query("SET NAMES UTF8");
	}

	/**
	 * Static Call alias
	 *
	 * @param string|array $host
	 * @param string $username
	 * @param string $password
	 * @param string $database
	 * @return Connection
	 */
	public static function connect($host, $username = NULL, $password = NULL, $database = NULL)
	{
		return new self($host, $username, $password, $database);
	}

	/**
	 * Execute a MySQL Query
	 *
	 * @param string $query
	 * @return this
	 */
	public function query($query = NULL)
	{
		$this->query = $query;
		$this->results = new Collection;
		
		try {
			$results = mysqli_query($this->connection->getConnection(), $query);
		}
		catch (mysqli_sql_exception $e)
		{
			throw $e;
		}

		$this->resource = $results;

		if (is_bool($results))
		{
			$this->results = new Collection;
			return $this;
		}

		while($row = mysqli_fetch_assoc($results))
		{
			if ($this->model)
			{
				$model = $this->model;
				$result = new $model($row);
			}
			else
				$result = new MySQLResult($row, $this->table);

			$this->results->add($result);
		}

		return $this->results;
	}

	/**
	 * Alias for get method
	 *
	 * @return Collection|bool
	 */
	public function results()
	{
		return $this->get();
	}

	/**
	 * Get the last query executed
	 *
	 * @return string
	 */
	public function lastQuery()
	{
		return $this->query;
	}

	/**
	 * ToString Implementation
	 *
	 * @return string
	 */
	public function __tostring()
	{
		return $this->query;
	}
}
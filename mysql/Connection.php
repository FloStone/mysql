<?php

namespace FloStone\MySQL;

class Connection
{
	/**
	 * Hostname of mysql database
	 *
	 * @var string
	 */
	protected $host;

	/**
	 * Username of Database
	 *
	 * @var string
	 */
	protected $username;

	/**
	 * Password of Database
	 *
	 * @var string
	 */
	protected $password;

	/**
	 * Database name
	 *
	 * @var string
	 */
	protected $database;

	/**
	 * MySQL Connection
	 *
	 * @var Resource
	 */
	protected $connection;

	/**
	 * Connectio instance
	 *
	 * @var Connection
	 */
	protected static $instance;

	public function __construct(array $credentials = [])
	{
		self::$instance = $this;
		$this->host = $credentials['host'];
		$this->username = $credentials['username'];
		$this->password = $credentials['password'];
		$this->database = $credentials['database'];

		$this->connect();
	}

	public function connect()
	{
		$this->connection = mysqli_connect($this->host, $this->username, $this->password, $this->database);

		if (!$this->connection)
			throw new Exception('Could not connect to database');
		
		$this->selectdb($this->database);
	}

	/**
	 * Select database
	 *
	 * @param string $db
	 * @return this
	 */
	public function selectdb($db)
	{
		if(!mysqli_select_db($this->connection, $db))
			throw new Exception('Database does not exist');

		return $this;
	}

	/**
	 * Get the MySQL Connection
	 *
	 * @return Resource
	 */
	public function getConnection()
	{
		return $this->connection;
	}

	/**
	 * Get the connection instance
	 *
	 * @return Connection
	 */
	public static function getInstance()
	{
		return self::$instance;
	}
}
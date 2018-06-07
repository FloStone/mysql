<?php

namespace FloStone\MySQL;

class MySQLException extends \Exception
{
	public function __construct(mysqli_sql_exception $e, $query = null)
	{
		parent::__construct($e->getMessage() . "\n" . "in query $query");
	}
}
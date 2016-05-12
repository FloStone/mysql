<?php

namespace Flo\MySQL;

class MySQLResult
{
	/**
	 * Initialization
	 *
	 * @param array $data
	 * @return void
	 */
	public function __construct(array $data = [])
	{
		foreach($data as $col => $value)
		{
			$this->$col = $value;
		}
	}

	/**
	 * Remove an entry from object
	 *
	 * @param string $key
	 * @return void
	 */
	public function remove($key)
	{
		unset($this->$key);
	}
}
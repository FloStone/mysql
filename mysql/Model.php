<?php

namespace Flo\MySQL;

class Model
{
	protected $attributes = [];

	protected $table = NULL;

	/**
	 * Initialization
	 *
	 * @param array $data
	 * @return void
	 */
	public function __construct(array $data = [], $table = NULL)
	{
		foreach($data as $col => $value)
		{
			$this->$col = $value;
		}

		$this->table = $table;
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

	public function save()
	{
		\SQL::table($this->table)->update($this->attributes['id'], $this->attributes);

		return true;
	}

	public function update(array $data = [])
	{
		foreach ($data as $key => $value)
		{
			$this->attributes[$key] = $value;
		}

		return $this->save();
	}

	public function __set($key, $value)
	{
		$this->attributes[$key] = addslashes($value);
	}

	public function __get($key)
	{
		return $this->attributes[$key];
	}
}
<?php

namespace Flo\MySQL;

class Model
{
	/**
	 * Attributes of table
	 *
	 * @var array
	 */
	protected $attributes = [];

	/**
	 * Table used by model
	 *
	 * @var string
	 */
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
			$this->attributes[$col] = $value;
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

	/**
	 * Save the model to database
	 *
	 * @return bool
	 */
	public function save()
	{
		\SQL::table($this->table)->update($this->attributes['id'], $this->attributes);

		return true;
	}

	/**
	 * Update the model
	 *
	 * @param array $data
	 * @return bool
	 */
	public function update(array $data = [])
	{
		foreach ($data as $key => $value)
		{
			$this->attributes[$key] = $value;
		}

		return $this->save();
	}

	/**
	 * Set a value in model
	 *
	 * @param string $key
	 * @param array $value
	 * @return void
	 */
	public function __set($key, $value)
	{
		$this->attributes[$key] = is_string($value) ? addslashes($value) : $value;
	}

	/**
	 * Get a value from model
	 *
	 * @param string $key
	 * @return string
	 */
	public function __get($key)
	{
		return $this->attributes[$key];
	}

	/**
	 * Json representation
	 *
	 * @return json
	 */
	public function toJson()
	{
		return json_encode($this->attributes);
	}
}
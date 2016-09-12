<?php

namespace Flo\MySQL;

class Model
{
	/**
	 * Attributes of table
	 *
	 * @var array
	 */
	protected $columns = [];

	/**
	 * Model data
	 *
	 * @var array
	 */
	protected $data = [];

	/**
	 * Table used by model
	 *
	 * @var string
	 */
	protected $table = NULL;

	/**
	 * Fields that cannot be filled by default
	 *
	 * @var array
	 */
	protected $guarded = ['id', 'created_at', 'updated_at'];

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
			$this->data[$col] = $value;
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
		\SQL::table($this->table)->update($this->id, $this->getDBValues());

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
			$this->$key = $value;
		}

		return $this->save();
	}

	/**
	 * Json representation
	 *
	 * @return json
	 */
	public function toJson()
	{
		return json_encode($this);
	}

	/**
	 * Get values to be inserted or updated to database
	 *
	 * @return array
	 */
	public function getDBValues()
	{
		$dbcols = \SQL::table($this->table)->getColumns();
		$columns = [];

		foreach ($dbcols as $col)
		{
			$field = $col->Field;

			if ($this->$field && array_search($field, $this->guarded) === false)
				$columns[$field] = $this->$field;
		}
		
		return $columns;
	}

	/**
	 * Convert model to array
	 *
	 * @return array
	 */
	public function toArray()
	{
		return $this->data;
	}

	/**
	 * Set a value to model
	 *
	 * @param string|int $key
	 * @param string|int|object|array $value
	 * @return void
	 */
	public function __set($key, $value)
	{
		$this->data[$key] = $value;
	}

	/**
	 * Get a model value
	 *
	 * @param string|int $key
	 * @return string|int
	 */
	public function __get($key)
	{
		return isset($this->data[$key]) ? $this->data[$key] : NULL;
	}
}
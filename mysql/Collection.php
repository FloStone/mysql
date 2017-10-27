<?php

namespace FloStone\MySQL;

use IteratorAggregate, ArrayIterator;

class Collection implements IteratorAggregate
{
	/**
	 * Array of data
	 *
	 * @var array
	 */
	protected $data;

	/**
	 * Initialization
	 *
	 * @param array $data
	 */
	public function __construct(array $data = [])
	{
		$this->data = $data;
	}

	/**
	 * Get first item from collection
	 *
	 * @return string|object|int|bool
	 */
	public function first()
	{
		return isset($this->data[0])?$this->data[0]:null;
	}

	/**
	 * Iterator handler
	 *
	 * @return array
	 */
	public function getIterator()
	{
		return new ArrayIterator($this->data);
	}

	/**
	 * Return data as array
	 *
	 * @return array
	 */
	public function toArray()
	{
		return $this->data;
	}

	/**
	 * Check if the collection is empty
	 *
	 * @return bool
	 */
	public function isEmpty()
	{
		return empty($this->data);
	}

    /**
     * Check if the collection is empty
     *
     * @return bool
     */
    public function count()
    {
        return sizeof($this->data);
    }

	/**
	 * Add an item from collection
	 *
	 * @param string|object|int|bool $item
	 * @return void
	 */
	public function add($item)
	{
		$this->data[] = $item;
	}

	/**
	 * Remove and item from the collection
	 *
	 * @param string $key
	 * @return void
	 */
	public function remove($key)
	{
		if (isset($this->data[$key]))
			unset($this->data[$key]);
	}

	/**
	 * Return the json form
	 *
	 * @return json
	 */
	public function toJson()
	{
		return json_encode($this->data);
	}

	/**
	 * List a key and value of collection items
	 *
	 * @param string $key
	 * @param string $value
	 * @return array
	 */
	public function lists($key, $value = NULL)
	{
		$lists = [];

		if ($value)
		{
			foreach ($this->data as $item)
			{
				$lists[$item->$key] = $item->$value;
			}
		}
		else
		{
			foreach ($this->data as $item)
			{
				$lists[] = $item->$key;
			}
		}

		return $lists;
	}
}
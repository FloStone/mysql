<?php

namespace Flo\MySQL;

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
		return array_shift($this->data);
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
	 * Return the json form
	 *
	 * @return json
	 */
	public function toJson()
	{
		return json_encode($this->data);
	}
}
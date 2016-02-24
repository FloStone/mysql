<?php

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
		return $this->data;
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
	 * Add an item from collection
	 *
	 * @param string|object|int|bool $item
	 * @return void
	 */
	public function add($item)
	{
		$this->data[] = $item;
	}
}
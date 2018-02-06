<?php

namespace FloStone\MySQL;

class Column
{
	const INTEGER = 'INT';
	const STRING = 'VARCHAR(255)';
	const TEXT = 'TEXT';
	const DATE = 'DATE';
	const TIMESTAMP = 'TIMESTAMP';

	/**
	 * Column is nullable
	 *
	 * @var bool
	 */
	protected $nullable = false;

	/**
	 * Column is unsigned
	 *
	 * @var bool
	 */
	protected $unsigned = false;

	/**
	 * Column is Auto Increment
	 *
	 * @var bool
	 */
	protected $ai = false;

	/**
	 * Column is primary key
	 *
	 * @var bool
	 */
	protected $primary = false;

	/**
	 * Column name
	 *
	 * @var string
	 */
	protected $name;

	/**
	 * Column type
	 *
	 * @var string
	 */
	protected $type;

	/**
	 * Column default
	 *
	 * @var string|int
	 */
	protected $default = NULL;

	/**
	 * Default is not encapsed in string markers
	 * @var boolean
	 */
	protected $rawDefault = false;

	/**
	 * Constructor
	 *
	 * @param string $name
	 * @param string $type
	 */
	public function __construct($name, $type)
	{
		$this->name = $name;
		$this->type = $type;
	}

	/**
	 * Alias for autoIncrement
	 *
	 * @return this
	 */
	public function increments()
	{
		return $this->autoIncrement();
	}

	/**
	 * Make column nullable
	 *
	 * @return this
	 */
	public function nullable()
	{
		$this->nullable = true;

		return $this;
	}

	/**
	 * Make column auto increment
	 *
	 * @return this
	 */
	public function autoIncrement()
	{
		$this->ai = true;

		return $this;
	}

	/**
	 * Make column unsigned
	 *
	 * @return this
	 */
	public function unsigned()
	{
		$this->unsigned = true;

		return $this;
	}

	/**
	 * Make column primary key
	 *
	 * @return this
	 */
	public function primary()
	{
		$this->primary = true;

		return $this;
	}

	/**
	 * Set column default
	 *
	 * @param string|int $default
	 * @return this
	 */
	public function default($default)
	{
		$this->default = $default;

		return $this;
	}

	public function rawDefault($default)
	{
		$this->default = $default;
		$this->rawDefault = true;

		return $this;
	}

	/**
	 * Call method for default function
	 *
	 * @param string $func
	 * @param array $args
	 *
	 * @return this|void
	 */
	public function __call($func, $args)
	{
		if ($func == 'default')
			return call_user_func_array([$this, 'def'], $args);
	}

	/**
	 * String representation
	 *
	 * @return string
	 */
	public function __toString()
	{
		$name = $this->name;
		$type = $this->type;
		$null = $this->nullable ? "" : "NOT NULL";
		$unsigned = $this->unsigned ? "UNSIGNED" : "";
		$ai = $this->ai ? "AUTO_INCREMENT" : "";
		$primary = $this->primary ? "KEY" : "";
		$default = !is_null($this->default) ? $this->rawDefault ? "DEFAULT {$this->default}" : "DEFAULT '{$this->default}'" : "";

		return "$name $type $unsigned $null $ai $primary $default";
	}
}
<?php

namespace Flo\MySQL;

class Index
{
	const FULLTEXT = "FULLTEXT";
	const UNIQUE = "UNIQUE";
	const SPATIAL = "SPATIAL";

	protected $type = NULL;

	protected $cols = [];

	protected $table = NULL;

	protected $name = NULL;

	public function __construct($name)
	{
		$this->name = $name;
	}

	public function on($table, array $cols = [])
	{
		$this->table = $table;
		$this->cols = $cols;

		return $this;
	}

	public function fulltext()
	{
		$this->type = self::FULLTEXT;

		return $this;
	}

	public function unique()
	{
		$this->type = self::UNIQUE;

		return $this;
	}

	public function spatial()
	{
		$this->type = self::SPATIAL;

		return $this;
	}

	public function __toString()
	{
		$name = $this->name;
		$cols = implode(', ', $this->cols);
		$table = $this->table;
		$type = $this->type;

		return "CREATE $type INDEX $name ON $table ($cols)";
	}
}
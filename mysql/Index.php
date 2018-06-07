<?php

namespace FloStone\MySQL;

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

        // Maybe replace with this?
        // select if (
        //     exists(
        //         select distinct index_name from information_schema.statistics
        //         where table_schema = 'schema_db_name'
        //         and table_name = 'tab_name' and index_name like 'index_1'
        //     )
        //     ,'select ''index index_1 exists'' _______;'
        //     ,'create index index_1 on tab_name(column_name_names)') into @a;
        // PREPARE stmt1 FROM @a;
        // EXECUTE stmt1;
        // DEALLOCATE PREPARE stmt1;

		return "CREATE $type INDEX $name ON $table ($cols)";
	}
}
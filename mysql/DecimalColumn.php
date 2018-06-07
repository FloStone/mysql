<?php

namespace FloStone\MySQL;

class DecimalColumn extends Column
{
	protected $max;

	protected $digits;

	public function __construct($name, $max = 65, $digits = 2)
	{
		$this->name = $name;
		$this->max = $max;
		$this->digits = $digits;
	}

	public function __toString()
	{
		$max = $this->max;
		$digits = $this->digits;
		$name = $this->name;
		$type = $this->type;
		$null = $this->nullable ? "" : "NOT NULL";
		$unsigned = $this->unsigned ? "UNSIGNED" : "";
		$ai = $this->ai ? "AUTO_INCREMENT" : "";
		$primary = $this->primary ? "KEY" : "";
		$default = $this->default ? "DEFAULT '{$this->default}'" : "";

		return "$name DECIMAL($max, $digits) $unsigned $null $ai $primary";
	}
}
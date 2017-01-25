<?php

namespace Flo\MySQL;

class StatementCollection
{
	const INITIAL = 'initial';
	const JOIN = 'join';
	const WHERE = 'where';
	const GROUPBY = 'groupby';
	const HAVING = 'having';
	const ORDERBY = 'orderby';
	const LIMIT = 'limit';
	const OFFSET = 'offset';

	protected $initial;

	protected $joins = [];

	protected $wheres = [];

	protected $groupbys = [];

	protected $havings = [];

	protected $orderbys = [];

	protected $limit;

	protected $offset;

	public function hasInitial()
	{
		return $this->initial ? true : false;
	}

	public function add($type, $query)
	{
		switch ($type)
		{
			case self::INITIAL:
				
				$this->initial = $query;

				break;
			
			case self::JOIN:

				$this->joins[] = $query;

				break;

			case self::WHERE:

				$this->wheres[] = $query;

				break;

			case self::GROUPBY:

				$this->groupbys = [$query];

				break;

			case self::HAVING:

				$this->havings[] = $query;

				break;

			case self::ORDERBY:

				$this->orderbys = [$query];

				break;

			case self::LIMIT:

				$this->limit = $query;

				break;

			case self::OFFSET:

				$this->offset = $query;

				break;

			default:

				break;
		}
	}

	public function build()
	{
		$initial = $this->initial;
		$joins =    empty($this->joins) ? '' : implode(' ', $this->joins);
		$wheres =   empty($this->wheres) ? '' : implode(' ', $this->wheres);
		$groupbys = empty($this->groupbys) ? '' : implode(' ', $this->groupbys);
		$havings =  empty($this->havings) ? '' : implode(' ', $this->havings);
		$orderbys = empty($this->orderbys) ? '' : implode(' ', $this->orderbys);
		$limit = $this->limit ?: '';
		$offset = $this->offset ?: '';

		return trim("$initial $joins $wheres $groupbys $havings $orderbys $limit $offset");
	}
}
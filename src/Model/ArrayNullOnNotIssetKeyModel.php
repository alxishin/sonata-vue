<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 2019-02-27
 * Time: 17:23
 */

namespace SonataVue\Model;

use ArrayAccess;

class ArrayNullOnNotIssetKeyModel implements ArrayAccess
{
	private $params;

	public function __construct($params)
	{
		$this->params = $params;
	}

	public function offsetSet($offset, $value): void
	{

	}

	public function offsetExists($offset):bool
	{
		return true;
	}

	public function offsetUnset($offset):void
	{
		unset($this->params[$offset]);
	}

	public function offsetGet($offset):mixed
	{
		return $this->params[$offset] ?? null;
	}

	public function toArray()
	{
		return $this->params;
	}
}

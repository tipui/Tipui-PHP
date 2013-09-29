<?php

/**
* @class  Append
* @file   Append.php
* @brief  Append HTML Miscellaneous functions.
* @date   2013-09-29 21:36:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-29 21:36:00
*/

namespace Tipui\Builtin\Helpers\HTML\Miscellaneous;

class Append
{

	/**
	* Handles string to append.
	*/
	protected static $data = null;

	/**
	* Returns self object
	* @param $clear, if true, sets the self::$data to null
	*/
	public function Exec( $clear = false )
	{
		$this -> Reset( $clear );
		return $this;
	}

	/**
	* Sets the string to data property.
	*/
	public static function Set( $str )
	{
		self::$data = $str;
		unset( $str );
	}

	/**
	* Returns the value of data property
	*/
	public static function Get()
	{
		return self::$data;
	}

	/**
	* Reset the data property
	*/
	public static function Reset( $clear = false )
	{
		$clear ? self::$data = null : null;
		return null;
	}

}
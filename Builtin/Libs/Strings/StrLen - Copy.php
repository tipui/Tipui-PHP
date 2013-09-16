<?php

/**
* @class  StrLen
* @file   StrLen.php
* @brief  StrLen strings functions.
* @date   2013-09-15 02:54:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-16 17:20:00
*/

namespace Tipui\Builtin\Libs\Strings;

class StrLen
{

	/**
	* Handles the result
	*/
	private $result;

	/**
	* Multibyte safe characters length
	*/
	public function __construct( $str, $charset = 'UTF-8' )
	{
		$this -> result = mb_strlen( $str, $charset );
		unset( $str, $charset );
    }

	/**
	* Reset the property
	*/
	public function __destruct()
	{
		$this -> result;
    }

	/**
	* Returns result
	*/
	public function GetResult()
	{
		$this -> result;
    }

}
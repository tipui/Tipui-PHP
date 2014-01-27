<?php

/**
* @class  Number
* @file   Number.php
* @brief  Number Builtin DataValidation Filters functions.
* @date   2014-01-05 02:35:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2014-01-05 02:35:00
*/

namespace Tipui\Builtin\Libs\DataValidation\Filters;

class Number
{

	/**
	* Allows only numbers
	* For decimal/float, see the filter "Float.php"
	*/
	public function Exec( $str )
	{
		$r = new \stdClass();

		$r -> str = \Tipui\Builtin\Libs\Strings::NumbersOnly( $str );
		if( $r -> str != $str )
		{
			$r -> error = true;
			$r -> str   = $str;
		}

		return $r;
    }

}
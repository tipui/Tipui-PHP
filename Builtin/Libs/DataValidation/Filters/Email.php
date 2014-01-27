<?php

/**
* @class  Email
* @file   Email.php
* @brief  Email Builtin DataValidation Filters functions.
* @date   2014-01-05 02:35:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2014-01-05 02:35:00
*/

namespace Tipui\Builtin\Libs\DataValidation\Filters;

class Email
{

	/**
	* Allows only numbers
	* For decimal/float, see the filter "Float.php"
	*/
	public function Exec( $str )
	{
		$r = new \stdClass();

		if( !$r -> str = \Tipui\Builtin\Libs\Strings::ValidMailAddress( $str ) )
		{
			$r -> error = $r -> str;
			$r -> str   = $str;
		}

		return $r;
    }

}
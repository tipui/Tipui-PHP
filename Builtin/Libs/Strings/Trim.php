<?php

/**
* @class  Trim
* @file   Trim.php
* @brief  Trim strings functions.
* @date   2013-09-15 02:54:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-15 02:54:00
*/

namespace Tipui\Builtin\Libs\Strings;

class Trim
{

	/**
	* Removes blank spaces from the beginning and the end of an string
	*/
	public function Exec( $str )
	{
		//return trim( $str );
		return preg_replace( '/(^\s+)|(\s+$)/us', '', $str );
    }

}
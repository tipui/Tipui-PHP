<?php

/**
* @class  LimitStr
* @file   LimitStr.php
* @brief  LimitStr strings functions.
* @date   2013-09-15 02:54:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-15 02:54:00
*/

namespace Tipui\Builtin\Libs\Strings;

class LimitStr
{

	/**
	* Cut string to an specified length
	*/
	public function Exec( $str = '', $limit = 10, $dots = '...' )
	{
		if( mb_strlen( $str ) <= $limit )
		{
			return $str;
		}
		return mb_substr( $str, 0, $limit ) . $dots;
    }

}
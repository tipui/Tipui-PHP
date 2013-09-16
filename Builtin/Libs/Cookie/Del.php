<?php

/**
* @class  Del
* @file   Del.php
* @brief  Del Cookie functions.
* @date   2013-09-16 20:11:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-16 20:11:00
*/

namespace Tipui\Builtin\Libs\Cookie;

class Del
{

	/**
	* [review]
	* Delete Cookie key
	*
	*/
    public function Exec( $key ) 
    {

		if( isset( $_COOKIE[$key] ) )
		{
			self::Set( $str, false );
			unset( $_COOKIE[$key] );
		}

        return null;

	}

}
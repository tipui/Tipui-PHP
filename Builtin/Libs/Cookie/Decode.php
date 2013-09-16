<?php

/**
* @class  Decode
* @file   Decode.php
* @brief  Decode Cookie functions.
* @date   2013-09-16 20:11:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-16 20:11:00
*/

namespace Tipui\Builtin\Libs\Cookie;

class Decode
{

	/**
	* Unserialize cookie value
	*
	*/
    public function Exec( $str ) 
    {

		if( get_magic_quotes_gpc() )
		{
			$str = stripslashes($str);
		}
        return unserialize( $str );

	}

}
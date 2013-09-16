<?php

/**
* @class  Encode
* @file   Encode.php
* @brief  Encode Cookie functions.
* @date   2013-09-16 20:11:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-16 20:11:00
*/

namespace Tipui\Builtin\Libs\Cookie;

class Encode
{

	/**
	* Serialize cookie value before set it
	*/
    public function Exec( $str ) 
    {

		/**
		* Serialize cookie value before set it
		*/
		if( is_string( $v ) )
		{
			return $v;
		}
		return serialize( $str );

	}

}
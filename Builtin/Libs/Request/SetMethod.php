<?php

/**
* @class  SetMethod
* @file   SetMethod.php
* @brief  SetMethod Request functions.
* @date   2013-09-16 20:11:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-16 20:11:00
*/

namespace Tipui\Builtin\Libs\Request;

use Tipui\Builtin\Libs as Libs;

class SetMethod extends Libs\Request
{

	/**
	* Sets method for request
	* @see Libs\Request::GetMethod()
	* @see Libs\Request::ValidMethod()
	*/
    public static function Exec( $method = 'GET' ) 
    {
		/**
		* If is running on CLI mode or if global 'REQUEST_METHOD' not found
		*/
		if( self::$sapi_is_cli or !isset( $_SERVER['REQUEST_METHOD'] ) )
        {
			return false;
		}

		/**
		* Validates the method
		*/
		if( $r = self::ValidMethod( $method ) )
		{
			self::$rq_method = $r;
		}

		unset( $r, $method );

		return null;
	}

}
<?php

/**
* @class  GetMethod
* @file   GetMethod.php
* @brief  GetMethod Request functions.
* @date   2013-09-16 20:11:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-16 20:11:00
*/

namespace Tipui\Builtin\Libs\Request;

use Tipui\Builtin\Libs as Libs;

class GetMethod extends Libs\Request
{

	/**
	* Returns requested method if is valid
	* @see Libs\Request::SetMethod()
	* @see Libs\Request::ValidMethod()
	*/
    public static function Exec() 
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
		if( $r = self::ValidMethod() )
		{
			return $r;
		}

		unset( $r );

		/**
		* Returns GET as default
		*/
		return 'GET';
	}

}
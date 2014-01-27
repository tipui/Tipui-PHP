<?php

/**
* @class  ValidMethod
* @file   ValidMethod.php
* @brief  ValidMethod Request functions.
* @date   2013-09-16 20:11:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2014-01-23 12:50:00
*/

namespace Tipui\Builtin\Libs\Request;

use Tipui\Builtin\Libs as Libs;

class ValidMethod extends Libs\Request
{

	/**
	* Check the permitted methods
	*/
    public static function Exec( $method = false ) 
    {
		/**
		GET Retrieve the resource from the server
		POST Create a resource on the server
		PUT Update the resource on the server
		DELETE Delete the resource from the server
		*/
		$r = (!$method) ? strtoupper( $_SERVER['REQUEST_METHOD'] ) : $method;
		if( in_array( $r, array( self::METHOD_GET, self::METHOD_POST, self::METHOD_FILES, self::METHOD_PUT, self::METHOD_DELETE ) ) )
		{
			return $r;
		}
		return false;
	}

}
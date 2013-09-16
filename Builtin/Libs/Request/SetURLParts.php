<?php

/**
* @class  SetURLParts
* @file   SetURLParts.php
* @brief  SetURLParts Request functions.
* @date   2013-09-16 20:11:00
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License
* @company: Tipui Co. Ltda.
* @author: Daniel Omine <omine@tipui.com>
* @updated: 2013-09-16 20:11:00
*/

namespace Tipui\Builtin\Libs\Request;

use Tipui\Builtin\Libs as Libs;

class SetURLParts extends Libs\Request
{

	/**
	* Sets the URL parts
	*/
    public static function Exec( $href_base = '/', $pfs = '/', $param_argumentor = '?' ) 
    {
		self::$url_href_base        = $href_base;
		self::$url_pfs              = $pfs;
		self::$url_param_argumentor = $param_argumentor;
		unset( $href_base, $param_argumentor, $pfs );
		return null;
	}

}